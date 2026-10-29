LG.NavFilter = class extends LG.Filter
{
    container
    elements
    cardMinHeight = 0
    pack
    packClass = 'w-100 d-f fd-c'
    user
    method
    
    nav
    btnNav
    page = 2

    constructor(param = {}) {
        super()

        if(param.method) {
            this.method = param.method
        }

        if(param.packClass) {
            this.packClass = param.packClass
        }

        this.container = document.querySelector('.lang--items')
        // this.user = id

        this.initEvent()
        this.initNav()
        this.minHeight()
    }

    minHeight() {
        this.container?.querySelectorAll('.lang--item')?.forEach(e => {
            if(e.clientHeight > this.cardMinHeight) {
                this.cardMinHeight = e.clientHeight;
            }
        })

        if(this.cardMinHeight > 0) {
            this.container.style.minHeight = `${this.cardMinHeight}px`
        }
    }

    initEvent() {
        this.receive('onApplyFilter', window, this.onApplyFilter.bind(this))
        this.receive('onResetFilter', window, this.onResetFilter.bind(this))
        this.receive('onPageLoad', this.container, this.onPageLoad.bind(this))
    }

    rebootContainer() {
        this.page = 1
        this.elements = ''
        this.container.innerHTML = ''
    }

    onApplyFilter() {
        this.rebootContainer()
        this.getPage()
    }

    onResetFilter() {
        this.rebootContainer()
        this.getPage()
    }

    onPageLoad() {
        this.removeNav()
        this.createPage()
        this.initNav()
    }

    initNav() {
        this.nav = this.container?.querySelector('.lang--nav')
        this.btnNav = this.nav?.querySelector('button')

        this.btnNav?.addEventListener("click", this.getPage.bind(this))
    }

    removeNav() {
        this.nav?.remove()
        this.btnNav?.remove()
    }

    getPage() {
        this.pack = document.createElement('div')

        this.formData['sessid'] = BX.bitrix_sessid()
        this.formData['page'] = this.page

        BX.ajax.runAction(`lang:ajax.page.${this.method}`, {
            getParameters: this.formData,
            navigation: {page: this.page},
            method: 'GET'
        }).then((data) => {
            if(data.status == 'success') {
                this.page = data.data.componentResult.next
                this.elements = data.data.html.replace(/[\r\n\t]/gi, '')
                this.send('onPageLoad', this.container)
            }
        },
        (response) => {
            console.log(response)
        })
    }

    createPage() {
        this.pack.className = this.packClass
        this.pack.innerHTML = this.elements
        this.container.append(this.pack)
        this.nav = this.container?.querySelector('.lang--nav')
        this.btnNav = this.nav?.querySelector('button')
    }
}