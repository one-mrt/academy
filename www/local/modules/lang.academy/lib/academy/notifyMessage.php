<?
namespace Lang\Academy;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Pull\Event as PullEvent;

use Bitrix\Im as IM;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Im\V2\Notification\ChatProvider;

Loc::loadMessages(__FILE__);

Loader::includeModule('im');

class NotifyMessage {

    private $user_id = 0;
	private $bHideLink = false;

	function __construct($user_id = false, $arParams = [])
	{
		$this->user_id = intval($user_id);

		if ($this->user_id == 0) {
            $user = CurrentUser::get();
			$this->user_id = intval($user->getId());
        }

		if (isset($arParams['HIDE_LINK']) && $arParams['HIDE_LINK'] == 'Y') {
			$this->bHideLink = true;
        }
	}

    public function Confirm($id, $value)
	{
		global $DB;

		$id = intval($id);

		$strSql = "
			SELECT M.*
			FROM b_im_relation R, b_im_message M
			WHERE M.ID = ".$id." AND R.USER_ID = ".$this->user_id." AND R.MESSAGE_TYPE = '".IM_MESSAGE_SYSTEM."' AND R.CHAT_ID = M.CHAT_ID AND M.NOTIFY_TYPE = ".IM_NOTIFY_CONFIRM;
		$dbRes = $DB->Query($strSql);

		if (!($arRes = $dbRes->Fetch())) {
			return false;
        }

		ServiceLocator::getInstance()->get(ChatProvider::class)->prime($this->user_id, (int)$arRes['CHAT_ID']);

		$arRes['RELATION_USER_ID'] = $this->user_id;
		$arRes['NOTIFY_BUTTONS'] = unserialize($arRes['NOTIFY_BUTTONS'], ['allowed_classes' => false]);

		$resultMessages = [];

		if ($arRes['NOTIFY_TAG'] <> '') {
			$CBXSanitizer = new \CBXSanitizer;
			$CBXSanitizer->AddTags(array(
				'a' => array('href','style', 'target'),
				'b' => [], 'u' => [],
				'i' => [], 'br' => [],
				'span' => array('style'),
			));

			foreach(\GetModuleEvents("im", "OnBeforeConfirmNotify", true) as $arEvent) {
				$resultEvent = \ExecuteModuleEventEx($arEvent, Array($arRes['NOTIFY_MODULE'], $arRes['NOTIFY_TAG'], $value, $arRes));

				if($resultEvent===false || is_array($resultEvent) && $resultEvent['result'] === false) {
					$resultMessages = [];

					if (is_array($resultEvent) && $resultEvent['text'])
					{
						$resultMessages[] = $CBXSanitizer->SanitizeHtml($resultEvent['text']);
					}

					break;
				} else if (is_array($resultEvent) && $resultEvent['text'] || is_string($resultEvent) && $resultEvent <> '') {
					$resultMessages[] = $CBXSanitizer->SanitizeHtml(is_string($resultEvent)? $resultEvent: $resultEvent['text']);
				}
			}
		}

		if (empty($resultMessages)) {
			foreach ($arRes['NOTIFY_BUTTONS'] as $button) {
				if ($button['VALUE'] == $value) {
					$resultMessages[] = Loc::getMessage('IM_CONFIRM_CHOICE', Array('#BUTTON#' => $button['TITLE']));

					break;
				}
			}
		}

		self::Delete($id);

		if ($arRes['NOTIFY_TAG'] <> '') {
			foreach(\GetModuleEvents("im", "OnAfterConfirmNotify", true) as $arEvent) {
				\ExecuteModuleEventEx($arEvent, array($arRes['NOTIFY_MODULE'], $arRes['NOTIFY_TAG'], $value, $arRes, $resultMessages));
            }
		}

		if (Loader::includeModule("pull")) {
			\Bitrix\Pull\Event::add($this->user_id, Array(
				'module_id' => 'im',
				'command' => 'notifyConfirm',
				'params' => Array(
					'id' => $id,
					'chatId' => intval($arRes['CHAT_ID']),
					'confirmMessages' => $resultMessages,
					'counter' => self::GetRealCounter($arRes['CHAT_ID']),
				),
				'extra' => \Bitrix\Im\Common::getPullExtra()
			));
		}

		\CIMMessenger::SpeedFileDelete($this->user_id, IM_SPEED_NOTIFY);

		return $resultMessages;
	}

    public static function Delete($id)
	{
		$id = (int)$id;
		$notification = self::getNotificationById($id);
		if (!$notification)
		{
			return false;
		}

		ServiceLocator::getInstance()
			->get(ChatProvider::class)
			->prime((int)$notification['RELATION_USER_ID'], (int)$notification['CHAT_ID'])
		;
		$message = (new IM\V2\Message())->setMessageId($id)->setChatId((int)$notification['CHAT_ID']);

		self::deleteInternal($message, (int)$notification['RELATION_USER_ID']);

		$counter = self::GetRealCounter($notification['CHAT_ID']);
		$chatId = (int)$notification['CHAT_ID'];

		self::updateStateAfterDelete($chatId, $notification['RELATION_USER_ID']);

		foreach(\GetModuleEvents("im", "OnAfterDeleteNotify", true) as $arEvent) {
			\ExecuteModuleEventEx($arEvent, [$id, $notification]);
		}

		if (Loader::includeModule("pull")) {
			\Bitrix\Pull\Event::add(
				$notification['RELATION_USER_ID'],
				[
					'module_id' => 'im',
					'command' => 'notifyDelete',
					'params' => [
						'id' => [$id => $notification['NOTIFY_TYPE']],
						'counter' => $counter
					],
					'extra' => \Bitrix\Im\Common::getPullExtra()
				]
			);
		}

		return true;
	}

    private static function getNotificationById(int $id): ?array
	{
		global $DB;

		$sqlQuery = "
			SELECT M.*, R.USER_ID as RELATION_USER_ID
			FROM b_im_message M
			LEFT JOIN b_im_relation R ON R.CHAT_ID = M.CHAT_ID
			WHERE M.ID = ".$id." AND R.MESSAGE_TYPE = '".IM_MESSAGE_SYSTEM."'
		";
		$dbRes = $DB->Query($sqlQuery);
		$result = $dbRes->Fetch();

		if (!$result) {
			return null;
		}

		return $result;
	}

    private static function deleteInternal(IM\V2\Message $message, int $userId): void
	{
		\CIMMessageParam::DeleteAll($message->getId());
		\Bitrix\Im\Model\MessageTable::delete($message->getId());
		ServiceLocator::getInstance()->get(IM\V2\Reading\Notification\Cleaner::class)->onDeleteMessage($message);
	}

    public static function GetRealCounter($chatId)
	{
		return \Bitrix\Im\Notify::getRealCounter($chatId);
	}

    private static function updateStateAfterDelete(int $chatId, int $userId): void
    {
		\CIMMessenger::SpeedFileDelete($userId, IM_SPEED_NOTIFY);

		// update total amount of notifications
		$date = new DateTime();
		$date->add('-60 days'); // sync with \Bitrix\Im\Notify::cleanNotifyAgent
		$messageCount = \Bitrix\Im\Model\MessageTable::getCount([
			'=CHAT_ID' => $chatId,
			'>DATE_CREATE' => $date
		]);

		IM\Model\ChatTable::update($chatId, ['MESSAGE_COUNT' => $messageCount]);
	}

}