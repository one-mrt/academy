LG.userPanel = class
{
    container
    btn
    menu
    btnClose
    im
    dialog
    dialogCount
    notify
    notifyCount
    wrapMessendger
    containerMessendger
    contentMessendger
    closeMessenger
    eventNotify = ['notifyAdd', 'notifyDelete', 'notifyRead', 'notifyUnread', 'notifyReadList', 'notifyReadAll', 'counterGet']
    eventMessage = ['messageUpdate']

    constructor() {
        this.initUser()
        this.initClose()
        this.initShow()
        this.initNotify()
    }

    close() {
        this.menu.classList.add('d-n')
        this.btnClose.classList.add('d-n')
        document.body.classList.remove('of-h')
    }

    show() {
        this.menu.classList.remove('d-n')
        this.btnClose.classList.remove('d-n')
        document.body.classList.add('of-h')
    }

    initNotify() {
        this.wrapMessendger = document.querySelector('.lang-messanger--wrap')
        this.containerMessendger = this.wrapMessendger.querySelector('.lang-messanger--container')
        this.contentMessendger = this.containerMessendger.querySelector('.lang-messanger--content')
        this.closeMessenger = this.contentMessendger?.querySelector('.messanger--close');


        this.closeMessenger.addEventListener('click', this.closeChat.bind(this))

        this.dialog = this.container.querySelector('.lang-messanger--dialog')
        this.dialogCount = this.dialog.querySelector('.dialog--count')
        this.notify = this.container.querySelector('.lang-messanger--notify')
        this.notifyCount = this.notify.querySelector('.notify--count')

        this.dialog.addEventListener('click', this.openDialog.bind(this))
        this.notify.addEventListener('click', this.openNotify.bind(this))

        this.initNotifyEvent()
    }

    openChat() {
        this.wrapMessendger.classList.remove('d-n')
        setTimeout(() => {
            this.contentMessendger.classList.remove('t-200%')
        }, 500)
        document.body.classList.add('of-h')
    }

    closeChat() {
        this.contentMessendger.classList.add('t-200%')

        setTimeout(() => {
            this.wrapMessendger.classList.add('d-n')
            document.body.classList.remove('of-h')
            this.dialog.classList.remove('z-3')
            this.notify.classList.remove('z-3')
        }, 500)
    }

    openDialog() {
        BXIM.messenger.popupMessengerDialog?.classList.remove('rt-0')
        BXIM.messenger.popupMessengerExtra?.classList.remove('rt-0')

        if(!this.isOpenMessenger()) {
            BXIM.openMessenger()
            this.dialog.classList.add('z-3')
        } else if(this.isOpenMessenger() && BXIM.isOpenNotify()) {

        } else {
            this.dialog.classList.remove('z-3')
            BXIM.closeMessenger()
        }

        BXIM.closeNotify()
    }

    isOpenMessenger() {
        return !this.wrapMessendger.classList.contains('d-n')
    }

    openNotify() {
        if(!this.isOpenMessenger() && !BXIM.isOpenNotify()) {
            BXIM.openMessenger()
            BXIM.openNotify()
            BXIM.messenger.popupMessengerDialog.classList.remove('rt-0')
            BXIM.messenger.popupMessengerExtra.classList.add('rt-0')
            this.notify.classList.add('z-3')
        } else if(BXIM.isOpenNotify() && !this.isOpenMessenger()) {
            BXIM.openMessenger()
            BXIM.messenger.popupMessengerDialog.classList.remove('rt-0')
            BXIM.messenger.popupMessengerExtra.classList.add('rt-0')
            this.notify.classList.add('z-3')
        } else if(!BXIM.isOpenNotify() && this.isOpenMessenger()) {
            BXIM.openNotify()
            BXIM.messenger.popupMessengerDialog.classList.remove('rt-0')
            BXIM.messenger.popupMessengerExtra.classList.add('rt-0')
            this.notify.classList.add('z-3')
        } else {
            BXIM.closeNotify()
            BXIM.closeMessenger()
            BXIM.messenger.popupMessengerDialog.classList.remove('rt-0')
            BXIM.messenger.popupMessengerExtra.classList.remove('rt-0')
            this.notify.classList.remove('z-3')
        }
    }

    initNotifyEvent() {
        BX.addCustomEvent(window, "onImInitBefore", BX.proxy(this.initIm, this))
    }

    initIm(im) {
        this.im = BX.IM
        BX.addCustomEvent(window, "onImUpdateCounterMessage", BX.proxy(this.updateCounter, this));

        for(let k = 0; k < this.eventNotify.length; k++) {
            BX.PULL.subscribe({
                moduleId: 'im',
                command: this.eventNotify[k],
                callback: (data) => {
                    this.updateCounter(data.counter, 'NOTIFY')
                }
            });
        }
    }

    notifyCounter(command, params, extra) {
        switch(command) {
            case 'notifyAdd':
            case 'notifyDelete':
            case 'notifyRead':
            case 'notifyUnread':
            case 'notifyReadList':
            case 'notifyReadAll':
                this.updateCounter(params, 'NOTIFY')
                break;
        }
    }

    updateIndicator(count, type) {
        let messageCountLabel = '0';

        if (count > 99) {
            messageCountLabel = '99+';
        } else if (count >= 0) {
            messageCountLabel = count;
        }

        if(count >= 0) {
            switch(type) {
                case "MESSAGE":
                    if(count > 0) {
                        this.dialogCount.classList.remove('bc-000')
                        this.dialogCount.classList.add('bc-c90536')
                    } else {
                        this.dialogCount.classList.add('bc-000')
                        this.dialogCount.classList.remove('bc-c90536')
                    }

                    this.dialogCount.innerText = messageCountLabel
                    break;
                case "NOTIFY":
                    if(count > 0) {
                        this.notifyCount.classList.remove('bc-000')
                        this.notifyCount.classList.add('bc-c90536')
                    } else {
                        this.notifyCount.classList.add('bc-000')
                        this.notifyCount.classList.remove('bc-c90536')
                    }

                    this.notifyCount.innerText = messageCountLabel
                    break;
            }
        }
    }

    updateCounter(count, type) {
        if (type == 'MESSAGE') {
            this.im.messageCount = count;
            this.updateIndicator(count, type)
        } else if (type == 'NOTIFY') {
            this.im.notifyCount = count;
            this.updateIndicator(count, type)
        } else if (type == 'MAIL')
            this.im.mailCount = count;

        var sumCount = 0;

        if (this.im.notifyCount > 0)
            sumCount += parseInt(this.im.notifyCount);
        if (this.im.messageCount > 0)
            sumCount += parseInt(this.im.messageCount);
        if (this.im.linesCount > 0)
            sumCount += parseInt(this.im.linesCount);

        if (BX.MessengerCommon.isPage() && this.im.notify && !this.im.userExtranet)
        {
            var lfCounter = this.im.notify.getCounter('**');
            BX.MessengerWindow.setTabBadge('im-lf', lfCounter);
        }

        BX.onCustomEvent(window, 'onImUpdateSumCounters', [sumCount, 'SUM']);

        if (!this.im.desktopStatus && !BX.MessengerCommon.isDesktop())
        {
            var result = document.title.match(/^(\((\d+)\)\s)(.*)+/);
            if (result && result[1])
            {
                var shownSumCount = parseInt(result[1]);
                if (shownSumCount != sumCount)
                {
                    document.title = (sumCount > 0? '('+sumCount+') ': '') + result[3];
                }
            }
            else if (sumCount > 0)
            {
                document.title = '('+sumCount+') '+document.title;
            }
        }
    }

    toggle() {
        if(this.menu.classList.contains('d-n')) {
            this.show()
        } else {
            this.close()
        }
    }

    initUser() {
        this.container = document.querySelector('.lang-userauth--element')
        this.btn = this.container?.querySelector('.lang-userauth--btn')
        this.menu = this.container?.querySelector('.lang-userauth--menu')
        this.btnClose = this.container?.querySelector('.lang-userauth--close')
    }

    initClose() {
        this.btnClose.addEventListener('click', this.close.bind(this))
    }

    initShow() {
        this.btn.addEventListener('click', this.toggle.bind(this))
    }
}