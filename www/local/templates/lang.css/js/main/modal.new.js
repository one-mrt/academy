LG.Dialog = class
{
    modifier = {
        show: {
            wrapper: '',
            container: 't-200%'
        },
    }
    wrapper
    container
    content
    loader
    loaderMessage
    btnClose
    loadData = new FormData()

    constructor() {
        this.clearData()
    }

    createModal() {
        this.wrapper = document.createElement('section')
        this.wrapper.className = "w-100 h-100 pn-f t-0 rt-0 b-0 l-0 d-f ai-c jc-e bc-f4eff95e bf-b6 z-3"

        this.container = document.createElement('div')
        this.container.className = "h-100 h-100%-110px w-100 md:mw-720px of-h pn-a t-110 t-200% mr-a ml-a d-f fd-c bxs-au tp-t tnd-500ms bc-w md:btlr-12"

        this.content = document.createElement('div')
        this.content.className = "w-100 h-100 ofx-h ofy-a pn-r p-32 md:p-32"

        this.btnClose = document.createElement('button')
        this.btnClose.className = "pn-a t-32px rt-32px m-0 p-0 bw-0 c-dt h:c-py tn-all-3 bc-t"
        this.btnClose.innerHTML = '<svg width="24" height="24" viewBox="0 0 13 13" fill="none" stroke="currentColor"><line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"></line><line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"></line></svg>'
        this.btnClose?.addEventListener('click', this.close.bind(this))

        this.loader = document.createElement('div')
        this.loader.className = "w-100 h-100 d-f fd-c m-0 z-2 ai-c jc-c pn-a t-0 rt-0 b-0 l-0 ofw-h bc-w"
        this.loader.innerHTML = '<svg class="loader--animation w-100 h-100 mw-200px mxh-200px" viewBox="25 25 50 50"><circle class="loader--path" cx="50" cy="50" r="20" fill="none" stroke-miterlimit="10"></circle></svg>'
        this.loaderMessage = document.createElement('div')
        this.loaderMessage.className = 'mt-8 f-12 md:f-14'
        this.loaderMessage.innerText = "Пожалуйста подождите"

        this.loader.append(this.loaderMessage)

        this.container.append(this.content)
        this.container.append(this.btnClose)
        this.container.append(this.loader)

        this.wrapper.append(this.container)
        document.body.append(this.wrapper)
    }

    clearData() {
        this.loadData = new FormData()
    }

    destroyModal() {
        this.btnClose.removeEventListener('click', this.close)
        this.loaderMessage = null
        this.loader = null
        this.btnClose = null
        this.content = null
        this.container = null
        this.wrapper = null
    }

    show() {
        this.createModal()
        document.body.classList.add('of-h')

        setTimeout(() => {
            this.container.classList.remove(this.modifier.show.container)
        }, 200)
    }

    close() {
        this.container.classList.add(this.modifier.show.container)

        setTimeout(() => {
            this.wrapper.remove()
            document.body.classList.remove('of-h')
            this.clearData()
            this.destroyModal()
        }, 500)
    }

    loaderActive() {
        this.loader?.classList.remove('d-n')
    }

    loaderInactive () {
        this.loader?.classList.add('d-n')
    }

    loadAction() {
        BX.ajax.runAction(`lang:ajax.page.${this.method}`, {data: this.loadData})
            .then(this.successLoad.bind(this), this.error.bind(this));
    }

    errorText() {
        let textError = document.createElement('div')
        textError.classList.add('w-100', 'h-100', 'd-f', 'ta-c', 'ai-c', 'jc-c', 'pn-a', 't-0', 'rt-0', 'b-0', 'l-0', 'bc-w', 'z-1')
        textError.innerText = 'Что то пошло не так. Перезагрузите страницу и попробуйте еще раз.'
        this.content.prepend(textError)
    }

    error(response) {
        setTimeout(this.loaderInactive.bind(this), 1000);
        this.errorText()
        console.log(response)
    }

    successLoad(data) {
        if(data.status == 'success') {
            this.content.innerHTML = data.data.html;
            setTimeout(this.loaderInactive.bind(this), 1000);
            this.sendData()
        }
    }

    sendData() {
        this.form = this.content?.querySelector('form')

        this.form?.addEventListener("submit", (e) => {
            e.preventDefault()
            this.loaderActive()

            BX.ajax.runAction(`lang:ajax.page.${this.method}`, {data: this.form ? new FormData(this.form) : false})
                .then(this.getData.bind(this), this.error.bind(this));
        })
    }

    getData(data) {
        if(data.status == 'success') {
            if(data.data.componentResult.DATA_SAVED == 'Y') {
                window.location.href = window.location.href
            } else {
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000);

                this.sendData()
            }
        }
    }

    load() {
        this.show()
        this.loaderActive()
        this.loadAction()
    }
}