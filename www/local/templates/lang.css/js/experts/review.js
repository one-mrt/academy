LG.Review = class extends LG.Util
{
    container
    review
    pack
    user
    
    nav
    btnNav
    page = 2

    constructor() {
        super()

        this.container = document.querySelector('.lang-review--items')
        this.user = LG.ExpertData.userId

        this.initEvent()
        this.initNav()
    }

    initEvent() {
        this.receive('onReviewPageLoad', this.container, this.onReviewPageLoad.bind(this))
    }

    onReviewPageLoad() {
        this.removeNav()
        this.createPage()
        this.initNav()
    }

    initNav() {
        this.nav = this.container?.querySelector('.lang-review--nav')
        this.btnNav = this.nav?.querySelector('button')

        this.btnNav?.addEventListener("click", this.reviewPage.bind(this))
    }

    removeNav() {
        this.nav?.remove()
        this.btnNav?.remove()
    }

    reviewPage() {
        this.pack = document.createElement('div')

        BX.ajax.runAction(`lang:ajax.page.getReview`, {
            getParameters: {user:this.user, sessid: BX.bitrix_sessid()},
            navigation: {page: this.page},
            method: 'GET'
        }).then((data) => {
            if(data.status == 'success') {
                this.page = data.data.componentResult.next
                this.review = data.data.html.replace(/[\r\n\t]/gi, '')
                this.send('onReviewPageLoad', this.container)
            }
        },
        (response) => {
            console.log(response)
        });
    }

    createPage() {
        this.pack.className = 'd-f fw-w mi--16 rg-32'
        this.pack.innerHTML = this.review
        this.container.append(this.pack)
        this.nav = this.container?.querySelector('.lang-review--nav')
        this.btnNav = this.nav?.querySelector('button')
    }
}