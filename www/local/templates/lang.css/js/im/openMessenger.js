BX.MessengerChat.prototype.openMessenger = function(userId, params)
{
    var promise = new BX.Promise();

    if (this.BXIM.errorMessage != '') {
        this.BXIM.closeMessenger();

        var errorButtons = [];

        if (this.BXIM.errorButtons) {
            errorButtons = this.BXIM.errorButtons;
        } else {
            errorButtons = [new BX.PopupWindowButton({
                text : BX.message('IM_NOTIFY_CONFIRM_CLOSE'),
                events : { click : BX.delegate(function(e) { BX.proxy_context.popupWindow.close(); if (BX.MessengerWindow){ BX.MessengerWindow.closePopup() } BX.PreventDefault(e) }, this) }
            })]
        }

        this.BXIM.openConfirm(this.BXIM.errorMessage, errorButtons);

        promise.reject();

        return promise;
    }

    if (this.BXIM.messenger.currentTab != userId) {
        BX.MessengerProxy.updateTextareaHistory(this.BXIM.messenger.currentTab);
    }

    if (this.BXIM.popupSettings != null && !BX.MessengerCommon.isDesktop())
        this.BXIM.popupSettings.close();

    if (this.popupMessenger != null && this.dialogOpen && this.currentTab == userId && userId != 0) {
        promise.reject();
        return promise;
    }

    if (userId !== false && BX.MessengerCommon.isPage() && BX.MessengerWindow.currentTab != 'im' && BX.MessengerWindow.currentTab != 'im-ol') {
        BX.MessengerWindow.changeTab('im', false, true);
    }

    if (this.popupMessengerEditForm) {
        this.editMessageCancel();
    }

    if (userId && userId.toString().toLowerCase() == 'general') {
        this.currentTab = 'chat'+this.generalChatId;
        userId = this.currentTab;
    }

    BX.localStorage.set('mcam', true, 5);

    if (typeof(userId) == "undefined" || userId == null) {
        userId = 0;
    }

    if (this.currentTab == null) {
        this.currentTab = 0;
    }

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

    if (this.popupMessenger != null)
    {
        BX.MessengerCommon.openDialog(
            userId,
            this.BXIM.dialogOpen? false: true
        );
        if (!(BX.browser.IsAndroid() || BX.browser.IsIOS() || window != window.top))
        {
            if (this.popupMessengerTextarea)
                this.popupMessengerTextarea.focus();
        }
        this.popupMessenger.show()
        promise.resolve();
        return promise;
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

    var contactListExtendCollapsedElements = 0;
    if (!this.BXIM.options.showSearch)
    {
        contactListExtendCollapsedElements += 48;
    }
    if (!this.BXIM.options.showStatus)
    {
        contactListExtendCollapsedElements += 39;
    }

    this.popupMessengerContent = BX.create("div",{
        props : {
            className : "w-100 h-100 d-f pn-r lg:btlr-12 of-h fw-w bx-messenger-mark bx-messenger-global-context-"+this.BXIM.context.toLowerCase()+" "+(BX.MessengerCommon.isPage()? ' bx-messenger-box-desktop': '')+(BX.browser.IsMac()? '': ' bx-messenger-custom-scroll')
        },
        // style: styleOfContent,
        children : [
            /* CL */
            this.popupContactListWrap = BX.create("div", {
                props : { className : "w-100 xl:w-4/12 h-100 fg-0 fs-0 d-f fd-c bc-w brw-1 brs-s bcr-c5c5c566 tn-all-3" },
                // style : {width: this.popupContactListSize+'px'}, 
                children : [
                    BX.create('div', {
                        props: {className: "w-100 h-100 fg-1 d-f fd-c pn-r"},
                        children: [

                            BX.create("div", {
                                props : { className : "w-100 d-f bbw-1 bbs-s bcr-f5f6f6" +(this.BXIM.options.showSearch ? '': ' d-n') },
                                    children : [
                                        BX.create("div", {
                                            props : { className : "w-100 d-f pn-r pby-16" },
                                            children : [
                                                this.popupContactListSearchIcon = BX.create("div", {
                                                    props : { className : "d-f ai-c jc-c c-dt h:c-py tn-all-3 cr-p pix-16" },
                                                    html: `<svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M11.4351 10.0629H10.7124L10.4563 9.81589C11.3838 8.74014 11.8936 7.36669 11.8925 5.94626C11.8925 4.7702 11.5438 3.62055 10.8904 2.6427C10.237 1.66484 9.30832 0.902692 8.22179 0.452634C7.13525 0.00257642 5.93966 -0.115179 4.7862 0.114258C3.63274 0.343696 2.57322 0.910021 1.74162 1.74162C0.910021 2.57322 0.343696 3.63274 0.114258 4.7862C-0.115179 5.93966 0.00257642 7.13525 0.452634 8.22179C0.902692 9.30832 1.66484 10.237 2.6427 10.8904C3.62055 11.5438 4.7702 11.8925 5.94626 11.8925C7.4191 11.8925 8.77301 11.3528 9.81589 10.4563L10.0629 10.7124V11.4351L14.6369 16L16 14.6369L11.4351 10.0629ZM5.94626 10.0629C3.66838 10.0629 1.82962 8.22413 1.82962 5.94626C1.82962 3.66838 3.66838 1.82962 5.94626 1.82962C8.22413 1.82962 10.0629 3.66838 10.0629 5.94626C10.0629 8.22413 8.22413 10.0629 5.94626 10.0629Z"/></svg>`
                                                }),
                                                this.popupContactListSearchInput = BX.create("input", {
                                                    attrs: {type: "text", placeholder: BX.message('IM_M_SEARCH'), value: this.contactListSearchText},
                                                    props : { className : "fg-1 bw-0 fv:ow-0 fv:os-n" }
                                                }),
                                                this.popupContactListSearchClose = BX.create("div", {
                                                    props : { className : "lang-search--close d-f d-n ai-c jc-c cr-p h:c-py tn-all-3 pix-16" },
                                                    html: `<svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M16 0.8L15.2 0L7.99991 7.2L0.800213 0L0.000587582 0.800193L7.1999 8L0 15.1998L0.800009 15.9998L7.99991 8.79999L15.2 16L16 15.2L8.79991 8L16 0.8Z"/></svg>`
                                                }),
                                            ]
                                        }),
                                ]
                            }),
                            this.popupContactListElements = BX.create("div", {
                                props : { className : "d-f fd-c fg-1 bx-messenger-cl" },
                                children : [
                                    this.popupContactListElementsWrap = BX.create("div", { props : { className : "w-100 bx-messenger-cl-wrap bx-messenger-recent-wrap" }}),
                                    this.popupNewRecentWrap = BX.create("div", { props : { className : "w-100 bx-messenger-cl-wrap bx-messenger-new-recent-wrap" }})
                                ]
                            }),
                        ]
                    }),
                ]
            }),
            /* DIALOG */
            this.popupMessengerDialog = BX.create("div",{
                props : { className : "h-100 w-100 xl:w-8/12 pn-a t-0 rt--200% tp-rt tnd-500ms b-0 md:pn-r md:rt-0 d-f fd-c fg-0 fs-0 bc-w " + (this.BXIM.isAdmin ? 'bx-messenger-user-admin' : '') },
                // style : {marginLeft: this.popupContactListSize+'px'},
                children : [

                    BX.create('div', {
                        props: {
                            className: 'w-100 h-100 d-f fd-c fg-1',
                        },
                        children: [
                            this.popupMessengerPanel = BX.create("div", {
                                props : { className : "d-f ai-c cg-16 bx-messenger-panel bx-messenger-context-user "+(this.openChatFlag? ' bx-messenger-hide': '') },
                                children : [
                                    BX.create('div', {
                                        // attrs : { href : this.users[this.currentTab]? this.users[this.currentTab].profile: BX.MessengerCommon.getUserParam().profile, target: '_blank'},
                                        props : { className : "bx-messenger-panel-avatar bx-messenger-panel-avatar-status-"+BX.MessengerCommon.getUserStatus(this.users[this.currentTab]) },
                                        children: [
                                            this.popupMessengerPanelAvatar = BX.create('span', {
                                                props : { className : "bx-messenger-panel-avatar-img bx-messenger-panel-avatar-img-default" }
                                            }),
                                            BX.create('span', {  props : { className : "bx-messenger-panel-avatar-status" }})
                                        ],
                                        events : {
                                            mouseover: BX.delegate(function(e) {
                                                if (this.users[this.currentTab])
                                                {
                                                    BX.proxy_context.title = BX.MessengerCommon.getUserStatus(this.users[this.currentTab], false).statusText;
                                                }
                                            }, this)
                                        }
                                    }),
                                    BX.create('div', {
                                        props: {className: 'fg-1 d-f fd-c rg-4'},
                                        children: [
                                            BX.create("div", {
                                                props : { className : "bx-messenger-panel-title"},
                                                children: [
                                                    this.popupMessengerPanelTitle = BX.create('a', {
                                                        props : { className : "bx-messenger-panel-title-link"+userTitleStyle},
                                                        attrs : { href : 'javascript:void(0)'}, //this.users[this.currentTab] ? this.users[this.currentTab].profile : BX.MessengerCommon.getUserParam().profile, target: '_blank'},
                                                        text: this.users[this.currentTab]? this.users[this.currentTab].name: '',
                                                        events: {
                                                            click: BX.delegate(function(e) {
                                                                e.preventDefault()
                                                            })
                                                        }
                                                    }),
                                                    this.popupMessengerPanelLastDate = BX.create("span", { props : { className : "bx-messenger-panel-title-position"}, html: ''})
                                                ]
                                            }),
                                            this.popupMessengerPanelStatus = BX.create("div", {
                                                props : { className : "bx-messenger-panel-desc"},
                                                // html: BX.MessengerCommon.getUserPosition(this.users[this.currentTab], false, true)
                                            })
                                        ]
                                    }),
                                    
                                    this.popupMessengerPanelMute = BX.create("a", {
                                        attrs: {href: "javascript:void(0);", title: this.muteButtonStatus(this.currentTab)? BX.message("IM_M_USER_BLOCK_ON"): BX.message("IM_M_USER_BLOCK_OFF")},
                                        props : { className : "bx-messenger-panel-button bx-messenger-panel-mute"},
                                        events : { click: BX.delegate(function(e){BX.MessengerCommon.muteMessageChat(this.currentTab);BX.PreventDefault(e);}, this)}
                                    }),
                                    this.enableGroupChat ? BX.create("a", {
                                        attrs: {href: "javascript:void(0);", title: BX.message("IM_M_CHAT_TITLE")},
                                        props : { className : "bx-messenger-panel-button bx-messenger-panel-chat"},
                                        html: BX.message("IM_M_CHAT_BTN_JOIN"),
                                        events : { click: BX.delegate(function(e){ this.openChatDialog({'type': 'CHAT_ADD', 'bind': BX.proxy_context}); BX.PreventDefault(e)}, this)}
                                    }) : null,

                                    BX.create("div", {
                                        props : { className : "lang-dialog--close d-f ai-c jc-c cr-p h:c-py tn-all-3" },
                                        html: `<svg width="14" height="24" viewBox="0 0 14 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M0.286515 11.3657L11.8909 0.342559C12.6173 -0.344887 14 0.0716956 14 0.976862V23.0231C14 23.9283 12.6173 24.3449 11.8909 23.6574L0.286515 12.6343C0.196272 12.554 0.124167 12.456 0.074827 12.3468C0.0254866 12.2376 0 12.1194 0 12C0 11.8806 0.0254866 11.7624 0.074827 11.6532C0.124167 11.544 0.196272 11.446 0.286515 11.3657Z"/></svg>`,
                                        events: {
                                            click: BX.delegate(function(e) {
                                                this.popupMessengerDialog.classList.remove('rt-0')
                                            }, this)
                                        }
                                    }),
                                    
                                ]
                            }),

                            this.popupMessengerPanelChat = BX.create("div", {
                                props : { className : "bx-messenger-panel bx-messenger-context-chat "+(this.openChatFlag && !this.openCallFlag? '': ' bx-messenger-hide') },
                                children : [
                                    this.popupMessengerPanelAvatarForm2 = BX.create('div', {
                                        // attrs : { action : this.BXIM.pathToFileAjax},
                                        props : { className : "bx-messenger-panel-avatar bx-messenger-panel-avatar-chat" },
                                        children: [
                                            BX.create('div', { props : { className : "bx-messenger-panel-avatar-progress"}, html: '<div class="bx-messenger-panel-avatar-progress-image"></div>'}),
                                            // BX.create('input', { attrs : { type : 'hidden', name: 'IM_AVATAR_UPDATE', value: 'Y'}}),
                                            this.popupMessengerPanelAvatarId2 = BX.create('input', { attrs : { type : 'hidden', name: 'CHAT_ID', value: this.getChatId()}}),
                                            // BX.create('input', { attrs : { type : 'hidden', name: 'IM_AJAX_CALL', value: 'Y'}}),
                                            // this.popupMessengerPanelAvatarUpload2 = this.disk.lightVersion || !this.BXIM.ppServerStatus? null: BX.create('input', { attrs : { type : 'file', title: BX.message('IM_M_AVATAR_UPLOAD')}, props : { className : "bx-messenger-panel-avatar-upload"}}),
                                            this.popupMessengerPanelAvatar2 = BX.create('span', { props : { className : "bx-messenger-panel-avatar-img bx-messenger-panel-avatar-img-default" }}),
                                            // this.popupMessengerPanelCrm = BX.create('span', {  props : { className : "bx-messenger-panel-avatar-badge-crm" }}),
                                            this.popupMessengerPanelStatus2 = BX.create('span', {  props : { className : "bx-messenger-panel-avatar-status" }})
                                        ]
                                    }),
                                    BX.create('div', {
                                        props: {className: 'fg-1 d-f fd-c rg-4'},
                                        children: [
                                            BX.create("div", { props : { className : "bx-messenger-panel-title bx-messenger-panel-title-chat"}, children: [
                                                this.popupMessengerPanelChatTitle = BX.create('span', { props : { className : ""}, html: this.chat[this.getChatId()]? this.chat[this.getChatId()].name: BX.message('IM_CL_LOAD')})
                                            ]}),
                                            BX.create("div", { props : { className : "bx-messenger-panel-desc"}, children : [
                                                this.popupMessengerPanelUsers = BX.create('div', { props : { className : "bx-messenger-panel-chat-users"}, html: BX.message('IM_CL_LOAD')})
                                            ]}),
                                        ],
                                    }),

                                    BX.create("span", {
                                        attrs: {title: BX.message('IM_P_MENU')},
                                        props : { className : "bx-messenger-panel-button bx-messenger-panel-menu"},
                                        events : { click: BX.delegate(function(e){ this.openPopupMenu(BX.proxy_context, this.chat[this.getChatId()].entity_type == "LINES"? 'openLinesMenu': 'pathMenu'); BX.PreventDefault(e); }, this)}
                                    }),

                                    this.popupMessengerPanelMute2 = BX.create("a", {
                                        attrs: {href: "javascript:void(0);", title: this.muteButtonStatus(this.currentTab)? BX.message("IM_M_CHAT_MUTE_ON_2"): BX.message("IM_M_CHAT_MUTE_OFF_2")},
                                        props : { className : "c-dt h:c-py tn-all-3 bx-messenger-panel-button bx-messenger-panel-mute " + (this.muteButtonStatus(this.currentTab) ? ' bx-messenger-panel-unmute' : '')},
                                        events : { click: BX.delegate(function(e){BX.MessengerCommon.muteMessageChat(this.currentTab);BX.PreventDefault(e);}, this)},
                                        html: `
                                            <svg class="lang--mute" width="12" height="16" viewBox="0 0 12 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M11.1427 16C10.9692 15.999 10.7997 15.9467 10.6541 15.8491L5.14276 11.9429H0.857127C0.629803 11.9429 0.411789 11.8494 0.251047 11.6829C0.0903044 11.5164 0 11.2906 0 11.0551V4.96508C0 4.72963 0.0903044 4.50382 0.251047 4.33734C0.411789 4.17085 0.629803 4.07732 0.857127 4.07732H5.14276L10.6369 0.17115C10.7661 0.0733594 10.919 0.0149421 11.0785 0.0025053C11.238 -0.00993152 11.3977 0.0241083 11.5395 0.100775C11.6813 0.177441 11.7996 0.293675 11.8809 0.436328C11.9622 0.578982 12.0034 0.742364 11.9998 0.907995V15.1122C11.9998 15.3477 11.9095 15.5735 11.7487 15.74C11.588 15.9065 11.37 16 11.1427 16Z"/></svg>
                                            <svg class="lang--unmute" width="18" height="16" viewBox="0 0 18 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M10.0669 0.184457C10.2068 0.0838866 10.3706 0.0219565 10.5418 0.00485053C10.713 -0.0122554 10.8856 0.0160615 11.0425 0.0869737C11.1994 0.157886 11.3351 0.268912 11.4359 0.408967C11.5367 0.549021 11.5992 0.713203 11.6172 0.88512L11.6225 0.987524V15.0116C11.6226 15.1844 11.5775 15.3543 11.4918 15.5042C11.4061 15.654 11.2827 15.7787 11.1341 15.8657C10.9854 15.9527 10.8166 15.999 10.6446 16C10.4725 16.0009 10.3033 15.9565 10.1536 15.8712L10.0678 15.8155L4.18412 11.5927H1.78808C1.33697 11.5928 0.902476 11.4216 0.571697 11.1135C0.240918 10.8053 0.0383037 10.3828 0.00447031 9.93085L0 9.79611V6.20297C8.27666e-05 5.74987 0.170558 5.31354 0.477273 4.98138C0.783988 4.64922 1.20429 4.44577 1.65398 4.41179L1.78808 4.4064H4.18412L10.0669 0.184457ZM13.9345 5.45919L15.1987 6.72937L16.4629 5.45919C16.5454 5.37339 16.644 5.30496 16.7531 5.25788C16.8622 5.21081 16.9795 5.18602 17.0982 5.18499C17.2169 5.18395 17.3346 5.20668 17.4445 5.25185C17.5544 5.29701 17.6542 5.36371 17.7381 5.44806C17.8221 5.5324 17.8885 5.63269 17.9334 5.74309C17.9784 5.85349 18.001 5.97177 18 6.09105C17.9989 6.21032 17.9743 6.32819 17.9274 6.43779C17.8806 6.54738 17.8124 6.6465 17.7271 6.72937L16.4629 7.99954L17.7271 9.26972C17.8948 9.43815 17.9891 9.66665 17.9892 9.90494C17.9893 10.1432 17.8951 10.3718 17.7275 10.5403C17.5599 10.7089 17.3325 10.8036 17.0953 10.8037C16.8581 10.8038 16.6306 10.7092 16.4629 10.5408L15.1987 9.26972L13.9345 10.5408C13.7668 10.7092 13.5393 10.8038 13.3021 10.8037C13.065 10.8036 12.8376 10.7089 12.6699 10.5403C12.5023 10.3718 12.4081 10.1432 12.4082 9.90494C12.4083 9.66665 12.5026 9.43815 12.6704 9.26972L13.9345 7.99954L12.6704 6.72937C12.585 6.6465 12.5169 6.54738 12.47 6.43779C12.4231 6.32819 12.3985 6.21032 12.3975 6.09105C12.3964 5.97177 12.419 5.85349 12.464 5.74309C12.5089 5.63269 12.5753 5.5324 12.6593 5.44806C12.7432 5.36371 12.843 5.29701 12.9529 5.25185C13.0628 5.20668 13.1805 5.18395 13.2992 5.18499C13.4179 5.18602 13.5353 5.21081 13.6443 5.25788C13.7534 5.30496 13.8521 5.37339 13.9345 5.45919Z"/></svg>
                                        `,
                                    }),

                                    BX.create("div", {
                                        props : { className : "lang-dialog--close d-f ai-c jc-c cr-p h:c-py tn-all-3" },
                                        html: `<svg width="14" height="24" viewBox="0 0 14 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M0.286515 11.3657L11.8909 0.342559C12.6173 -0.344887 14 0.0716956 14 0.976862V23.0231C14 23.9283 12.6173 24.3449 11.8909 23.6574L0.286515 12.6343C0.196272 12.554 0.124167 12.456 0.074827 12.3468C0.0254866 12.2376 0 12.1194 0 12C0 11.8806 0.0254866 11.7624 0.074827 11.6532C0.124167 11.544 0.196272 11.446 0.286515 11.3657Z"/></svg>`,
                                        events: {
                                            click: BX.delegate(function(e) {
                                                this.popupMessengerDialog.classList.remove('rt-0')
                                            }, this)
                                        }
                                    }),
                                ]
                            }),

                            this.popupMessengerPanelCall = BX.create("div", {
                                props : { className : "bx-messenger-panel bx-messenger-context-call "+(this.openChatFlag && this.openCallFlag? '': ' bx-messenger-hide') },
                                children : [
                                    this.popupMessengerPanelAvatarForm3 = BX.create('div', {
                                        attrs : { action : this.BXIM.pathToFileAjax},
                                        props : { className : "bx-messenger-panel-avatar bx-messenger-panel-avatar-call" },
                                        children: [
                                            BX.create('div', { props : { className : "bx-messenger-panel-avatar-progress"}, html: '<div class="bx-messenger-panel-avatar-progress-image"></div>'}),
                                            this.popupMessengerPanelAvatar3 = BX.create('span', {  props : { className : "bx-messenger-panel-avatar-img bx-messenger-panel-avatar-img-default" }}),
                                            this.popupMessengerPanelStatus3 = BX.create('span', {  props : { className : "bx-messenger-panel-avatar-status bx-messenger-panel-avatar-status-chat" }})
                                        ]
                                    }),
                                    BX.create("span", {
                                        attrs: {title: BX.message('IM_P_MENU')},
                                        props : { className : "bx-messenger-panel-button bx-messenger-panel-menu"},
                                        events : { click: BX.delegate(function(e){ this.openPopupMenu(BX.proxy_context, 'callContextMenu'); BX.PreventDefault(e); }, this)}
                                    }),
                                    this.popupMessengerPanelMute3 = BX.create("a", {
                                        attrs: {href: "javascript:void(0);", title: this.muteButtonStatus(this.currentTab)? BX.message("IM_M_CHAT_MUTE_ON_2"): BX.message("IM_M_CHAT_MUTE_OFF_2")},
                                        props : { className : "bx-messenger-panel-button bx-messenger-panel-mute "+(this.muteButtonStatus(this.currentTab)? ' bx-messenger-panel-unmute': '')},
                                        events : { click: BX.delegate(function(e){ BX.MessengerCommon.muteMessageChat(this.currentTab); BX.PreventDefault(e)}, this)}
                                    }),
                                ]
                            }),
                            
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

                            this.popupMessengerBodyDialog = BX.create("div", { props : { className : "d-f fd-c fg-1 bx-messenger-body-dialog bxu-file-input-over" }, children: [
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
                                this.popupMessengerBody = BX.create("div", { props : { className : "fg-1 bx-messenger-body" }, style : {height: this.popupMessengerBodySize+'px'}, children: [
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
                                ]})
                            ]})
                        ]
                    }),
                ]
            }),
            /* EXTRA PANEL */
            this.popupMessengerExtra = BX.create("div", {
                props : { className : "w-100 xl:w-8/12 pn-a t-0 rt--200% tp-rt tnd-500ms b-0 md:pn-r md:rt-0 d-f fd-c fg-0 fs-0 bc-w"},
            })
        ]
    });
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
        let mesD = document.querySelector('.lang-messanger--wrap')
        this.popupMessenger = document.querySelector('.lang-messanger--wrap')
        let dialogM = document.querySelector('.lang-messanger--content');
        this.popupMessenger.content = dialogM
        dialogM.append(this.popupMessengerContent)

        if(mesD.classList.contains('d-n')) {
            mesD.classList.remove('d-n')
            setTimeout(() => {
                dialogM.classList.remove('t-200%')
            }, 500)
            document.body.classList.add('of-h')
        } else {
            dialogM.classList.add('t-200%')

            setTimeout(() => {
                mesD.classList.add('d-n')
                document.body.classList.remove('of-h')
            }, 500)
        }

        this.popupMessenger.close = function() {
            let mesD = document.querySelector('.lang-messanger--wrap')
            let dialogM = document.querySelector('.lang-messanger--content');
            
            dialogM.classList.add('t-200%')

            setTimeout(() => {
                mesD.classList.add('d-n')
                document.body.classList.remove('of-h')
                // dialogM.innerHTML = "";
            }, 500)
        }

        this.popupMessenger.show = function() {
            let mesD = document.querySelector('.lang-messanger--wrap')
            let dialogM = document.querySelector('.lang-messanger--content');

            mesD.classList.remove('d-n')
            setTimeout(() => {
                dialogM.classList.remove('t-200%')
            }, 500)
            document.body.classList.add('of-h')
        }

        this.popupMessenger.destroy = function() {
            // this.popupMessenger.content.innerHTML = "";
        }
        BX.bind(document, "click", BX.proxy(this.BXIM.autoHide, this.BXIM));
    }

    this.BXIM.setBackground();

    this.popupMessengerTopLine = BX.create("div", { props : { className : "bx-messenger-box-topline"}});
    this.popupMessengerContent.insertBefore(this.popupMessengerTopLine, this.popupMessengerContent.firstChild);

    this.textareaIconPrepare();

    BX.MessengerCommon.userListRedraw();

    if (this.BXIM.quirksMode) {
        this.popupContactListWrap.style.position = "absolute";
        this.popupContactListWrap.style.display = "block";
    }

    this.setUpdateStateStep();

    if (!(BX.browser.IsAndroid() || BX.browser.IsIOS() || window != window.top) && this.popupMessenger != null) {
        setTimeout(BX.delegate(function() {
            this.popupMessengerTextarea.focus();
        }, this), 50);
    }

    // BX.Runtime.loadExtension('langmess.component.recent').then((exports) => {
        // console.info(exports);

        // let lis = new exports.Application.RecentApplication({
        //     node: this.BXIM.messenger.popupNewRecentWrap,
        //     hasDialog: true,
        // })

        // this.BXIM.initLeftPanelApp().then(function() {
        //     console.info(this.BXIM.leftpanelApp)
		// 	this.BXIM.leftpanelApp.initComponent(this.BXIM.messenger.popupNewRecentWrap);
		// }.bind(this));

        BX.Messenger.Application.Launch('leftPanel', {
            node: this.BXIM.messenger.popupNewRecentWrap,
            hasDialog: true,
            // mode: 'legacy',
            // initCounter: this.notifyCount
        }).then(function(application) {
            // this.notificationApp = application;
        }.bind(this));

        // BX.Messenger.Application.Launch('recent', {
        //     node: this.BXIM.messenger.popupNewRecentWrap,
        //     hasDialog: true,
        //     // mode: 'legacy',
        //     // initCounter: this.notifyCount
        // }).then(function(application) {
        //     // this.notificationApp = application;
        // }.bind(this));

        // console.info(lis)

        // var chat = new exports.MessengerApplication({node: "#mess", userId: 1, dialogId: "chat41"});

        // chat.setApplicationDialogInfo()
        // console.info(chat.getDialogId())

        // var chat = new exports.ChatService()

        // console.info(chat.loadChat("chat41"))
    // });

    // im.old-chat-embedding.component.left-panel
    // BX.Runtime.loadExtension('iim.old-chat-embedding.component.left-panel').then((exports) => {
    //     // console.info(BX.Messenger.Embedding.Application.LeftPanelApplication);

    //     let lis = new BX.Messenger.Embedding.Application.LeftPanelApplication({
    //         node: this.BXIM.messenger.popupNewRecentWrap,
    //         hasDialog: true,
    //     })

    //     lis.initComponent(this.BXIM.messenger.popupNewRecentWrap)

    //     console.info(lis)
    // });

    // BX.Messenger.Embedding.Application.Launch('leftPanel', {
    //     node: this.BXIM.messenger.popupNewRecentWrap,
    //     layout: BX.Messenger.Embedding.Const.ApplicationLayout.lines,
    //     preloadedList: this.BXIM.newRecent,
    //     chatOptions: this.BXIM.userChatOptions,
    // }).then(function(application) {
	// 	// this.leftpanelApp = application;
	// 	// this.leftPanelAppLaunched = true;
    //     console.info(application)
    //     application.initComponent(this.BXIM.messenger.popupNewRecentWrap)
	// }.bind(this))

    /* CL */

    // if (this.webrtc.phoneEnabled && this.BXIM.design != 'DESKTOP')
    // {
    // 	BX.bind(this.popupContactListSearchCall, "click", () => {
    // 		this.webrtc.openKeyPad({
    // 			bindElement: this.popupContactListSearchCall,
    // 			offsetTop: -10,
    // 			offsetLeft: -52,
    // 			anglePosition: this.externalMenu && !this.webrtc.callActive ? "left" : "top",
    // 			angleOffset: this.externalMenu ? (this.webrtc.callActive ? 120 : 76) : 94,
    // 		})
    // 	});
    // }
    // BX.bind(this.popupContactListSearchIcon, "click", BX.delegate(function(e) {
    //     this.BXIM.openContactList();
    // }, this));
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

    BX.bind(this.popupContactListSearchClose.parentNode, "click",  BX.delegate(function() {
        this.popupContactListSearchInput.focus();
    }, this));

    BX.bind(this.popupMessengerDialog, "click",  BX.delegate(function(e) {
        if (this.recentList && !this.chatList && !this.contactList) {
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
            darkMode: false,
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

        BX.addClass(this.popupChatUsers.popupContainer, "bx-messenger-mark");
        this.popupChatUsers.show();

        // BX.bindDelegate(this.popupChatUsers.popupContainer, "click", {className: 'bx-messenger-panel-chat-user'}, BX.delegate(function(e){this.openPopupMenu(BX.proxy_context, 'chatUser'); return BX.PreventDefault(e);}, this));

        return BX.PreventDefault(e);
    }, this));

    BX.bindDelegate(this.popupContactListElements, "click", {className: 'bx-messenger-cl-item'}, BX.delegate(BX.MessengerCommon.contactListClickItem, BX.MessengerCommon));
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

    // BX.bind(this.contactListPanelStatus, "click", BX.delegate(function(e){this.openPopupMenu(this.contactListPanelStatus, 'status');  return BX.PreventDefault(e);}, this));
    // if (this.contactListPanelSettings)
    // {
    // 	BX.bind(this.contactListPanelSettings, "click", BX.delegate(function(e){this.BXIM.openSettings(); BX.PreventDefault(e)}, this));
    // }
    // if (this.contactListPanelFull)
    // {
    // 	BX.bind(this.contactListPanelFull, "click", BX.delegate(function(e){
    // 		this.popupMessenger.enterFullScreen(); BX.PreventDefault(e)
    // 	}, this));
    // }

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
            darkMode: false,
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

        BX.addClass(this.popupChatUsers.popupContainer, "bx-messenger-mark");
        this.popupChatUsers.show();

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

    BX.bind(this.popupMessengerBody, "scroll", BX.delegate(function(e) {
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

    if (userId == 0) {
        this.extraOpen(
            BX.create("div", { props : { className : "bx-messenger-box-hello-wrap" }, children: [
                BX.create("div", { props : { className : "bx-messenger-box-hello" }, html: BX.message('IM_M_EMPTY')})
            ]})
        );
    } else if (!BX.MessengerCommon.isPopupPage()) {
        BX.MessengerCommon.openDialog(userId);
    }

    promise.resolve();

    return promise;
}