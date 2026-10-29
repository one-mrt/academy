BX.IM.Desktop = function(BXIM, params)
{
	this.BXIM = BXIM;

	this.initDate = new Date();

	this.clientVersion = false;
	this.markup = BX('placeholder-messanger');
	this.htmlWrapperHead = null;
	this.showNotifyId = {};
	this.showMessageId = {};
	this.lastSetIcon = null;

	this.preventEsc = 0;

	this.videoConfList = [];

	this.topmostWindow = null;
	this.topmostWindowTimeout = null;
	this.topmostWindowCloseTimeout = null;

	this.minCallVideoWidth = 320;
	this.minCallVideoHeight = 180;
	this.minCallWidth = 320;
	this.minCallHeight = 35;
	this.minHistoryWidth = 608;
	this.minHistoryDiskWidth = 780;
	this.minHistoryHeight = 593;
	this.minSettingsWidth = 720;
	this.startSettingsHeight = BX.browser.IsMac()? 448: 357;
	this.minSettingsHeight = 137;

	this.callToggleCount = 0;

	if (this.BXIM.init && BX.MessengerCommon.isPage())
	{
		// BX.MessengerWindow.addTab({
		// 	id: 'config',
		// 	title: BX.message('IM_SETTINGS'),
		// 	order: 150,
		// 	target: false,
		// 	events: {
		// 		open: BX.delegate(function(e){
		// 			this.BXIM.openSettings({'active': BX.MessengerWindow.getCurrentTab()});
		// 		}, this)
		// 	}
		// });

		// BX.MessengerWindow.addTab({
		// 	id: 'order',
		// 	title: "Заказы",
		// 	order: 110,
		// 	target: 'order',
		// 	toggleEnable: false,
		// 	events: {
		// 		open: BX.delegate(function() {
		// 			// this.openNotify(false, true)
		// 			// BX.MessengerWindow.setTabContent('order', "<div></div>");
		// 			// console.log('Открыт')
		// 			if (BX.MessengerCommon.isPage() && this.BXIM.context == 'POPUP-FULLSCREEN' && !this.popupMessenger)
		// 				{
		// 					return false;
		// 				}
		// 				if (this.BXIM.extraOpen)
		// 				{
		// 					this.extraClose();
		// 				}
		// 				else if (!this.BXIM.dialogOpen)
		// 				{
		// 					this.openMessenger(this.currentTab);
		// 				}
		// 		}, this),
		// 		// close: function() {
		// 		// 	// this.messenger.extraClose();
		// 		// 	console.log('Закрыт')
		// 		// }.bind(this),
		// 	}
		// });

		// BX.MessengerWindow.addSeparator({
		// 	order: 500
		// });

		// if (!this.BXIM.bitrix24net)
		// {
		// 	BX.MessengerWindow.addTab({
		// 		id: 'im-lf',
		// 		title: BX.message('IM_DESKTOP_GO_SITE_2').replace('#COUNTER#', ''),
		// 		order: 550,
		// 		target: false,
		// 		events: {
		// 			open: BX.delegate(function(){
		// 				BX.MessengerWindow.browse(BX.MessengerWindow.getCurrentUrl()+this.BXIM.path.lf);
		// 			}, this)
		// 		}
		// 	});
		// }

		if (this.BXIM.animationSupport && /Microsoft Windows NT 5/i.test(navigator.userAgent))
			this.BXIM.animationSupport = false;

		if (BX.MessengerCommon.isDesktop())
			this.BXIM.changeFocus(BX.desktop.windowIsFocused());

		if (typeof BXDesktopSystem != 'undefined')
		{
			BX.bind(window, "keydown", BX.delegate(function(e) {
				if (e.keyCode == 81 && (e.ctrlKey == true || e.metaKey == true))
				{
					clearTimeout(this.forceDesktopCloseTimeout);
					if (this.forceDesktopClose)
					{
						console.log('NOTICE: Forced exit from desktop.');
						BXDesktopSystem.Shutdown();
						return true;
					}

					console.log('NOTICE: Prevent forced exit from the desktop.');

					this.forceDesktopClose = true;
					this.forceDesktopCloseTimeout = setTimeout(function() {
						this.forceDesktopClose = false;
					}.bind(this), 3000);
				}
			}, this));
		}

		if (
			this.BXIM.context == 'DESKTOP'
			|| this.BXIM.context == 'POPUP-FULLSCREEN'
		)
		{
			// hotkey for desktop
			BX.bind(window, "keydown", BX.delegate(function(e) {
				if (!BX.MessengerWindow.isPopupShow())
					return false;

				if (!(BX.MessengerWindow.getCurrentTab() == 'im' || BX.MessengerWindow.getCurrentTab() == 'notify' || BX.MessengerWindow.getCurrentTab() == 'im-phone' || BX.MessengerWindow.getCurrentTab() == 'im-ol'))
					return false;

				// ESC
				if (e.keyCode == 27)
				{
					if (this.preventEsc > 0)
					{
						// esc prevented
					}
					else if (this.messenger.popupSmileMenu)
					{
						this.messenger.popupSmileMenu.destroy();
					}
					else if (this.disk.isFilePopupShown())
					{
						this.disk.closeFilePopup();
					}
					else if (this.messenger.popupPopupMenu)
					{
						this.messenger.popupPopupMenu.destroy();
					}
					else if (this.messenger.popupChatDialog && this.messenger.popupChatDialogContactListSearch.value.length >= 0)
					{
						this.messenger.popupChatDialogContactListSearch.value = '';
					}
					else if (this.BXIM.extraOpen)
					{
						this.messenger.extraClose(true);
					}
					else if (this.messenger.renameChatDialogInput && this.messenger.renameChatDialogInput.value.length > 0)
					{
						this.messenger.renameChatDialogInput.value = BX.util.htmlspecialcharsback(this.messenger.chat[this.messenger.currentTab.toString().substr(4)].name);
						this.messenger.popupMessengerTextarea.focus();
					}
					else if (this.messenger.popupContactListSearchInput && (this.messenger.popupContactListSearchInput.value.length > 0 || this.messenger.chatList))
					{
						BX.MessengerCommon.contactListSearch({'keyCode': 27});
						this.messenger.popupMessengerTextarea.focus();
					}
					else
					{
						if (BX.util.trim(this.messenger.popupMessengerEditTextarea.value).length > 0)
						{
							this.messenger.editMessageCancel();
						}
						else if (BX.util.trim(this.messenger.popupMessengerTextarea.value).length <= 0)
						{
							this.messenger.textareaHistory[this.messenger.currentTab] = '';
							BX.MessengerProxy.clearTextareaHistory(this.currentTab);

							this.messenger.popupMessengerTextarea.value = "";
							if (BX.MessengerCommon.isDesktop())
							{
								BX.desktop.windowCommand('hide');
							}
							else if (
								this.BXIM.context == 'DESKTOP'
								&& this.messenger.popupMessenger
							)
							{
								this.messenger.popupMessenger.destroy();
							}
						}
						else if (e.shiftKey)
						{
							this.messenger.textareaHistory[this.messenger.currentTab] = '';
							BX.MessengerProxy.clearTextareaHistory(this.currentTab);

							this.messenger.popupMessengerTextarea.value = "";
						}
					}
				}
				else if (e.altKey == true)
				{
					// ALT+[0-9]
					if (e.keyCode == 49 || e.keyCode == 50 || e.keyCode == 51
						|| e.keyCode == 52 || e.keyCode == 53 || e.keyCode == 54
						|| e.keyCode == 55 || e.keyCode == 56 || e.keyCode == 57)
					{
						this.messenger.openMessenger(this.messenger.recent[parseInt(e.keyCode)-49].id);
						BX.PreventDefault(e);
					}
					else if (e.keyCode == 48)
					{
						this.BXIM.openContactList();
						BX.PreventDefault(e);
					}
				}
			}, this));
		}

		if (BX.MessengerCommon.isDesktop())
		{
			BX.desktop.syncPause(false);

			BX.desktop.addCustomEvent("bxImClickNewMessage", BX.delegate(function(userId) {
				BX.desktop.windowCommand("show");
				BX.desktop.changeTab('im');
				this.BXIM.openMessenger(userId);
			}, this));
			BX.desktop.addCustomEvent("bxImClickCloseMessage", BX.delegate(function(userId) {
				//BX.MessengerCommon.readMessage(userId);
			}, this));
			BX.desktop.addCustomEvent("bxImClickCloseNotify", BX.delegate(function(notifyId) {
				this.BXIM.notify.viewNotify(notifyId);
			}, this));
			BX.desktop.addCustomEvent("bxImClickNotify", BX.delegate(function(notifyId, link) {
				BX.desktop.windowCommand("show");
				if (link)
				{
					BX.MessengerCommon.openLink(link);
				}
				else
				{
					BX.desktop.changeTab('notify');
					this.BXIM.openNotify();
				}
			}, this));

			BX.desktop.addCustomEvent('bxConferenceLoadComplete', BX.delegate(function() {
				this.messenger.updateMessageCount(false);
			}, this));

			BX.desktop.addCustomEvent('bxConferenceOpenChat', BX.delegate(function(userId) {
				BX.desktop.windowCommand("show");
				BX.desktop.changeTab('im');
				this.messenger.openMessenger(userId);
			}, this));

			BX.desktop.addCustomEvent('bxConferenceOpenProfile', BX.delegate(function(userId) {
				BX.desktop.windowCommand("show");
				BX.desktop.changeTab('im');
				var profilePath = BX.MessengerCommon.getUserParam(userId).profile;
				BX.SidePanel.Instance.open(profilePath);
			}, this));

			BX.desktop.addCustomEvent("bxImClearHistory", BX.delegate(function(userId) {
				this.messenger.history[userId] = [];
				this.messenger.showMessage[userId] = [];

				if (this.BXIM.init)
					BX.MessengerCommon.drawTab(userId);
			}, this));
			BX.desktop.addCustomEvent("bxSaveColor", BX.delegate(function(params) {
				BX.MessengerCommon.setColor(params.color, params.chatId);
			}, this));
			BX.desktop.addCustomEvent("bxImClickConfirmNotify", BX.delegate(function(notifyId) {
				delete this.BXIM.notify.notify[notifyId];
				delete this.BXIM.notify.unreadNotify[notifyId];
				delete this.BXIM.notify.flashNotify[notifyId];
				this.BXIM.notify.updateNotifyCount(false);
				if (this.BXIM.openNotify)
					this.BXIM.notify.openNotify(true, true);
			}, this));

			BX.desktop.addCustomEvent("BXUserAway", BX.delegate(this.onAwayAction, this));

			BX.desktop.addCustomEvent("BXApplicationClick", BX.delegate(this.onApplicationClick, this));

			BX.desktop.addCustomEvent("BXWakeAction", BX.delegate(this.onWakeAction, this));

			BX.desktop.addCustomEvent("BXForegroundChanged", BX.delegate(function(focus)
			{
				clearTimeout(this.BXIM.windowFocusTimeout);
				this.BXIM.windowFocusTimeout = setTimeout(BX.delegate(function(){
					this.BXIM.changeFocus(focus);
					if (this.BXIM.isFocus() && BX.MessengerCommon.getCounter(this.messenger.currentTab))
					{
						BX.MessengerCommon.readMessage(this.messenger.currentTab);
					}
				}, this), focus? 500: 0);
			}, this));

			BX.desktop.addCustomEvent("BXTopmostMoved", BX.delegate(function(x, y)
			{
				x = parseInt(x);
				y = parseInt(y);
				if (x >= 0 && y >= 0)
				{
					BXDesktopSystem.StoreSettings('global_topmost_x', ''+x);
					BXDesktopSystem.StoreSettings('global_topmost_y', ''+y);
				}
			}, this));

			BX.desktop.addCustomEvent("BXTrayMenu", BX.delegate(function (){
				var lFcounter = BXIM.notify.getCounter('**');
				var notifyCounter = BXIM.notify.getCounter('im_notify');
				var messengerCounter = BXIM.notify.getCounter('im_message');

				BX.desktop.addTrayMenuItem({Id: "messenger", Order: 100,Title: (BX.message('IM_DESKTOP_OPEN_MESSENGER') || '').replace('#COUNTER#', (messengerCounter>0? '('+messengerCounter+')':'')), Callback: function(){
					BX.desktop.windowCommand("show");
					BX.desktop.changeTab('im');
					BXIM.messenger.openMessenger(BXIM.messenger.currentTab);
				},Default: true	});

				BX.desktop.addTrayMenuItem({Id: "notify",Order: 120,Title: (BX.message('IM_DESKTOP_OPEN_NOTIFY') || '').replace('#COUNTER#', (notifyCounter>0? '('+notifyCounter+')':'')), Callback: function(){
					BX.desktop.windowCommand("show");
					BX.desktop.changeTab('notify');
					BXIM.notify.openNotify(false, true);
				}});
				BX.desktop.addTrayMenuItem({Id: "bdisk",Order: 130, Title: BX.message('IM_DESKTOP_BDISK_MSGVER_1'), Callback: function(){
					if (BX.desktop.diskAttachStatus())
					{
						BX.desktop.diskOpenFolder();
					}
					else
					{
						BX.desktop.windowCommand("show");
						BX.desktop.changeTab('disk');
					}
				}});
				BX.desktop.addTrayMenuItem({Id: "site",Order: 140, Title: (BX.message('IM_DESKTOP_GO_SITE_2') || '').replace('#COUNTER#', (lFcounter>0? '('+lFcounter+')':'')), Callback: function(){
					BX.desktop.browse(BX.desktop.getCurrentUrl());
				}});
				BX.desktop.addTrayMenuItem({Id: "separator1",IsSeparator: true, Order: 150});
				BX.desktop.addTrayMenuItem({Id: "settings",Order: 160, Title: BX.message('IM_DESKTOP_SETTINGS'), Callback: function(){
					BXIM.openSettings();
				}});
				BX.desktop.addTrayMenuItem({Id: "separator2",IsSeparator: true,Order: 1000});
				BX.desktop.addTrayMenuItem({Id: "logout",Order: 1010, Title: BX.message('IM_DESKTOP_LOGOUT'),Callback: function(){ BX.desktop.logout(false, 'tray_menu') }});
			}, this));
			BX.desktop.addCustomEvent("BXProtocolUrl", BX.delegate(function(command, params) {
				params = params? params: {}
				if (params.bitrix24net && params.bitrix24net == 'Y' && !this.BXIM.bitrix24net)
					return false;

				for (var i in params)
				{
					params[i] = decodeURIComponent(params[i]);
				}

				console.warn('BXProtocolUrl', command, params);

				if (command == 'messenger')
				{
					if (params.dialog)
					{
						this.BXIM.openMessenger(params.dialog);
					}
					else if (params.chat)
					{
						this.BXIM.openMessenger('chat'+params.chat);
					}
					else
					{
						this.BXIM.openMessenger();
					}
					if (params.tab)
					{
						BX.MessengerWindow.changeTab(params.tab, true);
					}
					BX.desktop.setActiveWindow();
					BX.desktop.windowCommand("show");
				}
				else if (command == 'chat' && params.id)
				{
					this.BXIM.openMessenger('chat'+params.id);
					BX.desktop.setActiveWindow();
					BX.desktop.windowCommand("show");
				}
				else if (command == 'chat' && params.create)
				{
					this.BXIM.openMessenger();
					this.BXIM.messenger.openChatCreateForm(params.create);
					BX.desktop.setActiveWindow();
					BX.desktop.windowCommand("show");
				}
				else if (command == 'videoconf')
				{
					this.openVideoconf(params.code);
				}
				else if (command == 'notify')
				{
					this.BXIM.openNotify({'force': true});
					BX.desktop.setActiveWindow();
					BX.desktop.windowCommand("show");
				}
				else if (command == 'history' && params.user)
				{
					if (params.dialog)
					{
						this.BXIM.openHistory(params.dialog);
					}
					else if (params.chat)
					{
						this.BXIM.openHistory('chat'+params.chat);
					}
					BX.desktop.setActiveWindow();
					BX.desktop.windowCommand("show");
				}
				else if (command == 'callto')
				{
				}
				else if (command == 'calllist')
				{
				}
				// compatibility layer with new chat
				else if (command == 'chat')
				{
					if (typeof params.id !== 'undefined')
					{
						this.BXIM.openMessenger('chat'+params.id);
					}
					else if (typeof params.dialogId !== 'undefined')
					{
						this.BXIM.openMessenger(params.dialogId);
					}
					BX.desktop.setActiveWindow();
					BX.desktop.windowCommand("show");
				}
				else if (command == 'lines')
				{
					this.BXIM.openMessenger(params.dialogId, 'im-ol');
					BX.desktop.setActiveWindow();
					BX.desktop.windowCommand("show");
				}
				else if (command == 'notifications')
				{
					this.BXIM.openNotify({'force': true});
					BX.desktop.setActiveWindow();
					BX.desktop.windowCommand("show");
				}
				else if (command == 'call')
				{
					const withVideo = params.withVideo !== 'N';
					this.BXIM.callTo(params.dialogId, withVideo);
					BX.desktop.setActiveWindow();
					BX.desktop.windowCommand("show");
				}
				else if (command == 'recentSearch' || command == 'timemanpwt')
				{
					BX.desktop.setActiveWindow();
					BX.desktop.windowCommand("show");
				}
			}, this));

			BX.addCustomEvent("onPullEvent-webdav", function(command,params)
			{
				BX.desktop.diskReportStorageNotification(command, params);
			});
		}

		BX.addCustomEvent("onPullEvent-main", BX.delegate(function(command,params)
		{
			if (command == 'user_counter' && params[BX.message('SITE_ID')])
			{
				var countersToCheck = [/^\*\*/, '^tasks_total$', '^calendar$'];
				var countersToUpdate = {};

				for (const counterCode in params[BX.message('SITE_ID')])
				{
					countersToCheck.map(pattern => {
						if (counterCode.match(pattern))
						{
							countersToUpdate[counterCode] = parseInt(params[BX.message('SITE_ID')][counterCode]);
						}
					});
				}

				if (Object.keys(countersToUpdate).length > 0)
				{
					this.notify.updateNotifyCounters(countersToUpdate);
				}
			}
		}, this));
	}

	if (BX.MessengerCommon.isDesktop() && this.BXIM.init)
	{
		BX.desktop.addCustomEvent("bxSaveSettings", BX.delegate(function(settings)
		{
			if (this.BXIM.messenger != null)
			{
				if (this.BXIM.settings.viewBirthday != settings.viewBirthday)
				{
					BX.MessengerCommon.applyBirthdaySettings(settings.viewBirthday);
				}
				if (this.BXIM.settings.viewCommonUsers != settings.viewCommonUsers)
				{
					BX.MessengerCommon.applyViewCommonUsers(settings.viewCommonUsers);
				}
			}

			this.BXIM.settings = settings;

			if (this.BXIM.messenger != null)
			{
				var changeTab = BX.MessengerWindow.currentTab == 'im-ol' || BX.MessengerWindow.currentTab == 'im';
				BX.MessengerCommon.drawTab(this.messenger.currentTab, true, 0, changeTab);

				BX.MessengerCommon.userListRedraw(true);

				if (this.BXIM.messenger.popupMessengerTextareaSendType)
				{
					this.BXIM.messenger.popupMessengerTextareaSendType.innerHTML = this.BXIM.settings.sendByEnter? 'Enter': (BX.browser.IsMac()? "&#8984;+Enter": "Ctrl+Enter");
				}

				BX.MessengerTheme.theme = this.BXIM.settings.enableDarkTheme;
				this.BXIM.messenger.toggleDarkTheme();
			}
			if(this.BXIM.webrtc != null)
			{
				this.BXIM.webrtc.readDefaults();
			}
		}, this));
	}
};

