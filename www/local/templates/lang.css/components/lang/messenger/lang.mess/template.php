<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $USER;
global $APPLICATION;

use Bitrix\Main\Loader;

$jsFilePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/js/im';

// $preloadExtensions = [
// 	'ui.design-tokens',
// 	// 'sidepanel',
// 	// 'intranet.sidepanel.bindings',
// 	// 'intranet.sidepanel.external',
// ];

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

	if (Loader::includeModule("pull")) {
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

	// if (\Bitrix\Main\Loader::includeModule('crm'))
	// {
	// 	$crmPath['LEAD'] = \Bitrix\Im\Integration\Crm\Common::getLink('LEAD');
	// 	$crmPath['CONTACT'] = \Bitrix\Im\Integration\Crm\Common::getLink('CONTACT');
	// 	$crmPath['COMPANY'] = \Bitrix\Im\Integration\Crm\Common::getLink('COMPANY');
	// 	$crmPath['DEAL'] = \Bitrix\Im\Integration\Crm\Common::getLink('DEAL');
	// }

	// if (\Bitrix\Main\Loader::includeModule('imopenlines'))
	// {
	// 	$olConfig['canDeleteMessage'] = str_replace('.', '_', \Bitrix\Imopenlines\Connector::getListCanDeleteMessage());
	// 	$olConfig['canDeleteOwnMessage'] = str_replace('.', '_', \Bitrix\Imopenlines\Connector::getListCanDeleteOwnMessage());
	// 	$olConfig['canUpdateOwnMessage'] = str_replace('.', '_', \Bitrix\Imopenlines\Connector::getListCanUpdateOwnMessage());

	// 	$olConfig['queue'] = Array();
	// 	foreach (\Bitrix\ImOpenLines\Config::getQueueList($USER->GetID()) as $config)
	// 	{
	// 		$olConfig['queue'][] = array_change_key_case($config, CASE_LOWER);
	// 	}

	// 	foreach (\Bitrix\ImOpenLines\Config::getAllLinesSettings(['CONFIRM_CLOSE']) as $config)
	// 	{
	// 		$lineId = (int)$config['ID'];
	// 		$olConfig['settings'][$lineId] = array_change_key_case($config, CASE_LOWER);

	// 		$connectorStatus = \Bitrix\ImConnector\Status::getInstance(\Bitrix\ImOpenLines\Connector::TYPE_NETWORK, $lineId);
	// 		if ($connectorStatus->isStatus())
	// 		{
	// 			$connectorData = $connectorStatus->getData();
	// 			if (isset($connectorData['MULTIDIALOG']) && $connectorData['MULTIDIALOG'] === 'Y')
	// 			{
	// 				$olConfig['settings'][$lineId]['multidialog'] = 'Y';
	// 			}
	// 			else
	// 			{
	// 				$olConfig['settings'][$lineId]['multidialog'] = 'N';
	// 			}
	// 		}
	// 	}

	// 	$olConfig['canUseVoteHead'] = Imopenlines\Limit::canUseVoteHead();
	// 	$olConfig['canJoinChatUser'] = Imopenlines\Limit::canJoinChatUser();
	// 	$olConfig['canTransferToLine'] = Imopenlines\Limit::canTransferToLine();
	// }

	$bitrix24blocked = false;
	$bitrix24Enabled = false;
	$bitrixPaid = true;
	// if (\Bitrix\Main\Loader::includeModule('bitrix24'))
	// {
	// 	$bitrix24Enabled = true;
	// 	$bitrixPaid = CBitrix24::IsLicensePaid() || CBitrix24::IsNfrLicense();
	// 	if (CIMMessenger::IsBitrix24UserRestricted())
	// 	{
	// 		$bitrix24blocked = \Bitrix\Bitrix24\Limits\User::getUserRestrictedHelperCode();
	// 	}
	// }

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
		'SKIP_DIALOG' => 'N',
		//'SHORT_INFO' => 'Y',
	]);

	// echo '<pre>', print_r($recent), '</pre>';

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

	$chatOptions = CIMChat::GetChatOptions();

	$sJS = "
		BX.ready(function() {
			BXIM = new BX.IM(document.createElement('div'), {
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
				'openChatEnable': true, //".(CIMMessenger::CheckEnableOpenChat()? 'true': 'false').",
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
				'desktopStatus': false,
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
				'externalRecentList' : '".(isset($arTemplate['EXTERNAL_RECENT_LIST'])?$arTemplate['EXTERNAL_RECENT_LIST']: '')."',

				'smile': ".\Bitrix\Im\Common::objectEncode($arTemplate["SMILE"]).",
				'smileSet': ".\Bitrix\Im\Common::objectEncode($arTemplate["SMILE_SET"]).",
				'settings': ".\Bitrix\Im\Common::objectEncode($arTemplate['SETTINGS']).",
				'settingsNotifyBlocked': ".(empty($arTemplate['SETTINGS_NOTIFY_BLOCKED'])? '{}': \Bitrix\Im\Common::objectEncode($arTemplate['SETTINGS_NOTIFY_BLOCKED'])).",

				'recent': ".\Bitrix\Im\Common::objectEncode($recent, true).",
				'recentLastUpdate': '".$recentLastUpdate."',
				'businessUsers': ".($businessUsers === false? 'false': (empty($businessUsers)? '{}': \Bitrix\Im\Common::objectEncode($businessUsers))).",
				'userChatOptions': ".\Bitrix\Im\Common::objectEncode($chatOptions).",
				'historyOptions' : ".\Bitrix\Im\Common::objectEncode(['fullTextEnabled' => $isFullTextEnabled, 'ftMinSizeToken' => $fullTextMinSizeToken]).",
				'openMessenger' : ".(isset($_REQUEST['IM_DIALOG'])? "'".CUtil::JSEscape(htmlspecialcharsbx($_REQUEST['IM_DIALOG']))."'": 'false').",
				'openHistory' : ".(isset($_REQUEST['IM_HISTORY'])? "'".CUtil::JSEscape(htmlspecialcharsbx($_REQUEST['IM_HISTORY']))."'": 'false').",
				'openNotify' : ".(isset($_GET['IM_NOTIFY']) && $_GET['IM_NOTIFY'] == 'Y'? 'true': 'false').",
				'openSettings' : ".(isset($_GET['IM_SETTINGS'])? $_GET['IM_SETTINGS'] == 'Y'? "'true'": "'".CUtil::JSEscape(htmlspecialcharsbx($_GET['IM_SETTINGS']))."'": 'false').",

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
					'contactListLoad' : true, //".($contactListLoad? 'true': 'false').",
					'contactListBirthday' : '".$contactListBirthday."',
					'chatExtendShowHistory' : false, //".($chatExtendShowHistory? 'true': 'false').",
					'frameMode': false, //".($iframeRequest === 'Y'? 'true': 'false').",
					'frameType': 'NONE', //".($iframeTypeRequest === 'SIDE_SLIDER'? 'SIDE_SLIDER': 'NONE')."',
					'showRecent': true, //".($imRecentRequest === 'N'? 'false': 'true').",
					'showMenu': true, //".($imMenuRequest === 'N'? 'false': 'true').",
					'showSearch': true, //".($imSearchRequest === 'N'? 'false': 'true').",
					'showStatus': false, //".($imStatusRequest === 'N'? 'false': 'true').",
					'openLinesRecent': false, //".($imLinesRequest === 'Y'? 'true': 'false').",
					'v2layout': false, //".($imV2LayoutRequest === 'Y'? 'true': 'false').",
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

// echo '<pre>', print_r(CIMChat::GetChatOptions(), 1), '</pre>';
?>

<style>
	.bx-im-recent-item {
		margin: 0;
		height: auto;
		padding: 0.75rem 1rem;
		align-items: center;
		column-gap: 1rem;
		border-bottom: 1px solid var(--im-recent-separator-line);
		
		.bx-im-recent-item-image-wrap {
			margin: 0;
			width: 3rem;
			height: 3rem;

			.bx-im-recent-icon-avatar-bottom-right {
				top: auto;
				right: 0;
				bottom: 0;
				left: auto;
			}
		}

		.bx-im-recent-item-content {
			margin: 0;
			padding: 0;
			border: none;
		}
	}
</style>

<script>
	BX.IM.prototype.initLeftPanelApp = function()
	{
		if (this.leftPanelAppLaunched)
		{
			return Promise.resolve();
		}

		var applicationConfig = {};
		if (BX.MessengerCommon.isDesktop())
		{
			applicationConfig.preloadedList = this.newRecent;
			applicationConfig.chatOptions = this.userChatOptions;
		}

		applicationConfig.layout = this.options.v2layout === true
			? BX.Messenger.Embedding.Const.ApplicationLayout.lines
			: BX.Messenger.Embedding.Const.ApplicationLayout.full
		;

		// return BX.Messenger.Application.Launch('recent', applicationConfig).then(function(application) {
		// 	// console.info(application.template)
		// 	this.leftpanelApp = application;
		// 	this.leftPanelAppLaunched = true;
		// }.bind(this))
	};
</script>

<script>
	BX.MessengerChat.prototype.setClosingByEsc = function(result) {}
</script>

<script><?=file_get_contents("{$jsFilePath}/openMessenger.js")?></script>
<script><?//=file_get_contents("{$jsFilePath}/MessengerWindow.js")?></script>
<script><?//=file_get_contents("{$jsFilePath}/MessengerNotify.js")?></script>
<script><?=file_get_contents("{$jsFilePath}/openNotify.js")?></script>
<script><?=file_get_contents("{$jsFilePath}/closeNotify.js")?></script>
<script><?=file_get_contents("{$jsFilePath}/openDialog.js")?></script>
<script><?=file_get_contents("{$jsFilePath}/updateNotifyCount.js")?></script>


<script>
	ImEventHandler.prototype.bindEvents = function() {
		if (!BX.Messenger.Embedding)
		{
			this.BXIM.errorMessage = BX.message('IM_M_OLD_REVISION');
			return false;
		}

		var EventType = BX.Messenger.Embedding.Const.EventType;

		BX.Event.EventEmitter.subscribe(EventType.dialog.open, function(event) {
			BX.MessengerProxy.addChatData(event.data);

			var openDesktop = event.data.target === BX.Messenger.Embedding.Const.OpenTarget.auto;

			var target = this;
			if (
				this.BXIM.options.v2layout
				&& (
					!BX.MessengerCommon.isChatId(event.data.dialogId)
					|| event.data.chat.type !== "lines"
				)
			)
			{
				target = top;
			}

			target.BXIM.openMessenger(event.data.id, undefined, !openDesktop);
		}.bind(this));

		// BX.Event.EventEmitter.subscribe(EventType.dialog.call, function(event){
		// 	this.BXIM.callTo(event.data.id, true)
		// }.bind(this));

		BX.Event.EventEmitter.subscribe(EventType.dialog.openHistory, function(event){
			BX.MessengerProxy.addChatData(event.data);
			this.BXIM.messenger.openHistory(event.data.id);
		}.bind(this));

		BX.Event.EventEmitter.subscribe(EventType.dialog.hide, function(event){
			BX.MessengerProxy.addChatData(event.data);
			BX.MessengerCommon.recentListHide(event.data.id);
		}.bind(this));

		BX.Event.EventEmitter.subscribe(EventType.dialog.leave, function(event){
			BX.MessengerProxy.addChatData(event.data);
			BX.MessengerCommon.leaveFromChat(event.data.id.slice(4));
		}.bind(this));
	};

	MessengerProxy.prototype.addChatData = function(data) {
		var dialogId = data.id;
		var isChat = dialogId.startsWith('chat');

		if (isChat && !this.BXIM.messenger.chat[dialogId.substring(4)])
		{
			var cutDialogId = dialogId.substring(4);
			var chat = data.item;

			this.BXIM.messenger.chat[cutDialogId] = {
				avatar: chat.avatar,
				color: chat.color,
				id: chat.id,
				name: BX.util.htmlspecialchars(chat.title.value),
				type: chat.type,
				entity_id: chat.entity_id,
				entity_data_1: chat.entity_data_1,
				entity_data_2: chat.entity_data_2,
			};
		}
		else if (!isChat && !this.BXIM.messenger.users[dialogId])
		{
			var user = data.user;
			this.BXIM.messenger.users[dialogId] = {
				id: dialogId,
				first_name: BX.util.htmlspecialchars(user.firstName),
				last_name: BX.util.htmlspecialchars(user.lastName),
				name: BX.util.htmlspecialchars(user.name),
				gender: user.gender,
				status: user.status,
				work_position: BX.util.htmlspecialchars(user.workPosition),
				avatar: data.chat.avatar,
				color: data.chat.color,
				bot: user.bot,
				absent: user.absent,
				extranet: user.extranet,
				network: user.network,
				idle: user.idle,
				last_activity_date: user.lastActivityDate,
				mobile_last_date: user.mobileLastDate
			};
		}

		if (isChat && !this.BXIM.messenger.userInChat[dialogId.substring(4)])
		{
			var cutDialogId = dialogId.substring(4);
			var currentUserId = this.BXIM.userId;
			this.BXIM.messenger.userInChat[cutDialogId] = [currentUserId];
		}
	}
</script>

<script><?=GetTemplateJS(Array(), $arResult)?></script>
<script>BX.PULL.start()</script>