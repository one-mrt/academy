LG.Filter = class extends LG.Util
{
    filter
    filterIput
    formData = {}
    input
    resetBtn

    constructor() {
        super()

        this.filter = document.querySelector(".lang--filter")
        this.filterIput = this.filter.querySelectorAll('input, select')
        this.resetBtn = this.filter?.querySelector('.filter--reset')

        this.initFilter()
        this.getFilterData()
    }

    initFilter() {
        this.filterIput?.forEach(input => input.addEventListener("change", this.applyFilter.bind(this, input)))
        this.resetBtn?.addEventListener('click', this.reset.bind(this))
    }

    getFilterData() {
        this.filterIput?.forEach(input => {
            switch(input.tagName) {
                case 'INPUT':
                    switch(input.type) {
                        case 'radio':
                            if(input.checked) {
                                this.formData[input.name] = input.value
                            } else {
                                this.formData[input.name] = ''
                            }
                            break
                        case 'checkbox':
                            if(input.checked) {
                                this.formData[input.name] = input.value
                            } else {
                                this.formData[input.name] = ''
                            }
                            break
                        case 'text':
                        case 'search':
                            this.formData[input.name] = input.value
                        case 'date':
                            this.formData[input.name] = input.value
                            break
                    }
                    break;
                case 'SELECT':
                    this.formData[input.name] = input.options[input.selectedIndex].value
                    break;
            }
        })

        console.info(this.formData, 'text')
    }

    applyFilter(input) {
        this.input = input
        this.getFilterData()
        this.send('onApplyFilter', window);
    }

    clearFilter() {
        this.filterIput?.forEach(input => {
            switch(input.tagName) {
                case 'INPUT':
                    switch(input.type) {
                        case 'radio':
                            input.checked = false
                            break
                        case 'checkbox':
                            input.checked = false
                            break
                        case 'text':
                            input.value = ""
                            break
                    }
                    break;
                case 'SELECT':
                    for(let k = 0; k < input.options.length; k++)
                        input.options[k].selected = false;
                    input.options[0].selected = true
                    break;
            }

            this.formData[input.name] = '';
        })
    }

    reset() {
        this.clearFilter()
        this.send('onResetFilter', window)
    }
}