BX.IM.Desktop.prototype.run = function()
{
	return BX.MessengerCommon.isPage();
};

BX.IM.Desktop.prototype.ready = function()
{
	return BX.MessengerCommon.isDesktop();
};

BX.IM.Desktop.prototype.getCurrentUrl = function()
{
	if (!BX.MessengerCommon.isDesktop()) return false;
	return BX.desktop.getCurrentUrl();
}

BX.IM.Desktop.prototype.enableInVersion = function(version)
{
	if (typeof(BXDesktopSystem) == 'undefined')
		return false;

	return this.getApiVersion() >= parseInt(version);
}

BX.IM.Desktop.prototype.getApiVersion = function (full)
{
	if (typeof(BXDesktopSystem) == 'undefined')
		return 0;

	if (!this.clientVersion)
	{
		this.clientVersion = BXDesktopSystem.GetProperty('versionParts');
	}

	return full? this.clientVersion.join('.'): this.clientVersion[3];
}

BX.IM.Desktop.prototype.addCustomEvent = function(eventName, eventHandler)
{
	if (!BX.MessengerCommon.isDesktop()) return false;
	BX.desktop.addCustomEvent(eventName, eventHandler);
}

BX.IM.Desktop.prototype.onCustomEvent = function(windowTarget, eventName, arEventParams)
{
	if (!BX.MessengerCommon.isDesktop()) return false;

	if (typeof(arEventParams) === 'undefined')
	{
		BX.desktop.onCustomEvent(windowTarget, eventName);
	}
	else
	{
		BX.desktop.onCustomEvent(windowTarget, eventName, arEventParams);
	}
};

