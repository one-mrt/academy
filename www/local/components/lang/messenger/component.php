<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($_REQUEST['AJAX_CALL']) && $_REQUEST['AJAX_CALL'] == 'Y')
	return;

if (defined('IM_COMPONENT_INIT'))
	return;
else
	define("IM_COMPONENT_INIT", true);

if (intval($USER->GetID()) <= 0)
	return;

if (!CModule::IncludeModule('im'))
	return;

// if (!\Bitrix\Im\Settings::isLegacyChatActivated())
// {
// 	\Bitrix\Main\UI\Extension::load(['planner']);

// 	$arResult['MESSENGER_V2'] = true;
// 	$arResult['DESKTOP'] = $arParams['CONTEXT'] === 'DESKTOP';
// 	$arResult['COPILOT_AVAILABLE'] = \Bitrix\Im\V2\Chat\CopilotChat::isActive();
// 	// for soft dependence on module intranet
// 	$arResult['COPILOT_AVAILABLE_TAB'] = \Bitrix\Im\V2\Chat\CopilotChat::isAvailable();
// 	if ($arResult['DESKTOP'] === true)
// 	{
// 		CIMMessenger::SetDesktopVersion(empty($_GET['BXD_API_VERSION'])? 0 : $_GET['BXD_API_VERSION']);
// 		CIMMessenger::SetDesktopStatusOnline(null, false);
// 	}

// 	if (CModule::IncludeModule('disk'))
// 	{
// 		\Bitrix\Main\UI\Extension::load(['file_dialog', 'im.integration.viewer']);
// 	}
// 	CModule::IncludeModule('voximplant');

// 	$this->IncludeComponentTemplate();
// 	return;
// }

// CModule::IncludeModule('voximplant');
// CModule::IncludeModule('disk');

$arParams['DESKTOP'] = isset($arParams['DESKTOP']) && $arParams['DESKTOP'] == 'Y'? 'Y': 'N';
$arParams['CONTEXT'] = isset($arParams['CONTEXT']) ? $arParams['CONTEXT'] : '';

$arResult = Array();

$isFullscreen = $arParams['FULLSCREEN'] ?? null;

$arParams["INIT"] = 'Y';

if ($arParams['CONTEXT'] == 'DESKTOP' || $arParams['DESKTOP'] == 'Y')
{
	$darkClass = \CIMSettings::GetSetting(CIMSettings::SETTINGS, 'isCurrentThemeDark')? 'bx-messenger-dark': '';
	$GLOBALS["APPLICATION"]->SetPageProperty("BodyClass", "im-desktop $darkClass");

	CIMMessenger::SetDesktopVersion(empty($_GET['BXD_API_VERSION'])? 0 : $_GET['BXD_API_VERSION']);
	CIMMessenger::SetDesktopStatusOnline(null, false);

	$arParams["DESIGN"] = "DESKTOP";
	$arResult["CONTEXT"] = "DESKTOP";

	$event = new \Bitrix\Main\Event("im", "onDesktopStart", array('USER_ID' => $USER->GetID()));
	$event->send();
}
else if ($arParams["CONTEXT"] == "FULLSCREEN" || $isFullscreen)
{
	$APPLICATION->SetPageProperty("BodyClass", "bx-im-fullscreen bx-language-".LANGUAGE_ID);
	if (!isset($arParams["DESIGN"]))
	{
		$arParams["DESIGN"] = "DESKTOP";
	}
	$arResult["CONTEXT"] = "FULLSCREEN";
}
else if ($arParams["CONTEXT"] == "PAGE")
{
	$arResult["CONTEXT"] = "PAGE";
	$arParams["DESIGN"] = "DESKTOP";
}
else if ($arParams["CONTEXT"] == "POPUP-FULLSCREEN")
{
	$arResult["CONTEXT"] = "POPUP-FULLSCREEN";
	$arParams["DESIGN"] = "DESKTOP";
}
else if ($arParams["CONTEXT"] == "DIALOG")
{
	$arResult["CONTEXT"] = "DIALOG";
	$arParams["DESIGN"] = "DESKTOP";
}
else if ($arParams["CONTEXT"] == "LINES")
{
	$arResult["CONTEXT"] = "LINES";
	$arParams["DESIGN"] = "DESKTOP";
}
else if ($arParams["CONTEXT"] == "HISTORY-FULLSCREEN")
{
	$arResult["CONTEXT"] = "HISTORY-FULLSCREEN";
	$arParams["DESIGN"] = "DESKTOP";
	$arParams["INIT"] = 'N';
}
else
{
	$arResult["CONTEXT"] = "MESSENGER";
	$arResult["DESIGN"] = "POPUP";
}

