BX.IM.WebRTC = function(BXIM, params)
{
	if (this.parent)
	{
		this.parent.constructor.apply(this, arguments);
	}
	this.BXIM = BXIM;

	this.panel = params.panel;
	this.desktop = params.desktopClass;

	this.callToPhone = false;
	this.bitrixCallEnabled = BX.prop.getBoolean(params, 'bitrixCallEnabled', false);
	this.callOverlayFullScreen = false;

	this.callToMobile = false;

	this.callAspectCheckInterval = null;
	this.callAspectHorizontal = true;
	this.callInviteTimeout = null;
	this.callNotify = null;
	this.callAllowTimeout = null;
	this.callOverlay = null;
	this.callOverlayMinimize = null;
	this.callOverlayChatId = 0;
	this.callOverlayUserId = 0;
	this.callSelfDisabled = false;
	this.callOverlayPhotoSelf = null;
	this.callOverlayPhotoUsers = {};
	this.callOverlayVideoUsers = {};
	this.callOverlayVideoPhotoUsers = {};
	this.callOverlayPhotoCompanion = null;
	this.callOverlayPhotoMini = null;
	this.callOverlayVideoMain = null;
	this.callOverlayVideoReserve = null;
	this.callOverlayVideoSelf = null;
	this.callOverlayProgressBlock = null;
	this.callOverlayStatusBlock = null;
	this.callOverlayButtonsBlock = null;

	this.callView = null;

	this.phoneEnabled = params.phoneEnabled;
	this.phoneCanPerformCalls = params.phoneCanPerformCalls;
	this.phoneDeviceActive = params.phoneDeviceActive == 'Y';
	this.phoneCanCallUserNumber = params.phoneCanCallUserNumber == 'Y';
	this.phoneCallerID = '';
	this.phoneLogin = '';
	this.phoneServer = '';
	this.phoneCheckBalance = false;
	this.phoneCallHistory = {};
	this.phoneHistory = this.BXIM.getLocalConfig('phone-history') || [];

	this.phoneSDKinit = false;
	this.phoneMicAccess = false;
	this.phoneIncoming = false;
	this.phoneCallId = '';
	this.phoneCallTime = 0;
	this.phoneCallConfig = {};
	this.phoneCallExternal = false;
	this.phoneCallDevice = 'WEBRTC';
	this.phonePortalCall = false;
	this.phoneNumber = '';
	this.phoneFullNumber = '';
	this.phoneNumberUser = '';
	this.phoneParams = {};
	this.phoneAPI = null;
	this.phoneDisconnectAfterCallFlag = true;
	this.phoneCurrentCall = null;
	this.phoneCrm = params.phoneCrm? params.phoneCrm: {};
	this.phoneMicMuted = false;
	this.phoneHolded = false;
	this.phoneRinging = 0;
	this.phoneTransferEnabled = false;
	this.phoneTransferTargetType = 'user'; // user|phone
	this.phoneTransferTargetId = '';
	this.phoneTransferCallId = '';
	this.phoneConnectedInterval = null;
	this.phoneDeviceDelayTimeout = null;
	this.phoneLines = null;
	this.phoneDefaultLineId = params.phoneDefaultLineId || false;
	this.phoneAvailableLines = params.phoneAvailableLines || [];

	this.phoneCallView = false;
	this.callListId = 0;
	this.lastCallListCallParams = null;

	this.debug = false;

	this.phoneKeypad = null;
	this.popupTransferDialog = null;
	this.popupTransferDialogDestElements = null;
	this.popupTransferDialogContactListSearch = null;
	this.popupTransferDialogContactListElements = null;

	this.initAudio();
};

BX.IM.WebRTC.prototype.openKeyPad = function(e)
{
};

BX.IM.WebRTC.prototype.closeKeyPad = function(e)
{
};

BX.IM.WebRTC.prototype.onKeyPadClose = function()
{
	if (this.messenger.popupMessenger && /*this.BXIM.design == 'DESKTOP'*/ this.messenger.externalMenu && BX.MessengerCommon.isPage())
	{
		if (BX.MessengerWindow.lastTabTarget != 'im')
		{
			BX.MessengerWindow.changeTab(this.BXIM.dialogOpen ? 'im' : 'notify');
		}
		else
		{
			BX.MessengerWindow.closeTab('im-phone');
		}
	}

	this.messenger.setClosingByEsc(true);
	BX.removeClass(this.messenger.popupContactListSearchCall, 'bx-messenger-input-search-call-active');
};

BX.IM.WebRTC.prototype.ready = function()
{
	return false; //this.enabled;
};


BX.IM.WebRTC.prototype.phoneSupport = function()
{
	return false;
};