BX.IM.Desktop.prototype.windowCommand = function(command, currentWindow)
{
	if (!BX.MessengerCommon.isDesktop()) return false;

	if (typeof(currentWindow) == "undefined")
		BX.desktop.windowCommand(command)
	else
		BX.desktop.windowCommand(currentWindow, command)
};

BX.IM.Desktop.prototype.browse = function(url)
{
	if (!BX.MessengerCommon.isDesktop()) return false;
	BX.desktop.browse(url);
};

BX.IM.Desktop.prototype.drawOnPlaceholder = function(content)
{
	if (this.markup == null || !BX.type.isDomNode(content)) return false;

	this.markup.innerHTML = '';
	this.markup.appendChild(content);
};

BX.IM.Desktop.prototype.openNewNotify = function(notifyId, content, js)
{
	if (!BX.MessengerCommon.isDesktop()) return;
	if (content == "") return false;

	if (this.showNotifyId[notifyId])
		return false;

	this.showNotifyId[notifyId] = true;

	BXDesktopSystem.ExecuteCommand('notification.show.html', this.getHtmlPage(content, js, {}, 'im-notify-popup'));
};

BX.IM.Desktop.prototype.openNewMessage = function(messageId, content, js)
{
	if (!BX.MessengerCommon.isDesktop()) return;
	if (content == "") return false;

	if (this.showMessageId[messageId])
		return false;

	this.showMessageId[messageId] = true;

	BXDesktopSystem.ExecuteCommand('notification.show.html', this.getHtmlPage(content, js, true, 'im-notify-popup'));
};

