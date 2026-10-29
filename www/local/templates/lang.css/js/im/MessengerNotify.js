// BX.MessengerNotify = function(BXIM, params)
// {
//     this.BXIM = BXIM;
//     this.settings = {};
//     this.params = params || {};
//     this.windowInnerSize = {};
//     this.windowScrollPos = {};
//     this.sendAjaxTry = 0;

//     /*
//     -2 - Not supported
//     -1 - Failed
//     0 - All enabled
//     1 - Priority only
//     2 - Alarm only / DnD
//     3 - Toast disabled
//     4 - Disabled
//     */
//     this.muteModeCode = 0;

//     this.webrtc = params.webrtcClass;
//     this.desktop = params.desktopClass;

//     this.notifyCount = params.chatCounters? params.chatCounters.type.notify: 0;
//     this.notifyUpdateCount = params.chatCounters? params.chatCounters.type.notify: 0;
//     this.notify = {};
//     this.mailCount = params.chatCounters? params.chatCounters.type.mail: 0;
//     this.counters = params.counters;

//     this.notifyAnswerBlock = {};
//     this.notifyAnswerText = {};

//     this.notifyHistoryPage = 0;
//     this.notifyHistoryLoad = false;

//     this.notifyBody = null;
//     this.notifyLoad = false;
//     this.unreadNotify = {};
//     this.unreadNotifyLoad = true;
//     this.flashNotify = {};
//     this.confirmDisabledButtons = false;

//     if (params.domNode)
//     {
//         this.panel = params.domNode;
//         this.panelEnabled = true;
//         BX.bind(this.panel, 'click', BX.PreventDefault);
//     }
//     else
//     {
//         this.panel = BX.create('span', {props: { className: "bx-messenger-hide"}});
//         this.panelEnabled = false;
//     }

//     if (this.panelEnabled)
//     {
//         if (BX.browser.IsDoctype())
//             BX.addClass(this.panel, 'bx-notifier-panel-doc');
//         else
//             BX.addClass(document.body, 'bx-no-doctype');

//         this.panelButtonCall = BX.findChildByClassName(this.panel, "bx-notifier-call");
//         if (!this.webrtc.phoneEnabled || !this.webrtc.phoneCanPerformCalls)
//         {
//             BX.style(this.panelButtonCall, 'display', 'none');
//         }

//         this.panelButtonNetwork = BX.findChildByClassName(this.panel, "bx-notifier-network");
//         if (this.panelButtonNetwork)
//         {
//             this.panelButtonNetworkCount = BX.findChildByClassName(this.panelButtonNetwork, "bx-notifier-indicator-count");
//             if (this.BXIM.bitrixNetwork)
//             {
//                 this.panelButtonNetwork.href = "https://www.bitrix24.net/";
//                 this.panelButtonNetwork.setAttribute('target', '_blank');
//                 if (this.panelButtonNetworkCount != null)
//                     this.panelButtonNetworkCount.innerHTML = '';
//             }
//             else
//             {
//                 BX.style(this.panelButtonNetwork, 'display', 'none');
//                 this.panelButtonNetworkCount.innerHTML = '';
//             }
//         }

//         this.panelButtonNotify = BX.findChildByClassName(this.panel, "bx-notifier-notify");
//         if (this.panelButtonNotify)
//         {
//             this.panelButtonNotifyCount = BX.findChildByClassName(this.panelButtonNotify, "bx-notifier-indicator-count");
//             if (this.panelButtonNotifyCount)
//                 this.panelButtonNotifyCount.innerHTML = '';
//         }

//         this.panelButtonMessage = BX.findChildByClassName(this.panel, "bx-notifier-message");
//         if (this.panelButtonMessage)
//         {
//             this.panelButtonMessageCount = BX.findChildByClassName(this.panelButtonMessage, "bx-notifier-indicator-count");
//             if (this.panelButtonMessageCount)
//                 this.panelButtonMessageCount.innerHTML = '';
//         }

//         this.panelButtonMail = BX.findChildByClassName(this.panel, "bx-notifier-mail");
//         if (this.panelButtonMail)
//         {
//             this.panelButtonMailCount = BX.findChildByClassName(this.panelButtonMail, "bx-notifier-indicator-count");
//             if (this.panelButtonMailCount)
//             {
//                 this.panelButtonMail.href = this.BXIM.path.mail;
//                 this.panelButtonMail.setAttribute('target', '_blank');
//                 if (this.panelButtonMailCount != null)
//                     this.panelButtonMailCount.innerHTML = '';
//             }
//         }
//         this.panelDragLabel = BX.findChildByClassName(this.panel, "bx-notifier-drag");
//         if (this.panelDragLabel)
//         {
//             BX.bind(this.panelDragLabel, "mousedown", BX.delegate(this._startDrag, this));
//             BX.bind(this.panelDragLabel, "dobleclick", BX.delegate(this._stopDrag, this));
//         }
//     }

//     if (BX.browser.IsAndroid() || BX.browser.IsIOS())
//         BX.addClass(document.body, 'bx-im-mobile');

//     this.messenger = null;
//     this.messengerNotifyButton = null;
//     this.messengerNotifyButtonCount = null;

//     /* full window notify */
//     this.popupNotifyItem = null;
//     this.popupNotifySize = 387;
//     this.popupNotifySizeMin = 317;

