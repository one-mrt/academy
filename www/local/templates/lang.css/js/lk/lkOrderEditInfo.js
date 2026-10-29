LG.LKEditOrderInfo = class extends LG.Modal
{
    modifierContainer = "lang-edit--container h-100 h-100%-110px w-100 md:mw-720px of-h pn-a t-110 t-200% mr-a ml-a d-f fd-c bxs-au tp-t tnd-500ms bc-w md:btlr-12"
    order
    orderId
    info
    status
    statusSelected
    statusList
    statusClose
    result
    resultDownload
    updateEvents = ['langOrderStatusUpdate', 'langOrderUpdate']

    description
    file
    allEdit
    util

    constructor() {
        super()

        this.orderId = LG.LKOrderData.id
        this.util = new LG.Util()
        this.method = "getOrderEdit"

        this.initOrderElements()
        this.initEvent()
    }

    initEvent() {
        this.util.receive('onLangOrderUpdateAfter', window, this.initOrderElements.bind(this))
    }

    initOrderElements()
    {
        this.order = document.querySelector('.lang-order--detail')
        this.info = this.order?.querySelector('.order--content')
        this.status = this.info?.querySelector('.order--status')
        this.statusSelected = this.status?.querySelector('.status--selected')
        this.statusList = this.status?.querySelector('.status--list')
        this.statusClose = this.status?.querySelector('.lang-status--close')
        this.result = this.info?.querySelector('.result--edit')
        this.resultDownload = this.info?.querySelector('.result--download')
        this.description = this.info?.querySelector('.description--edit')
        this.file = this.info?.querySelector('.file--edit')
        this.allEdit = this.info?.querySelector('.all--edit')

        this.statusSelected?.addEventListener('click', this.initSelectedStatus.bind(this))
        this.result?.addEventListener('click', this.initLoadResult.bind(this))
        this.resultDownload?.addEventListener('click', this.downloadResult.bind(this))
        // this.pay?.addEventListener('click', this.payOrder.bind(this))
        this.description?.addEventListener('click', this.editDescription.bind(this))
        this.file?.addEventListener('click', this.editFile.bind(this))
        this.allEdit?.addEventListener('click', this.editAll.bind(this))

        this.statusList?.querySelectorAll('input')?.forEach(it => {
            it.addEventListener('change', this.setStatusOrder.bind(this, it))
        })

        this.statusClose?.addEventListener('click', this.sClose.bind(this))
    }

    initSelectedStatus() {
        if(this.statusList?.classList.contains('d-n')) {
            this.sShow()
        } else {
            this.sClose()
        }
    }

    sShow() {
        this.statusList?.classList.remove('d-n')
        this.statusClose?.classList.remove('d-n')
        document.body.classList.add('of-h')
    }

    sClose() {
        this.statusList?.classList.add('d-n')
        this.statusClose?.classList.add('d-n')
        document.body.classList.remove('of-h')
    }

    setStatusOrder(input) {
        if(input.checked) {
            this.loadData = new FormData()
            this.loadData.append('sessid', BX.bitrix_sessid())
            this.loadData.append('orderId', this.orderId)
            this.loadData.append('typeEdit', 'status')
            this.loadData.append('edit', 'y');
            this.loadData.append('status', input.value)

            BX.ajax.runAction(`lang:ajax.page.${this.method}`, {data: this.loadData})
                .then((data) => {
                    if(data.data.componentResult.saveSuccess == 'y') {
                        // console.info('Успех', data)
                    } else {
                        // console.info('Неудача', data)
                    }
                }, (response) => {
                    console.info(response)
                });
        }
    }

    initLoadResult() {
        this.container.className = this.modifierContainer
        this.modifier.show.container = "t-200%"
        this.method = "getOrderEdit"
        this.loadData = new FormData()
        this.loadData.append('sessid', BX.bitrix_sessid())
        this.loadData.append('orderId', this.orderId)
        this.loadData.append('typeEdit', 'fileResult')

        this.load()
    }

    downloadResult() {
        console.info('downloadResult')
    }

    editDescription() {
        this.container.className = this.modifierContainer
        this.modifier.show.container = "t-200%"
        this.method = "getOrderEdit"
        this.loadData = new FormData()
        this.loadData.append('sessid', BX.bitrix_sessid())
        this.loadData.append('orderId', this.orderId)
        this.loadData.append('typeEdit', 'description')

        this.load()
    }

    editFile() {
        this.container.className = this.modifierContainer
        this.modifier.show.container = "t-200%"
        this.method = "getOrderEdit"
        this.loadData = new FormData()
        this.loadData.append('sessid', BX.bitrix_sessid())
        this.loadData.append('orderId', this.orderId)
        this.loadData.append('typeEdit', 'file')
        this.load()
    }

    editAll() {
        this.container.className = this.modifierContainer
        this.modifier.show.container = "t-200%"
        this.method = "getOrderEdit"
        this.loadData = new FormData()
        this.loadData.append('sessid', BX.bitrix_sessid())
        this.loadData.append('orderId', this.orderId)
        this.loadData.append('typeEdit', 'all')
        this.load()
    }

    initTextEditor() {
        this.description = this.content.querySelector('.description--input')
        this.editor = this.content.querySelector('.lang-order--description')
        
        if(this.description) {
            BX.Runtime.loadExtension('ui.text-editor').then((exports) => {
                let edit = exports.TextEditor;

                let editor = new exports.TextEditor({
                    content: this.description.value,
                    newLineMode: 'paragraph',
                    events:  {
                        onUpdate: (editor) => {
                            console.info('update')
                        },
                        onChange: (event) => {
                            this.description.value = event.target.getText()
                        }
                    },
                    toolbar: [
                        "bold",
                        "italic",
                        "underline",
                        // "code",
                        "link",
                        "numbered-list",
                        "bulleted-list",
                        // "quote",
                        // "table",
                    ],
                    floatingToolbar: [
                        "bold",
                        "italic",
                        "underline",
                        // "code",
                        "link",
                        "numbered-list",
                        "bulleted-list",
                        // "quote",
                        // "table",
                    ],
                    hideToolbar: false,
                    minHeight: 400,
                    placeholder: 'описание',
                    plugins: edit.getBuiltinPlugins()
                })
                
                editor.renderTo(this.editor)
            });
        }
    }

    initLoaderFile() {
        this.file = this.content?.querySelector('.private--file')
        this.fileView = this.content?.querySelector('.private--view-file')
        this.fullFileSize = this.content?.querySelector('.file--size')

        this.file?.addEventListener('change', this.loaderFile.bind(this))
    }

    getNewFile() {
        let file = document.createElement('div')

        file.icon = document.createElement('div')
        file.name = document.createElement('div'),
        file.size = document.createElement('div')
        // file.close = document.createElement('div')

        file.className = "w-100 pt-16 pr-24 pb-16 pl-24 d-f ai-c jc-b cg-16 r-8 bw-1 bs-s bcr-008000 c-w bc-008000"

        file.icon.className = "d-f c-w"
        file.icon.innerHTML = `<svg width="13" height="12" viewBox="0 0 17 16" fill="currentColor"><path d="M15.358 0.0233339C14.9958 0.0906644 14.6751 0.299546 14.4665 0.604027L6.46105 12.2988L2.45695 7.45892L2.25716 7.26132C2.00782 7.0601 1.69678 6.95152 1.37676 6.95399C1.05673 6.95646 0.747401 7.06982 0.50117 7.27486C0.254939 7.4799 0.0869527 7.76402 0.0256737 8.07907C-0.0356053 8.39412 0.0135904 8.72074 0.164927 9.00357L0.323094 9.23457L5.49818 15.4967C5.63512 15.6623 5.80849 15.7938 6.00467 15.8809C6.20085 15.968 6.4145 16.0083 6.62882 15.9986C6.84314 15.9889 7.05229 15.9294 7.23985 15.825C7.4274 15.7205 7.58824 15.5738 7.70973 15.3965L16.7557 2.18207C16.9644 1.87759 17.0439 1.50245 16.9767 1.13918C16.9096 0.775909 16.7013 0.454263 16.3978 0.245001C16.0942 0.0357393 15.7202 -0.0439965 15.358 0.0233339Z"/></svg>`

        file.name.className = "fg-1 of-h to-e f-14 ftw-5 ws-nw"

        file.size.className = "f-12 c-w"

        // file.close.className = "d-f cr-p h:c-py tn-all-3"
        // file.close.innerHTML = `<svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 9.77072L14.2293 16L16 14.2284L9.77072 8L16 1.77072L14.2284 0L8 6.22836L1.77072 0L0 1.77072L6.22928 8L0 14.2284L1.77072 16L8 9.77072Z"/></svg>`

        file.append(file.icon)
        file.append(file.name)
        file.append(file.size)
        // file.append(file.close)

        return file
    }

    removeFile(file) {

        if(this.file.files.length > 0) {
            for (let k in this.file.files) {
                if(this.file.files[k].name == file.id) {
                    delete this.file.files[k]
                }

            }
        }

        if(this.file.files.length > 0) {
            this.fileView.classList.remove('d-n')
        } else {
            this.fileView.classList.add('d-n')
        }

        file.remove()
    }

    loaderFile() {
        let fullSize = 0
        this.fileView.innerHTML = "";

        if(this.file.files.length > 0) {
            for (let fileData of this.file.files) {
                let file = this.getNewFile()
                let size = Math.trunc((fileData.size / 1024) * 100) / 100;

                fullSize += fileData.size

                // file.setAttribute('id', fileData.name)
                file.setAttribute('title', fileData.name)
                file.name.innerText = fileData.name
                file.size.innerText = `${size} КБ`

                // file.close.addEventListener('click', this.removeFile.bind(this, file))

                this.fileView.append(file)
            }

            fullSize = Math.trunc((fullSize / 1024 / 1024) * 100) / 100;
            this.fullFileSize.innerText = `${fullSize} МБ`

            this.fileView.classList.remove('d-n')
        } else {
            this.fileView.classList.add('d-n')
        }
    }

    successLoad(data) {
        if(data.status == 'success') {
            this.content.innerHTML = data.data.html;
            setTimeout(this.loaderInactive.bind(this), 1000);
            this.initTextEditor()
            this.initLoaderFile()
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
            if(data.data.componentResult.saveSuccess == 'y') {
                setTimeout(this.loaderInactive.bind(this), 1000);
                setTimeout(this.close.bind(this), 500);
            } else {
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000);
                this.initLoaderFile()
                this.initTextEditor()
                this.sendData()
            }
        }
    }
}