BX.IM.Desktop.prototype.adjustSize = function()
{
	documentOffsetHeight = document.body.offsetHeight;

	if (
		BX.MessengerCommon.isPage()
		&& this.BXIM.context == 'POPUP-FULLSCREEN'
		&& BX.MessengerCommon.isIntranet()
	)
	{
		if (
			!BX.MessengerWindow.content.parentNode
			|| !this.BXIM.messenger.popupMessengerContent
		)
		{
			return false;
		}

		documentOffsetHeight = BX.MessengerWindow.content.offsetHeight;
		if (this.initHeight <= 0)
		{
			this.initHeight = this.BXIM.messenger.popupMessengerContent.offsetHeight;
		}

		var newHeight = documentOffsetHeight-this.initHeight;
		this.initHeight = documentOffsetHeight;
	}
	else if (BX.MessengerCommon.isPage())
	{
		if (this.BXIM.context == 'POPUP-FULLSCREEN' && BX.hasClass(BX.MessengerWindow.popup, 'bx-im-fullscreen-closed'))
		{
			return false;
		}
		if (this.BXIM.context == "LINES")
		{
			if (window.innerHeight < BX.MessengerWindow.minHeight)
			{
				return false;
			}
		}
		else if (BX.MessengerWindow.content)
		{
			documentOffsetHeight = BX.MessengerWindow.content.offsetHeight;
		}

		var newHeight = documentOffsetHeight-this.initHeight;
		this.initHeight = documentOffsetHeight;
	}
	else if (!BX.MessengerCommon.isDesktop() || !this.BXIM.init  || !this.BXIM.messenger || !this.BXIM.notify)
	{
		return false;
	}
	else
	{
		if (window.innerHeight < BX.MessengerWindow.minHeight)
			return false;
		var newHeight = documentOffsetHeight-this.initHeight;
		this.initHeight = documentOffsetHeight;
	}

	this.BXIM.messenger.popupMessengerBodySize = Math.max(this.BXIM.messenger.popupMessengerBodySize+newHeight, this.BXIM.messenger.popupMessengerBodySizeMin-(this.BXIM.messenger.popupMessengerTextareaSize-30));
	if (this.BXIM.messenger.popupMessengerBody != null)
	{
		this.BXIM.messenger.popupMessengerBody.style.height = this.BXIM.messenger.popupMessengerBodySize+'px';
		this.BXIM.messenger.popupMessengerBodyPanel.style.height = this.BXIM.messenger.popupMessengerBodyDialog.offsetHeight+'px';
		this.BXIM.messenger.redrawChatHeader();
	}

	this.BXIM.messenger.popupContactListElementsSize = Math.max(this.BXIM.messenger.popupContactListElementsSize+newHeight, this.BXIM.messenger.popupContactListElementsSizeMin);
	if (this.BXIM.messenger.popupContactListElements != null)
	{
		var finalClSize = this.BXIM.messenger.popupContactListElementsSize;
		if (!this.BXIM.options.showSearch)
		{
			finalClSize += 48;
		}
		if (!this.BXIM.options.showStatus)
		{
			finalClSize += 40;
		}
		this.BXIM.messenger.popupContactListElements.style.height = finalClSize+'px';
	}

	this.BXIM.messenger.popupMessengerFullHeight = documentOffsetHeight;
	if (this.BXIM.messenger.popupMessengerExtra != null)
		this.BXIM.messenger.popupMessengerExtra.style.height = this.BXIM.messenger.popupMessengerFullHeight+'px';

	this.BXIM.notify.popupNotifySize = Math.max(this.BXIM.notify.popupNotifySize+newHeight, this.BXIM.notify.popupNotifySizeMin);
	if (this.BXIM.notify.popupNotifyItem != null)
		this.BXIM.notify.popupNotifyItem.style.height = this.BXIM.notify.popupNotifySize+'px';

	if (this.BXIM.messenger.chatCreateFormBody)
	{
		BX.style(this.BXIM.messenger.chatCreateFormBody, 'height', this.BXIM.messenger.popupMessengerBodySize+'px');
	}
	if (this.BXIM.messenger.popupCreateChatTextarea)
	{
		BX.style(this.BXIM.messenger.popupCreateChatTextarea, 'height', this.BXIM.messenger.popupMessengerTextareaSize+'px');
	}

	this.BXIM.messenger.closeMenuPopup();

	if (BX.MessengerCommon.isDesktop())
	{
		clearTimeout(this.BXIM.adjustSizeTimeout);
		this.BXIM.adjustSizeTimeout = setTimeout(BX.delegate(function(){
			this.BXIM.setLocalConfig('global_msz_v2', {
				'wz': this.BXIM.messenger.popupMessengerFullWidth,
				'ta2': this.BXIM.messenger.popupMessengerTextareaSize,
				'b': this.BXIM.messenger.popupMessengerBodySize,
				'cl': this.BXIM.messenger.popupContactListSize,
				'hi': this.BXIM.messenger.popupHistoryItemsSize,
				'fz': this.BXIM.messenger.popupMessengerFullHeight,
				'ez': this.BXIM.messenger.popupContactListElementsSize,
				'nz': this.BXIM.notify.popupNotifySize,
				'hf': this.BXIM.messenger.popupHistoryFilterVisible,
				'dw': window.innerWidth,
				'dh': window.innerHeight,
				'place': 'desktop'
			});
		}, this), 500);
	}

	return true;
};

