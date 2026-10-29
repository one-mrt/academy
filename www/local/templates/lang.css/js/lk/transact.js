LG.Transact = class extends LG.FilterTransact
{
    container
    transact
    pack
    user
    
    nav
    btnNav
    page = 2

    constructor() {
        super()

        this.container = document.querySelector('.lang-transact--items')
        // this.user = id

        this.initEvent()
        this.initNav()
    }

    initEvent() {
        this.receive('onApplyFilterTransact', window, this.onApplyFilterTransact.bind(this))
        this.receive('onTransactPageLoad', this.container, this.onTransactPageLoad.bind(this))
    }

    rebootContainer() {
        this.page = 1
        this.transact = ''
        this.container.innerHTML = ''
    }

    onApplyFilterTransact() {
        this.rebootContainer()
        this.transactPage()
    }

    onTransactPageLoad() {
        this.removeNav()
        this.createPage()
        this.initNav()
    }

    initNav() {
        this.nav = this.container?.querySelector('.lang-transact--nav')
        this.btnNav = this.nav?.querySelector('button')

        this.btnNav?.addEventListener("click", this.transactPage.bind(this))
    }

    removeNav() {
        this.nav?.remove()
        this.btnNav?.remove()
    }

    transactPage() {
        this.pack = document.createElement('div')

        this.formData['sessid'] = BX.bitrix_sessid()
        this.formData['page'] = this.page

        BX.ajax.runAction(`lang:ajax.page.getUserTransact`, {
            getParameters: this.formData,
            // navigation: {page: this.page},
            method: 'GET'
        }).then((data) => {
            if(data.status == 'success') {
                this.page = data.data.componentResult.next
                this.transact = data.data.html.replace(/[\r\n\t]/gi, '')
                this.send('onTransactPageLoad', this.container)
            }
        },
        (response) => {
            console.log(response)
        });
    }

    createPage() {
        this.pack.className = 'w-100 d-f fd-c'
        this.pack.innerHTML = this.transact
        this.container.append(this.pack)
        this.nav = this.container?.querySelector('.lang-transact--nav')
        this.btnNav = this.nav?.querySelector('button')
    }
}