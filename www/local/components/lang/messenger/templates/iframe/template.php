<?php
/** @var array $arResult */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $USER;
global $APPLICATION;

$preloadExtensions = [
	'ui.design-tokens',
	// 'sidepanel',
	// 'intranet.sidepanel.bindings',
	// 'intranet.sidepanel.external',
];

// if (\Bitrix\Im\Settings::isLegacyChatActivated())
// {
// 	$preloadExtensions[] = 'socialnetwork.slider';
// }

// \Bitrix\Main\Loader::includeModule("ui");
// \Bitrix\Main\UI\Extension::load('helper');

// $helpUrl = \Bitrix\UI\Util::getHelpdeskUrl(true) . "/widget2/";
// $helpUrl = CHTTP::urlAddParams($helpUrl, [
// 	"url" => urlencode("https://" . $_SERVER["HTTP_HOST"] . $APPLICATION->GetCurPageParam()),
// 	"user_id" => $USER->GetID(),
// 	"is_cloud" => IsModuleInstalled('bitrix24') ? "1" : "0",
// 	"action" => "open",
// ]);

function GetTemplateJS($arParams, $arTemplate)
{
	global $USER;

	$ppStatus = 'false';
	$ppServerStatus = 'false';
	$updateStateInterval = 'auto';
	if (CModule::IncludeModule("pull"))
	{
		$ppStatus = CPullOptions::ModuleEnable()? 'true': 'false';
		$ppServerStatus = CPullOptions::GetNginxStatus()? 'true': 'false';

		$updateStateInterval = CPullOptions::GetNginxStatus()? CIMMessenger::GetSessionLifeTime(): 80;
		if ($updateStateInterval > 100)
		{
			if ($updateStateInterval > 3600)
				$updateStateInterval = 3600;

			if (in_array($arTemplate["CONTEXT"], Array("POPUP-FULLSCREEN", "MESSENGER")))
				$updateStateInterval = $updateStateInterval-60;
			else
				$updateStateInterval = intval($updateStateInterval/2)-10;
		}
	}

	$diskStatus = CIMDisk::Enabled();
	$diskExternalLinkStatus = CIMDisk::EnabledExternalLink();

	$phoneCanPerformCalls = false;
	$phoneDeviceActive = false;
	$phoneCanCallUserNumber = false;
	$phoneEnabled = false;
	$chatExtendShowHistory = false;//\COption::GetOptionInt('im', 'chat_extend_show_history');
	$contactListLoad = false;//\COption::GetOptionInt('im', 'contact_list_load');
	$contactListBirthday = false;//\COption::GetOptionString('im', 'contact_list_birthday');
	$isFullTextEnabled = \Bitrix\Im\Model\MessageIndexTable::getEntity()->fullTextIndexEnabled("SEARCH_CONTENT");
	$fullTextMinSizeToken = \Bitrix\Main\ORM\Query\Filter\Helper::getMinTokenSize();
	$phoneCanInterceptCall = false;//CIMMessenger::CanInterceptCall();

	// if ($arTemplate['INIT'] == 'Y')
	// {
	// 	$phoneEnabled = CIMMessenger::CheckPhoneStatus();
	// 	if ($phoneEnabled && CModule::IncludeModule('voximplant'))
	// 	{
	// 		$phoneCanPerformCalls = CIMMessenger::CanUserPerformCalls();
	// 		$phoneDeviceActive = CVoxImplantUser::GetPhoneActive($USER->GetId());
	// 		$phoneCanCallUserNumber = CIMMessenger::CanUserCallUserNumber();
	// 	}
	// }

	$counters = \Bitrix\Im\Counter::get(null, ['JSON' => 'Y']);
	$counters['type']['mail'] = isset($arParams['MAIL_COUNTER']) ? (int)$arParams['MAIL_COUNTER'] : 0;

	$crmPath = Array();
	$olConfig = Array();
	$businessUsers = false;

	if (\Bitrix\Main\Loader::includeModule('crm'))
	{
		$crmPath['LEAD'] = \Bitrix\Im\Integration\Crm\Common::getLink('LEAD');
		$crmPath['CONTACT'] = \Bitrix\Im\Integration\Crm\Common::getLink('CONTACT');
		$crmPath['COMPANY'] = \Bitrix\Im\Integration\Crm\Common::getLink('COMPANY');
		$crmPath['DEAL'] = \Bitrix\Im\Integration\Crm\Common::getLink('DEAL');
	}

	if (\Bitrix\Main\Loader::includeModule('imopenlines'))
	{
		$olConfig['canDeleteMessage'] = str_replace('.', '_', \Bitrix\Imopenlines\Connector::getListCanDeleteMessage());
		$olConfig['canDeleteOwnMessage'] = str_replace('.', '_', \Bitrix\Imopenlines\Connector::getListCanDeleteOwnMessage());
		$olConfig['canUpdateOwnMessage'] = str_replace('.', '_', \Bitrix\Imopenlines\Connector::getListCanUpdateOwnMessage());

		$olConfig['queue'] = Array();
		foreach (\Bitrix\ImOpenLines\Config::getQueueList($USER->GetID()) as $config)
		{
			$olConfig['queue'][] = array_change_key_case($config, CASE_LOWER);
		}

		foreach (\Bitrix\ImOpenLines\Config::getAllLinesSettings(['CONFIRM_CLOSE']) as $config)
		{
			$lineId = (int)$config['ID'];
			$olConfig['settings'][$lineId] = array_change_key_case($config, CASE_LOWER);

			$connectorStatus = \Bitrix\ImConnector\Status::getInstance(\Bitrix\ImOpenLines\Connector::TYPE_NETWORK, $lineId);
			if ($connectorStatus->isStatus())
			{
				$connectorData = $connectorStatus->getData();
				if (isset($connectorData['MULTIDIALOG']) && $connectorData['MULTIDIALOG'] === 'Y')
				{
					$olConfig['settings'][$lineId]['multidialog'] = 'Y';
				}
				else
				{
					$olConfig['settings'][$lineId]['multidialog'] = 'N';
				}
			}
		}

		$olConfig['canUseVoteHead'] = Imopenlines\Limit::canUseVoteHead();
		$olConfig['canJoinChatUser'] = Imopenlines\Limit::canJoinChatUser();
		$olConfig['canTransferToLine'] = Imopenlines\Limit::canTransferToLine();
	}

	$bitrix24blocked = false;
	$bitrix24Enabled = false;
	$bitrixPaid = true;
	if (\Bitrix\Main\Loader::includeModule('bitrix24'))
	{
		$bitrix24Enabled = true;
		$bitrixPaid = CBitrix24::IsLicensePaid() || CBitrix24::IsNfrLicense();
		if (CIMMessenger::IsBitrix24UserRestricted())
		{
			$bitrix24blocked = \Bitrix\Bitrix24\Limits\User::getUserRestrictedHelperCode();
		}
	}

	$userBirthday = \Bitrix\Im\Integration\Intranet\User::getBirthdayForToday();

	$pathToIm = isset($arTemplate['PATH_TO_IM']) ? $arTemplate['PATH_TO_IM'] : '';
	$pathToCall = isset($arTemplate['PATH_TO_CALL']) ? $arTemplate['PATH_TO_CALL'] : '';
	$pathToFile = isset($arTemplate['PATH_TO_FILE']) ? $arTemplate['PATH_TO_FILE'] : '';
	$pathToLf = isset($arTemplate['PATH_TO_LF']) ? $arTemplate['PATH_TO_LF'] : '/';
	$pathToDisk = Array(
		'localFile' => \CIMDisk::GetLocalDiskFilePath(),
	);

	$recentLastUpdate = (new \Bitrix\Main\Type\DateTime())->format(\DateTimeInterface::RFC3339);
	$recent = \Bitrix\Im\Recent::getList(null, [
		'SKIP_NOTIFICATION' => 'Y',
		'SKIP_OPENLINES' => 'Y',
		'JSON' => 'Y',
		'GET_ORIGINAL_TEXT' => 'Y',
		//'SHORT_INFO' => 'Y',
	]);

	$iframeRequest = $_REQUEST['IFRAME'] ?? null;
	$iframeTypeRequest = $_REQUEST['IFRAME_TYPE'] ?? null;
	$imMenuRequest = $_REQUEST['IM_MENU'] ?? null;
	$imSearchRequest = $_REQUEST['IM_SEARCH'] ?? null;
	$imStatusRequest = $_REQUEST['IM_STATUS'] ?? null;
	$imRecentRequest = $_REQUEST['IM_RECENT'] ?? null;
	$imLinesRequest = $_REQUEST['IM_LINES'] ?? null;
	$imV2LayoutRequest = $_REQUEST['IM_V2_LAYOUT'] ?? null;
	if (!isset($_REQUEST['IM_BACKGROUND']))
	{
		$imBackgroundRequest = 'auto';
	}
	else
	{
		$imBackgroundRequest = $_REQUEST['IM_BACKGROUND'] === 'dark'? 'dark': 'light';
	}

	if (is_bool($arTemplate["DESKTOP"]))
	{
		$arTemplate["DESKTOP"] = $arTemplate["DESKTOP"] ? 'true' : 'false';
	}

	$sJS = "
		BX.ready(function() {
			BXIM = new BX.IM(BX('bx-notifier-panel'), {
				'init': ".($arTemplate['INIT'] == 'Y'? 'true': 'false').",
				'context': '".$arTemplate["CONTEXT"]."',
				'design': '".$arTemplate["DESIGN"]."',
				'colors': ".(\Bitrix\Im\Color::isEnabled()? \Bitrix\Im\Common::objectEncode(\Bitrix\Im\Color::getSafeColorNames()): 'false').",
				'colorsHex': ".\Bitrix\Im\Common::objectEncode(\Bitrix\Im\Color::getSafeColors()).",
				'chatCounters': ".\Bitrix\Im\Common::objectEncode($counters, true).",
				'counters': ".(empty($arTemplate['COUNTERS'])? '{}': \Bitrix\Im\Common::objectEncode($arTemplate['COUNTERS'])).",
				'ppStatus': ".$ppStatus.",
				'ppServerStatus': ".$ppServerStatus.",
				'updateStateInterval': '".$updateStateInterval."',
				'openChatEnable': ".(CIMMessenger::CheckEnableOpenChat()? 'true': 'false').",
				'xmppStatus': ".(CIMMessenger::CheckXmppStatusOnline()? 'true': 'false').",
				'isAdmin': ".($USER->IsAdmin()? 'true': 'false').",
				'canInvite': ".(\Bitrix\Im\Integration\Intranet\User::canInvite()? 'true': 'false').",
				'isLinesOperator': 'true',
				'isUtfMode': true,
				'bitrixNetwork': ".(CIMMessenger::CheckNetwork()? 'true': 'false').",
				'bitrix24': ".($bitrix24Enabled? 'true': 'false').",
				'bitrix24blocked': ".($bitrix24blocked? $bitrix24blocked: 'false').",
				'bitrix24net': false, //".(IsModuleInstalled('b24network')? 'true': 'false').",
				'bitrixPaid': ".($bitrixPaid? 'true': 'false').",
				'bitrixIntranet': ".(IsModuleInstalled('intranet')? 'true': 'false').",
				'bitrixXmpp': ".(IsModuleInstalled('xmpp')? 'true': 'false').",
				'bitrixMobile': ".(IsModuleInstalled('mobile')? 'true': 'false').",
				'bitrixOpenLines': ".(IsModuleInstalled('imopenlines')? 'true': 'false').",
				'bitrixCrm': ".(IsModuleInstalled('crm')? 'true': 'false').",
				'desktop': ".($arTemplate["DESKTOP"] ?? 'false').",
				'desktopStatus': ".(CIMMessenger::CheckDesktopStatusOnline()? 'true': 'false').",
				'desktopVersion': ".CIMMessenger::GetDesktopVersion().",
				'desktopLinkOpen': ".($arTemplate["DESKTOP_LINK_OPEN"] ?? 'false').",
				'language': '".LANGUAGE_ID."',
				'loggerConfig': ".\Bitrix\Im\Common::objectEncode(\Bitrix\Im\Settings::getLoggerConfig(), true).",
				'broadcastingEnabled': ".\Bitrix\Im\Common::objectEncode(\Bitrix\Im\Settings::isBroadcastingEnabled(), true).",
				'tooltipShowed': ".\Bitrix\Im\Common::objectEncode(CUserOptions::GetOption('im', 'tooltipShowed', array())).",
				'limit': ".(empty($arTemplate['LIMIT'])? 'false': \Bitrix\Im\Common::objectEncode($arTemplate["LIMIT"])).",
				'promo': ".(empty($arTemplate['PROMO'])? '[]': \Bitrix\Im\Common::objectEncode($arTemplate["PROMO"])).",
				'bot': ".(empty($arTemplate['BOT'])? '{}': \Bitrix\Im\Common::objectEncode($arTemplate["BOT"])).",
				'textareaIcon': ".(empty($arTemplate['TEXTAREA_ICON'])? '{}': \Bitrix\Im\Common::objectEncode($arTemplate["TEXTAREA_ICON"])).",
				// 'command': ".(empty($arTemplate['COMMAND'])? '[]': \Bitrix\Im\Common::objectEncode($arTemplate["COMMAND"])).",

				'smile': ".\Bitrix\Im\Common::objectEncode($arTemplate["SMILE"]).",
				'smileSet': ".\Bitrix\Im\Common::objectEncode($arTemplate["SMILE_SET"]).",
				'settings': ".\Bitrix\Im\Common::objectEncode($arTemplate['SETTINGS']).",
				'settingsNotifyBlocked': ".(empty($arTemplate['SETTINGS_NOTIFY_BLOCKED'])? '{}': \Bitrix\Im\Common::objectEncode($arTemplate['SETTINGS_NOTIFY_BLOCKED'])).",

				'recent': ".\Bitrix\Im\Common::objectEncode($recent, true).",
				'recentLastUpdate': '".$recentLastUpdate."',
				'businessUsers': ".($businessUsers === false? 'false': (empty($businessUsers)? '{}': \Bitrix\Im\Common::objectEncode($businessUsers))).",
				'userChatOptions': ".\Bitrix\Im\Common::objectEncode(CIMChat::GetChatOptions()).",
				'historyOptions' : ".\Bitrix\Im\Common::objectEncode(['fullTextEnabled' => $isFullTextEnabled, 'ftMinSizeToken' => $fullTextMinSizeToken]).",
				'openMessenger' : ".(isset($_REQUEST['IM_DIALOG'])? "'".CUtil::JSEscape(htmlspecialcharsbx($_REQUEST['IM_DIALOG']))."'": 'false').",
				'openHistory' : ".(isset($_REQUEST['IM_HISTORY'])? "'".CUtil::JSEscape(htmlspecialcharsbx($_REQUEST['IM_HISTORY']))."'": 'false').",
				'openNotify' : ".(isset($_GET['IM_NOTIFY']) && $_GET['IM_NOTIFY'] == 'Y'? 'true': 'false').",
				'openSettings' : ".(isset($_GET['IM_SETTINGS'])? $_GET['IM_SETTINGS'] == 'Y'? "'true'": "'".CUtil::JSEscape(htmlspecialcharsbx($_GET['IM_SETTINGS']))."'": 'false').",
				'externalRecentList' : '".(isset($arTemplate['EXTERNAL_RECENT_LIST'])?$arTemplate['EXTERNAL_RECENT_LIST']: '')."',

				'generalChatId': ".CIMChat::GetGeneralChatId().",
				'canSendMessageGeneralChat': ".(CIMChat::CanSendMessageToGeneralChat($USER->GetID())? 'true': 'false').",
				'debug': ".(defined('IM_DEBUG')? 'true': 'false').",
				'next': ".(defined('IM_NEXT')? 'true': 'false').",
				'userId': ".$USER->GetID().",
				'userEmail': '".CUtil::JSEscape($USER->GetEmail())."',
				'userColor': '".\Bitrix\Im\Color::getCode(\Bitrix\Im\User::getInstance()->getColor())."',
				'userGender': '".\Bitrix\Im\User::getInstance()->getGender()."',
				'userExtranet': ".(\Bitrix\Im\User::getInstance()->isExtranet()? 'true': 'false').",
				'user': ".($arTemplate['CURRENT_USER']? \Bitrix\Im\Common::objectEncode($arTemplate['CURRENT_USER']): '{}').",
				'userBirthday': ".(!empty($userBirthday)? \Bitrix\Im\Common::objectEncode($userBirthday): '[]').",
				'webrtc': {
					'turnServer' : '".CUtil::JSEscape($arTemplate['TURN_SERVER'])."',
					'turnServerFirefox' : '".CUtil::JSEscape($arTemplate['TURN_SERVER_FIREFOX'])."',
					'turnServerLogin' : '".CUtil::JSEscape($arTemplate['TURN_SERVER_LOGIN'])."',
					'turnServerPassword' : '".CUtil::JSEscape($arTemplate['TURN_SERVER_PASSWORD'])."',
					'bitrixCallEnabled': ".(\Bitrix\Main\Loader::includeModule('call') && \Bitrix\Call\Call::isCallServerEnabled() ? 'true' : 'false').",
					'mobileSupport': false,
					'phoneEnabled': ".($phoneEnabled? 'true': 'false').",
					'phoneDeviceActive': '".($phoneDeviceActive? 'Y': 'N')."',
					'phoneCanPerformCalls': '".($phoneCanPerformCalls? 'Y': 'N')."',
					'phoneCanCallUserNumber': '".($phoneCanCallUserNumber? 'Y': 'N')."',
					'phoneCanInterceptCall': ".($phoneCanInterceptCall? 'true': 'false').",
					'phoneCallCardRestApps': ".\Bitrix\Im\Common::objectEncode(CIMMessenger::GetCallCardRestApps()).",
					'phoneDefaultLineId': '".CIMMessenger::GetDefaultTelephonyLine()."',
					'availableLines': ".\Bitrix\Im\Common::objectEncode(CIMMessenger::GetTelephonyAvailableLines()).",
					'formatRecordDate': '".\Bitrix\Main\Context::getCurrent()->getCulture()->getShortDateFormat()."'
				},
				'openlines': ".\Bitrix\Im\Common::objectEncode($olConfig).",
				'options': {
					'contactListLoad' : false, //".($contactListLoad? 'true': 'false').",
					'contactListBirthday' : '".$contactListBirthday."',
					'chatExtendShowHistory' : false, //".($chatExtendShowHistory? 'true': 'false').",
					'frameMode': ".($iframeRequest === 'Y'? 'true': 'false').",
					'frameType': '".($iframeTypeRequest === 'SIDE_SLIDER'? 'SIDE_SLIDER': 'NONE')."',
					'showRecent': ".($imRecentRequest === 'N'? 'false': 'true').",
					'showMenu': true, //".($imMenuRequest === 'N'? 'false': 'true').",
					'showSearch': false, //".($imSearchRequest === 'N'? 'false': 'true').",
					'showStatus': false,//".($imStatusRequest === 'N'? 'false': 'true').",
					'openLinesRecent': ".($imLinesRequest === 'Y'? 'true': 'false').",
					'v2layout': ".($imV2LayoutRequest === 'Y'? 'true': 'false').",
					'background': '".$imBackgroundRequest."'
				},
				'disk': {'enable' : ".($diskStatus? 'true': 'false').", 'external' : ".($diskExternalLinkStatus? 'true': 'false')."},
				'zoomStatus': {
					'active' : ".(\Bitrix\Im\Integration\Socialservices\Zoom::isActive()? 'true': 'false').",
					'enabled' : ".(\Bitrix\Im\Integration\Socialservices\Zoom::isAvailable()? 'true': 'false').",
					'connected' : ".(\Bitrix\Im\Integration\Socialservices\Zoom::isConnected($USER->GetID())? 'true': 'false')."
				},
				'path' : {
					'lf' : '".CUtil::JSEscape($pathToLf)."',
					'profile' : '".CUtil::JSEscape($arTemplate['PATH_TO_USER_PROFILE'])."',
					'profileTemplate' : '".CUtil::JSEscape($arTemplate['PATH_TO_USER_PROFILE_TEMPLATE'])."',
					'mail' : '".CUtil::JSEscape($arTemplate['PATH_TO_USER_MAIL'])."',
					'im': '".CUtil::JSEscape($pathToIm)."',
					'call': '".CUtil::JSEscape($pathToCall)."',
					'file': '".CUtil::JSEscape($pathToFile)."',
					'crm' : ".\Bitrix\Im\Common::objectEncode($crmPath).",
					'disk' : ".\Bitrix\Im\Common::objectEncode($pathToDisk)."
				}
			});
		});
	";

	return $sJS;
}
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=<?=SITE_CHARSET?>"/>
		<?//php \Bitrix\Main\UI\Extension::load($preloadExtensions); ?>
		<?php $APPLICATION->ShowCSS(); ?>
		<?php $APPLICATION->ShowHeadStrings(); ?>
		<?
		$loadOrder = SITE_TEMPLATE_PATH . '/js/load.order.js';
		?>
		<script src="<?=$loadOrder?>?<?=md5_file($_SERVER['DOCUMENT_ROOT'] . $loadOrder)?>"></script>
		<?php $APPLICATION->ShowHeadScripts(); ?>
		<title><?php $APPLICATION->ShowTitle(); ?></title>
		<style>
			.bx-desktop-tab-icon-order {
				background-image: url('data:image/svg+xml,<?=rawurlencode('<svg width="14" height="14" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 16C4.9 16 4.01 16.9 4.01 18C4.01 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16ZM0 0V2H2L5.6 9.59L4.25 12.04C4.09 12.32 4 12.65 4 13C4 14.1 4.9 15 6 15H18V13H6.42C6.28 13 6.17 12.89 6.17 12.75L6.2 12.63L7.1 11H14.55C15.3 11 15.96 10.59 16.3 9.97L19.88 3.48C19.9625 3.32739 20.004 3.15598 20.0005 2.98253C19.9969 2.80908 19.9485 2.63951 19.8598 2.49039C19.7711 2.34127 19.6453 2.2177 19.4946 2.13175C19.3439 2.04579 19.1735 2.0004 19 2H4.21L3.27 0H0ZM16 16C14.9 16 14.01 16.9 14.01 18C14.01 19.1 14.9 20 16 20C17.1 20 18 19.1 18 18C18 16.9 17.1 16 16 16Z" fill="#93979B" fill-rule="evenodd" /></svg>')?>');
			}
			.bx-desktop-tab-order.bx-desktop-tab-active .bx-desktop-tab-icon-order,
			.bx-desktop-tab:hover .bx-desktop-tab-icon-order {
				background-image: url('data:image/svg+xml,<?=rawurlencode('<svg width="14" height="14" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 16C4.9 16 4.01 16.9 4.01 18C4.01 19.1 4.9 20 6 20C7.1 20 8 19.1 8 18C8 16.9 7.1 16 6 16ZM0 0V2H2L5.6 9.59L4.25 12.04C4.09 12.32 4 12.65 4 13C4 14.1 4.9 15 6 15H18V13H6.42C6.28 13 6.17 12.89 6.17 12.75L6.2 12.63L7.1 11H14.55C15.3 11 15.96 10.59 16.3 9.97L19.88 3.48C19.9625 3.32739 20.004 3.15598 20.0005 2.98253C19.9969 2.80908 19.9485 2.63951 19.8598 2.49039C19.7711 2.34127 19.6453 2.2177 19.4946 2.13175C19.3439 2.04579 19.1735 2.0004 19 2H4.21L3.27 0H0ZM16 16C14.9 16 14.01 16.9 14.01 18C14.01 19.1 14.9 20 16 20C17.1 20 18 19.1 18 18C18 16.9 17.1 16 16 16Z" fill="#17b0e1" fill-rule="evenodd" /></svg>')?>');
			}
		</style>
	</head>
	<body class="<?php $APPLICATION->ShowProperty("BodyClass"); ?>">

	<?
		// echo '<pre>', print_r($arResult, 1), '</pre>';
		?>
		<div class="bx-desktop-placeholder" id="workarea-content"></div>

		<script>
			document.title = '<?= GetMessage('IM_FULLSCREEN_TITLE_2') ?>';
			<?=GetTemplateJS(Array(), $arResult) ?>
		</script>


		<?/*<script>
			BX.Helper.init({
				frameOpenUrl : '<?= CUtil::JSEscape($helpUrl) ?>',
				langId: '<?= LANGUAGE_ID ?>'
			});
		</script>*/?>

	</body>
</html>