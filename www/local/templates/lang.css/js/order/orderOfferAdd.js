LG.OrderOfferAdd = class extends LG.Modal
{
    modal = {
        wrapper: '.lang-offer--wrap',
        container: '.lang-offer--container',
        content: '.lang-offer--content',
        loader: '.lang-offer--preloader',
        btnClose: '.lang-offer--close',
    }
    add
    customer
    orderId
    serviceId
    price
    comment
    maxComment
    maxLength = 100
    offers
    offerContainer
    offerId
    offerContent
    offerList
    nav
    btnNav
    page = 2
    pack

    constructor() {
        super()
        this.initModal()

        this.customer = LG.OrderOfferData.customer
        this.orderId = LG.OrderOfferData.orderId
        this.serviceId = LG.OrderOfferData.serviceId
        this.offerList = LG.OrderOfferData.offer

        this.initElements()
        this.initEvent()
        this.initNav()
    }

    initElements() {
        this.offerContent = document.querySelector('.lang-offer--content')
        this.offerContainer = document.querySelector('.offer--list')
        this.add = document.querySelector('.lang-offer--add')
        this.add?.addEventListener('click', this.loadForm.bind(this))

        this.initActionOffer()
    }

    initActionOffer() {
        if(this.offerList) {
            for(let k in this.offerList) {
                if(!this.offerList[k].item) {
                    this.offerList[k].item = document.querySelector(`#comment${this.offerList[k].id}`)
                    this.offerList[k].item
                        ?.querySelector('.offer--cancel')
                        ?.addEventListener('click', this.cancel.bind(this, this.offerList[k]))
                    this.offerList[k].item
                        ?.querySelector('.offer--accept')
                        ?.addEventListener('click', this.accept.bind(this, this.offerList[k]))
                    this.offerList[k].item
                        ?.querySelector('.offer--withdraw')
                        ?.addEventListener('click', this.withdraw.bind(this, this.offerList[k]))

                    this.offerList[k].item
                        ?.querySelector('.answer--cancel')
                        ?.addEventListener('click', this.cancelConfirm.bind(this, this.offerList[k]))

                    this.offerList[k].item
                        ?.querySelector('.answer--accept')
                        ?.addEventListener('click', this.acceptConfirm.bind(this, this.offerList[k]))
                }
            }
        }
    }

    sendActionOffer() {
        this.method = "addOrderOffer"

        this.loadData.append('sessid', BX.bitrix_sessid())
        this.loadData.append('orderId', this.orderId)
        this.loadData.append('serviceId', this.serviceId)
        this.loadData.append('customer', this.customer)
        this.loadData.append('offerId', this.offerId)

        BX.ajax.runAction(`lang:ajax.page.${this.method}`, {
            data: this.loadData
        }).then((data) => {
            if(data.status == 'success') {
                // console.info('Успех', data)
            }
        }, (response) => {
            console.log(response)
        })
    }

    cancel(ofr) {
        this.offerId = ofr.id
        this.loadData = new FormData()
        this.loadData.append('cancel', 'y')

        this.sendActionOffer()

        // console.info(this.offerList)
    }

    cancelConfirm(ofr) {
        this.offerId = ofr.id
        this.loadData = new FormData()
        this.loadData.append('cancelConfirm', 'y')

        this.sendActionOffer()
    }

    accept(ofr) {
        this.offerId = ofr.id
        this.loadData = new FormData()
        this.loadData.append('accept', 'y')

        this.sendActionOffer()
    }

    acceptConfirm(ofr) {
        this.offerId = ofr.id
        this.loadData = new FormData()
        this.loadData.append('acceptConfirm', 'y')

        this.sendActionOffer()
    }

    withdraw(ofr) {
        this.offerId = ofr.id
        this.loadData = new FormData()
        this.loadData.append('withdraw', 'y')

        this.sendActionOffer()
    }

    initEvent() {
        BX.PULL.subscribe({
            moduleId: "lang.academy",
            command: 'langOrderPublicUpdate',
            callback: this.update.bind(this)
        })
    }

    initNav() {
        this.nav = this.offerContainer?.querySelector('.lang-offer--nav')
        this.btnNav = this.nav?.querySelector('button')

        this.btnNav?.addEventListener("click", this.offerPage.bind(this))
    }

    removeNav() {
        this.nav?.remove()
        this.btnNav?.remove()
    }

    offerPage() {
        this.pack = document.createElement('div')

        BX.ajax.runAction(`lang:ajax.page.addOrderOffer`, {
            getParameters: {
                sessid: BX.bitrix_sessid(),
                customer: this.customer,
                orderId: this.orderId,
                serviceId: this.serviceId
            },
            navigation: {page: this.page},
            method: 'GET'
        }).then(
            this.updateNav.bind(this),
            (response) => {
                console.log(response)
            }
        );
    }

    updateNav(data) {
        if(data.status == 'success') {
            this.page = data.data.componentResult.next
            this.offers = data.data.html.replace(/[\r\n\t]/gi, '')
            this.removeNav()
            this.createPage()
            this.initNav()
            this.updateOfferList(data.data.componentResult.offer)
        }
    }

    createPage() {
        this.pack.className = 'w-100 d-f fd-c rg-16'
        this.pack.innerHTML = this.offers
        this.offerContainer.append(this.pack)
        this.nav = this.offerContainer?.querySelector('.lang-offer--nav')
        this.btnNav = this.nav?.querySelector('button')
    }

    updateOfferList(newOffer) {
        for(let k in newOffer) {
            if(!this.offerList[k]) {
                this.offerList[k] = newOffer[k]
            }
        }

        this.initActionOffer()
    }

    update(eData) {
        if(eData.id == this.orderId && eData.serviceId == this.serviceId && eData.customer == this.customer) {
            this.offerId = eData.offerId

            BX.ajax.runAction('lang:ajax.page.addOrderOffer', {
                data: {
                    sessid: BX.bitrix_sessid(),
                    customer: this.customer,
                    orderId: this.orderId,
                    serviceId: this.serviceId
                }
            }).then((data) => {
                if(data.status == 'success') {
                    if(eData.status == 'updatePage') {
                        location.reload()
                    } else {
                        this.offerContent.innerHTML = data.data.html;
                        this.offerList = data.data.componentResult.offer
                        this.initElements()
                        this.initNav()
                    }
                }
            }, (response) => {
                console.log(response)
            })
        }
    }

    loadForm() {
        this.modifier.show.container = "t--100%"
        this.method = "addOrderOffer"
        this.loadData = new FormData()
        this.loadData.append('sessid', BX.bitrix_sessid())
        this.loadData.append('add', 'y')
        this.loadData.append('orderId', this.orderId)
        this.loadData.append('serviceId', this.serviceId)
        this.loadData.append('customer', this.customer)
        
        this.load()
    }

    successLoad(data) {
        if(data.status == 'success') {
            this.content.innerHTML = data.data.html;
            setTimeout(this.loaderInactive.bind(this), 1000);
            this.sendData()
            this.initNewOffer()
        }
    }

    typeMaskCurrency() {
        let param = {
            offer: {
                prefix: "",
                suffix: " ₽",
                groupSeparator: " ",
                alias: "numeric",
                placeholder: "",
                autoGroup: true,
                digits: 0,
                digitsOptional: true,
                clearMaskOnLostFocus: false,
                step: 1,
                rightAlign: false,
            }
        }

        if(Number(this.price.min) > 0) {
            param.offer.min = Number(this.price.min)
        }

        if(Number(this.price.max) > 0) {
            param.offer.max = Number(this.price.max)
        }

        Inputmask.extendAliases(param)

        new Inputmask("offer", {}).mask(this.price)
    }

    commentLength() {
        if(this.comment.value.length > this.maxLength) {
            this.maxComment.classList.add('c-c90536')
        } else {
            this.maxComment.classList.remove('c-c90536')
        }

        this.maxComment.innerText = (this.maxLength - this.comment.value.length)
    }

    initNewOffer() {
        this.maxComment = this.content?.querySelector('.comment--length')
        this.comment = this.content?.querySelector('.offer--comment')
        this.price = this.content?.querySelector('.offer--price')

        this.commentLength()
        this.comment?.addEventListener('input', this.commentLength.bind(this))
        LG.loadJS("/local/templates/lang.css/js/inputmask.min.js", this.typeMaskCurrency.bind(this))
    }

    getData(data) {
        if(data.status == 'success') {
            if(data.data.componentResult.addSuccess == 'y') {
                setTimeout(this.close.bind(this), 5000);
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000)
            } else {
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000)
                this.sendData()
                this.initNewOffer()
            }
        }
    }
}

new LG.OrderOfferAdd()