//     this.popupNotifyButtonFilter = null;
//     this.popupNotifyButtonFilterBox = null;
//     this.popupHistoryFilterVisible = false;
//     /* more users from notify */
//     this.popupNotifyMore = null;

//     this.dragged = false;
//     this.dragPageX = 0;
//     this.dragPageY = 0;

//     this.notificationApp = null;
//     this.nextNotifications = true;

//     if (this.BXIM.init)
//     {
//         if (!this.BXIM.options.v2layout)
//         {
//             BX.Messenger.Application.Launch('notifications', {
//                 node: '#notifyNext',
//                 mode: 'legacy',
//                 initCounter: this.notifyCount
//             }).then(function(application) {
//                 this.notificationApp = application;
//             }.bind(this));
//         }

//         if (BX.MessengerCommon.isPage())
//         {
//             BX.MessengerWindow.addTab({
//                 id: 'notify',
//                 title: BX.message('IM_NOTIFY_BUTTON_TITLE'),
//                 badge: this.notifyCount,
//                 order: 115,
//                 target: 'im',
//                 toggleEnable: false,
//                 events: {
//                     open: function(){
//                         this.openNotify(false, true)
//                     }.bind(this),
//                     close: function() {
//                         this.messenger.extraClose();
//                     }.bind(this),
//                 }
//             });
//         }

//         this.panel.appendChild(this.BXIM.audio.reminder = BX.create("audio", { props : { className : "bx-notify-audio" }, children : [
//             BX.create("source", { attrs : { src : "/bitrix/js/im/audio/reminder.ogg", type : "audio/ogg; codecs=vorbis" }}),
//             BX.create("source", { attrs : { src : "/bitrix/js/im/audio/reminder.mp3", type : "audio/mpeg" }})
//         ]}));
//         if (typeof(this.BXIM.audio.reminder.play) == 'undefined')
//         {
//             this.BXIM.settings.enableSound = false;
//         }

//         if (BX.browser.SupportLocalStorage())
//         {
//             BX.addCustomEvent(window, "onLocalStorageSet", BX.proxy(this.storageSet, this));
//             var panelPosition = BX.localStorage.get('npp');
//             this.BXIM.settings.panelPositionHorizontal = !!panelPosition? panelPosition.h: this.BXIM.settings.panelPositionHorizontal;
//             this.BXIM.settings.panelPositionVertical = !!panelPosition? panelPosition.v: this.BXIM.settings.panelPositionVertical;

//             var mfn = BX.localStorage.get('mfn');
//             if (mfn)
//             {
//                 for (var i in this.flashNotify)
//                     if (this.flashNotify[i] != mfn[i] && mfn[i] == false)
//                         this.flashNotify[i] = false;
//             }
//         }

//         if (this.panelButtonNotify)
//         {
//             BX.bind(this.panelButtonNotify, "click", BX.proxy(function(){
//                 this.toggleNotify()
//             }, this.BXIM));
//         }

//         if (this.webrtc.phoneEnabled && this.webrtc.phoneCanPerformCalls)
//         {
//             if (this.panelButtonCall)
//             {
//                 BX.bind(this.panelButtonCall, "click", () => {
//                     this.webrtc.openKeyPad({
//                         bindElement: this.panelButtonCall,
//                         offsetTop: 5,
//                         offsetLeft: -75,
//                         anglePosition: this.panelButtonCallAnlgePosition ? this.panelButtonCallAnlgePosition : undefined,
//                         angleOffset: this.panelButtonCallAnlgeOffset ? this.panelButtonCallAnlgeOffset : undefined,
//                     });
//                 });
//             }
//             BX.bind(window, 'scroll', BX.delegate(function(){
//                 this.webrtc.closeKeyPad();
//             }, this));
//         }

//         if (this.panelDragLabel)
//         {
//             BX.bind(this.panelDragLabel, "mousedown", BX.proxy(this._startDrag, this));
//             BX.bind(this.panelDragLabel, "dobleclick", BX.proxy(this._stopDrag, this));
//         }

//         this.updateNotifyMailCount();

//         if (!BX.MessengerCommon.isPage())
//         {
//             this.adjustPosition({resize: true});
//             BX.bind(window, "resize", BX.proxy(function(){
//                 this.closePopup();
//                 this.adjustPosition({resize: true});
//             }, this));
//             if (!BX.browser.IsDoctype())
//                 BX.bind(window, "scroll", BX.proxy(function(){ this.adjustPosition({scroll: true});}, this));
//         }

//         setTimeout(BX.delegate(function(){
//             this.updateNotifyCounters();
//             this.updateNotifyCount();
//         }, this), 100);
//     }

//     BX.addCustomEvent(window, "onSonetLogCounterClear", BX.proxy(function(counter){
//         var sendObject = {};
//         sendObject[counter] = 0;
//         this.updateNotifyCounters(sendObject);
//     }, this));

//     // desktop integratinon
//     if (this.desktop.ready() && this.desktop.getApiVersion() >= 58)
//     {
//         this.muteModeCode = BXDesktopSystem.NotificationsMode();
//         BX.desktop.addCustomEvent("BXNotificationsMode", function(code) {
//             this.muteModeCode = code;
//         }.bind(this));
//     }
// };