BX.IM.WebRTC.prototype.initAudio = function(onlyError)
{
	if (onlyError === true)
	{
		this.panel.appendChild(this.BXIM.audio.error = BX.create("audio", { props : { className : "bx-messenger-audio" }, children : [
			BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-error.ogg", type : "audio/ogg; codecs=vorbis" }}),
			BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-error.mp3", type : "audio/mpeg" }})
		]}));

		return false;
	}

	this.panel.appendChild(this.BXIM.audio.dialtone = BX.create("audio", { props : { className : "bx-messenger-audio" }, children : [
		BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-dialtone.ogg", type : "audio/ogg; codecs=vorbis" }}),
		BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-dialtone.mp3", type : "audio/mpeg" }})
	]}));

	this.panel.appendChild(this.BXIM.audio.ringtone = BX.create("audio", { props : { className : "bx-messenger-audio" }, children : [
		BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-ringtone.ogg", type : "audio/ogg; codecs=vorbis" }}),
		BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-ringtone.mp3", type : "audio/mpeg" }})
	]}));

	this.panel.appendChild(this.BXIM.audio.start = BX.create("audio", { props : { className : "bx-messenger-audio" }, children : [
		BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-start.ogg", type : "audio/ogg; codecs=vorbis" }}),
		BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-start.mp3", type : "audio/mpeg" }})
	]}));

	this.panel.appendChild(this.BXIM.audio.stop = BX.create("audio", { props : { className : "bx-messenger-audio" }, children : [
		BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-stop.ogg", type : "audio/ogg; codecs=vorbis" }}),
		BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-stop.mp3", type : "audio/mpeg" }})
	]}));

	this.panel.appendChild(this.BXIM.audio.error = BX.create("audio", { props : { className : "bx-messenger-audio" }, children : [
		BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-error.ogg", type : "audio/ogg; codecs=vorbis" }}),
		BX.create("source", { attrs : { src : "/bitrix/js/im/audio/video-error.mp3", type : "audio/mpeg" }})
	]}));

	if (typeof(this.BXIM.audio.stop.play) == 'undefined')
	{
		this.BXIM.settings.enableSound = false;
	}

};

/* WebRTC Signaling API  */
BX.IM.WebRTC.prototype.callSupport = function(dialogId, messengerClass)
{
	return false;

	messengerClass = messengerClass? messengerClass: this.messenger;
	var userCheck = true;
	if (typeof(dialogId) != 'undefined')
	{
		if (parseInt(dialogId)>0)
		{
			userCheck = messengerClass.users[dialogId] && messengerClass.users[dialogId].status != 'guest' && !messengerClass.users[dialogId].bot && !messengerClass.users[dialogId].network;
		}
		else
		{
			if (messengerClass.chat[dialogId.toString().substr(4)] && messengerClass.chat[dialogId.toString().substr(4)].type == 'open')
			{
				userCheck = false;
			}
			else
			{
				userCheck = (messengerClass.userInChat[dialogId.toString().substr(4)] && messengerClass.userInChat[dialogId.toString().substr(4)].length <= 4);
			}
		}
	}
	return this.BXIM.ppServerStatus && this.enabled && userCheck;
};


/* WebRTC dialogs markup */
BX.IM.WebRTC.prototype.callDialog = function()
{
	if (!this.callSupport())
		return false;

	clearTimeout(this.callInviteTimeout);

	this.callActive = true;
	if(!this.callView)
		return;

	if (this.messenger.popupMessenger == null)
	{
		this.messenger.openMessenger(this.callUserId);
	}

	this.callView.setProgress('wait');
	this.callView.setStatus(BX.message('IM_M_CALL_ST_WAIT_ACCESS'));
	if(this.callToMobile)
		this.callView.setCallWithMobile(true);

	this.callView.setFloating(false);
	this.callView.setUiSize(BX.IM.Call.UiSize.Full);
	this.callView.setUiState(BX.IM.Call.UiState.Connecting);

	this.startGetUserMedia(this.callVideo);
};

BX.IM.WebRTC.prototype.callOverlayDeleteEvents = function(params)
{
	params = params || {};

	this.desktop.closeTopmostWindow();


	var closeNotify = params.closeNotify !== false;
	if (closeNotify && this.callNotify)
		this.callNotify.destroy();

	var callId = null;
	if (this.phoneCallId)
	{
		callId = this.phoneCallId;
	}
	else if (this.callToGroup)
	{
		callId = 'chat'+this.callChatId;
	}
	else
	{
		callId = 'user'+this.callUserId;
	}
	BX.onCustomEvent(window, 'onImCallEnd', {'CALL_ID': callId});

	clearInterval(this.callAspectCheckInterval);

	if (BX.MessengerCommon.isDesktop() && this.BXIM.init)
	{
		BX.desktop.syncPause(false);
	}

	this.deleteEvents();

	this.callToMobile = false;
	this.callToPhone = false;

	if (this.messenger.popupMessenger)
	{
		this.messenger.popupMessenger.setClosingByEsc(true);
		this.messenger.dialogStatusRedraw();
	}

	this.phoneCallFinish();

	clearTimeout(this.callDialtoneTimeout);
	this.BXIM.stopRepeatSound('ringtone');
	this.BXIM.stopRepeatSound('dialtone');

	clearTimeout(this.callInviteTimeout);
}