BX.IM.Desktop.prototype.autoResize = function(window)
{
	if (!BX.MessengerCommon.isDesktop()) return;

	BX.desktop.resize();
};

BX.IM.Desktop.prototype.openSettings = function(content, js, params)
{
	if (!BX.MessengerCommon.isDesktop()) return false;
	params = params || {};

	if(params.minSettingsWidth)
		this.minSettingsWidth = params.minSettingsWidth;

	if(params.minSettingsHeight)
		this.minSettingsHeight = params.minSettingsHeight;

	BX.desktop.createWindow("settings", BX.delegate(function(settings) {
		settings.SetProperty("clientSize", { Width: this.minSettingsWidth, Height: this.startSettingsHeight });
		settings.SetProperty("minClientSize", { Width: this.minSettingsWidth, Height: this.minSettingsHeight });
		settings.SetProperty("resizable", false);
		settings.SetProperty("title", BX.message('IM_SETTINGS'));
		settings.ExecuteCommand("html.load", this.getHtmlPage(content, js, {}));
	},this), true);
};

BX.IM.Desktop.prototype.openVideoconf = function(code)
{
	if (!BX.MessengerCommon.isDesktop())
	{
		return false;
	};

	var windowSize = null;

	var sizes = [
		{width: 2560, height: 1440},
		{width: 2048, height: 1152},
		{width: 1920, height: 1080},
		{width: 1600, height: 900},
		{width: 1366, height: 768},
		{width: 1024, height: 576},
	];

	for (var i = 0; i < sizes.length; i++)
	{
		windowSize = sizes[i];
		if (screen.width > sizes[i].width && screen.height > sizes[i].height)
		{
			break;
		}
	};

	this.videoConfList = this.videoConfList.filter(function(name) {
		return !!BX.desktop.findWindow(name);
	});

	this.videoConfList.push("videoconf"+code);

	BX.desktop.createWindow("videoconf"+code, BX.delegate(function(controller)
	{
		controller.SetProperty("title", BX.message('IM_M_CALL_VIDEOCONF'));
		controller.SetProperty("clientSize", { Width: windowSize.width, Height: windowSize.height });
		controller.SetProperty("minClientSize", { Width: 940, Height: 400 });
		controller.SetProperty("backgroundColor", "#2B3038");
		controller.ExecuteCommand("html.load",
			'<script>location.href="/desktop_app/router.php?alias='+code+'&videoconf";</script>'
		);
		controller.ExecuteCommand('show');
	},this), true);

	return true;
};

