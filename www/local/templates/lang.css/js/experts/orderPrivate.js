LG.AddOrder = class extends LG.Dialog
{
    user
    btn
    public
    dataOrder
    method
    description
    isPlag
    plag

    constructor() {
        super()
        this.user = LG?.ExpertData?.userId
        this.btn = document.querySelector('.lang-order--private')
        this.public = document.querySelector('.lang-order--add')
        this.btn?.addEventListener('click', this.initOrder.bind(this))
        this.public?.addEventListener('click', this.initOrderAdd.bind(this))
    }

    updateData() {
        this.method = "addPrivateOrder"
        this.loadData = new FormData()
        this.loadData.append('sessid', BX.bitrix_sessid())
    }

    getDataOrder() {
        this.updateData()
        this.loadData.append('toUser', this.user)
        this.loadData.append('private', 'y')
    }

    initOrder() {
        this.getDataOrder()
        this.load()
    }

    initOrderAdd() {
        this.updateData()
        this.loadData.append('public', 'y')
        this.load()
    }

    successLoad(data) {
        if(data.status == 'success') {
            this.content.innerHTML = data.data.html;
            setTimeout(this.loaderInactive.bind(this), 1000);
            this.setData()
        }
    }

    getData(data) {
        if(data.status == 'success') {
            if(data.data.componentResult.published == 'y') {
                this.close()
                setTimeout(() => {
                    window.location.href = data.data.componentResult.publishedUrl
                }, 1000);
            } else {
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000);

                this.setData()
            }
        }
    }

    setData() {
        this.initLoaderFile()
        this.initTextEditor()
        this.initPlag()
        this.sendData()
    }

    initTextEditor() {
        this.description = document.querySelector('.description--input')

        if(this.description) {
            BX.Runtime.loadExtension('ui.text-editor').then((exports) => {
                var edit = exports.TextEditor;

                var editor = new exports.TextEditor({
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
                    minHeight: 200,
                    placeholder: 'напишите максимально подробное описание работы и требования',
                    plugins: edit.getBuiltinPlugins()
                })
                
                editor.renderTo(this.content.querySelector('.lang-order--description'))
            });
        }
    }

    initLoaderFile() {
        this.file = this.content.querySelector('.private--file')
        this.fileView = this.content.querySelector('.private--view-file')

        this.file.addEventListener('change', this.loaderFile.bind(this))
    }

    initPlag() {
        this.isPlag = this.content.querySelector('.lang--is-plag')
        this.plag = this.content.querySelector('.lang--plag')

        this.isPlag.addEventListener('change', this.plagActive.bind(this))
    }

    plagActive() {
        if(this.isPlag.checked) {
            this.plag.classList.remove('d-n')
        } else {
            this.plag.classList.add('d-n')
        }
    }

    getNewFile() {
        let file = document.createElement('div')

        file.icon = document.createElement('div')
        file.name = document.createElement('div'),
        file.close = document.createElement('div')

        file.className = "w-100 pt-16 pr-24 pb-16 pl-24 d-f ai-c jc-b cg-16 r-8 bw-1 bs-s bcr-008000 c-w bc-008000"

        file.icon.className = "d-f c-w"
        file.icon.innerHTML = `<svg width="13" height="12" viewBox="0 0 17 16" fill="currentColor"><path d="M15.358 0.0233339C14.9958 0.0906644 14.6751 0.299546 14.4665 0.604027L6.46105 12.2988L2.45695 7.45892L2.25716 7.26132C2.00782 7.0601 1.69678 6.95152 1.37676 6.95399C1.05673 6.95646 0.747401 7.06982 0.50117 7.27486C0.254939 7.4799 0.0869527 7.76402 0.0256737 8.07907C-0.0356053 8.39412 0.0135904 8.72074 0.164927 9.00357L0.323094 9.23457L5.49818 15.4967C5.63512 15.6623 5.80849 15.7938 6.00467 15.8809C6.20085 15.968 6.4145 16.0083 6.62882 15.9986C6.84314 15.9889 7.05229 15.9294 7.23985 15.825C7.4274 15.7205 7.58824 15.5738 7.70973 15.3965L16.7557 2.18207C16.9644 1.87759 17.0439 1.50245 16.9767 1.13918C16.9096 0.775909 16.7013 0.454263 16.3978 0.245001C16.0942 0.0357393 15.7202 -0.0439965 15.358 0.0233339Z"/></svg>`

        file.name.className = "fg-1 of-h to-e f-14 ftw-5 ws-nw"

        // file.close.className = "d-f cr-p h:c-py tn-all-3"
        // file.close.innerHTML = `<svg width="12" height="12" viewBox="0 0 16 16" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M8 9.77072L14.2293 16L16 14.2284L9.77072 8L16 1.77072L14.2284 0L8 6.22836L1.77072 0L0 1.77072L6.22928 8L0 14.2284L1.77072 16L8 9.77072Z"/></svg>`

        file.append(file.icon)
        file.append(file.name)
        file.append(file.close)

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
        
        // console.info(this.file)

        file.remove()
    }

    loaderFile() {
        this.fileView.innerHTML = "";

        if(this.file.files.length > 0) {
            for (let fileData of this.file.files) {
                let file = this.getNewFile()

                // file.setAttribute('id', fileData.name)
                file.setAttribute('title', fileData.name)
                file.name.innerText = fileData.name

                // file.close.addEventListener('click', this.removeFile.bind(this, file))

                this.fileView.append(file)
            }

            this.fileView.classList.remove('d-n')
        } else {
            this.fileView.classList.add('d-n')
        }
    }
}