<?
namespace Lang\Academy;

use Bitrix\Main\Localization\Loc;
use Bitrix\UI\EntitySelector\Item;
use Bitrix\UI\EntitySelector\Dialog;
use Bitrix\UI\EntitySelector\BaseFilter;

use Bitrix\Im\User as ImUser;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Im\Integration\UI\EntitySelector\Helper\User as ImHelperUser;

final class ImUsersFilter extends BaseFilter
{
    public function isAvailable(): bool
    {
        $user = CurrentUser::get();

        if(intval($user->getId()) > 1) {
            return true;
        }

        return false;
    }

    public function apply(array $items, Dialog $dialog): void
    {
        foreach ($items as $item) {
			if (!($item instanceof Item)) {
				continue;
			}

			if ($item->getId() === ImHelperUser::getCurrentUserId()) {
				$item->addBadges([[
					'id' => 'IT_IS_YOU',
					'title' => "Это вы",
				]]);
			}

			$customData = $item->getCustomData();
			$userInfo = ImUser::getInstance($item->getId())->getArray();
			$customData->set('imUser', $userInfo);

			//TODO delete after immobile chatselector fix with revision 21c5a9948579
			$defaultIcon = '';

			if (!$item->getAvatar()) {
				$item->setAvatar($defaultIcon);
			}
		}
    }
}