BX.MessengerChat.prototype.openMessengerOrder = function(userId, params)
{
	var promise = new BX.Promise();

	if (this.BXIM.bitrix24blocked && typeof BX.UI.InfoHelper !== 'undefined')
	{
		BX.Helper.show('redirect=detail&code=' + this.BXIM.bitrix24blocked);
		this.BXIM.closeMessenger();
		promise.reject();

		return promise;
	}

	if (this.BXIM.errorMessage != '')
	{
		this.BXIM.closeMessenger();

		var errorButtons = [];
		if (this.BXIM.errorButtons)
		{
			errorButtons = this.BXIM.errorButtons;
		}
		else
		{
			errorButtons = [new BX.PopupWindowButton({
				text : BX.message('IM_NOTIFY_CONFIRM_CLOSE'),
				events : { click : BX.delegate(function(e) { BX.proxy_context.popupWindow.close(); if (BX.MessengerWindow){ BX.MessengerWindow.closePopup() } BX.PreventDefault(e) }, this) }
			})]
		}
		this.BXIM.openConfirm(this.BXIM.errorMessage, errorButtons);
		promise.reject();
		return promise;
	}

	if (this.BXIM.messenger.currentTab != userId)
	{
		BX.MessengerProxy.updateTextareaHistory(this.BXIM.messenger.currentTab);
	}

	// if (
	// 	BX.MessengerCommon.isPopupPage()
	// 	&& !BX.MessengerSlider.isFocus()
	// )
	// {
	// 	if (!BX.MessengerSlider.canOpen())
	// 	{
	// 		promise.reject();
	// 		return promise;
	// 	}

	// 	this.popupMessenger = null;

	// 	BX.MessengerSlider.open().then(function(){
	// 		this.openMessenger(userId, params).then(function(){
	// 			this.BXIM.desktop.adjustSize();
	// 			setTimeout(function(){

	// 				var dialogId = userId || this.BXIM.messenger.currentTab;
	// 				if (dialogId)
	// 				{
	// 					BX.MessengerCommon.openDialog(dialogId);
	// 				}

	// 				BX.MessengerSlider.getCurrent().closeLoader();

	// 				if (BXIM.messenger.checkRecentNeedLoad())
	// 				{
	// 					BXIM.messenger.recentListLoadMore();
	// 				}
	// 			}, 50)
	// 			promise.resolve();
	// 		}.bind(this));
	// 	}.bind(this));

	// 	return promise;
	// }

	if (
		BX.SidePanel
		&& BX.SidePanel.Instance.isOpen()
		&& BX.SidePanel.Instance.isOnTop()
		&& this.popupMessenger !== null
	)
	{
		if (BX.SidePanel.Instance.getSlider('timeman:pwt-report'))
		{
			promise.reject();
			return promise;
		}
		else if (!BX.MessengerCommon.isPopupPage() && !this.BXIM.options.v2layout)
		{
			BX.SidePanel.Instance.closeAll();
		}
		else if (BX.MessengerCommon.isPage())
		{
			BX.MessengerWindow.setZIndex(BX.SidePanel.Instance.getTopSlider().getZindex() + 1);
		}
		else
		{
			this.popupMessenger.close();
		}
	}

	if (this.popupImageUploader)
	{
		this.popupImageUploader.close();
	}

	if (this.BXIM.popupSettings != null && !BX.MessengerCommon.isDesktop())
		this.BXIM.popupSettings.close();

	if (this.popupMessenger != null && this.dialogOpen && this.currentTab == userId && userId != 0)
	{
		promise.reject();
		return promise;
	}

	if (userId !== false && BX.MessengerCommon.isPage() && BX.MessengerWindow.currentTab != 'order' && BX.MessengerWindow.currentTab != 'im-ol')
	{
		BX.MessengerWindow.changeTab('order', false, true);
	}

	if (this.popupMessengerEditForm)
		this.editMessageCancel();

	if (userId && userId.toString().toLowerCase() == 'general')
	{
		this.currentTab = 'chat'+this.generalChatId;
		userId = this.currentTab;
	}

	BX.localStorage.set('mcam', true, 5);
	if (typeof(userId) == "undefined" || userId == null)
	{
		userId = 0;
	}

	if (this.currentTab == null)
		this.currentTab = 0;

	this.openChatFlag = false;
	this.openNetworkFlag = false;
	this.openBotFlag = false;
	this.openLinesFlag = false;
	this.openCallFlag = false;

	if (typeof(userId) == "boolean")
	{
		userId = 0;
		this.currentTab = 0;
	}
	else if (userId == 0)
	{
		if (userId == 0 && this.currentTab != null)
		{
			if (this.users[this.currentTab] && this.users[this.currentTab].id)
				userId = this.currentTab;
			else if (this.chat[this.getChatId()] && this.chat[this.getChatId()].id)
				userId = this.currentTab;
		}
		if (userId.toString().substr(0,4) == 'chat')
		{
			BX.MessengerCommon.getUserParam(userId);
			this.openChatFlag = true;
			if (this.chat[userId.toString().substr(4)].type == 'call')
				this.openCallFlag = true;
			else if (this.chat[userId.toString().substr(4)].type == 'lines')
				this.openLinesFlag = true;
		}
		else
		{
			userId = parseInt(userId);
		}
	}
	else if (
		userId.toString().substr(0,4) == 'chat'
		|| userId.toString().substr(0,2) == 'sg'
		|| userId.toString().substr(0,3) == 'crm'
	)
	{
		BX.MessengerCommon.getUserParam(userId);
		this.openChatFlag = true;

		if (userId.toString().substr(0,4) == 'chat')
		{
			if (this.chat[userId.toString().substr(4)].type == 'call')
				this.openCallFlag = true;
			else if (this.chat[userId.toString().substr(4)].type == 'lines')
			{
				this.openLinesFlag = true;
				if (this.BXIM.options.v2layout)
				{
					const eventParams = { detail: userId };
					parent.dispatchEvent(new CustomEvent('onLinesChatOpen', eventParams));
				}
			}
		}
	}
	else if (userId.toString().substr(0,7) == 'network')
	{
		BX.MessengerCommon.getUserParam(userId);
		this.openNetworkFlag = true;
	}
	else if (this.users[userId] && this.users[userId].id)
	{
		userId = parseInt(userId);
	}
	else
	{
		userId = parseInt(userId);
		if (isNaN(userId))
		{
			userId = 0;
		}
		else
		{
			BX.MessengerCommon.getUserParam(userId);
		}
	}
	if (this.openNetworkFlag)
	{}
	else if (!this.openChatFlag && typeof(userId) != 'number')
	{
		userId = 0;
	}
	if (this.openChatFlag || userId > 0)
	{
		this.currentTab = userId;
		this.BXIM.notifyManager.closeByTag('im-message-'+userId);
		BX.localStorage.set('mct', this.currentTab, 15);

		if (!this.openChatFlag && this.users[userId] && this.users[userId].bot)
		{
			this.openBotFlag = true;
		}
	}

	// if (this.popupMessenger != null)
	// {
	// 	BX.MessengerCommon.openDialog(
	// 		userId,
	// 		this.BXIM.dialogOpen? false: true
	// 	);
	// 	if (!(BX.browser.IsAndroid() || BX.browser.IsIOS() || window != window.top))
	// 	{
	// 		if (this.popupMessengerTextarea)
	// 			this.popupMessengerTextarea.focus();
	// 	}
	// 	promise.resolve();
	// 	return promise;
	// }

	// TODO remove this
	var styleOfContent = {};
	if (BX.MessengerCommon.isPage())
	{
		if (!(this.BXIM.context == 'POPUP-FULLSCREEN' && BX.MessengerCommon.isIntranet()))
		{
			var newHeight = BX.MessengerWindow.content.offsetHeight - this.popupMessengerFullHeight;
			this.popupContactListElementsSize = this.popupContactListElementsSize + newHeight;
			this.popupMessengerBodySize = this.popupMessengerBodySize + newHeight;
			this.popupMessengerFullHeight = this.popupMessengerFullHeight + newHeight;
			this.notify.popupNotifySize = this.notify.popupNotifySize + newHeight;
		}
	}
	else
	{
		styleOfContent = {width: this.popupMessengerFullWidth+'px'};
	}

	var userStatus = this.BXIM.settings.status;

	var userTitleStyle = "";
	if (this.users[this.currentTab])
	{
		if (this.users[this.currentTab].extranet)
		{
			userTitleStyle = " bx-messenger-user-extranet";
		}
		else if (this.users[this.currentTab].bot)
		{
			if (!this.bot[this.currentTab])
			{
				userTitleStyle = " bx-messenger-user-bot";
			}
			else if (this.bot[this.currentTab].type == 'network')
			{
				userTitleStyle = " bx-messenger-user-network";
			}
			else if (this.bot[this.currentTab].type == 'support24')
			{
				userTitleStyle = " bx-messenger-user-support24";
			}
		}
	}

	// if (!BX.MessengerCommon.isDesktop() || this.desktop.enableInVersion(46))
	// {
	// 	BX.MessengerPromo.showConfirm('im:video:01042020:web');
	// }

	var contactListExtendCollapsedElements = 0;
	if (!this.BXIM.options.showSearch)
	{
		contactListExtendCollapsedElements += 48;
	}
	if (!this.BXIM.options.showStatus)
	{
		contactListExtendCollapsedElements += 39;
	}

	this.popupMessengerContent = BX.create("div", { props : { className : "bx-messenger-box bx-messenger-mark bx-messenger-global-context-"+this.BXIM.context.toLowerCase()+" "+(BX.MessengerCommon.isPage()? ' bx-messenger-box-desktop': '')+(BX.browser.IsMac()? '': ' bx-messenger-custom-scroll')+(this.BXIM.options.showRecent? '': ' bx-messenger-hide-recent')+(BX.MessengerTheme.isDark() ? ' bx-messenger-dark' : '') }, style: styleOfContent, children : [
		/* CL */
		this.popupContactListWrap = BX.create("div", { props : { className : "bx-messenger-box-contact bx-messenger-box-contact-normal" }, style : {width: this.popupContactListSize+'px'},  children : [
			BX.create("div", { props : { className : "bx-messenger-cl-search"+(this.BXIM.options.showSearch? '': ' bx-messenger-cl-search-hide') }, children : [
				BX.create("div", { props : { className : "bx-messenger-input-wrap bx-messenger-cl-search-wrap" }, children : [
					this.popupContactListSearchIcon = BX.create("span", { props : { className : "bx-messenger-input-search-icon" }}),
					this.popupContactListSearchClose = BX.create("a", {attrs: {href: "javascript:void(0);"}, props : { className : "bx-messenger-input-close" }}),
					this.popupContactListSearchInput = BX.create("input", {attrs: {type: "text", placeholder: BX.message('IM_M_SEARCH'), value: this.contactListSearchText}, props : { className : "bx-messenger-input" }})
				]}),
				this.popupContactListCreateChat = this.BXIM.options.v2layout? null: BX.create("span", {props : { className : "bx-messenger-input-search-create" }, text: BX.message('IM_M_CREATE_MENU')}),
				this.popupContactListCreateChatShort = this.BXIM.options.v2layout? null: BX.create("span", {props : { className : "bx-messenger-input-search-create-short" }}),
			]}),
			this.popupContactListElements = BX.create("div", { props : { className : "bx-messenger-cl" }, style : {height: this.popupContactListElementsSize+(contactListExtendCollapsedElements)+'px'}, children : [
				this.popupContactListElementsWrap = BX.create("div", { props : { className : "bx-messenger-cl-wrap bx-messenger-recent-wrap" }}),
				this.orderNewRecentWrap = BX.create("div", { props : { className : "bx-messenger-cl-wrap bx-messenger-new-recent-wrap", }})
			]}),
			/*this.BXIM.design == 'DESKTOP'*/ this.externalMenu? null: BX.create('div', {props : { className : "bx-messenger-cl-notify-wrap" }, children : [
				this.notify.messengerNotifyButton = BX.create("div", { props : { className : "bx-messenger-cl-notify-button"}, events : { click : BX.delegate(this.notify.openNotify, this.notify)}, children : [
					BX.create('span', {props : { className : "bx-messenger-cl-notify-text"}, html: BX.message('IM_NOTIFY_BUTTON_TITLE')}),
					this.notify.messengerNotifyButtonCount = BX.create('span', { props : { className : "bx-messenger-cl-count" }, html: parseInt(this.notify.notifyCount)>0? '<span class="bx-messenger-cl-count-digit">'+this.notify.notifyCount+'</span>':''})
				]}),
				this.BXIM.design == 'DESKTOP'? null: this.popupContactListSearchCall = !this.webrtc.phoneSupport() || !this.webrtc.phoneCanPerformCalls? null: BX.create("div", { props : { className : "bx-messenger-cl-phone-button"}, children : [
					BX.create('span', {props : { className : "bx-messenger-cl-phone-text"}, html: BX.message('IM_PHONE_BUTTON_TITLE')}),
				]})
			]}),
			BX.create('div', {props : { className : "bx-messenger-cl-panel"+(this.BXIM.options.showStatus? '': ' bx-messenger-cl-panel-hide') }, children : [
				BX.create('div', {props : { className : "bx-messenger-cl-panel-wrap" }, children : [
					this.contactListPanelStatus = BX.create("span", { props : { className : "bx-messenger-cl-panel-status-wrap bx-messenger-cl-status-"+userStatus }, html: '<span class="bx-messenger-cl-panel-status bx-messenger-cl-status"></span><span class="bx-messenger-cl-panel-status-text">'+BX.message("IM_STATUS_"+userStatus.toUpperCase())+'</span><span class="bx-messenger-cl-panel-status-arrow"></span>'}),
					BX.create('span', {props : { className : "bx-messenger-cl-panel-right-wrap" }, children : [
						//this.contactListPanelFull = BX.MessengerCommon.isPage()? null: BX.create("span", { props : { title : BX.message("IM_FULLSCREEN"), className : "bx-messenger-cl-panel-fullscreen-wrap"}}),
						this.contactListPanelSettings = /*this.BXIM.design == 'DESKTOP'*/ this.externalMenu? null: BX.create("span", { props : { title : BX.message("IM_SETTINGS"), className : "bx-messenger-cl-panel-settings-wrap"}})
					]})
				]})
			]})
		]}),
		/* DIALOG */
		this.popupMessengerDialog = BX.create("div", { props : { className : "bx-messenger-box-dialog"+(this.BXIM.isAdmin? ' bx-messenger-user-admin': '') }, style : {marginLeft: this.popupContactListSize+'px'},  children : [

			this.popupMessengerPanel = BX.create("div", { props : { className : "bx-messenger-panel bx-messenger-context-user "+(this.openChatFlag? ' bx-messenger-hide': '') }, children : [
				BX.create('a', { attrs : { href : this.users[this.currentTab]? this.users[this.currentTab].profile: BX.MessengerCommon.getUserParam().profile, target: '_blank'}, props : { className : "bx-messenger-panel-avatar bx-messenger-panel-avatar-status-"+BX.MessengerCommon.getUserStatus(this.users[this.currentTab]) }, children: [
					this.popupMessengerPanelAvatar = BX.create('span', { props : { className : "bx-messenger-panel-avatar-img bx-messenger-panel-avatar-img-default" }}),
					BX.create('span', {  props : { className : "bx-messenger-panel-avatar-status" }})
				], events : {
					mouseover: BX.delegate(function(e){
						if (this.users[this.currentTab])
						{
							BX.proxy_context.title = BX.MessengerCommon.getUserStatus(this.users[this.currentTab], false).statusText;
						}
					}, this)
				}}),
				// BX.create("a", {attrs: {href: "javascript:void(0);", title: BX.message("IM_M_OPEN_HISTORY_2")}, props : { className : "bx-messenger-panel-button bx-messenger-panel-history"}, events : { click: BX.delegate(function(e){ this.openHistory(this.currentTab); BX.PreventDefault(e)}, this)}}),
				this.popupMessengerPanelMute = BX.create("a", {attrs: {href: "javascript:void(0);", title: this.muteButtonStatus(this.currentTab)? BX.message("IM_M_USER_BLOCK_ON"): BX.message("IM_M_USER_BLOCK_OFF")}, props : { className : "bx-messenger-panel-button bx-messenger-panel-mute"}, events : { click: BX.delegate(function(e){BX.MessengerCommon.muteMessageChat(this.currentTab);BX.PreventDefault(e);}, this)}}),
				this.enableGroupChat? BX.create("a", {attrs: {href: "javascript:void(0);", title: BX.message("IM_M_CHAT_TITLE")}, props : { className : "bx-messenger-panel-button bx-messenger-panel-chat"}, html: BX.message("IM_M_CHAT_BTN_JOIN"), events : { click: BX.delegate(function(e){ this.openChatDialog({'type': 'CHAT_ADD', 'bind': BX.proxy_context}); BX.PreventDefault(e)}, this)}}): null,
				this.popupMessengerPanelButtonCall1 = this.callButton(),
				BX.create("span", { props : { className : "bx-messenger-panel-title"}, children: [
					this.popupMessengerPanelTitle = BX.create('a', { props : { className : "bx-messenger-panel-title-link"+userTitleStyle}, attrs : { href : this.users[this.currentTab]? this.users[this.currentTab].profile: BX.MessengerCommon.getUserParam().profile, target: '_blank'}, text: this.users[this.currentTab]? this.users[this.currentTab].name: ''}),
					this.popupMessengerPanelLastDate = BX.create("span", { props : { className : "bx-messenger-panel-title-position"}, html: ''})
				]}),
				this.popupMessengerPanelStatus = BX.create("span", { props : { className : "bx-messenger-panel-desc"}, html: BX.MessengerCommon.getUserPosition(this.users[this.currentTab], false, true)})
			]}),

			this.popupMessengerPanelChat = BX.create("div", { props : { className : "bx-messenger-panel bx-messenger-context-chat "+(this.openChatFlag && !this.openCallFlag? '': ' bx-messenger-hide') }, children : [
				this.popupMessengerPanelAvatarForm2 = BX.create('form', { attrs : { action : this.BXIM.pathToFileAjax}, props : { className : "bx-messenger-panel-avatar bx-messenger-panel-avatar-chat" }, children: [
					BX.create('div', { props : { className : "bx-messenger-panel-avatar-progress"}, html: '<div class="bx-messenger-panel-avatar-progress-image"></div>'}),
					BX.create('input', { attrs : { type : 'hidden', name: 'IM_AVATAR_UPDATE', value: 'Y'}}),
					this.popupMessengerPanelAvatarId2 = BX.create('input', { attrs : { type : 'hidden', name: 'CHAT_ID', value: this.getChatId()}}),
					BX.create('input', { attrs : { type : 'hidden', name: 'IM_AJAX_CALL', value: 'Y'}}),
					// this.popupMessengerPanelAvatarUpload2 = this.disk.lightVersion || !this.BXIM.ppServerStatus? null: BX.create('input', { attrs : { type : 'file', title: BX.message('IM_M_AVATAR_UPLOAD')}, props : { className : "bx-messenger-panel-avatar-upload"}}),
					this.popupMessengerPanelAvatar2 = BX.create('span', { props : { className : "bx-messenger-panel-avatar-img bx-messenger-panel-avatar-img-default" }}),
					this.popupMessengerPanelCrm = BX.create('span', {  props : { className : "bx-messenger-panel-avatar-badge-crm" }}),
					this.popupMessengerPanelStatus2 = BX.create('span', {  props : { className : "bx-messenger-panel-avatar-status" }})
					/*this.popupMessengerPanelLoader = BX.create('span', {  props : { className : "bx-messenger-loader" }, children: [
						BX.create('span', {  props : { className : "bx-messenger-loader-default bx-messenger-loader-first" }}),
						BX.create('span', {  props : { className : "bx-messenger-loader-default bx-messenger-loader-second" }}),
						BX.create('span', {  props : { className : "bx-messenger-loader-mask" }})
					]})*/
				]}),
				BX.create("span", {attrs: {title: BX.message('IM_P_MENU')}, props : { className : "bx-messenger-panel-button bx-messenger-panel-menu"}, events : { click: BX.delegate(function(e){ this.openPopupMenu(BX.proxy_context, this.chat[this.getChatId()].entity_type == "LINES"? 'openLinesMenu': 'pathMenu'); BX.PreventDefault(e); }, this)}}),
				// BX.create("a", {attrs: {href: "javascript:void(0);", title: BX.message("IM_M_OPEN_HISTORY_2")}, props : { className : "bx-messenger-panel-button bx-messenger-panel-history"}, events : { click: BX.delegate(function(e){ this.openHistory(this.currentTab); BX.PreventDefault(e)}, this)}}),
				this.popupMessengerPanelMute2 = BX.create("a", {attrs: {href: "javascript:void(0);", title: this.muteButtonStatus(this.currentTab)? BX.message("IM_M_CHAT_MUTE_ON_2"): BX.message("IM_M_CHAT_MUTE_OFF_2")}, props : { className : "bx-messenger-panel-button bx-messenger-panel-mute "+(this.muteButtonStatus(this.currentTab)? ' bx-messenger-panel-unmute': '')}, events : { click: BX.delegate(function(e){BX.MessengerCommon.muteMessageChat(this.currentTab);BX.PreventDefault(e);}, this)}}),
				this.popupOpenLinesSpam = BX.create("span", {attrs: {title: BX.message('IM_M_OL_FORCE_CLOSE')? BX.message('IM_M_OL_FORCE_CLOSE').replace('<br>', ''): BX.message('IM_M_OL_SPAM')}, props : { className : "bx-messenger-panel-button bx-messenger-panel-spam"}, events : { click: BX.delegate(function(e){ this.linesMarkAsSpam(); BX.PreventDefault(e); }, this)}}),
				this.popupOpenLinesClose = BX.create("span", {attrs: {title: BX.message('IM_M_OL_CLOSE')}, props : { className : "bx-messenger-panel-button bx-messenger-panel-close"}, events : { click: BX.delegate(function(e){ this.linesCloseDialog(); BX.PreventDefault(e); }, this)}}),
				this.popupOpenLinesTransfer = BX.create("span", {attrs: {title: BX.message('IM_P_TRANSFER')}, props : { className : "bx-messenger-panel-button bx-messenger-panel-transfer"}, events : { click: BX.delegate(function(e){ this.linesOpenTransferDialog({'bind': BX.proxy_context}); BX.PreventDefault(e); }, this)}}),
				this.popupMessengerPanelButtonExtend = this.enableGroupChat? BX.create("a", {attrs: {href: "javascript:void(0);", title: BX.message("IM_M_CHAT_TITLE")}, props : { className : "bx-messenger-panel-button bx-messenger-panel-chat"}, html: BX.message("IM_M_CHAT_BTN_JOIN"), events : { click: BX.delegate(function(e){ this.openChatDialog({'chatId': this.getChatId(),'type': 'CHAT_EXTEND', 'bind': BX.proxy_context}); BX.PreventDefault(e)}, this)}}): null,
				this.popupMessengerPanelButtonCall2 = this.callButton(),
				BX.create("span", { props : { className : "bx-messenger-panel-title bx-messenger-panel-title-chat"}, children: [
					this.popupMessengerPanelChatTitle = BX.create('span', { props : { className : ""}, html: this.chat[this.getChatId()]? this.chat[this.getChatId()].name: BX.message('IM_CL_LOAD')})
				]}),
				BX.create("span", { props : { className : "bx-messenger-panel-desc"}, children : [
					this.popupMessengerPanelUsers = BX.create('div', { props : { className : "bx-messenger-panel-chat-users"}, html: BX.message('IM_CL_LOAD')})
				]})
			]}),

			this.popupMessengerPanelCall = BX.create("div", { props : { className : "bx-messenger-panel bx-messenger-context-call "+(this.openChatFlag && this.openCallFlag? '': ' bx-messenger-hide') }, children : [
				this.popupMessengerPanelAvatarForm3 = BX.create('form', { attrs : { action : this.BXIM.pathToFileAjax}, props : { className : "bx-messenger-panel-avatar bx-messenger-panel-avatar-call" }, children: [
					BX.create('div', { props : { className : "bx-messenger-panel-avatar-progress"}, html: '<div class="bx-messenger-panel-avatar-progress-image"></div>'}),
					BX.create('input', { attrs : { type : 'hidden', name: 'IM_AVATAR_UPDATE', value: 'Y'}}),
					this.popupMessengerPanelAvatarId3 = BX.create('input', { attrs : { type : 'hidden', name: 'CHAT_ID', value: this.getChatId()}}),
					BX.create('input', { attrs : { type : 'hidden', name: 'IM_AJAX_CALL', value: 'Y'}}),
					this.popupMessengerPanelAvatarUpload3 = this.disk.lightVersion || !this.BXIM.ppServerStatus? null: BX.create('input', { attrs : { type : 'file', title: BX.message('IM_M_AVATAR_UPLOAD_2')}, props : { className : "bx-messenger-panel-avatar-upload"}}),
					this.popupMessengerPanelAvatar3 = BX.create('span', {  props : { className : "bx-messenger-panel-avatar-img bx-messenger-panel-avatar-img-default" }}),
					this.popupMessengerPanelStatus3 = BX.create('span', {  props : { className : "bx-messenger-panel-avatar-status bx-messenger-panel-avatar-status-chat" }})
				]}),
				BX.create("span", {attrs: {title: BX.message('IM_P_MENU')}, props : { className : "bx-messenger-panel-button bx-messenger-panel-menu"}, events : { click: BX.delegate(function(e){ this.openPopupMenu(BX.proxy_context, 'callContextMenu'); BX.PreventDefault(e); }, this)}}),
				// BX.create("a", {attrs: {href: "javascript:void(0);", title: BX.message("IM_M_OPEN_HISTORY_2")}, props : { className : "bx-messenger-panel-button bx-messenger-panel-history"}, events : { click: BX.delegate(function(e){ this.openHistory(this.currentTab); BX.PreventDefault(e)}, this)}}),
				this.popupMessengerPanelMute3 = BX.create("a", {attrs: {href: "javascript:void(0);", title: this.muteButtonStatus(this.currentTab)? BX.message("IM_M_CHAT_MUTE_ON_2"): BX.message("IM_M_CHAT_MUTE_OFF_2")}, props : { className : "bx-messenger-panel-button bx-messenger-panel-mute "+(this.muteButtonStatus(this.currentTab)? ' bx-messenger-panel-unmute': '')}, events : { click: BX.delegate(function(e){ BX.MessengerCommon.muteMessageChat(this.currentTab); BX.PreventDefault(e)}, this)}}),
				this.popupMessengerPanelButtonCall3 = this.callButton('call'),
				this.popupMessengerPanelCallTitle = BX.create("span", { props : { className : "bx-messenger-panel-title"}, html: this.chat[this.getChatId()]? this.chat[this.getChatId()].name: BX.message('IM_CL_LOAD')}),
				this.popupMessengerPanelCallDescription = BX.create("span", { props : { className : "bx-messenger-panel-desc"}, text: this.chat[this.getChatId()] && this.chat[this.getChatId()].entity_data_1 && this.chat[this.getChatId()].entity_data_1.toString().charAt(0) === "Y" ? this.chat[this.getChatId()].call_number : BX.message('IM_PHONE_DESC')})
			]}),
			
			this.popupMessengerConnectionStatus = BX.create("div", { props : { className : "bx-messenger-connection-status "+(this.popupMessengerConnectionStatusState == 'online'? "": "bx-messenger-connection-status-show bx-messenger-connection-status-"+this.popupMessengerConnectionStatusState) }, children : [
				BX.create("div", { props : { className : "bx-messenger-connection-status-wrap" }, children : [
					this.popupMessengerConnectionStatusText = BX.create("span", { props : { className : "bx-messenger-connection-status-text"}, html: this.popupMessengerConnectionStatusStateText}),
					BX.create("span", { props : { className : "bx-messenger-connection-status-text-reload"}, children : [
						BX.create("span", { props : { className : "bx-messenger-connection-status-text-reload-title"}, html: BX.message('IM_CS_RELOAD')}),
						BX.create("span", { props : { className : "bx-messenger-connection-status-text-reload-hotkey"}, html: (BX.browser.IsMac()? "&#8984;+R": "Ctrl+R")})
					], events: {
						'click': function(){ location.reload() }
					}})
				]})
			]}),
			this.popupMessengerEditForm = BX.create("div", { props : { className : "bx-messenger-editform bx-messenger-editform-disable" }, children : [
				BX.create("div", { props : { className : "bx-messenger-editform-wrap" }, children : [
					BX.create("div", { props : { className : "bx-messenger-editform-textarea" }, children : [
						this.popupMessengerEditTextarea = BX.create("textarea", { props : { value: '', className : "bx-messenger-editform-textarea-input" }, style : {height: '70px', resize: 'vertical', maxHeight: '500px'}})
					]}),
					BX.create("div", { props : { className : "bx-messenger-editform-buttons" }, children : [
						BX.create("span", { props : { className : "popup-window-button popup-window-button-accept" }, children : [
							BX.create("span", { props : { className : "popup-window-button-left"}}),
							BX.create("span", { props : { className : "popup-window-button-text"}, html: BX.message('IM_M_CHAT_BTN_EDIT')}),
							BX.create("span", { props : { className : "popup-window-button-right"}})
						], events : {
							click: BX.delegate(function(e){
								var editedMessageId = this.popupMessengerEditMessageId;
								BX.MessengerCommon.editMessageAjax(this.popupMessengerEditMessageId, this.popupMessengerEditTextarea.value);
								if(this.message[editedMessageId].quick_saved)
								{
									BX.MessengerCommon.linesSaveToQuickAnswers(editedMessageId, true);
								}
							}, this)
						}}),
						BX.create("span", { props : { className : "popup-window-button" }, children : [
							BX.create("span", { props : { className : "popup-window-button-left"}}),
							BX.create("span", { props : { className : "popup-window-button-text"}, html: BX.message('IM_M_CHAT_BTN_CANCEL')}),
							BX.create("span", { props : { className : "popup-window-button-right"}})
						], events : {
							click: BX.delegate(function(e){
								this.editMessageCancel();
							}, this)
						}}),
						BX.create("span", { props : { className : "bx-messenger-editform-progress"}, html: BX.message('IM_MESSAGE_EDIT_TEXT') })
					]})
				]})
			]}),
			this.popupMessengerBodyDialog = BX.create("div", { props : { className : "bx-messenger-body-dialog bxu-file-input-over" }, children: [
				this.popupMessengerFileDropZone = !this.disk.enable? null: BX.create("div", { props : { className : "bx-messenger-file-dropzone" }, children : [
					BX.create("div", { props : { className : "bx-messenger-file-dropzone-wrap" }, children: [
						BX.create("div", { props : { className : "bx-messenger-file-dropzone-icon" }}),
						BX.create("div", { props : { className : "bx-messenger-file-dropzone-text" }, html: BX.message('IM_F_DND_TEXT')}),
					]})
				]}),
				this.popupMessengerBodyPanel = BX.create("div", { props : { className : "bx-messenger-body-panel" }, style : {height: this.popupMessengerBodySize+'px'}, children: [
					BX.create("div", { props : { className : "bx-messenger-body-panel-title" }, children: [
						this.popupMessengerBodyPanelTitleName = BX.create("div", { props : { className : "bx-messenger-body-panel-title-name" }}),
						this.popupMessengerBodyPanelTitleDesc = BX.create("div", { props : { className : "bx-messenger-body-panel-title-desc" }}),
						BX.create("div", { props : { className : "bx-messenger-body-panel-title-close" }, events: {click: BX.delegate(function(){
							this.closeMessengerPanel();
						}, this)}})
					]}),
					this.popupMessengerBodyPanelWrap = BX.create("div", { props : { className : "bx-messenger-body-panel-wrap" }})
				]}),
				this.popupMessengerBody = BX.create("div", { props : { className : "bx-messenger-body" }, style : {height: this.popupMessengerBodySize+'px'}, children: [
					BX.create("div", { props : { className : "bx-messenger-body-bg" }, children: [
						this.popupMessengerBodyWrap = BX.create("div", { props : { className : "bx-messenger-body-wrap" }})
					]}),
				]}),
				this.popupMessengerBodyLiveChatForm = BX.create("div", { props : { className : "bx-messenger-livechat-form" }}),
				this.popupMessengerTextareaPlace = BX.create("div", { props : { className : "bx-messenger-textarea-place"}, children : [
					BX.create("div", { props : { className : "bx-messenger-textarea-open-lines" }, children : [
						BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-text-box" }, children: [
							BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-text-box-element" }, children: [
								this.popupMessengerTextareaOpenLinesText = BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-text" }, html: BX.message('IM_OL_INVITE_TEXT')})
							]})
						]}),
						BX.create("div", { props: { className : "bx-messenger-textarea-open-invite-join-box"}, children: [
							this.popupMessengerTextareaOpenLinesAnswer = BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-answer bx-notifier-item-button bx-notifier-item-button-confirm bx-notifier-item-button-accept" }, html: BX.message('IM_OL_INVITE_ANSWER')}),
							this.popupMessengerTextareaOpenLinesSkip = BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-skip bx-notifier-item-button bx-notifier-item-button-confirm bx-notifier-item-button-cancel" }, html: BX.message('IM_OL_INVITE_SKIP')}),
							this.popupMessengerTextareaOpenLinesTransfer = BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-transfer bx-notifier-item-button bx-notifier-item-button-confirm bx-notifier-item-button-transfer" }, html: BX.message('IM_OL_INVITE_TRANSFER'), events : { click: BX.delegate(function(e){ this.linesOpenTransferDialog({'bind': BX.proxy_context}); BX.PreventDefault(e); }, this)}})
						]})
					]}),
					BX.create("div", { props : { className : "bx-messenger-textarea-open-invite" }, children : [
						BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-text-box" }, children: [
							BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-text-box-element" }, children: [
								this.popupMessengerTextareaOpenText = BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-text" }, html: BX.message(this.BXIM.bitrixIntranet? 'IM_O_INVITE_TEXT_NEW': 'IM_O_INVITE_TEXT_SITE_NEW')})
							]})
						]}),
						this.popupMessengerTextareaOpenJoin = BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-join bx-notifier-item-button bx-notifier-item-button-confirm bx-notifier-item-button-accept" }, html: BX.message('IM_O_INVITE_JOIN')})
					]}),
					BX.create("div", { props : { className : "bx-messenger-textarea-general-invite" }, children : [
						BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-text-box" }, children: [
							BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-text-box-element" }, children: [
								this.popupMessengerTextareaGeneralText = BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-text" }})
							]})
						]}),
						this.popupMessengerTextareaGeneralJoin = BX.create("div", { props : { className : "bx-messenger-textarea-open-invite-join bx-notifier-item-button bx-notifier-item-button-confirm bx-notifier-item-button-accept" }, html: BX.message('IM_G_JOIN_'+this.BXIM.userGender)})
					]}),
					BX.create("div", { props : { className : "bx-messenger-textarea-resize" }, events : { mousedown : BX.delegate(this.resizeTextareaStart, this)}}),
					BX.create("div", { props : { className : "bx-messenger-textarea-send" }, children : [
						BX.create("a", {attrs: {href: "javascript:void(0);"}, props : { className : "bx-messenger-textarea-send-button" }, events : { click : BX.delegate(this.sendMessage, this)}}),
						this.popupMessengerTextareaSendType = BX.browser.IsMobile()? BX.create("span"): BX.create("span", {attrs : {title : BX.message('IM_M_SEND_TYPE_TITLE')}, props : { className : "bx-messenger-textarea-cntr-enter"}, html: this.BXIM.settings.sendByEnter? 'Enter': (BX.browser.IsMac()? "&#8984;+Enter": "Ctrl+Enter"), events: {
							click: BX.delegate(function() {
								if (this.popupMessengerTextareaPlace && this.popupMessengerTextareaPlace.className.indexOf('bx-messenger-textarea-with-text') == -1)
								{
									return false;
								}
								this.BXIM.settings.sendByEnter = this.BXIM.settings.sendByEnter? false: true;
								this.BXIM.saveSettings({'sendByEnter': this.BXIM.settings.sendByEnter});
								BX.proxy_context.innerHTML = this.BXIM.settings.sendByEnter? 'Enter': (BX.browser.IsMac()? "&#8984;+Enter": "Ctrl+Enter");
							}, this)
						}})
					]}),
					this.popupMessengerTextareaIcons = BX.create("div", {props : { className : "bx-messenger-textarea-icons" }, children: [
						this.popupMessengerFileButton = this.disk.getFileMenuIcon(),
						// this.BXIM.context == "LINES"? null: BX.create("div", {attrs : { title: BX.message('IM_MENTION_MENU_NEW')},  props : { className : "bx-messenger-textarea-icon bx-messenger-textarea-mention" }, events : { click : BX.delegate(function(e){ this.openMentionDialog({delay: 0}); return BX.PreventDefault(e);}, this)}}),
						// this.BXIM.context == "LINES"? null: BX.create("div", {attrs : { title: BX.message('IM_COMMAND_MENU')},  props : { className : "bx-messenger-textarea-icon bx-messenger-textarea-command" }, events : { click : BX.delegate(function(e){ this.openCommandDialog(); return BX.PreventDefault(e);}, this)}}),
						this.popupMessengerSmileButton = BX.create("div", {attrs : { title: BX.message('IM_SMILE_MENU')},  props : { className : "bx-messenger-textarea-icon bx-messenger-textarea-smile" }, events : { click : BX.delegate(function(e){this.openSmileMenu(); return BX.PreventDefault(e);}, this)}}),
						this.popupMessengerCrmButton = BX.create("div", {attrs : { title: BX.message('IM_FORMS_MENU')},  props : { className : "bx-messenger-textarea-icon bx-messenger-textarea-forms" }, events : { click : BX.delegate(function(e){this.openFormsMenu(); return BX.PreventDefault(e);}, this)}}),
						this.popupMessengerHiddenModeButton = BX.create("div", {attrs : { title: BX.message('IM_HIDDEN_MODE_MENU')},  props : { className : "bx-messenger-textarea-icon bx-messenger-textarea-hidden" }, events : { click : BX.delegate(function(e){ this.linesToggleSilentMode(); return BX.PreventDefault(e);}, this)}}),
						this.popupMessengerTextareaIconBox = BX.create("div", { props : { className : "bx-messenger-textarea-icon-box" }})
					]}),
					BX.create("div", { props : { className : "bx-messenger-textarea" }, children : [
						this.popupMessengerTextarea = BX.create("textarea", { props : { value: (this.textareaHistory[userId]? this.textareaHistory[userId]: ''), className : "bx-messenger-textarea-input"}, style : {height: this.popupMessengerTextareaSize+'px'}}),
						this.popupMessengerTextareaPlaceholder = BX.create("div", { props : {className : "bx-messenger-textarea-placeholder"}, html : BX.message('IM_M_TA_TEXT')})
					]}),
					BX.create("div", { props : { className : "bx-messenger-textarea-clear" }}),
					//BX.MessengerCommon.isPage() && !BX.MessengerCommon.isDesktop()? null: BX.create("span", { props : { className : "bx-messenger-resize" }, events : BX.MessengerCommon.isPage()? {}: { mousedown : BX.delegate(this.resizeWindowStart, this)}})
				]})
			]})
		]}),
		/* EXTRA PANEL */
		this.popupMessengerExtra = BX.create("div", { props : { className : "bx-messenger-box-extra"}, style : {marginLeft: this.popupContactListSize+'px', height: this.popupMessengerFullHeight+'px'}})
	]});
	this.textareaCheckText();

	this.BXIM.dialogOpen = true;
	if (BX.MessengerCommon.isPage())
	{
		if (BX.MessengerCommon.isPopupPage())
		{
			this.popupMessenger = new BX.PopupWindowSlider(this.BXIM);
		}
		else
		{
			this.popupMessenger = new BX.PopupWindowDesktop(this.BXIM);
		}

		BX.MessengerWindow.setTabContent('im', this.popupMessengerContent);
		this.disk.chatDialogInit();
		this.disk.chatAvatarInit();
	}
	else
	{
		this.popupMessenger = new BX.PopupWindow('bx-messenger-popup-messenger', null, {
			lightShadow : true,
			autoHide: false,
			closeByEsc: true,
			overlay: {opacity: 50, backgroundColor: "#000000"},
			draggable: {restrict: true},
			events : {
				onPopupShow : BX.delegate(function() {
					this.disk.chatDialogInit();
					this.disk.chatAvatarInit();
				}, this),
				onPopupClose : function() { this.destroy(); },
				onPopupDestroy : BX.delegate(function() {
					this.closeMessenger();
				}, this)
			},
			titleBar: {content: BX.create('div')},
			closeIcon : {'top': '10px', 'right': '13px'},
			content : this.popupMessengerContent,
			noAllPaddings : true,
			contentColor : BX.MessengerTheme.isDark()? "": "white",
		});
		this.popupMessenger.show();

		BX.bind(this.popupMessenger.popupContainer, "click", BX.MessengerCommon.preventDefault);
		BX.bind(document, "click", BX.proxy(this.BXIM.autoHide, this.BXIM));

		BX.addCustomEvent(this.popupMessenger, "onPopupFullscreenEnter", BX.delegate(function(){
			BX.addClass(this.popupMessengerContent, 'bx-messenger-fullscreen');

			this.messengerFullscreenStatus = true;
			this.resizeMainWindow();
			if (BX.browser.IsChrome())
			{
				setTimeout(BX.delegate(function(){
					this.resizeMainWindow();
				}, this), 100);
			}

			this.popupMessengerBody.scrollTop = this.popupMessengerBody.scrollHeight - this.popupMessengerBody.offsetHeight;

		}, this));

		BX.addCustomEvent(this.popupMessenger, "onPopupFullscreenLeave", BX.delegate(function(){

			BX.removeClass(this.popupMessengerContent, 'bx-messenger-fullscreen');
			if (BX.browser.IsChrome())
			{
				BX.addClass(this.popupMessengerContent, 'bx-messenger-fullscreen-chrome-hack');
				setTimeout(BX.delegate(function(){
					BX.removeClass(this.popupMessengerContent, 'bx-messenger-fullscreen-chrome-hack');
				}, this), 100);
			}
			this.resizeMainWindow();

			this.popupMessengerBody.scrollTop = this.popupMessengerBody.scrollHeight - this.popupMessengerBody.offsetHeight;
		}, this));
	}

	this.BXIM.setBackground();

	this.popupMessengerTopLine = BX.create("div", { props : { className : "bx-messenger-box-topline"}});
	this.popupMessengerContent.insertBefore(this.popupMessengerTopLine, this.popupMessengerContent.firstChild);

	this.textareaIconPrepare();

	BX.MessengerCommon.userListRedraw();

	if (this.BXIM.quirksMode)
	{
		this.popupContactListWrap.style.position = "absolute";
		this.popupContactListWrap.style.display = "block";
	}
	this.setUpdateStateStep();
	if (!(BX.browser.IsAndroid() || BX.browser.IsIOS() || window != window.top) && this.popupMessenger != null)
	{
		setTimeout(BX.delegate(function(){
			this.popupMessengerTextarea.focus();
		}, this), 50);
	}

	/* CL */
	this.BXIM.initLeftPanelApp().then(function() {
		// this.BXIM.leftpanelApp.initComponent(this.BXIM.messenger.orderNewRecentWrap);
        console.log(this.BXIM.messenger.orderNewRecentWrap)
	}.bind(this));

	if (this.webrtc.phoneEnabled && this.BXIM.design != 'DESKTOP')
	{
		BX.bind(this.popupContactListSearchCall, "click", () => {
			this.webrtc.openKeyPad({
				bindElement: this.popupContactListSearchCall,
				offsetTop: -10,
				offsetLeft: -52,
				anglePosition: this.externalMenu && !this.webrtc.callActive ? "left" : "top",
				angleOffset: this.externalMenu ? (this.webrtc.callActive ? 120 : 76) : 94,
			})
		});
	}
	BX.bind(this.popupContactListSearchIcon, "click", BX.delegate(function(e) {
		this.BXIM.openContactList();
	}, this));
	BX.bind(this.popupContactListWrap, "mouseover", BX.delegate(function(e) {
		if (this.popupContactListHovered || this.popupContactListActive)
			return false;

		clearTimeout(this.popupContactListWrapAnimation);
		this.popupContactListWrapAnimation = setTimeout(BX.delegate(function(){
			BX.addClass(this.popupContactListWrap, 'bx-messenger-box-contact-hover');
			clearTimeout(this.popupContactListWrapAnimation);
			this.popupContactListWrapAnimation = setTimeout(BX.delegate(function(){
				BX.removeClass(this.popupContactListWrap, 'bx-messenger-box-contact-normal');
			}, this), 100);
		}, this), 2000);

		this.popupContactListHovered = true;
	}, this));

	BX.bind(this.popupContactListWrap, "mouseout", BX.delegate(function(e) {
		if (!this.popupContactListHovered || this.popupContactListActive)
			return false;

		clearTimeout(this.popupContactListWrapAnimation);
		this.popupContactListWrapAnimation = setTimeout(BX.delegate(function(){
			BX.addClass(this.popupContactListWrap, 'bx-messenger-box-contact-normal');
			clearTimeout(this.popupContactListWrapAnimation);
			this.popupContactListWrapAnimation = setTimeout(BX.delegate(function(){
				BX.removeClass(this.popupContactListWrap, 'bx-messenger-box-contact-hover');
			}, this), 50);
		}, this), 400);

		this.popupContactListHovered = false;

	}, this));

	var createChatMenu = BX.delegate(function(e) {
		if (!this.recentList)
		{
			this.recentList = true;
			BX.MessengerCommon.recentListRedraw();
		}
		this.openPopupMenu(e.currentTarget, 'createChat');
		return BX.PreventDefault(e);
	}, this);

	BX.bind(this.popupContactListCreateChat, "click", createChatMenu);
	BX.bind(this.popupContactListCreateChatShort, "click", createChatMenu);

	if (!this.tooltipIsShowed('IM_CREATE_MENU'))
	{
		setTimeout(function() {
			this.tooltip(this.popupContactListCreateChat, BX.message('IM_M_CREATE_MENU_TOOLTIP'), {offsetLeft: 41, offsetTop: 10, showOnce: 'IM_CREATE_MENU'});
		}.bind(this), 5000);
	}

	BX.bind(this.popupContactListSearchClose.parentNode, "click",  BX.delegate(function(){
		this.popupContactListSearchInput.focus();
	}, this));
	BX.bind(this.popupMessengerDialog, "click",  BX.delegate(function(e){
		if (this.recentList && !this.chatList && !this.contactList)
		{
			return false;
		}
		BX.MessengerCommon.contactListSearchClear(e);
	}, this));
	BX.bind(this.popupContactListSearchClose, "click",  BX.delegate(function(e){
		BX.MessengerCommon.contactListSearchClear(e);
		return BX.PreventDefault(e);
	}, BX.MessengerCommon));
	/*
	BX.bind(this.popupContactListSearchInput, "click", BX.delegate(function(e) {
		if (this.contactListSearchText.length == 0 && !this.contactList && e.altKey == true)
		{
			clearTimeout(this.BXIM.messenger.redrawChatListTimeout);
			BX.MessengerCommon.contactListPrepareOld();
		}
	}, this));
	*/
	BX.bind(this.popupContactListSearchInput, "focus", BX.delegate(function(e) {
		clearTimeout(this.BXIM.messenger.redrawChatListTimeout);
		this.BXIM.messenger.redrawChatListTimeout = setTimeout(BX.delegate(function(){
			if (this.contactListSearchText.length == 0 && !this.chatList && !this.contactList)
			{
				BX.MessengerCommon.chatListRedraw();
			}
		}, this), 100);
		this.setClosingByEsc(false);
	}, this));
	BX.bind(this.popupContactListSearchInput, "blur", BX.delegate(function(){
		if (this.contactListSearchText.length == 0 && !this.popupContactListHovered && !this.recentList)
		{
			this.setClosingByEsc(true);
		}
	}, this));
	if (BX.MessengerCommon.isDesktop())
	{
		BX.bind(this.popupContactListSearchInput, "contextmenu", BX.delegate(function(e) {
			this.openPopupMenu(e, 'copypaste', false, {'spell': true});
			return BX.PreventDefault(e);
		}, this));
	}
	BX.bind(this.popupContactListSearchInput, "keyup", BX.delegate(BX.MessengerCommon.contactListSearch, BX.MessengerCommon));
	BX.bind(this.popupContactListSearchInput, "input", BX.delegate(BX.MessengerCommon.handleInputEvent, BX.MessengerCommon));

	// BX.bind(this.popupMessengerPanelChatTitle, "click",  BX.delegate(this.renameChatDialog, this));

	// BX.bindDelegate(this.popupMessengerPanelUsers, "click", {className: 'bx-messenger-panel-chat-user'}, BX.delegate(function(e){this.openPopupMenu(BX.proxy_context, 'chatUser'); return BX.PreventDefault(e);}, this));

	BX.bindDelegate(this.popupMessengerPanelUsers, "click", {className: 'bx-notifier-popup-user-more'}, BX.delegate(function(e) {
		if (this.popupChatUsers != null)
		{
			this.popupChatUsers.destroy();
			return false;
		}

		var chatId = this.getChatId();

		var userInChat = [];
		for (var i = 0; i < this.userInChat[chatId].length; i++)
		{
			var user = this.users[this.userInChat[chatId][i]];
			if (!user || !user.active)
			{
				continue;
			}

			if (this.chat[chatId].entity_type == "LINES" && this.chat[chatId].owner == 0 && user.id != this.BXIM.userId && !(user.bot || user.connector))
			{
				continue;
			}

			userInChat.push(this.userInChat[chatId][i]);
		}

		var htmlElement = '<span class="bx-notifier-item-help-popup">';
		for (var i = parseInt(BX.proxy_context.getAttribute('data-last-item')); i < userInChat.length; i++)
		{
			var user = this.users[userInChat[i]];
			var avatarColor = BX.MessengerCommon.isBlankAvatar(user.avatar)? 'style="background-color: '+user.color+'"': '';
			htmlElement += '<span class="bx-notifier-item-help-popup-img bx-messenger-panel-chat-user" data-userId="'+user.id+'">' +
				'<span class="bx-notifier-popup-avatar  bx-notifier-popup-avatar-status-'+BX.MessengerCommon.getUserStatus(user)+'">' +
					'<span class="bx-notifier-popup-avatar-img'+(BX.MessengerCommon.isBlankAvatar(user.avatar)? " bx-notifier-popup-avatar-img-default": "")+'" '+BX.MessengerCommon.getAvatarStyle(user)+'></span>' +
				'</span>' +
				'<span class="bx-notifier-item-help-popup-name  '+(user.extranet? ' bx-notifier-popup-avatar-extranet':'')+'">'+user.name+'</span>' +
			'</span>';
		}
		htmlElement += '</span>';

		this.popupChatUsers = new BX.PopupWindow('bx-messenger-popup-chat-users', BX.proxy_context, {
			//parentPopup: this.popupMessenger,
			targetContainer: document.body,
			darkMode: BX.MessengerTheme.isDark(),
			zIndex: BX.MessengerCommon.getDefaultZIndex()+200,
			lightShadow : true,
			offsetTop: -2,
			offsetLeft: 3,
			autoHide: true,
			closeByEsc: true,
			events : {
				onPopupClose : function() { this.destroy() },
				onPopupDestroy : BX.proxy(function() { this.popupChatUsers = null; }, this)
			},
			content : BX.create("div", { props : { className : "bx-messenger-popup-menu"+(BX.browser.IsMac()? '': ' bx-messenger-custom-scroll') }, html: htmlElement})
		});
		if (!BX.MessengerTheme.isDark())
			this.popupChatUsers.setAngle({offset: BX.proxy_context.offsetWidth});
		BX.addClass(this.popupChatUsers.popupContainer, "bx-messenger-mark");
		this.popupChatUsers.show();

		// BX.bindDelegate(this.popupChatUsers.popupContainer, "click", {className: 'bx-messenger-panel-chat-user'}, BX.delegate(function(e){this.openPopupMenu(BX.proxy_context, 'chatUser'); return BX.PreventDefault(e);}, this));

		return BX.PreventDefault(e);
	}, this));
	BX.bindDelegate(this.popupContactListElements, "contextmenu", {className: 'bx-messenger-cl-item'}, BX.delegate(function(e) {
		this.openPopupMenu(BX.proxy_context, 'contactList');
		return BX.PreventDefault(e);
	}, this));
	BX.bindDelegate(this.popupContactListElements, "click", {className: 'bx-messenger-cl-item'}, BX.delegate(BX.MessengerCommon.contactListClickItem, BX.MessengerCommon));
	BX.bindDelegate(this.popupContactListElements, "click", {className: 'bx-messenger-chatlist-group-add'}, BX.delegate(function(e){
		if (!this.recentList)
		{
			this.recentList = true;
			BX.MessengerCommon.recentListRedraw();
		}
		this.openChatCreateForm(BX.proxy_context.getAttribute('data-type'));
	}, this));

	BX.bindDelegate(this.popupContactListElements, "click", {className: 'bx-messenger-chatlist-more'}, BX.delegate(this.toggleChatListGroup, this));
	BX.bindDelegate(this.popupContactListElements, "click", {className: 'bx-messenger-chatlist-search-button'}, BX.delegate(function(){
		this.BXIM.messenger.chatListSearchAction(BX.proxy_context.parentNode);
	}, this));

	BX.bind(this.popupContactListElements, "scroll", BX.delegate(function(event) {

		if (this.checkRecentNeedLoad())
		{
			this.recentListLoadMore();
		}

		if (this.popupPopupMenu != null && this.popupPopupMenuDateCreate+500 < (+new Date()) && this.popupPopupMenu.uniquePopupId.replace('bx-messenger-popup-','') == 'contactList')
		{
			this.popupPopupMenu.close();
		}
	}, this));

	BX.bind(this.contactListPanelStatus, "click", BX.delegate(function(e){this.openPopupMenu(this.contactListPanelStatus, 'status');  return BX.PreventDefault(e);}, this));
	if (this.contactListPanelSettings)
	{
		BX.bind(this.contactListPanelSettings, "click", BX.delegate(function(e){this.BXIM.openSettings(); BX.PreventDefault(e)}, this));
	}
	if (this.contactListPanelFull)
	{
		BX.bind(this.contactListPanelFull, "click", BX.delegate(function(e){
			this.popupMessenger.enterFullScreen(); BX.PreventDefault(e)
		}, this));
	}

	/* EDIT FORM */
	BX.bind(this.popupMessengerEditTextarea, "focus", BX.delegate(function() {
		this.setClosingByEsc(false);
	}, this));
	BX.bind(this.popupMessengerEditTextarea, "blur", BX.delegate(function() {
		this.setClosingByEsc(true);
	}, this));
	BX.bind(this.popupMessengerEditTextarea, "keydown", BX.delegate(function(event){
		this.textareaPrepareText(BX.proxy_context, event, BX.delegate(function(){
			BX.MessengerCommon.editMessageAjax(this.popupMessengerEditMessageId, this.popupMessengerEditTextarea.value);
		}, this), BX.delegate(function(){
			setTimeout(function(){
				this.editMessageCancel();
			}.bind(this), 50);
		}, this));
	}, this));

	if (BX.MessengerCommon.isDesktop())
	{
		BX.bind(this.popupMessengerEditTextarea, "contextmenu", BX.delegate(function(e) {
			this.openPopupMenu(e, 'copypaste', false, {'spell': true});
			return BX.PreventDefault(e);
		}, this));
		BX.bind(this.popupMessengerTextarea, "contextmenu", BX.delegate(function(e) {
			this.openPopupMenu(e, 'copypaste', false, {'spell': true});
			return BX.PreventDefault(e);
		}, this));
		BX.bind(this.popupMessengerEditTextarea, "click", BX.delegate(function(e) {
			if (!(e.metaKey || e.ctrlKey) || !this.desktop.enableInVersion(34))
				return false;

			var selectedText = BX.desktop.clipboardSelected(this.popupMessengerEditTextarea, true);
			if (!selectedText.text)
				return false;

			BXDesktopSystem.SpellCheckWord(selectedText.text, BX.delegate(function(isCorrect, suggest){
				if (isCorrect || suggest.length <= 0)
					return false;

				var selectedText = BX.desktop.clipboardSelected(this.popupMessengerEditTextarea, true);
				BX.desktop.clipboardReplaceText(this.popupMessengerEditTextarea, selectedText.selectionStart, selectedText.selectionEnd, suggest[0]);
			}, this));
		}, this));
		BX.bind(this.popupMessengerTextarea, "click", BX.delegate(function(e) {
			if (!(e.metaKey || e.ctrlKey) || !this.desktop.enableInVersion(34))
				return false;

			var selectedText = BX.desktop.clipboardSelected(this.popupMessengerTextarea, true);
			if (!selectedText.text)
				return false;

			BXDesktopSystem.SpellCheckWord(selectedText.text, BX.delegate(function(isCorrect, suggest){
				if (isCorrect || suggest.length <= 0)
					return false
				var selectedText = BX.desktop.clipboardSelected(this.popupMessengerTextarea, true);
				BX.desktop.clipboardReplaceText(this.popupMessengerTextarea, selectedText.selectionStart, selectedText.selectionEnd, suggest[0]);
			}, this));
		}, this));
	}
	BX.bind(this.popupMessengerTextarea, "paste", BX.delegate(this.onPaste, this));
	BX.bind(this.popupMessengerTextarea, "focus", BX.delegate(function() {
		this.textareaCheckText();
		this.setClosingByEsc(false);
		BX.addClass(this.popupMessengerTextarea.parentNode, 'bx-messenger-textarea-focus');
		BX.onCustomEvent(window, 'onImTextareaFocus', [true]);
	}, this));
	BX.bind(this.popupMessengerTextarea, "blur", BX.delegate(function() {
		this.textareaCheckText();
		this.setClosingByEsc(true);
		BX.removeClass(this.popupMessengerTextarea.parentNode, 'bx-messenger-textarea-focus');
		BX.onCustomEvent(window, 'onImTextareaFocus', [false]);
	}, this));

	BX.bind(this.popupMessengerTextarea, "keydown", BX.delegate(function(event){
		this.textareaPrepareText(BX.proxy_context, event, BX.delegate(this.sendMessage, this), function(){});
	}, this));

	BX.bind(this.popupMessengerTextarea, "keyup", BX.delegate(this.textareaCheckText, this));

	if (BX.MessengerCommon.isDesktop())
	{
		BX.bindDelegate(this.popupMessengerBodyWrap, "contextmenu", {className: 'bx-messenger-content-item-content'}, BX.delegate(function(e) {
			this.openPopupMenu(e, 'dialogContext', false);
			return BX.PreventDefault(e);
		}, this));
	}

	BX.bindDelegate(this.popupMessengerBodyWrap, 'click', {className: 'bx-messenger-content-item-avatar-button'}, BX.delegate(function(e)
	{
		var userId = BX.proxy_context.parentNode.parentNode.getAttribute('data-senderId');
		if (!this.users[userId] || this.users[userId].fake)
		{
			return false;
		}

		if (
			!(e.metaKey || e.ctrlKey)
			&& !e.target.className.includes('bx-messenger-content-item-avatar-name')
		)
		{
			this.openPopupMenu(e.target, 'chatUser', true, {userId: userId});
			return BX.PreventDefault(e);
		}

		var userName =  BX.util.htmlspecialcharsback(this.users[userId].name);
		if (e.altKey)
		{
			userName = '[USER='+userId+']'+userName+'[/USER]';
		}
		else
		{
			BX.MessengerCommon.addMentionList(this.currentTab, userName, userId);
		}

		this.insertTextareaText(this.popupMessengerTextarea, ' '+userName+' ', false);
		this.popupMessengerTextarea.focus();

		return BX.PreventDefault(e);
	}, this));

	BX.bindDelegate(this.popupMessengerBodyWrap, 'click', {className: 'bx-messenger-attach-block-spoiler'}, BX.delegate(function(e) {
		var item = BX.findChildByClassName(BX.proxy_context, "bx-messenger-attach-block-value");
		if (BX.hasClass(BX.proxy_context, 'bx-messenger-attach-block-spoiler-show'))
		{
			height = item.getAttribute('data-min-height');
			BX.removeClass(BX.proxy_context, 'bx-messenger-attach-block-spoiler-show');
		}
		else
		{
			BX.addClass(BX.proxy_context, 'bx-messenger-attach-block-spoiler-show');
			height = item.getAttribute('data-max-height');
		}

		item.style.maxHeight = height+'px';
	}, this));


	BX.bindDelegate(this.popupMessengerBodyWrap, 'click', {className: 'bx-messenger-content-item-menu'}, BX.delegate(function(e) {
		if (e.metaKey || e.ctrlKey)
		{
			var messageId = BX.proxy_context.parentNode.parentNode.getAttribute('data-blockmessageid');
			if (this.message[messageId] && this.users[this.message[messageId].senderId].name)
			{
				var arQuote = [];

				if (this.message[messageId].text)
				{
					arQuote.push(this.message[messageId].textOriginal);
				}
				if (this.message[messageId].params && this.message[messageId].params.FILE_ID)
				{
					for (var j = 0; j < this.message[messageId].params.FILE_ID.length; j++)
					{
						var fileId = this.message[messageId].params.FILE_ID[j];
						var chatId = this.message[messageId].chatId;
						if (this.disk.files[chatId][fileId])
						{
							arQuote.push('['+BX.message('IM_F_FILE')+': '+this.disk.files[chatId][fileId].name+']');
						}
					}
				}

				if (arQuote.length > 0)
				{
					this.insertQuoteText(this.users[this.message[messageId].senderId].name, this.message[messageId].date, arQuote.join("\n"));
				}
			}
		}
		else
		{
			this.openPopupMenu(BX.proxy_context, 'dialogMenu');
		}
		return BX.PreventDefault(e);
	}, this));

	BX.bindDelegate(this.popupMessengerBodyWrap, 'click', {className: 'bx-messenger-content-like-digit'}, BX.delegate(function(e)
	{
		BX.localStorage.set('implc', true, 1);

		var messageId = BX.proxy_context.parentNode.parentNode.parentNode.parentNode.parentNode.getAttribute('data-blockmessageid');
		if (messageId.substr(0,4) == 'temp' || !this.message[messageId].params || !this.message[messageId].params['LIKE'] || this.message[messageId].params['LIKE'].length <= 0)
			return false;

		if (this.popupChatUsers != null)
		{
			this.popupChatUsers.destroy();
			return false;
		}

		var htmlElement = '<span class="bx-notifier-item-help-popup">';
		for (var i = 0; i < this.message[messageId].params['LIKE'].length; i++)
		{
			if (this.users[this.message[messageId].params['LIKE'][i]])
			{
				var avatarColor = BX.MessengerCommon.isBlankAvatar(this.users[this.message[messageId].params['LIKE'][i]].avatar)? 'style="background-color: '+this.users[this.message[messageId].params['LIKE'][i]].color+'"': '';
				htmlElement += '<span class="bx-notifier-item-help-popup-img bx-messenger-panel-chat-user" data-userId="'+this.message[messageId].params['LIKE'][i]+'">' +
					'<span class="bx-notifier-popup-avatar  bx-notifier-popup-avatar-status-'+BX.MessengerCommon.getUserStatus(this.users[this.message[messageId].params['LIKE'][i]])+'">' +
						'<span class="bx-notifier-popup-avatar-img'+(BX.MessengerCommon.isBlankAvatar(this.users[this.message[messageId].params['LIKE'][i]].avatar)? " bx-notifier-popup-avatar-img-default": "")+'" '+BX.MessengerCommon.getAvatarStyle(this.users[this.message[messageId].params['LIKE'][i]])+'></span>' +
					'</span>' +
					'<span class="bx-notifier-item-help-popup-name  '+(this.users[this.message[messageId].params['LIKE'][i]].extranet? ' bx-notifier-popup-avatar-extranet':'')+'">'+this.users[this.message[messageId].params['LIKE'][i]].name+'</span>' +
				'</span>';
			}
		}
		htmlElement += '</span>';

		this.popupChatUsers = new BX.PopupWindow('bx-messenger-popup-like-users', BX.proxy_context, {
			//parentPopup: this.popupMessenger,
			targetContainer: document.body,
			darkMode: BX.MessengerTheme.isDark(),
			zIndex: BX.MessengerCommon.getDefaultZIndex()+200,
			lightShadow : true,
			offsetTop: 5,
			offsetLeft: 12,
			autoHide: true,
			closeByEsc: true,
			bindOptions: {position: "top"},
			events : {
				onPopupClose : function() { this.destroy() },
				onPopupDestroy : BX.proxy(function() { this.popupChatUsers = null; }, this)
			},
			content : BX.create("div", { props : { className : "bx-messenger-popup-menu"+(BX.browser.IsMac()? '': ' bx-messenger-custom-scroll') }, html: htmlElement})
		});
		if (!BX.MessengerTheme.isDark())
			this.popupChatUsers.setAngle({offset: BX.proxy_context.offsetWidth});
		BX.addClass(this.popupChatUsers.popupContainer, "bx-messenger-mark");
		this.popupChatUsers.show();

		// BX.bindDelegate(this.popupChatUsers.popupContainer, "click", {className: 'bx-messenger-panel-chat-user'}, BX.delegate(function(e){this.openPopupMenu(BX.proxy_context, 'chatUser'); return BX.PreventDefault(e);}, this));

		return BX.PreventDefault(e);
	}, this));

	BX.bindDelegate(this.popupMessengerBodyWrap, 'click', {className: 'bx-messenger-keyboard-button-text'}, BX.delegate(BX.MessengerCommon.clickButtonKeyboard, BX.MessengerCommon));

	BX.bindDelegate(this.popupMessengerBodyWrap, 'click', {className: 'bx-messenger-content-like-button'}, BX.delegate(function(e) {
		var chatId = this.getChatId();
		if (this.openChatFlag && !BX.MessengerCommon.userInChat(chatId))
		{
			return false;
		}
		if (BX.localStorage.get('implc', true, 1))
		{
			return false;
		}
		var messageId = BX.proxy_context.parentNode.parentNode.parentNode.parentNode.parentNode.getAttribute('data-blockmessageid');
		BX.MessengerCommon.messageLike(messageId);

		return BX.PreventDefault(e);
	}, this));

	BX.bindDelegate(this.popupMessengerBodyWrap, 'click', {className: 'bx-messenger-attach-delete'}, BX.delegate(function(e) {
		var messageId = BX.proxy_context.getAttribute('data-messageId');
		var attachId = BX.proxy_context.getAttribute('data-attachId');
		var action = BX.proxy_context.getAttribute('data-action');

		if (action == 'url')
		{
			BX.MessengerCommon.messageUrlAttachDelete(messageId, attachId);
		}

		return BX.PreventDefault(e);
	}, this));

	BX.bind(this.popupMessengerTextareaOpenJoin, 'click', BX.delegate(function() {
		if (this.currentTab.substr(0, 4) != 'chat')
		{
			return false;
		}

		if (this.BXIM.messenger.popupMessengerDialog && BX.hasClass(this.BXIM.messenger.popupMessengerDialog, "bx-messenger-chat-load-last-message"))
		{
			return false;
		}
		var chatId = this.currentTab.substr(4);
		BX.MessengerCommon.joinToChat(chatId);

		return true;
	}, this));

	BX.bind(this.popupMessengerTextareaGeneralJoin, 'click', BX.delegate(function() {
		if (this.BXIM.messenger.popupMessengerDialog && BX.hasClass(this.BXIM.messenger.popupMessengerDialog, "bx-messenger-chat-load-last-message"))
		{
			return false;
		}

		this.BXIM.settings.generalNotify = false;

		this.BXIM.saveSettings({'generalNotify': this.BXIM.settings.generalNotify});
		this.redrawChatHeader({userRedraw: false});

		this.popupMessengerTextarea.focus();

		return true;
	}, this));

	BX.bind(this.popupMessengerTextareaOpenLinesAnswer, 'click', BX.delegate(function() {
		if (this.currentTab.substr(0, 4) != 'chat')
			return false;

		if (this.BXIM.messenger.popupMessengerDialog && BX.hasClass(this.BXIM.messenger.popupMessengerDialog, "bx-messenger-chat-load-last-message"))
		{
			return false;
		}

		var chatId = this.currentTab.substr(4);
		if (!BX.MessengerCommon.userInChat(chatId))
		{
			var session = BX.MessengerCommon.linesGetSession(this.chat[chatId]);
			if (parseInt(session.id) <= 0)
			{
				BX.MessengerCommon.linesStartSession(chatId);
			}
			else if (parseInt(this.chat[chatId].owner) == 0)
			{
				BX.MessengerCommon.linesAnswer(chatId);
			}
			else
			{
				BX.MessengerCommon.linesJoinSession(chatId);
			}
		}
		else
		{
			BX.MessengerCommon.linesAnswer(chatId);
		}

		return true;
	}, this));

	BX.bind(this.popupMessengerTextareaOpenLinesSkip, 'click', BX.delegate(function() {
		if (this.currentTab.substr(0, 4) != 'chat')
			return false;

		if (this.BXIM.messenger.popupMessengerDialog && BX.hasClass(this.BXIM.messenger.popupMessengerDialog, "bx-messenger-chat-load-last-message"))
		{
			return false;
		}

		var chatId = this.currentTab.substr(4);

		if (!BX.MessengerCommon.userInChat(chatId))
		{
			BX.MessengerCommon.dialogCloseCurrent(true);
		}
		else if (BX.MessengerCommon.isSessionBlocked(chatId))
		{
			BX.MessengerCommon.linesMarkAsSpam(chatId)
		}
		else
		{
			BX.MessengerCommon.linesSkip(chatId);
		}

		return true;
	}, this));

	// BX.bindDelegate(this.popupMessengerBodyWrap, 'click', {className: 'bx-messenger-ajax'}, BX.delegate(function() {
	// 	if (BX.proxy_context.getAttribute('data-entity') == 'readedList')
	// 	{
	// 		this.openPopupExternalData(BX.proxy_context, 'readedList', true, {'TAB': this.BXIM.messenger.currentTab})
	// 	}
	// 	else if (BX.proxy_context.getAttribute('data-entity') == 'user')
	// 	{
	// 		this.openPopupExternalData(BX.proxy_context, 'user', true, {'ID': BX.proxy_context.getAttribute('data-userId')})
	// 	}
	// 	else if (BX.proxy_context.getAttribute('data-entity') == 'openlines')
	// 	{
	// 		this.linesOpenHistory(BX.proxy_context.getAttribute('data-sessionId'));
	// 	}
	// 	else if (BX.proxy_context.getAttribute('data-entity') == 'chat')
	// 	{
	// 		this.openPopupExternalData(BX.proxy_context, 'chat', true, {'ID': BX.proxy_context.getAttribute('data-chatId')})
	// 	}
	// 	else if (BX.proxy_context.getAttribute('data-entity') == 'network')
	// 	{
	// 		this.openMessenger('network'+BX.proxy_context.getAttribute('data-networkId'))
	// 	}
	// 	else if (BX.proxy_context.getAttribute('data-entity') == 'date')
	// 	{
	// 		this.openPopupMenu(BX.proxy_context, 'shareMenu');
	// 	}
	// 	else if (this.webrtc.phoneSupport() && BX.proxy_context.getAttribute('data-entity') == 'phoneCallHistory')
	// 	{
	// 		this.openPopupExternalData(BX.proxy_context, 'phoneCallHistory', true, {'ID': BX.proxy_context.getAttribute('data-historyID')})
	// 	}
	// }, this));

	BX.bindDelegate(this.popupMessengerBodyWrap, 'click', {className: 'bx-messenger-command'}, BX.delegate(function() {
		if (BX.proxy_context.getAttribute('data-entity') == 'send')
		{
			this.BXIM.sendMessage(this.currentTab, BX.proxy_context.nextSibling.innerHTML);
		}
		else if (BX.proxy_context.getAttribute('data-entity') == 'put')
		{
			this.BXIM.putMessage(BX.proxy_context.nextSibling.innerHTML);
		}
		else if (BX.proxy_context.getAttribute('data-entity') == 'call')
		{
			this.BXIM.phoneTo(BX.proxy_context.getAttribute('data-command'));
		}
	}, this));

	BX.bindDelegate(this.popupMessengerBodyWrap, 'click', {className: 'bx-messenger-content-item-date'}, BX.delegate(function(e) {
		if (this.openLinesFlag &&
			//TODO: FIX 0109478
			BX.hasClass(BX.proxy_context.parentNode.parentNode.parentNode.parentNode, 'bx-messenger-content-item-system'))
		{
			this.tooltip(BX.proxy_context, BX.message('IM_TIP_OL_SYSTEM'), {offsetLeft: 48});
		}
		BX.PreventDefault(e);
	}, this));

	BX.bind(this.popupMessengerBody, "scroll", BX.delegate(function(e)
	{
		if (
			BX.MessengerCommon.getCounter(this.currentTab)
			&& BX.MessengerCommon.isScrollMax(this.popupMessengerBody, 200)
			&& this.BXIM.isFocus()
		)
		{
			clearTimeout(this.readMessageTimeout);
			this.readMessageTimeout = setTimeout(BX.delegate(function ()
			{
				BX.MessengerCommon.readMessage(this.currentTab);
			}, this), 100);
		}

		BX.MessengerCommon.redrawDateMarks();

		if (!this.redrawTab[this.currentTab])
		{
			BX.MessengerCommon.loadHistory(this.currentTab, false);
		}

		if (this.popupPopupMenu != null)
		{
			if (this.popupPopupMenuDateCreate+500 < (+new Date()) && BX.util.in_array(this.popupPopupMenu.uniquePopupId.replace('bx-messenger-popup-',''), ["copypaste", "copylink", "dialogContext", "dialogMenu", "external-data"]))
			{
				this.popupPopupMenu.close();
			}
			else if (false && BX.util.in_array(this.popupPopupMenu.uniquePopupId.replace('bx-messenger-popup-',''), ["dialogMenu", "external-data"]))
			{
				this.popupPopupMenu.adjustPosition();
			}
		}
		if (this.popupChatUsers != null && this.popupChatUsers.uniquePopupId.replace('bx-messenger-popup-','') == 'like-users')
		{
			this.popupChatUsers.close();
		}
		if (this.popupTooltip != null)
		{
			this.popupTooltip.close();
		}
	}, this));

	BX.bindDelegate(this.popupMessengerBodyWrap, 'click', {className: 'bx-messenger-content-item-error'}, BX.delegate(BX.MessengerCommon.sendMessageRetry, BX.MessengerCommon));

	if (userId == 0)
	{
		this.extraOpen(
			BX.create("div", { props : { className : "bx-messenger-box-hello-wrap" }, children: [
				BX.create("div", { props : { className : "bx-messenger-box-hello" }, html: BX.message('IM_M_EMPTY')})
			]})
		);
	}
	else if (!BX.MessengerCommon.isPopupPage())
	{
		BX.MessengerCommon.openDialog(userId);
	}

	promise.resolve();
	return promise;
};