// Counters
$arResult["COUNTERS"] = CUserCounter::GetValues($USER->GetID(), SITE_ID);

CIMContactList::SetOnline(null, $arResult["CONTEXT"] != "DESKTOP");

$arSettings = CIMSettings::Get();
$arResult['SETTINGS'] = $arSettings['settings'];

if (isset($arParams['DESIGN']))
{
	$arResult["DESIGN"] = $arParams['DESIGN'];
}


$arParams["DESKTOP_LINK_OPEN"] = 'N';

// Exchange
$arResult["PATH_TO_USER_MAIL"] = "";
$arResult["MAIL_COUNTER"] = 0;
if ($arParams["INIT"] == 'Y')
{
	if (CIMMail::IsExternalMailAvailable())
	{
		$arResult["PATH_TO_USER_MAIL"] = $arParams['PATH_TO_SONET_EXTMAIL'] ?? null;
		$arResult["MAIL_COUNTER"] = (int)($arResult["COUNTERS"]["mail_unseen"] ?? null);
	}
	else if (CModule::IncludeModule("dav"))
	{
		$ar = CDavExchangeMail::GetTicker($GLOBALS["USER"]);
		if ($ar !== null)
		{
			$arResult["PATH_TO_USER_MAIL"] = $ar["exchangeMailboxPath"];
			$arResult["MAIL_COUNTER"] = intval($ar["numberOfUnreadMessages"]);
		}
	}
}
// Message & Notify

$arResult['SETTINGS_NOTIFY_BLOCKED'] = CIMSettings::GetSimpleNotifyBlocked();

$arResult['CURRENT_USER'] = \CIMContactList::GetUserData(Array(
	'ID' => $USER->GetID(),
	'PHONES' => 'Y',
	'SHOW_ONLINE' => 'N',
	'EXTRA_FIELDS' => 'Y',
	'DATE_ATOM' => 'Y'
))['users'][$USER->GetID()];

if ($arParams["INIT"] == 'Y')
{
	$arSmile = CIMMessenger::PrepareSmiles();
	$arResult['SMILE'] = $arSmile['SMILE'];
	$arResult['SMILE_SET'] = $arSmile['SMILE_SET'];

	if ($arResult["CONTEXT"] == "LINES")
	{
		$arResult['PATH_TO_IM'] = '/online/im.ajax.php';
		$arResult['PATH_TO_CALL'] = '/online/call.ajax.php';
		$arResult['PATH_TO_FILE'] = '/online/file.ajax.php';
	}
	else if ($arResult["CONTEXT"] == "DESKTOP")
	{
		$arResult['PATH_TO_IM'] = '/desktop_app/im.ajax.php';
		$arResult['PATH_TO_CALL'] = '/desktop_app/call.ajax.php';
		$arResult['PATH_TO_FILE'] = '/desktop_app/file.ajax.php';
	}

	if (isset($arParams['CURRENT_TAB']))
	{
		$_REQUEST['IM_DIALOG'] = $arParams['CURRENT_TAB'];
		$arResult['CURRENT_TAB'] = $arParams['CURRENT_TAB'];
	}
}

$arResult['BOT'] = \Bitrix\Im\Bot::getListForJs();
$arResult['COMMAND'] = \Bitrix\Im\Command::getListForJs();
$arResult['TEXTAREA_ICON'] = \Bitrix\Im\App::getListForJs();

