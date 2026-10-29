<?
namespace Lang\Academy;

use Bitrix\Main\Loader;
use Lang\Academy\NotifyMessage as LangNotifyMessage;
use Bitrix\Main\Localization\Loc;
use Bitrix\Pull\Event as PullEvent;

Loc::loadMessages(__FILE__);

Loader::includeModule('im');

class Rest extends \IRestService {

    public static function OnRestServiceBuildDescription()
	{
        return [
            'lang.academy' => [
                'im.notify.get' => ['callback' => [__CLASS__, 'notifyGet'], 'options' => ['private' => true]],
                'im.notify.confirm' => array(__CLASS__, 'notifyConfirm'),
            ]
        ];
    }

    public static function notifyConfirm($arParams, $n, \CRestServer $server): array
	{
		$arParams = array_change_key_case($arParams, CASE_UPPER);

		if (isset($arParams['NOTIFY_ID']))
		{
			$arParams['NOTIFY_ID'] = (int)$arParams['NOTIFY_ID'];
		}

		if (isset($arParams['ID']))
		{
			$arParams['NOTIFY_ID'] = (int)$arParams['ID'];
		}

		if ($arParams['NOTIFY_ID'] <= 0)
		{
			throw new \Bitrix\Rest\RestException("Notification ID can't be empty", "ID_ERROR", \CRestServer::STATUS_WRONG_REQUEST);
		}

		if (empty($arParams['NOTIFY_VALUE']))
		{
			throw new \Bitrix\Rest\RestException("Notification Value  can't be empty", "NOTIFY_VALUE_ERROR", \CRestServer::STATUS_WRONG_REQUEST);
		}

		$CIMNotify = new LangNotifyMessage();
		$result = $CIMNotify->Confirm($arParams['NOTIFY_ID'], $arParams['NOTIFY_VALUE']);

		return [
			'result_message' => $result,
            // 'test' => 1,
            'value' => $arParams['NOTIFY_VALUE'],
		];
	}

    public static function notifyGet($arParams, $n, \CRestServer $server)
	{
		$arParams = array_change_key_case($arParams, CASE_UPPER);

		if (
			(isset($arParams['LAST_ID']) && !isset($arParams['LAST_TYPE'])) ||
			(!isset($arParams['LAST_ID']) && isset($arParams['LAST_TYPE']))
		)
		{
			throw new \Bitrix\Rest\RestException(
				"Parameters LAST_ID and LAST_TYPE should be used together.",
				"LAST_ID_AND_LAST_TYPE",
				CRestServer::STATUS_WRONG_REQUEST
			);
		}

		if (isset($arParams['LAST_ID']) )
		{
			if (!preg_match('/^\d+$/', $arParams['LAST_ID']))
			{
				throw new \Bitrix\Rest\RestException(
					"Last notification ID can't be string",
					"LAST_ID_STRING",
					CRestServer::STATUS_WRONG_REQUEST
				);
			}

			$options['LAST_ID'] = (int)$arParams['LAST_ID'];
			$options['LAST_TYPE'] = (int)$arParams['LAST_TYPE'];
		}

		if (isset($arParams['LIMIT']))
		{
			$options['LIMIT'] = (int)$arParams['LIMIT'] > 50 ? 50 : (int)$arParams['LIMIT'];
		}
		else
		{
			$options['LIMIT'] = 50;
		}

		$options['CONVERT_TEXT'] = isset($arParams['CONVERT_TEXT']) && $arParams['CONVERT_TEXT'] === 'Y';

		return (new \Lang\Academy\Notify($options))->get();
	}
}