BX.MessengerCommon.openDialog = function(dialogId, extraClose, callToggle)
{
    this.BXIM.messenger.popupMessengerDialog.classList.add('rt-0')
    var dialog = BX.MessengerCommon.getUserParam(dialogId);
    if (dialog.id <= 0)
        return false;

    dialogId = dialogId? dialogId: 0;

    var element = this.recentListGetItem(dialogId);
    if (element && element.unread)
    {
        this.recentListUpdateItem({
            id: dialogId,
            unread: false
        });
        this.recentListRedraw();

        BX.rest.callMethod('im.recent.unread', {'DIALOG_ID': dialogId, 'ACTION': 'N'});
    }

    this.BXIM.messenger.currentTab = dialogId;
    if (dialogId.toString().substr(0,4) == 'chat')
    {
        this.BXIM.messenger.openChatFlag = true;
        if (this.BXIM.messenger.chat[dialogId.toString().substr(4)] && this.BXIM.messenger.chat[dialogId.toString().substr(4)].type == 'call')
            this.BXIM.messenger.openCallFlag = true;
        else if (this.BXIM.messenger.chat[dialogId.toString().substr(4)] && this.BXIM.messenger.chat[dialogId.toString().substr(4)].type == 'lines')
        {
            if (!this.BXIM.bitrixOpenLines)
            {
                return false;
            }
            this.BXIM.messenger.openLinesFlag = true;
        }
    }

    BX.localStorage.set('mct', this.BXIM.messenger.currentTab, 15);

    if (this.isMobile())
    {
        this.BXIM.messenger.dialogStatusRedrawDelay();
    }
    else
    {
        this.BXIM.messenger.dialogStatusRedraw();
    }

    if (!this.isMobile())
    {
        if (this.BXIM.messenger.popupMessengerPanel)
        {
            this.BXIM.messenger.popupMessengerPanel.className  = this.BXIM.messenger.openChatFlag? 'bx-messenger-panel bx-messenger-hide': 'bx-messenger-panel';
        }

        if (this.BXIM.messenger.openChatFlag)
        {
            this.BXIM.messenger.popupMessengerPanelChat.className = this.BXIM.messenger.openCallFlag? 'bx-messenger-panel bx-messenger-hide': 'bx-messenger-panel';
            this.BXIM.messenger.popupMessengerPanelCall.className = this.BXIM.messenger.openCallFlag? 'bx-messenger-panel': 'bx-messenger-panel bx-messenger-hide';
        }
        else
        {
            this.BXIM.messenger.popupMessengerPanelChat.className = 'bx-messenger-panel bx-messenger-hide';
            this.BXIM.messenger.popupMessengerPanelCall.className = 'bx-messenger-panel bx-messenger-hide';
        }
    }

    extraClose = extraClose == true;
    callToggle = callToggle === true;

    var arMessage = [];
    if (typeof(this.BXIM.messenger.showMessage[dialogId]) != 'undefined' && this.BXIM.messenger.showMessage[dialogId].length > 0)
    {
        if (
            !this.isMobile()
            && this.BXIM.messenger.showMessage[dialogId]
            && this.BXIM.messenger.showMessage[dialogId].length != 0
            && this.BXIM.messenger.showMessage[dialogId].length == BX.MessengerCommon.getCounter(dialogId)
        )
        {
            this.drawTab(dialogId, true);

            BX.addClass(this.BXIM.messenger.popupMessengerBodyWrap, 'bx-messenger-loading');
            var loading = BX.create("div", { props : { className : "bx-notifier-content-link-history"}, children : [
                BX.create('span', { props : { className : "bx-messenger-content-load-img" }}),
                BX.create("span", { props : { className : "bx-messenger-content-load-text"}, html: BX.message('IM_M_LOAD_MESSAGE')})
            ]});
            this.BXIM.messenger.redrawTab[dialogId] = true;

            this.BXIM.messenger.popupMessengerBodyWrap.insertBefore(loading, this.BXIM.messenger.popupMessengerBodyWrap.firstChild);

            if (this.isMobile())
            {
                setTimeout(BX.delegate(function(){this.BXIM.messenger.autoScroll()}, this), 100);
            }
        }
        else if (!dialog.fake && this.BXIM.messenger.showMessage[dialogId].length >= 15)
        {
            if (this.isMobile() && this.BXIM.webComponent)
            {
                this.drawTab(dialogId, true);
                this.BXIM.messenger.redrawTab[dialogId] = true;
            }
            else if (this.BXIM.messenger.redrawTab[dialogId])
            {
                this.drawTab(dialogId, true);
            }
            // else // TODO remove this later
            // {
            //	this.BXIM.messenger.redrawTab[dialogId] = false;
            // }
        }
        else
        {
            this.drawTab(dialogId, true);
            this.BXIM.messenger.redrawTab[dialogId] = true;
        }
    }
    else if (this.BXIM.messenger.popupMessengerConnectionStatusState != 'online')
    {
        BX.addClass(this.BXIM.messenger.popupMessengerBodyWrap, 'bx-messenger-loading');
        arMessage = [BX.create("div", { props : { className : "bx-messenger-content-empty"}, children : [
            BX.create("span", { props : { className : "bx-messenger-content-load-text"}, html: BX.message("IM_M_LOAD_ERROR")})
        ]})];
        this.BXIM.messenger.redrawTab[dialogId] = true;
    }
    else if (typeof(this.BXIM.messenger.showMessage[dialogId]) == 'undefined')
    {
        BX.addClass(this.BXIM.messenger.popupMessengerBodyWrap, 'bx-messenger-loading');
        arMessage = [BX.create("div", { props : { className : "bx-messenger-content-load"}, children : [
            BX.create('span', { props : { className : "bx-messenger-content-load-img" }}),
            BX.create("span", { props : { className : "bx-messenger-content-load-text"}, html: BX.message('IM_M_LOAD_MESSAGE')})
        ]})];
        this.BXIM.messenger.redrawTab[dialogId] = true;
    }
    else if (this.BXIM.messenger.redrawTab[dialogId] && this.BXIM.messenger.showMessage[dialogId].length == 0)
    {
        BX.addClass(this.BXIM.messenger.popupMessengerBodyWrap, 'bx-messenger-loading');
        arMessage = [BX.create("div", { props : { className : "bx-messenger-content-load"}, children : [
            BX.create('span', { props : { className : "bx-messenger-content-load-img" }}),
            BX.create("span", { props : { className : "bx-messenger-content-load-text"}, html: BX.message("IM_M_LOAD_MESSAGE")})
        ]})];
        this.BXIM.messenger.showMessage[dialogId] = [];
    }
    else
    {
        var messageEmpty = "";
        if (this.isBot(dialogId) && this.BXIM.messenger.users[dialogId])
        {
            messageEmpty = BX.message("IM_M_NO_MESSAGE_BOT").replace('#BOT_NAME#', this.BXIM.messenger.users[dialogId].name);
        }
        else
        {
            messageEmpty = BX.message("IM_M_NO_MESSAGE");
        }
        BX.removeClass(this.BXIM.messenger.popupMessengerBodyWrap, 'bx-messenger-loading');
        arMessage = [BX.create("div", { props : { className : "bx-messenger-content-empty"}, children : [
            BX.create("span", { props : { className : "bx-messenger-content-load-text"}, html: messageEmpty})
        ]})];
    }
    if (arMessage.length > 0)
    {
        this.BXIM.messenger.popupMessengerBodyWrap.innerHTML = '';
        BX.adjust(this.BXIM.messenger.popupMessengerBodyWrap, {children: arMessage});
    }

    if (extraClose)
        this.BXIM.messenger.extraClose();

    if (this.isMobile())
    {
        BXMobileApp.UI.Page.TextPanel.setText(this.BXIM.messenger.textareaHistory[dialogId]? this.BXIM.messenger.textareaHistory[dialogId]: "");
    }
    else
    {
        this.BXIM.messenger.popupMessengerTextarea.value = this.BXIM.messenger.textareaHistory[dialogId]? this.BXIM.messenger.textareaHistory[dialogId]: "";
    }

    if (this.BXIM.messenger.redrawTab[dialogId])
    {
        this.loadLastMessage(dialogId);
    }
    else
    {
        this.drawTab(dialogId, true);
        if (this.isMobile())
        {
            this.BXIM.isFocusMobile(BX.delegate(function(visible){
                if (visible)
                {
                    BX.MessengerCommon.readMessage(dialogId);
                }
            },this));
        }
        else if (this.BXIM.isFocus())
        {
            this.readMessage(dialogId);
        }
    }

    if (!this.isMobile())
        this.BXIM.messenger.resizeMainWindow();

    if (BX.MessengerCommon.countWriting(dialogId))
    {
        if (this.BXIM.messenger.openChatFlag)
            BX.MessengerCommon.drawWriting(0, dialogId);
        else
            BX.MessengerCommon.drawWriting(dialogId);
    }
    else if (this.BXIM.messenger.readedList[dialogId])
    {
        if (this.BXIM.messenger.openChatFlag)
        {
            this.drawReadMessageChat(dialogId, false);
        }
        else
        {
            this.drawReadMessage(dialogId, this.BXIM.messenger.readedList[dialogId].messageId, this.BXIM.messenger.readedList[dialogId].date, false);
        }
    }

    BX.onCustomEvent("onImDialogOpen", [{id: dialogId}]);
    if (this.isMobile())
    {
        BXMobileApp.onCustomEvent('onImDialogOpen', {'id': dialogId}, true);
    }
    else
    {
        this.BXIM.messenger.linesShowPromo();
        this.support24QuestionShowPromo();
    }
};