BX.IM.Desktop.prototype.showActiveVideocall = function()
{
	this.callToggleCount++;

	if (this.callToggleCount % 2 == 0)
	{
		return true;
	}

	this.videoConfList = this.videoConfList.filter(function(name) {
		var popup = BX.desktop.findWindow(name);
		if (popup)
		{
			BX.desktop.windowCommand(popup, 'show');
		}
		return !!popup;
	});
}

BX.IM.Desktop.prototype.openHistory = function(userId, content, js)
{
	if (!BX.MessengerCommon.isDesktop()) return false;

	BX.desktop.createWindow("history", BX.delegate(function(history)
	{
		var data = {'chat':{}, 'users':{}, 'files':{}};

		var diskEnable = this.messenger.disk.enable;
		if (userId.toString().substr(0,4) == 'chat')
		{
			var chatId = userId.substr(4);
			data['chat'][chatId] = this.messenger.chat[chatId];
			data['files'][chatId] = this.disk.files[chatId];
			for (var i = 0; i < this.messenger.userInChat[chatId].length; i++)
				data['users'][this.messenger.userInChat[chatId][i]] = this.messenger.users[this.messenger.userInChat[chatId][i]];
		}
		else
		{
			chatId = this.messenger.userChat[userId]? this.messenger.userChat[userId]: 0;

			data['userChat'] = {}
			data['userChat'][userId] = chatId;
			data['users'][userId] = this.messenger.users[userId];
			data['users'][this.BXIM.userId] = this.messenger.users[this.BXIM.userId];
			data['files'][chatId] = this.disk.files[chatId];
		}
		history.SetProperty("clientSize", { Width: diskEnable? this.minHistoryDiskWidth: this.minHistoryWidth, Height: this.minHistoryHeight });
		history.SetProperty("minClientSize", { Width: diskEnable? this.minHistoryDiskWidth: this.minHistoryWidth, Height: this.minHistoryHeight });
		history.SetProperty("resizable", false);
		history.ExecuteCommand("html.load", this.getHtmlPage(content, js, data));
		history.SetProperty("title", BX.message('IM_M_HISTORY'));
	},this));
};

BX.IM.Desktop.prototype.openTopmostWindow = function(name, js, initJs, bodyClass)
{
	if (!BX.MessengerCommon.isDesktop())
		return false;

	this.closeTopmostWindow();

	console.log('openTopmostWindow init', name, js);
	clearTimeout(this.topmostWindowTimeout);
	this.topmostWindowTimeout = setTimeout(BX.delegate(function(){
		if (this.topmostWindow)
			return false;

		console.log('openTopmostWindow show', name);
		this.topmostWindow = BXDesktopSystem.ExecuteCommand('topmost.show.html', this.getHtmlPage("", js, initJs, bodyClass));
	}, this), 500);
};

BX.IM.Desktop.prototype.closeTopmostWindow = function()
{
	clearTimeout(this.topmostWindowTimeout);
	clearTimeout(this.topmostWindowCloseTimeout);
	if (!this.topmostWindow)
		return false;

	console.log('closeTopmostWindow init');
	if (this.topmostWindow && this.topmostWindow.document)
		BX.desktop.windowCommand(this.topmostWindow, "hide");

	this.topmostWindowCloseTimeout = setTimeout(BX.delegate(function(){
		if (this.topmostWindow && this.topmostWindow.document)
		{
			/*if (this.topmostWindow.document && this.topmostWindow.document.title.length > 0)
			{*/
				console.log('closeTopmostWindow close');
				BX.desktop.windowCommand(this.topmostWindow, "close");
				this.topmostWindow = null;
			/*}
			else
			{
				this.closeTopmostWindow();
			}*/
		}
	}, this), 300);
}

