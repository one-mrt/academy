LG.Auth = class {
    modal
    container
    content
    loader
    close
    form
    fieldsCode
    fieldCode
    minute
    second
    containerNewCode
    timerId

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
        this.fieldsCode = this.content?.querySelectorAll('.field--code')
        this.fieldCode = this.content?.querySelector('.lang--code-logic')
        this.containerNewCode = this.content?.querySelector('.lang-reg--new-code')
        this.minute = this.content?.querySelector('.reg--minute')
        this.second = this.content?.querySelector('.reg--seconds')
        this.initLogicCode()
        this.timerStart()
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
                this.showError(response)
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

                this.showError(response)

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

    sendFormOrder() {
        this.form = this.content.querySelector('form')

        this.form?.addEventListener("submit", (e) => {
            e.preventDefault()
            this.loaderActive()

            BX.ajax.runAction('lang:ajax.page.orderAdd', {
                data: new FormData(this.form),
            }).then((data) => {
                if(data.status == 'success') {
                    if(data.data.componentResult.order == 'y') {
                        window.location.href = data.data.componentResult.orderUrl
                    } else {

                        this.content.innerHTML = data.data.html
                        setTimeout(this.loaderInactive.bind(this), 1000);

                        this.initBtnAction()
                        this.sendFormOrder()
                    }
                }
            }, (response) => {
                setTimeout(this.loaderInactive.bind(this), 1000);
                this.showError()
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
                this.sendFormOrder()
            }
        },
        (response) => {
            setTimeout(this.loaderInactive.bind(this), 1000);
            this.showError()
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

    initLogicCode() {
        this.fieldsCode?.forEach((input, index) => {
            input.dataset.index = index;

            input.addEventListener("keyup", this.logicCodeInput.bind(this, input))
            input.addEventListener("paste", this.logicCodePaste.bind(this))

            if(index == 0) {
                input.focus()
            }
        })
    }

    logicCodeInput(input, event) {
        let value = input.value
        let isValidInput = value.match(/[0-9a-z]/gi)
        let fieldIndex = input.dataset.index

        input.value = ""
        input.value = isValidInput ? value[0] : ""

        if (fieldIndex < this.fieldsCode?.length - 1 && isValidInput) {
            input.nextElementSibling.focus()
        }

        if (event.key === "Backspace" && fieldIndex > 0) {
            input.previousElementSibling.focus()
        }

        if (fieldIndex == this.fieldsCode?.length - 1 && isValidInput) {
            this.logicCodeSend()
        }
    }

    logicCodePaste(event) {
        const data = event.clipboardData.getData("text");
        const value = data.split("")

        if (value.length === this.fieldsCode?.length) {
            this.fieldsCode?.forEach((input, index) => (input.value = value[index]))
            this.logicCodeSend()
        }
    }

    logicCodeSend() {
        this.fieldsCode?.forEach((input) => {
            this.fieldCode.value += input.value;
            input.disabled = true;
            input.classList.add("disabled");
        })

        this.form?.requestSubmit()
    }

    timerCode(mn = 0, sec = 0) {
        clearTimeout(this.timerId)

        if(this.minute && this.second && (mn > 0 || sec > 0)) {

            this.minute.innerText = mn < 10 ? `0${mn}` : mn
            this.second.innerText = sec < 10 ? `0${sec}` : sec

            if(sec > 0) {
                sec--
                this.timerId = setTimeout(this.timerCode.bind(this, mn, sec), 1000)
            } else {
                mn--
                sec = 59

                if(mn >= 0) {
                    this.timerId = setTimeout(this.timerCode.bind(this, mn, sec), 1000)
                } else {
                    this.minute.innerText = '00'
                    this.second.innerText = '00'
                }
            }
        } else {
            if(this.containerNewCode) {
                this.containerNewCode.innerHTML = `<input type="hidden" name="newCode" value="y">
                    <button class="bw-0 bc-t c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3 f-12" type="submit">Отправить код повторно</button>`
            }
        }
    }

    timerStart() {
        let mn = Number(this.minute ? this.minute?.innerText : 0)
        let sec = Number(this.second ? this.second?.innerText : 0)

        this.timerCode(mn, sec)
    }

    showError(data) {
        if(data.errors && data.errors?.length > 0) {
            for(let k in data.errors) {
                if(data.errors[k].code == 'invalid_csrf') {
                    this.content.innerHTML = `<div class="w-100 h-100 pby-64 pix-32 ta-c">Пожалуйста обновите страницу</div>`
                    break
                }
            }
        }
    }
}