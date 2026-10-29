LG.LKBalansIn = class extends LG.Modal
{
    balansIn
    paySystem
    util = new LG.Util()
    list = LG.PaySystemIn
    field
    numField
    currentPay
    inSumEnd

    constructor() {
        super()
        this.initBalans()
    }

    initBalans() {
        this.balansIn = document?.querySelectorAll('.lang-acount--pay')
        this.balansIn?.forEach(pay => pay?.addEventListener('click', this.initCheckedPaySystem.bind(this)))
    }

    initCheckedPaySystem() {
        this.balansIn?.forEach(pay => {
            if(pay.checked) {
                this.paySystem = pay.value
                this.currentPay = this.list[this.paySystem]
                this.loadOutBalans()
            }
        })
    }

    loadOutBalans() {
        this.modifier.show.container = "t--100%"
        this.method = "getBalansIn"
        this.loadData = new FormData()
        this.loadData.append('sessid', BX.bitrix_sessid())
        this.loadData.append('paySystem', this.paySystem)

        this.load()
    }

    successLoad(data) {
        if(data.status == 'success') {
            this.content.innerHTML = data.data.html;
            setTimeout(this.loaderInactive.bind(this), 1000);
            this.sendData()
        }
    }

    typeMaskCurrency() {
        let param = {
            payin: {
                prefix: "", // Символ валюты
                suffix: " ₽",
                groupSeparator: " ", // Разделитель групп (например, точка для тысяч)
                alias: "numeric", // Псевдоним для базового числового формата
                placeholder: "", // Заполнитель для пустых позиций
                autoGroup: true, // Автоматически добавлять разделители групп
                digits: 0, // Количество цифр после запятой
                digitsOptional: true, // Обязательно ли вводить цифры
                clearMaskOnLostFocus: false, // Не очищать маску при потере фокуса
                step: 1,
                rightAlign: false,
            }
        }

        if(Number(this.field.min) > 0) {
            param.payin.min = Number(this.field.min)
        } else {
            param.payin.min = this.currentPay.mn
        }

        if(Number(this.field.max) > 0) {
            param.payin.max = Number(this.field.max)
        } else {
            param.payin.max = this.currentPay.mx
        }

        Inputmask.extendAliases(param)

        new Inputmask("payin", {}).mask(this.field)
    }

    searchInputType() {
        this.field = this.content?.querySelector('.payin--sum')
        this.inSumEnd = this.content?.querySelector('.lang-sum--end')

        if(this.field) {
            this.field.value = this.currentPay.mn ?? 1
            LG.loadJS("/local/templates/lang.css/js/inputmask.min.js", this.typeMaskCurrency.bind(this))
            this.field.addEventListener('input', this.mathFee.bind(this))
            this.mathFee()
        }
    }

    mathFee() {
        this.numField = Number(this.field.value.replace(/\D/g, ""))

        if(this.currentPay.com > 0) {
            let com = (this.currentPay.com / 100) * this.numField

            this.inSumEnd.innerText = `≈${this.numField + com} ₽`
        }
    }

    sendData() {
        this.form = this.content?.querySelector('form')
        this.searchInputType()

        this.form?.addEventListener("submit", (e) => {
            e.preventDefault()
            this.loaderActive()

            BX.ajax.runAction(`lang:ajax.page.${this.method}`, {data: this.form ? new FormData(this.form) : false})
                .then(this.getData.bind(this), this.error.bind(this));
        })
    }

    redirect() {
        window.location.href = window.location.href
    }

    setFormButton() {
        // fm
        this.form.id = "langFormPay"
        let form = this.content.querySelector('.form--pay')
        let btn = document.createElement('input')
        btn.type = "submit"
        btn.className = "pby-12 pix-24 lg:pby-16 lg:pix-32 m-0 r-12 ff-ud f-14 xl:f-16 ftw-6 tt-l lh-1 ta-c ws-nw a:tm-s0.9 lg:h:r-32 tn-all-3 bc-527CC8 h:bc-w h:bc-w bw-2 bs-s bcr-527CC8 c-w h:c-dt tn-all-3"
        btn.setAttribute('form', 'langFormPay')
        btn.value = "Оплатить"
        form.append(btn)
    }

    getData(data) {
        if(data.status == 'success') {
            if(data.data.componentResult.paySuccess == 'y') {
                this.content.innerHTML = data.data.html
                this.form = this.content?.querySelector('form')
                if(this.form) {
                    this.content.classList.add('d-n|fm');
                    this.form.setAttribute('target', '_blank')
                    this.setFormButton()
                    this.form.requestSubmit()
                    setTimeout(this.close.bind(this), 1000)
                }
                setTimeout(this.loaderInactive.bind(this), 1000);
            } else {
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000);

                this.sendData()
            }
        }
    }
}