BX.IM.Desktop.prototype.getHtmlPage = function(content, jsContent, initImJs, bodyClass)
{
	if (!BX.MessengerCommon.isDesktop()) return;

	content = content || '';
	jsContent = jsContent || '';
	bodyClass = bodyClass || '';

	var initImConfig = typeof(initImJs) == "undefined" || typeof(initImJs) != "object"? {}: initImJs;
	initImJs = typeof(initImJs) != "undefined";

	if (content != '' && BX.type.isDomNode(content))
	{
		content = content.outerHTML;
	}

	if (jsContent != '' && BX.type.isDomNode(jsContent))
	{
		jsContent = jsContent.outerHTML;
	}

	if (jsContent != '')
	{
		jsContent = '<script>BX.ready(function(){'+jsContent+'});</script>';
	}

	var initJs = '';
	if (initImJs == true)
	{
		initJs = "<script>"+
			"BX.ready(function() {"+
				"BXIM = new BX.IM(null, {"+
					"'init': false,"+
					"'debug': "+this.BXIM.debug+","+
					"'next': "+this.BXIM.next+","+
					"'betaAvailable': "+this.BXIM.betaAvailable+","+
					// context
					// design
					"'colors' : "+(this.BXIM.colors? JSON.stringify(this.BXIM.colors): "false")+","+
					"'colorsHex' : "+(this.BXIM.colorsHex? JSON.stringify(this.BXIM.colorsHex): "false")+","+
					// counters
					"'ppStatus': false,"+
					"'ppServerStatus': false,"+
					"'updateStateInterval': '"+this.BXIM.updateStateInterval+"',"+
					"'openChatEnable': "+this.BXIM.messenger.openChatEnable+","+
					"'xmppStatus': "+this.BXIM.xmppStatus+","+
					"'isAdmin': "+this.BXIM.isAdmin+","+
					"'isUtfMode': "+this.BXIM.isUtfMode+","+
					"'bitrixNetwork': "+this.BXIM.bitrixNetwork+","+
					"'bitrix24': "+this.BXIM.bitrix24+","+
					"'bitrix24blocked': "+this.BXIM.bitrix24blocked+","+
					"'bitrix24net': "+this.BXIM.bitrix24net+","+
					"'bitrixIntranet': "+this.BXIM.bitrixIntranet+","+
					"'bitrixXmpp': "+this.BXIM.bitrixXmpp+","+
					"'bitrixMobile': "+this.BXIM.bitrixMobile+","+
					"'bitrixOpenLines': "+this.BXIM.bitrixOpenLines+","+
					"'bitrixCrm': "+this.BXIM.bitrixCrm+","+
					"'desktop': "+BX.MessengerCommon.isPage()+","+
					"'desktopStatus': "+this.BXIM.desktopStatus+","+
					"'desktopVersion': "+this.BXIM.desktopVersion+","+
					// desktopLinkOpen
					"'language': '"+this.BXIM.language+"',"+
					// tooltipShowed
					// limit
					// promo
					// bot
					// textareaIcon
					// command
					// smile
					// smileSet
					"'settings' : "+JSON.stringify(this.BXIM.settings)+","+
					"'settingsNotifyBlocked' : "+JSON.stringify(this.BXIM.settingsNotifyBlocked)+","+
					"'settingsView' : "+JSON.stringify(this.BXIM.settingsView)+","+
					"'files' : "+(initImConfig.files? JSON.stringify(initImConfig.files): '{}')+","+
					"'notify' : "+(initImConfig.notify? JSON.stringify(initImConfig.notify): '{}')+","+
					"'users' : "+(initImConfig.users? JSON.stringify(initImConfig.users): '{}')+","+
					"'chat' : "+(initImConfig.chat? JSON.stringify(initImConfig.chat): '{}')+","+
					"'userChat' : "+(initImConfig.userChat? JSON.stringify(initImConfig.userChat): '{}')+","+
					"'userInChat' : "+(initImConfig.userInChat? JSON.stringify(initImConfig.userInChat): '{}')+","+
					"'hrphoto' : "+(initImConfig.hrphoto? JSON.stringify(initImConfig.hrphoto): '{}')+","+
					"'phoneCrm' : "+(initImConfig.phoneCrm? JSON.stringify(initImConfig.phoneCrm): '{}')+","+
					"'generalChatId': "+this.BXIM.messenger.generalChatId+","+
					"'canSendMessageGeneralChat': "+this.BXIM.messenger.canSendMessageGeneralChat+","+
					"'userId': "+this.BXIM.userId+","+
					"'userEmail': '"+this.BXIM.userEmail+"',"+
					"'userColor': '"+this.BXIM.userColor+"',"+
					"'userGender': '"+this.BXIM.userGender+"',"+
					"'userExtranet': "+this.BXIM.userExtranet+","+
					"'disk': {'enable': "+(this.disk? this.disk.enable: false)+", 'external': "+(this.disk? this.disk.external: false)+"},"+
					"'path' : "+JSON.stringify(this.BXIM.path)+
				"});"+
				"if (BX.SidePanel) { BX.SidePanel.Instance.anchorBinding = false; }"+
			"});"+
		"</script>";
	}

	if (BX.MessengerTheme.isDark())
	{
		bodyClass = bodyClass+' bx-messenger-dark';
	}

	if (BX.desktop.isPopupPageLoaded())
	{
		return '<div class="im-desktop im-desktop-popup bx-messenger-mark '+bodyClass+'"><div id="placeholder-messanger">'+content+'</div>'+initJs+jsContent+'</div>';
	}
	else
	{
		if (this.htmlWrapperHead == null)
		{
			this.htmlWrapperHead = document.head.outerHTML.replace(/BX\.PULL\.start\([^)]*\);/g, '');
		}

		return '<!DOCTYPE html><html>'+this.htmlWrapperHead+'<div><div class="im-desktop im-desktop-popup bx-messenger-mark '+bodyClass+'"><div id="placeholder-messanger">'+content+'</div>'+initJs+jsContent+'</div></body></html>';
	}
};

