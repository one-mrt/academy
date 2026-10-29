LG.Modal = class
{
    modal = {
        wrapper: '.lang-edit--wrap',
        container: '.lang-edit--container',
        content: '.lang-edit--content',
        loader: '.lang-edit--preloader',
        btnClose: '.lang-edit--close',
    }
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
    btnClose
    loadData = new FormData()

    constructor() {
        this.initModal()
    }

    initModal() {
        this.wrapper = document.querySelector(this.modal.wrapper)
        this.container = this.wrapper?.querySelector(this.modal.container)
        this.content = this.wrapper?.querySelector(this.modal.content)
        this.loader = this.wrapper?.querySelector(this.modal.loader)
        this.btnClose = this.wrapper?.querySelector(this.modal.btnClose)

        this.btnClose?.addEventListener('click', this.close.bind(this))
    }

    show() {
        this.wrapper.classList.remove('d-n')
        document.body.classList.add('of-h')

        setTimeout(() => {
            this.container.classList.remove(this.modifier.show.container)
        }, 200)
    }

    close() {
        this.container.classList.add(this.modifier.show.container)

        setTimeout(() => {
            this.wrapper.classList.add('d-n')
            this.content.innerHTML = ""
            document.body.classList.remove('of-h')
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