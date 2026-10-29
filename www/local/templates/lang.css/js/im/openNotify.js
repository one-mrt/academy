BX.MessengerNotify.prototype.openNotify = function(reOpen, force)
{
    this.BXIM.messenger.popupMessengerExtra.classList.add('rt-0')

    if (this.nextNotifications)
    {
        return this.openNotifyNext(reOpen, force);
    }

    reOpen = reOpen == true;
    force = force == true;

    if (!this.messenger)
    {
        return false;
    }

    if (this.messenger.popupMessenger == null)
    {
        this.messenger.openMessenger(false);
    }

    if (
        BX.MessengerCommon.isPopupPage()
        && !BX.MessengerSlider.isFocus()
        && !this.BXIM.notifyOpen
    )
    {
        BX.MessengerSlider.open().then(function(){
            this.openNotify(reOpen, force);
            this.BXIM.desktop.adjustSize();
            setTimeout(function(){
                BX.MessengerSlider.getCurrent().closeLoader();
            }, 50);
        }.bind(this));

        return true;
    }

    if (BX.MessengerCommon.isPage())
    {
        BX.MessengerWindow.changeTab('notify', true, true);
    }

    if (this.BXIM.notifyOpen && !force)
    {
        if (!reOpen)
        {
            this.messenger.extraClose(true);
            return false;
        }
    }
    else
    {
        this.BXIM.dialogOpen = false;
        this.BXIM.notifyOpen = true;
        if (!BX.MessengerCommon.isPage())
        {
            this.messengerNotifyButton.className = "bx-messenger-cl-notify-button bx-messenger-cl-notify-button-active";
        }
    }

    this.messenger.closeMenuPopup();

    var arNotify = this.drawNotify();
    this.notifyBody = BX.create("div", { props : { className : "bx-notifier-wrap" }, children : [
        BX.create("div", { props : { className : "bx-messenger-panel" }, children : [
            BX.create('span', { props : { className : "bx-messenger-panel-avatar bx-messenger-avatar-notify"}}),
            BX.create("span", { props : { className : "bx-messenger-panel-title bx-messenger-panel-title-middle"}, html: BX.message('IM_NOTIFY_WINDOW_TITLE')})
        ]}),
        this.popupNotifyButtonFilterBox = BX.create("div", { props : { className : "bx-messenger-panel-filter-box" }, style : {display: 'none'}, children : [
            BX.create('div', {props : { className : "bx-messenger-filter-name" }, html: BX.message('IM_PANEL_FILTER_NAME')}),
            this.popupHistorySearchDateWrap = BX.create('div', {props : { className : "bx-messenger-filter-date bx-messenger-input-wrap bx-messenger-filter-date-notify" }, html: '<span class="bx-messenger-input-date"></span><a class="bx-messenger-input-close" href="#close"></a><input type="text" class="bx-messenger-input" value="" tabindex="1002" placeholder="'+BX.message('IM_PANEL_FILTER_DATE')+'" />'})
        ]}),
        this.popupNotifyItem = BX.create("div", { props : { className : "bx-notifier-item-wrap" }, style : {height: this.popupNotifySize+'px'}, children : arNotify})
    ]});
    this.messenger.extraOpen(this.notifyBody);

    /*
    clearTimeout(this.popupMessengerTopLineTimeout);
    this.popupMessengerTopLineTimeout = setTimeout(BX.delegate(function(){
        this.BXIM.notifyManager.nativeNotifyAccessForm();
    }, this), 10000);
    */

    if (this.unreadNotifyLoad)
        this.loadNotify();
    else if (!this.notifyLoad && this.BXIM.settings.loadLastNotify)
        this.notifyHistory();

    if (!reOpen && this.BXIM.isFocus('notify') && this.notifyUpdateCount > 0)
        this.viewNotifyAll();

    BX.bind(this.popupNotifyItem, "scroll", BX.delegate(function() {
        if (this.messenger.popupPopupMenu != null)
        {
            if (BX.util.in_array(this.messenger.popupPopupMenu.uniquePopupId.replace('bx-messenger-popup-', ''), ["copypaste", "copylink", "notifyDelete", "notify", "external-data"]))
            {
                this.messenger.popupPopupMenu.close();
            }
        }
    }, this));

    BX.bind(BX('bx-notifier-content-link-history'), "click", BX.delegate(this.notifyHistory, this));

    BX.bind(this.popupNotifyItem, "click", BX.delegate(this.closePopup, this));

    BX.bind(this.notifyBody, "click",  BX.delegate(function(e){
        BX.MessengerCommon.contactListSearchClear(e);
    }, BX.MessengerCommon));

    // BX.bindDelegate(this.popupNotifyItem, 'click', {className: 'bx-messenger-ajax'}, BX.delegate(function() {
    // 	if (BX.proxy_context.getAttribute('data-entity') == 'user')
    // 	{
    // 		this.messenger.openPopupExternalData(BX.proxy_context, 'user', true, {'ID': BX.proxy_context.getAttribute('data-userId')})
    // 	}
    // 	else if (BX.proxy_context.getAttribute('data-entity') == 'chat')
    // 	{
    // 		this.messenger.openPopupExternalData(BX.proxy_context, 'chat', true, {'ID': BX.proxy_context.getAttribute('data-chatId')})
    // 	}
    // 	else if (BX.proxy_context.getAttribute('data-entity') == 'openlines')
    // 	{
    // 		this.messenger.linesOpenHistory(BX.proxy_context.getAttribute('data-sessionId'));
    // 	}
    // 	else if (BX.proxy_context.getAttribute('data-entity') == 'network')
    // 	{
    // 		this.messenger.openMessenger('network'+BX.proxy_context.getAttribute('data-networkId'))
    // 	}
    // 	else if (BX.proxy_context.getAttribute('data-entity') == 'date')
    // 	{
    // 		this.messenger.openPopupMenu(BX.proxy_context, 'shareMenu');
    // 	}
    // }, this));

    BX.bindDelegate(this.popupNotifyItem, 'click', {className: 'bx-notifier-item-help'}, BX.proxy(function(e) {
        if (this.popupNotifyMore != null)
            this.popupNotifyMore.destroy();
        else
        {
            var notifyHelp = this.notify[BX.proxy_context.getAttribute('data-help')];
            if (!Array.isArray(notifyHelp.params.USERS) || notifyHelp.params.USERS.length <= 0)
            {
                return false;
            }

            //check if we need to request users data
            var isNeedUserRequest = false;
            for (var i = 0; i < notifyHelp.params.USERS.length; i++)
            {
                if (BXIM.messenger.users[notifyHelp.params.USERS[i]] === undefined)
                {
                    isNeedUserRequest = true;
                    break;
                }
            }

            if (isNeedUserRequest)
            {
                var popupContent = BX.create("div", {
                    props: { className: "bx-messenger-popup-menu" },
                    children: [
                        BX.create('div', {
                            style: {
                                'display': 'flex',
                                'align-items': 'center',
                                'justify-content': 'center',
                                'height': notifyHelp.params.USERS.length * 30 + 'px',
                                'min-width': '180px',
                            },
                            children: [
                                BX.create('span', { props: { className: "bx-messenger-content-load-img-old" } })
                            ]
                        })
                    ]
                });

                BX.rest.callMethod('im.user.list.get', {
                    ID: notifyHelp.params.USERS
                }).then(function(result)
                {
                    var users = result.data();
                    for (var userId in users)
                    {
                        if (typeof BXIM.messenger.users[users[userId]] === 'undefined')
                        {
                            users[userId].name = BX.util.htmlspecialchars(users[userId].name);
                            users[userId].first_name = BX.util.htmlspecialchars(users[userId].first_name);
                            users[userId].last_name = BX.util.htmlspecialchars(users[userId].last_name);
                            users[userId].work_position = BX.util.htmlspecialchars(users[userId].work_position);
                            users[userId].external_auth_id = BX.util.htmlspecialchars(users[userId].external_auth_id);
                            users[userId].status = BX.util.htmlspecialchars(users[userId].status);
                            users[userId].absent = users[userId].absent? new Date(users[userId].absent): false;
                            users[userId].idle = users[userId].idle? new Date(users[userId].idle): false;
                            users[userId].last_activity_date = users[userId].last_activity_date? new Date(users[userId].last_activity_date): false;
                            users[userId].mobile_last_date = users[userId].mobile_last_date? new Date(users[userId].mobile_last_date): false;
                            BXIM.messenger.users[userId] = users[userId];
                        }
                    }

                    //build popup content
                    htmlElement = this.buildMoreUsersNotifyPopup(notifyHelp.params.USERS);
                    popupContent = BX.create("div", { props: { className: "bx-messenger-popup-menu" }, html: htmlElement });
                    this.popupNotifyMore.setContent(htmlElement);
                }.bind(this));
            }
            else
            {
                htmlElement = this.buildMoreUsersNotifyPopup(notifyHelp.params.USERS)
                var popupContent = BX.create("div", { props: { className: "bx-messenger-popup-menu" }, html: htmlElement });
            }

            this.popupNotifyMore = new BX.PopupWindow('bx-notifier-other-window', BX.proxy_context, {
                //parentPopup: this.messenger.popupMessenger,
                targetContainer: document.body,
                darkMode: BX.MessengerTheme.isDark(),
                zIndex: BX.MessengerCommon.getDefaultZIndex()+200,
                lightShadow : true,
                offsetTop: -2,
                offsetLeft: 3,
                autoHide: true,
                closeByEsc: true,
                bindOptions: {position: "top"},
                events : {
                    onPopupClose : function() { this.destroy() },
                    onPopupDestroy : BX.proxy(function() { this.popupNotifyMore = null; }, this)
                },
                content: popupContent
            });
            if (!BX.MessengerTheme.isDark())
                this.popupNotifyMore.setAngle({});
            BX.addClass(this.popupNotifyMore.popupContainer, "bx-messenger-mark");
            this.popupNotifyMore.show();
            BX.bind(this.popupNotifyMore.popupContainer, "click", BX.MessengerCommon.preventDefault);
        }

        return BX.PreventDefault(e);
    }, this));

    BX.bindDelegate(this.popupNotifyItem, 'click', {className: 'bx-notifier-answer-reply'}, BX.proxy(function(e) {
        if (!BX.proxy_context) return;

        if (!this.toggleNotifyAnswer(BX.proxy_context.parentNode))
            return true;

        return BX.PreventDefault(e);
    }, this));

    var item = BX.findChildByClassName(this.popupNotifyItem, "bx-notifier-answer-box-open");
    if (item)
    {
        var itemInput = item.firstChild.nextSibling.firstChild;
        itemInput.focus();
        itemInput.selectionStart = itemInput.value.length+1;
        itemInput.selectionEnd = itemInput.value.length+1;
    }

    BX.bindDelegate(this.popupNotifyItem, 'click', {className: 'bx-notifier-answer-button'}, BX.proxy(function(e) {
        if (!BX.proxy_context) return;

        this.sendNotifyAnswer(BX.proxy_context.parentNode);

        return BX.PreventDefault(e);
    }, this));

    BX.bindDelegate(this.popupNotifyItem, 'click', {className: 'bx-notifier-item-delete'}, BX.proxy(function(e) {
        if (!BX.proxy_context) return;

        BX.proxy_context.setAttribute('id', 'bx-notifier-item-delete-'+BX.proxy_context.getAttribute('data-notifyId'));
        this.deleteNotify(BX.proxy_context.getAttribute('data-notifyId'));

        return BX.PreventDefault(e);
    }, this));

    BX.bindDelegate(this.popupNotifyItem, 'click', {className: 'bx-notifier-item-button-confirm'}, BX.proxy(function(e) {
        if (this.messenger.popupMessengerConnectionStatusState != 'online')
            return false;

        var notifyId = BX.proxy_context.getAttribute('data-id');
        this.confirmRequest({
            'notifyId': notifyId,
            'notifyValue': BX.proxy_context.getAttribute('data-value'),
            'notifyURL': BX.proxy_context.getAttribute('data-url'),
            'notifyTag': this.notify[notifyId] && this.notify[notifyId].tag? this.notify[notifyId].tag: null,
            'groupDelete': BX.proxy_context.getAttribute('data-group') != null
        });
        this.openNotify(true);

        if (BX.MessengerCommon.isMobile())
        {
            if (BX.proxy_context.parentNode.parentNode.parentNode.previousSibling == null && BX.proxy_context.parentNode.parentNode.parentNode.nextSibling == null)
                this.openNotify(true);
            else if (BX.proxy_context.parentNode.parentNode.parentNode.previousSibling == null && BX.proxy_context.parentNode.parentNode.parentNode.nextSibling.tagName.toUpperCase() == 'A')
                this.openNotify(true);
            else
                BX.remove(BX.proxy_context.parentNode.parentNode.parentNode);
        }

        return BX.PreventDefault(e);
    }, this));

    if (BX.MessengerCommon.isDesktop())
    {
        BX.bindDelegate(this.popupNotifyItem, 'contextmenu', {className: 'bx-notifier-item-content'}, BX.delegate(function(e) {
            if (!BX.proxy_context) return;

            BX.proxy_context.parentNode.setAttribute('id', 'bx-notifier-item-delete-'+BX.proxy_context.parentNode.getAttribute('data-notifyId'));
            this.messenger.openPopupMenu(e, 'notify', false);

            return BX.PreventDefault(e);
        }, this));
    }
    else
    {
        BX.bindDelegate(this.popupNotifyItem, 'contextmenu', {className: 'bx-notifier-item-delete'}, BX.proxy(function(e) {
            if (!BX.proxy_context) return;

            BX.proxy_context.setAttribute('id', 'bx-notifier-item-delete-'+BX.proxy_context.getAttribute('data-notifyId'));
            this.messenger.openPopupMenu(BX.proxy_context, 'notifyDelete');

            return BX.PreventDefault(e);
        }, this));
    }

    BX.bindDelegate(this.popupNotifyItem, 'dblclick', {className: 'bx-notifier-item'}, BX.delegate(function(e) {
        if (!BX.proxy_context) return;

        var notifyId = BX.proxy_context.getAttribute('data-notifyId');
        if (this.unreadNotify[notifyId])
        {
            this.viewNotify(notifyId, true);
        }
        else
        {
            this.viewNotify(notifyId, false);
        }

        return BX.PreventDefault(e);
    }, this));

    if (false && !this.BXIM.settings.notifyAutoRead) // TODO read after click
    {
        BX.bindDelegate(this.popupNotifyItem, 'click', {className: 'bx-notifier-item-text-link'}, BX.delegate(function(e) {
            var notifyId = BX.proxy_context.parentNode.parentNode.parentNode.getAttribute('data-notifyId');
            if (this.unreadNotify[notifyId])
            {
                this.viewNotify(notifyId, true);
            }
        }, this));
    }

    return false;
};