LG.LKBalansOut = class extends LG.Modal
{
    balansOut
    paySystem
    util = new LG.Util()
    list = LG.PaySystemOut
    currentPay
    field
    numField
    inSumEnd
    sumMax
    format

    constructor() {
        super()

        this.initBalans()
    }

    initBalans() {
        this.format = new Intl.NumberFormat('ru-RU', {style: 'currency',currency: 'RUB',maximumFractionDigits:0})
        this.balansOut = document?.querySelectorAll('.lang-acount--pay')
        
        this.balansOut?.forEach(pay => pay?.addEventListener('click', this.initCheckedPaySystem.bind(this)))
    }

    initCheckedPaySystem() {
        this.balansOut?.forEach(pay => {
            if(pay.checked) {
                this.paySystem = pay.value
            }
        })

        this.loadOutBalans()
    }

    typeMaskCurrency() {
        let param = {
            payout: {
                prefix: "", // Символ валюты
                suffix: " ₽",
                groupSeparator: " ", // Разделитель групп (например, точка для тысяч)
                alias: "numeric", // Псевдоним для базового числового формата
                // placeholder: "", // Заполнитель для пустых позиций
                autoGroup: true, // Автоматически добавлять разделители групп
                digits: 0, // Количество цифр после запятой
                digitsOptional: true, // Обязательно ли вводить цифры
                clearMaskOnLostFocus: false, // Не очищать маску при потере фокуса
                step: 1,
                rightAlign: false,
            }
        }

        if(Number(this.field.min) > 0) {
            param.payout.min = Number(this.field.min)
        } else {
            param.payout.min = this.currentPay.mn
        }

        if(Number(this.field.max) > 0) {
            param.payout.max = Number(this.field.max)
        } else {
            param.payout.max = this.currentPay.mx
        }

        Inputmask.extendAliases(param)

        new Inputmask("payout", {}).mask(this.field)

        this.mathFee()
    }

    typeMaskCard(field) {
        new Inputmask("9999 9999 9999 9999", {
            autoclear: false,
            placeholder: "XXXX XXXX XXXX XXXX",
            oncomplete: () => {
                field.classList.add('c-008000', 'bcr-008000')
                field.classList.remove('c-c90536', 'bcr-c90536')
            },
            onincomplete: () => {
                field.classList.add('c-c90536', 'bcr-c90536')
                field.classList.remove('c-008000', 'bcr-008000')
            }
        }).mask(field);
    }

    typeMaskPhone(field) {
        new Inputmask("+7 (999) 999-99-99", {
            autoclear: false,
            placeholder: "+7 (___) ___-__-__",
            completed: () => {},
            incomplete: () => {}
        }).mask(field);
    }

    searchInputType() {
        this.field = this.content?.querySelector('.payout--sum')
        this.inSumEnd = this.content?.querySelector('.lang-sum--end')
        this.sumMax = this.content?.querySelector('.sum--max')
        
        this.content?.querySelectorAll('.paysystem--card')?.forEach(field => {
            LG.loadJS("/local/templates/lang.css/js/inputmask.min.js", this.typeMaskCard.bind(this, field))
        })

        this.content?.querySelectorAll('.paysystem--phone')?.forEach(field => {
            LG.loadJS("/local/templates/lang.css/js/inputmask.min.js", this.typeMaskPhone.bind(this, field))
        })

        if(this.field) {
            this.field.value = this.currentPay.mn ?? 1
            LG.loadJS("/local/templates/lang.css/js/inputmask.min.js", this.typeMaskCurrency.bind(this))
            this.field.addEventListener('input', this.mathFee.bind(this))
            this.field.addEventListener('blur', this.mathFeeBlur.bind(this))
            this.sumMax?.addEventListener('click', this.pasteSumMax.bind(this))
            this.mathFee()
        }
    }

    pasteSumMax() {
        if(this.currentPay.com > 0) {
            let com1 = (this.currentPay.com / 100) * this.currentPay.budget
            let budgetCom = this.currentPay.budget - com1
            let com2 = budgetCom * (this.currentPay.com / 100)
            let comEnd = com1 - com2
            let endSum = Math.floor(budgetCom + comEnd)

            this.field.value = endSum
        } else {
            this.field.value = this.currentPay.budget
        }
        this.mathFee()
    }

    numberFormat(number) {
        return this.format.format(number)
    }

    mathFeeBlur() {
        let min = 0;
        this.numField = Number(this.field.value.replace(/\D/g, ""))

        if(Number(this.field.min) > 0) {
            min = Number(this.field.min)
        } else {
            min = this.currentPay.mn
        }

        if(this.numField <= 0) {
            this.field.value = min
            this.numField = min
        }

        this.mathFee()
    }

    mathFee() {
        this.numField = Number(this.field.value.replace(/\D/g, ""))

        if(this.currentPay.com > 0) {
            let com = (this.currentPay.com / 100) * this.numField
            
            if(com < this.currentPay.mn) {
                com = Number(this.currentPay.mn)
            }

            this.inSumEnd.innerText = `≈${this.numberFormat(this.numField + com)}`
        }
    }

    loadOutBalans() {
        this.modifier.show.container = "t--100%"
        this.method = "getBalansOut"
        this.loadData = new FormData()
        this.loadData.append('sessid', BX.bitrix_sessid())
        this.loadData.append('paySystem', this.paySystem)
        this.currentPay = this.list[this.paySystem]

        this.load()
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

    getData(data) {
        if(data.status == 'success') {
            if(data.data.componentResult.saveSumOut == 'y') {
                setTimeout(this.close.bind(this), 10000)
                setTimeout(this.loaderInactive.bind(this), 1000);
                this.content.innerHTML = data.data.html
                setTimeout(this.redirect.bind(this), 10000)
            } else {
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000);

                this.sendData()
            }
        }
    }
}