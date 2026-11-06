LG.Auth = class {
    modal
    container
    content
    loader
    close
    form

    constructor() {
        this.modal = document.querySelector('.lang-modal--wrap')
        this.container = this.modal.querySelector('.lang-modal--container')
        this.content = this.modal.querySelector('.lang-modal--content')
        this.loader = this.modal.querySelector('.lang-modal--preloader')
        this.close = this.modal.querySelector('.lang-modal--close')

        this.close.addEventListener('click', this.closeModal.bind(this))

        document.querySelectorAll('.lang-auth--button')?.forEach((btn) => this.initAuth(btn))
        document.querySelectorAll('.lang-form--reg')?.forEach(btn => this.initReg(btn))
        document.querySelectorAll('.lang-form--order-add')?.forEach(btn => this.initOrderAdd(btn))
        document.querySelectorAll('.lang-form--forgot')?.forEach(btn => this.initForgot(btn))

        document.querySelectorAll('.lang-auth--content')?.forEach(content => this.initFormAuthStatic(content.parentNode))
        
        this.initChange()
    }

    getParamUrl() {
        let param = new URLSearchParams(location.search)
        return {
            sessid: BX.bitrix_sessid(),
            change_password: `${param.get('change_password')}`,
            USER_CHECKWORD: `${param.get('USER_CHECKWORD')}`,
            USER_LOGIN: `${param.get('USER_LOGIN')}`
        }
    }

    initChange() {
        let param = this.getParamUrl()

        if(param['change_password'] == 'yes' && param['USER_CHECKWORD'] && param['USER_LOGIN']) {
            this.content.classList.add('sm:w-480px')
            this.show()
            this.loaderActive()
            this.loadFormChange()
        }
    }

    initBtnAction() {
        this.content.querySelectorAll('.lang-auth--button')?.forEach((btn) => this.initAuth(btn))
        this.content.querySelectorAll('.lang-form--reg')?.forEach(btn => this.initReg(btn))
        this.content.querySelectorAll('.lang-form--order-add')?.forEach(btn => this.initOrderAdd(btn))
        this.content.querySelectorAll('.lang-form--forgot')?.forEach(btn => this.initForgot(btn))
    }

    initBtnActionContent(content) {
        content.querySelectorAll('.lang-auth--button')?.forEach((btn) => this.initAuth(btn))
        content.querySelectorAll('.lang-form--reg')?.forEach(btn => this.initReg(btn))
        content.querySelectorAll('.lang-form--order-add')?.forEach(btn => this.initOrderAdd(btn))
        content.querySelectorAll('.lang-form--forgot')?.forEach(btn => this.initForgot(btn))
    }

    closeModal() {
        this.container.classList.add('t--100%')

        setTimeout(() => {
            this.modal.classList.add('d-n')
            this.content.innerHTML = ""
            document.body.classList.remove('of-h')
        }, 500)
    }

    show() {
        this.modal.classList.remove('d-n')
        document.body.classList.add('of-h')

        setTimeout(() => {
            this.container.classList.remove('t--100%')
        }, 500)
    }

    loaderActive() {
        this.loader?.classList.remove('d-n')
    }

    loaderInactive () {
        this.loader?.classList.add('d-n')
    }

    initAuth(btn) {
        btn?.addEventListener("click", this.initFormAuth.bind(this))
    }

    initReg(btn) {
        btn?.addEventListener('click', this.initFormReg.bind(this))
    }

    initOrderAdd(btn) {
        btn?.addEventListener('click', this.initFormOrderAdd.bind(this))
    }

    initForgot(btn) {
        btn?.addEventListener('click', this.initFormForgot.bind(this))
    }

    initFormAuth() {
        this.content.classList.add('sm:w-480px')
        this.show()
        this.loaderActive()
        this.loadFormAuth()
    }

    initFormAuthStatic(content) {
        this.form = content.querySelector('form')

        this.form.addEventListener("submit", (e) => {
            e.preventDefault()

            BX.ajax.runAction('lang:ajax.page.auth', {
                data: new FormData(this.form)
            }).then((data) => {
                if(data.status == 'success') {
                    if(data.data.componentResult.auth == 'y') {
                        window.location.href = data.data.componentResult.authUrl
                    } else {
                        content.innerHTML = data.data.html

                        this.initBtnActionContent(content)
                        this.initFormAuthStatic(content)
                    }
                }
            }, (response) => {
                console.log(response)
            });
        })
    }

    initFormReg() {
        this.content.classList.add('sm:w-480px')
        this.show();
        this.loaderActive()
        this.loadFormReg();
    }

    initFormOrderAdd() {
        this.show()
        this.loaderActive()
        this.loadFormOrderAdd()
    }

    initFormForgot() {
        this.show()
        this.loaderActive()
        this.loadFormForgot()
    }

    loadFormAuth() {
        BX.ajax.runAction('lang:ajax.page.auth', {data: {sessid: BX.bitrix_sessid()}}).then((data) => {
            if(data.status == 'success') {
                if(data.data.componentResult.auth == 'y') {
                    window.location.href = data.data.componentResult.authUrl
                }

                this.content.innerHTML = data.data.html;
                setTimeout(this.loaderInactive.bind(this), 1000);

                this.initBtnAction()
                this.sendFormAuth()
            }
        },
        (response) => {
            setTimeout(this.loaderInactive.bind(this), 1000);
            console.log(response)
        });
    }

    sendFormAuth() {
        this.form = this.content.querySelector('form')

        this.form.addEventListener("submit", (e) => {
            e.preventDefault()
            this.loaderActive()

            BX.ajax.runAction('lang:ajax.page.auth', {
                data: new FormData(this.form)
            }).then((data) => {
                if(data.status == 'success') {
                    if(data.data.componentResult.auth == 'y') {
                        window.location.href = data.data.componentResult.authUrl
                    } else {
                        this.content.innerHTML = data.data.html
                        setTimeout(this.loaderInactive.bind(this), 1000);

                        this.initBtnAction()
                        this.sendFormAuth()
                    }
                }
            }, (response) => {
                setTimeout(this.loaderInactive.bind(this), 1000);
                console.log(response)
            });
        })
    }

    loadFormReg() {
        BX.ajax.runAction('lang:ajax.page.reg', {data: {sessid: BX.bitrix_sessid()}}).then((data) => {
            if(data.status == 'success') {
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000)

                this.initBtnAction()
                this.sendFormReg()
            }
        },
        (response) => {
            setTimeout(this.loaderInactive.bind(this), 1000);
            console.log(response)
        });
    }

    sendFormReg() {
        this.form = this.content.querySelector('form')

        this.form?.addEventListener("submit", (e) => {
            e.preventDefault()
            this.loaderActive()

            BX.ajax.runAction('lang:ajax.page.reg', {
                data: new FormData(this.form),
            }).then((data) => {
                if(data.status == 'success') {
                    if(data.data.componentResult.reg == 'y') {
                        window.location.href = data.data.componentResult.regUrl
                    } else {

                        this.content.innerHTML = data.data.html
                        setTimeout(this.loaderInactive.bind(this), 1000);

                        this.initBtnAction()
                        this.sendFormReg()
                    }
                }
            }, (response) => {
                setTimeout(this.loaderInactive.bind(this), 1000);
                console.log(response)
            });
        })
    }

    sendFormForgot(data) {
        this.form = this.content.querySelector('form')

        this.form?.addEventListener("submit", (e) => {
            e.preventDefault()
            this.loaderActive()

            BX.ajax.runAction('lang:ajax.page.forgot', {
                data: new FormData(this.form),
            }).then((data) => {
                if(data.status == 'success') {
                    if(data.data.componentResult.auth == 'y') {
                        window.location.href = data.data.componentResult.authUrl
                    }

                    this.content.innerHTML = data.data.html;
                    setTimeout(this.loaderInactive.bind(this), 1000);

                    this.initBtnAction()
                    this.sendFormForgot()
                }
            }, (response) => {
                setTimeout(this.loaderInactive.bind(this), 1000);
                console.log(response)
            });
        })
    }

    sendFormChange(data) {
        this.form = this.content.querySelector('form')

        this.form?.addEventListener("submit", (e) => {
            e.preventDefault()
            this.loaderActive()

            BX.ajax.runAction('lang:ajax.page.change', {
                data: new FormData(this.form),
            }).then((data) => {
                if(data.status == 'success') {
                    if(data.data.componentResult.change == 'y') {
                        setTimeout(this.loadFormAuth.bind(this), 5000)
                    }

                    this.content.innerHTML = data.data.html;
                    setTimeout(this.loaderInactive.bind(this), 1000);

                    this.initBtnAction()
                    this.sendFormChange()
                }
            }, (response) => {
                setTimeout(this.loaderInactive.bind(this), 1000);
                console.log(response)
            });
        })
    }

    loadFormOrderAdd() {
        BX.ajax.runAction('lang:ajax.page.orderAdd', {data: {sessid: BX.bitrix_sessid()}}).then((data) => {
            if(data.status == 'success') {
                if(data.data.componentResult.auth == 'y') {
                    window.location.href = data.data.componentResult.authUrl
                }

                this.content.innerHTML = data.data.html;

                this.content.querySelectorAll('script').forEach(script => eval(script.innerText))
                setTimeout(this.loaderInactive.bind(this), 1000);

                this.initBtnAction()
            }
        },
        (response) => {
            setTimeout(this.loaderInactive.bind(this), 1000);
            console.log(response)
        });
    }

    loadFormForgot() {
        BX.ajax.runAction('lang:ajax.page.forgot', {data: {sessid: BX.bitrix_sessid()}}).then((data) => {
            if(data.status == 'success') {
                if(data.data.componentResult.auth == 'y') {
                    window.location.href = data.data.componentResult.authUrl
                }

                this.content.innerHTML = data.data.html;
                setTimeout(this.loaderInactive.bind(this), 1000);

                this.initBtnAction()
                this.sendFormForgot()
            }
        },
        (response) => {
            setTimeout(this.loaderInactive.bind(this), 1000);
            console.log(response)
        });
    }

    loadFormChange() {
        BX.ajax.runAction('lang:ajax.page.change', {
            getParameters: this.getParamUrl(),
            method: "GET"
        }).then((data) => {
            if(data.status == 'success') {
                if(data.data.componentResult.change == 'y') {
                    setTimeout(this.loadFormAuth.bind(this), 5000)
                }
                this.content.innerHTML = data.data.html;
                setTimeout(this.loaderInactive.bind(this), 1000);

                this.initBtnAction()
                this.sendFormChange()
            }
        },
        (response) => {
            setTimeout(this.loaderInactive.bind(this), 1000);
            console.log(response)
        });
    }
}