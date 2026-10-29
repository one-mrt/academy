LG.LKOrderAddReview = class extends LG.Modal
{
    review
    orderId
    serviceId
    description
    editor
    modifierContainer = "lang-edit--container h-100 h-100%-110px w-100 md:mw-720px of-h pn-a t-110 t-100% mr-a ml-a d-f fd-c bxs-au tp-t tnd-500ms bc-w md:btlr-12"
    scores
    score
    reviewScore
    next
    prev
    charLength

    constructor() {
        super()
        
        this.orderId = LG.LKOrderData.id
        this.serviceId = LG.LKOrderData.serviceId

        this.review = document?.querySelector('.review--add')
        this.review?.addEventListener('click', this.add.bind(this))
    }

    initDescription() {
        this.editor = this.content?.querySelector('.review--description')
        this.description = this.editor?.querySelector('.description--input')
        this.charLength = this.editor?.querySelector('.description--length')

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
                            let text = event.target.getText().replace(/[\r\n]+/g, '')
                            this.description.value = text

                            if(text.length > 400) {
                                this.charLength.classList.add('c-c90536')
                            } else {
                                this.charLength.classList.remove('c-c90536')
                            }

                            this.charLength.innerText = (400 - text.length)
                        }
                    },
                    toolbar: [
                        // "bold",
                        // "italic",
                        // "underline",
                    ],
                    floatingToolbar: [
                        // "bold",
                        // "italic",
                        // "underline",
                    ],
                    hideToolbar: true,
                    minHeight: 400,
                    maxHeight: 400,
                    placeholder: 'отзыв',
                    // plugins: edit.getBuiltinPlugins()
                })
                
                editor.renderTo(this.editor)
            });
        }
    }

    nextChecked(score) {
        if(score) {
            if(score.querySelector('.review--score').checked) {
                return true
            } else {
                return this.nextChecked(score.nextElementSibling)
            }
        }

        return false;
    }

    recursivePrevScore() {
        if(this.prev && !this.prev.classList.contains('c-e64231')) {
            this.prev.classList.add('c-e64231')
            this.prev = this.prev.previousElementSibling
            this.recursivePrevScore()
        }
    }

    recursiveNextScore() {
        if(this.next && this.next.classList.contains('c-e64231') && !this.nextChecked(this.next)) {
            this.next.classList.remove('c-e64231')
            this.next = this.next.nextElementSibling
            this.recursiveNextScore()
        }
    }

    initScore() {
        this.reviewScore = this.content?.querySelector('.lang--review')
        this.scores = this.reviewScore?.querySelectorAll('.score--wrap')
        
        this.reviewScore?.addEventListener('mouseleave', this.changeOut.bind(this))

        this.scores?.forEach(score => {
            score._this = this
            score.addEventListener('mouseenter', this.changeScore.bind(score))
        })
    }

    changeScore() {
        this.classList.add('c-e64231')
        this._this.next = this.nextElementSibling
        this._this.prev = this.previousElementSibling
        this._this.recursiveNextScore(this)
        
        if(!this.querySelector('.review--score').checked && !this._this.nextChecked(this)) {
            this._this.recursivePrevScore(this)
        }
    }

    changeOut() {
        let checked = false
        this.scores?.forEach(score => {
            if(score.querySelector('.review--score').checked) {
                checked = score
            }
        })

        if(checked) {
            this.next = checked.nextElementSibling
            this.recursiveNextScore()
        } else {
            this.prev = this.scores[0]?.previousElementSibling
            this.recursivePrevScore()
        }
    }

    add() {
        this.container.className = this.modifierContainer
        this.modifier.show.container = "t-100%"
        this.method = "addOrderReview"
        this.loadData = new FormData()
        this.loadData.append('sessid', BX.bitrix_sessid())
        this.loadData.append('orderId', this.orderId)
        this.loadData.append('serviceId', this.serviceId)

        this.load()
    }

    sendData() {
        this.form = this.content?.querySelector('form')
        this.initScore()
        this.initDescription()

        this.form?.addEventListener("submit", (e) => {
            e.preventDefault()
            this.loaderActive()

            BX.ajax.runAction(`lang:ajax.page.${this.method}`, {data: this.form ? new FormData(this.form) : false})
                .then(this.getData.bind(this), this.error.bind(this))
        })
    }

    getData(data) {
        if(data.status == 'success') {
            if(data.data.componentResult.addSuccess == 'y') {
                setTimeout(this.close.bind(this), 5000)
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000)
            } else {
                this.content.innerHTML = data.data.html
                setTimeout(this.loaderInactive.bind(this), 1000)
                this.sendData()
            }
        }
    }
}