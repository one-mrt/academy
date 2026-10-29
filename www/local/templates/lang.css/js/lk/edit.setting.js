LG.SettingEdit = class extends LG.Modal
{
    btnImage
    btnPassword
    btnNotify
    method
    form

    constructor() {
        super()

        this.btnImage = document.querySelector('.lang-image--edit')
        this.btnPassword = document.querySelector('.lang-password--edit')
        this.btnNotify = document.querySelector('.lang-notify--edit')

        this.btnImage?.addEventListener('click', this.initEditImage.bind(this))
        this.btnPassword?.addEventListener('click', this.initEditPassword.bind(this))
        this.btnNotify?.addEventListener('click', this.initNofity.bind(this))
    }

    initEditImage() {
        this.method = "editUserImage"
        this.show()
        this.loaderActive()
        this.loadAction()
    }

    initEditPassword() {
        this.method = "editUserPassword"
        this.show()
        this.loaderActive()
        this.loadAction()
    }

    initNofity() {
        this.method = "editLkSettingNotify"
        this.show()
        this.loaderActive()
        this.loadAction()
    }
}

new LG.SettingEdit()