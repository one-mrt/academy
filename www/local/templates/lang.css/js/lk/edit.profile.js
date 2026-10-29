LG.ProfileEdit = class
{
    btnDescription
    btnSpec
    wrapper
    container
    content
    loader
    close
    description
    editor

    constructor() {
        this.btnDescription = document.querySelector('.lang-description--add')
        this.btnSpec = document.querySelector('.lang-spec--edit')
        this.wrapper = document.querySelector('.lang-edit--wrap')
        this.container = this.wrapper.querySelector('.lang-edit--container')
        this.content = this.wrapper.querySelector('.lang-edit--content')
        this.loader = this.wrapper.querySelector('.lang-edit--preloader')
        this.close = this.wrapper.querySelector('.lang-edit--close')

        this.btnDescription?.addEventListener('click', this.initEditDescription.bind(this))
        this.btnSpec?.addEventListener('click', this.initEditSpec.bind(this))
        this.close.addEventListener('click', this.closeEdit.bind(this))
    }

    show() {
        this.wrapper.classList.remove('d-n')
        setTimeout(() => {
            this.container.classList.remove('t-200%')
        }, 100)
        document.body.classList.add('of-h')
    }

    closeEdit() {
        this.wrapper.classList.add('d-n')
        setTimeout(() => {
            this.container.classList.add('t-200%')
            this.content.innerHTML = ""
            document.body.classList.remove('of-h')
        }, 200)
    }

    loaderActive() {
        this.loader?.classList.remove('d-n')
    }

    loaderInactive () {
        this.loader?.classList.add('d-n')
    }

    initEditDescription() {
        this.show()
        this.loaderActive()
        this.loadForm()
    }

    initEditSpec() {
        this.show()
        this.loaderActive()
        this.loadFormSpec()
    }

    loadForm() {
        BX.ajax.runAction('lang:ajax.page.editUserDescription', {}).then((data) => {
            if(data.status == 'success') {
                this.content.innerHTML = data.data.html;
                setTimeout(this.loaderInactive.bind(this), 1000);

                this.initTextEditor()
                this.sendForm()
            }
        },
        (response) => {
            setTimeout(this.loaderInactive.bind(this), 1000);
            console.log(response)
        });
    }

    loadFormSpec() {
        BX.ajax.runAction('lang:ajax.page.editUserSpec', {}).then((data) => {
            if(data.status == 'success') {
                this.content.innerHTML = data.data.html;
                setTimeout(this.loaderInactive.bind(this), 1000);

                this.initTab()
                this.sendFormSpec()
            }
        },
        (response) => {
            setTimeout(this.loaderInactive.bind(this), 1000);
            console.log(response)
        });
    }

    sendForm() {
        this.form = this.content.querySelector('form')

        this.form.addEventListener("submit", (e) => {
            e.preventDefault()
            this.loaderActive()

            BX.ajax.runAction('lang:ajax.page.editUserDescription', {
                data: new FormData(this.form)
            }).then((data) => {
                if(data.status == 'success') {
                    if(data.data.componentResult.DATA_SAVED == 'Y') {
                        window.location.href = window.location.href
                    } else {
                        this.content.innerHTML = data.data.html
                        setTimeout(this.loaderInactive.bind(this), 1000);

                        this.initTextEditor()
                        this.sendForm()
                    }
                }
            }, (response) => {
                setTimeout(this.loaderInactive.bind(this), 1000);
                console.log(response)
            });
        })
    }

    sendFormSpec() {
        this.form = this.content.querySelector('form')

        this.form.addEventListener("submit", (e) => {
            e.preventDefault()
            this.loaderActive()

            BX.ajax.runAction('lang:ajax.page.editUserSpec', {
                data: new FormData(this.form)
            }).then((data) => {
                if(data.status == 'success') {
                    if(data.data.componentResult.DATA_SAVED == 'Y') {
                        window.location.href = window.location.href
                    } else {
                        this.content.innerHTML = data.data.html
                        setTimeout(this.loaderInactive.bind(this), 1000);

                        this.initTab()
                        this.sendFormSpec()
                    }
                }
            }, (response) => {
                setTimeout(this.loaderInactive.bind(this), 1000);
                console.log(response)
            });
        })
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
                    placeholder: 'напишите о себе',
                    plugins: edit.getBuiltinPlugins()
                })
                
                editor.renderTo(this.editor)
            });
        }
    }

    initTab()
    {
        LG.loadJS('/local/templates/lang.css/js/tabbis.es5.min.js', () => {
			tabbis({
				tabGroup: '[data-tab-categoryspec]',
				paneGroup: '[data-pan-categoryspec]',
				prefix: 'spec',
			});
		});

        document.addEventListener("tabbis", this.tabActive.bind(this), false)
    }

    tabActive(eTab) {
        this.content.querySelectorAll('[data-tab-categoryspec] > div').forEach((el) => {
            el.classList.add("bc-F4F4FD", 'c-dt', 'h:bi-st', 'h:c-w');
            el.classList.remove("bi-st", 'c-w', 'h:bc-F4F4FD', 'h:c-dt');
        });

        eTab.detail.tab.classList.add("bi-st", 'c-w', 'h:bc-F4F4FD', 'h:c-dt');
    }
}

new LG.ProfileEdit()