BX.IM.Desktop.prototype.onAwayAction = function (away, manual)
{
	BX.ajax({
		url: this.BXIM.pathToAjax+'?IDLE&V='+this.BXIM.revision,
		method: 'POST',
		dataType: 'json',
		timeout: 30,
		data: {'IM_IDLE' : 'Y', 'IM_AJAX_CALL' : 'Y', IDLE: away? 'Y': 'N', MANUAL: manual? 'Y': 'N', 'sessid': BX.bitrix_sessid()},
		onsuccess: BX.delegate(function(data)
		{
			if (data && data.BITRIX_SESSID)
			{
				BX.message({'bitrix_sessid': data.BITRIX_SESSID});
			}
			if (data.ERROR == 'AUTHORIZE_ERROR' && BX.MessengerCommon.isDesktop() && this.messenger.sendAjaxTry < 3)
			{
				this.messenger.sendAjaxTry++;
				BX.onCustomEvent(window, 'onImError', [data.ERROR]);
			}
			else if (data.ERROR == 'SESSION_ERROR' && this.messenger.sendAjaxTry < 2)
			{
				this.messenger.sendAjaxTry++;
				BX.onCustomEvent(window, 'onImError', [data.ERROR, data.BITRIX_SESSID]);
			}
			else
			{
				if (data.ERROR == 'AUTHORIZE_ERROR' || data.ERROR == 'SESSION_ERROR')
				{
					BX.onCustomEvent(window, 'onImError', [data.ERROR]);
				}
			}
		}, this)
	});
}

BX.IM.Desktop.prototype.onWakeAction = function ()
{
	BX.desktop.setIconStatus('offline');

	this.BXIM.messenger.toggleDarkTheme();
}

BX.IM.Desktop.prototype.onApplicationClick = function ()
{
	this.showActiveVideocall();

	return true;
};

BX.IM.Desktop.prototype.birthdayStatus = function(value)
{
	if (!BX.MessengerCommon.isDesktop()) return false;

	if (typeof(value) !='boolean')
	{
		return this.BXIM.getLocalConfig('birthdayStatus', true);
	}
	else
	{
		this.BXIM.setLocalConfig('birthdayStatus', value);
		return value;
	}
};

BX.IM.Desktop.prototype.isTwoWindowMode = function()
{
	return !!BXDesktopSystem.IsTwoWindowsMode();
}

BX.IM.Desktop.prototype.setTwoWindowMode = function(enable)
{
	if (!BX.MessengerCommon.isDesktop())
	{
		return false;
	}

	if (enable)
	{
		if (this.isTwoWindowMode())
		{
			return true;
		}

		BXDesktopSystem.V10();
	}
	else
	{
		if (!this.isTwoWindowMode())
		{
			return true;
		}

		BXDesktopSystem.V8();
	}

	this.BXIM.openConfirm(BX.message('IM_M_BITRIX24_WINDOW_MODE_NOTICE'), [
		!this.BXIM.desktop.enableInVersion(74) ? null : new BX.PopupWindowButton({
			text : BX.message('IM_M_BITRIX24_WINDOW_MODE_RELAUNCH'),
			className : "popup-window-button-accept",
			events : { click : function() {
				BXDesktopSystem.Restart();
			}}
		}),
		new BX.PopupWindowButton({
			text : BX.message('IM_NOTIFY_CONFIRM_CLOSE'),
			className : "popup-window-button",
			events : { click : function() { this.popupWindow.close(); } }
		})
	]);

	return true;
};

BX.IM.Desktop.prototype.sliderStatus = function(value)
{
	if (!(BX.MessengerCommon.isDesktop() && BX.MessengerCommon.isSliderBindingsEnable()))
	{
		return false;
	}

	if (typeof(value) !='boolean')
	{
		return this.BXIM.getLocalConfig('sliderStatus', true);
	}
	else
	{
		this.BXIM.setLocalConfig('sliderStatus', value);

		if (value)
		{
			(opener? opener: top).BX.SidePanel.Instance.enableAnchorBinding();
		}
		else
		{
			(opener? opener: top).BX.SidePanel.Instance.disableAnchorBinding();
		}

		return value;
	}
};

BX.IM.Desktop.prototype.changeTab = function(currentTab)
{
	return false;
};

BX.IM.Desktop.prototype.setBrowserIconBadge = function (count, text)
{
	if (this.getApiVersion() < 57)
	{
		return false;
	}

	if (typeof text !== 'string')
	{
		text = '';
	}

	if (this.lastCounter === count)
	{
		return true;
	}

	this.lastCounter = count;

	BXDesktopSystem.SetBrowserIconBadge(count, text);

	return true;
}

BX.IM.Desktop.prototype.setPreventEsc = function(shouldPrevent)
{
	shouldPrevent = !!shouldPrevent;

	if (shouldPrevent)
	{
		this.preventEsc++;
	}
	else
	{
		this.preventEsc--;
		if (this.preventEsc < 0)
		{
			this.preventEsc = 0;
		}
	}

	if (
		this.BXIM.messenger
		&& this.BXIM.messenger.popupMessenger
	)
	{
		this.BXIM.messenger.popupMessenger.setClosingByEsc(this.preventEsc <= 0);
	}
};