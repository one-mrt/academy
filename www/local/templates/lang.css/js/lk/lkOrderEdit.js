LG.LKEditOrder = class extends LG.Modal
{
    order
    orderId
    serviceId
    chatId
    dialog
    info
    updateEvents = ['langOrderStatusUpdate', 'langOrderUpdate']
    util

    constructor() {
        super()

        this.orderId = LG.LKOrderData.id
        this.serviceId = LG.LKOrderData.serviceId
        this.chatId = LG.LKOrderData.chat
        this.util = new LG.Util()

        this.initOrderElements()
        this.initDialog()
        this.initEvent()
    }

    initOrderElements()
    {
        this.order = document.querySelector('.lang-order--detail')
        this.info = this.order?.querySelector('.order--content')
        this.pay = this.info?.querySelector('.order--pay')
        this.dialog = this.order?.querySelector('.order--dialog')
        this.pay?.addEventListener('click', this.payOrder.bind(this))
    }

    initDialog() {
        BX.Runtime.loadExtension('langmess.messenger').then((exports) => {
            new exports.MessengerApplication({node: this.dialog, dialogId: `chat${this.chatId}`});
            BX.PULL.start()
        });
    }

    payOrder() {
        this.container.className = "lang-edit--container w-100 sm:mw-540px mnh-250px of-h pn-r t-0 t--100% mr-a ml-a d-f fd-c bxs-au tp-t tnd-500ms bc-w sm:brr-12 sm:blr-12"
        this.modifier.show.container = "t--100%"
        this.method = "getOrderPay"
        this.loadData = new FormData()
        this.loadData.append('sessid', BX.bitrix_sessid())
        this.loadData.append('orderId', this.orderId)
        this.loadData.append('chatId', this.chatId)

        this.load()
    }

    initEvent() {
        for (let event of this.updateEvents) {
            BX.PULL.subscribe({
                moduleId: "sale",
                command: event,
                callback: this.update.bind(this, event)
            })
        }
    }

    update(eventName, eventData) {
        if(eventData.id == this.orderId && eventData.serviceId == this.serviceId) {
            BX.ajax.runAction('lang:ajax.page.getOrderDetail', {
                data: {
                    sessid: BX.bitrix_sessid(),
                    orderId: this.orderId,
                    serviceId: this.serviceId
                }
            }).then((data) => {
                if(data.status == 'success') {
                    this.info.innerHTML = data.data.html;
                    this.initOrderElements()
                    this.util.send('onLangOrderUpdateAfter', window)
                }
            }, (response) => {
                console.log(response)
            })
        }
    }

    sendData() {
        this.form = this.content?.querySelector('form')
        this.payClose = this.content?.querySelector('.pay--close')

        this.payClose?.addEventListener('click', this.close.bind(this))

        this.form?.addEventListener("submit", (e) => {
            e.preventDefault()
            this.loaderActive()

            BX.ajax.runAction(`lang:ajax.page.${this.method}`, {data: this.form ? new FormData(this.form) : false})
                .then(this.getData.bind(this), this.error.bind(this))
        })
    }

    getData(data) {
        if(data.status == 'success') {
            if(data.data.componentResult.paySuccess == 'y') {
                setTimeout(this.close.bind(this), 10000);
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000)
            } else {
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000)
                this.sendData()
            }
        }
    }

}