$arResult['INIT'] = $arParams['INIT'];
$arResult['DESKTOP'] = $arResult["CONTEXT"] == "DESKTOP"? 'true': 'false';
$arResult['PHONE_ENABLED'] = CIMMessenger::CheckPhoneStatus() && CIMMessenger::CanUserPerformCalls();
$arResult['OL_OPERATOR'] = CModule::IncludeModule('imopenlines') && \Bitrix\ImOpenLines\Config::isOperator($USER->GetID());
$arResult['DESKTOP_LINK_OPEN'] = $arParams['DESKTOP_LINK_OPEN'] == 'Y'? 'true': 'false';
$arResult['PATH_TO_USER_PROFILE_TEMPLATE'] = CIMContactList::GetUserPath();
$arResult['PATH_TO_USER_PROFILE'] = CIMContactList::GetUserPath($USER->GetId());
$arResult['PATH_TO_LF'] = IsModuleInstalled('intranet') && \Bitrix\Main\IO\File::isFileExists(\Bitrix\Main\Application::getDocumentRoot().'/stream/index.php')? '/stream/': '/';



CJSCore::RegisterExt('mess_common', array(
	'js' => '/local/js/langmess/common.js',
	'css' => ['/local/js/langmess/css/common.css', /*'/bitrix/js/im/css/dark_im.css'*/],
	'lang' => '/bitrix/modules/im/js_common.php',
	'rel' => array(/*'ui.design-tokens',*/ 'ls', 'ajax', 'date', 'fx', 'user', 'rest.client', /*'phone_number',*/ 'loader', 'ui.viewer', 'main.md5', 'im.debug', /*'ui.notification'*/)
));

// CJSCore::RegisterExt('mess_desktop_utils', array(
// 	'js' => '/bitrix/js/im/desktop_utils.js',
// ));

$jsIm = [
	'/local/js/langmess/im.js'
];

$jsCoreRel = [
	'ui.design-tokens',
	'ui.fonts.opensans',
	// 'mess_desktop_utils',
	'resize_observer',
	'mess_common',
	'im.lib.localstorage',
	'clipboard',
	// 'sidepanel',
	'loader',
	'ui.notification',
	'ui.alerts',
	'ui.vue',
	'ui.buttons',
	'ui.switcher',
	'ui.hint',
	'langmess.application.launch',
	'langmess.application.left-panel',
	// 'langmess.component.recent',
	// 'langmess.application.recent',
	'im.old-chat-embedding.application.core',
	// 'langmess.entity-selector',
	// 'langmess.old-chat-embedding.component.search'
	'langmess.old-chat-embedding.component.recent-list'
	// 'langmess.old-chat-embedding.application.left-panel',
	// 'im.old-chat-embedding.application.sidebar',
];

CJSCore::RegisterExt('langmess', array(
	'js' => $jsIm,
	'css' => array(
		'/local/js/langmess/css/im.css',
	),
	'lang' => '/bitrix/modules/im/lang/'.LANGUAGE_ID.'/js_im.php',
	'rel' => $jsCoreRel
));


$initJs = 'langmess';
$promoType = \Bitrix\Im\Promotion::DEVICE_TYPE_BROWSER;
// if ($arResult["CONTEXT"] == 'DESKTOP' || (isset($arParams['WITH_DESKTOP']) && $arParams['WITH_DESKTOP']))
// {
// 	$initJs = 'im_desktop';
// 	$promoType = \Bitrix\Im\Promotion::DEVICE_TYPE_DESKTOP;
// }
// else if ($arResult["DESIGN"] == 'DESKTOP')
// {
// 	$initJs = 'im_page';
// }

$arResult['PROMO'] = \Bitrix\Im\Promotion::getActive($promoType);

// if (CModule::IncludeModule('call'))
// {
// 	$arResult['TURN_SERVER'] = COption::GetOptionString('call', 'turn_server');
// 	$arResult['TURN_SERVER_FIREFOX'] = COption::GetOptionString('call', 'turn_server');
// 	$arResult['TURN_SERVER_LOGIN'] = COption::GetOptionString('call', 'turn_server_login');
// 	$arResult['TURN_SERVER_PASSWORD'] = COption::GetOptionString('call', 'turn_server_password');

// 	$arResult['LIMIT'] = \Bitrix\Call\Limit::getTypesForJs();
// }

// echo '<pre>', print_r($initJs, 1), '</pre>';

CJSCore::Init($initJs);
// \Bitrix\Main\UI\Extension::load(['ui.buttons', 'ui.buttons.icons']);

if (!(isset($arParams['TEMPLATE_HIDE']) && $arParams['TEMPLATE_HIDE'] == 'Y'))
	$this->IncludeComponentTemplate();

return $arResult;
