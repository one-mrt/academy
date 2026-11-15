<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Data\LocalStorage\SessionLocalStorage as Sls;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langServiceOrder = "LangServiceOrder{$lang}";
$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;

$session = Application::getInstance()->getSession();
$sessionFilter = $session->get('exFilter');

$typeWorkList = Data::getElementsObject("LangLkTypeWork", ['select' => ['NAME']]);
$specList = Data::getElementsObject("LangLkSpec", ['select' => ['NAME']]);
?>

<div class="w-100 pn-r d-f fd-c rg-32">
    <div class="w-100 d-f fd-c rg-32 pn-r">
        <div class="lang-order--filter w-100 bc-w r-12 p-32 d-f fd-c rg-32">
            <h1 class="<?=comp::get("h2")?>"><?=$image->getName()?></h1>
            <div class="<?=comp::get("row8")?> rg-16 f-14">
                
                <div class="w-100 <?=comp::get('setcol8')?>">
                    <input class="w-100 pby-12 pix-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3" type="text" name="name" value="<?=$sessionFilter['name']?>"
                        placeholder="поиск по имени или фамилии">
                </div>

                <div class="w-100 xl:w-6/12 <?=comp::get('setcol8')?>">
                    <select name="spec" class="w-100 pby-12 pix-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 ap-n">
                        <option value="" <?=(empty($sessionFilter['spec'])) ? "selected" : ""?>>предмет</option>
                        <?foreach($specList as $type):?>
                            <option value="<?=$type->getId()?>" <?=($sessionFilter['spec'] == $type->getId()) ? "selected" : ""?>><?=$type->getName()?></option>
                        <?endforeach?>
                    </select>
                </div>

                <div class="w-100 xl:w-6/12 <?=comp::get('setcol8')?>">
                    <select name="type" class="w-100 pby-12 pix-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 ap-n">
                        <option value="" <?=(empty($sessionFilter['type'])) ? "selected" : ""?>>тип работы</option>
                        <?foreach($typeWorkList as $type):?>
                            <option value="<?=$type->getId()?>" <?=($sessionFilter['type'] == $type->getId()) ? "selected" : ""?>><?=$type->getName()?></option>
                        <?endforeach?>
                    </select>
                </div>

                <?/*
                $listSort = [
                    'new' => [
                        'name' => 'Новые'
                    ],
                    'old' => [
                        'name' => 'Старые'
                    ],
                    'dedd' => [
                        'name' => 'Позже сдавать'
                    ],
                    'deda' => [
                        'name' => 'Раньше сдавать'
                    ],
                    'skua' => [
                        'name' => 'Больше откликов'
                    ],
                    'skud' => [
                        'name' => 'Меньше откликов'
                    ],
                ];

                $modSortInactive = "bc-w h:bc-py h:c-w";
                $modSortActive = "bc-py c-w";
                $sortSelected = !empty($sessionFilter['sort']) ? $sessionFilter['sort'] : 'new';
                $listSort[$sortSelected]['selected'] = 'y';
                ?>

                <div class="w-100 <?=comp::get('setcol8')?>">
                    <div class="d-f cg-16 ws-nw">
                        <div class="pby-8 lh-16px">Показать сначала</div>
                        <div class="filter--sort d-f fd-c pn-r">
                            <div class="sort--close w-100 h-100 pn-f t-0 rt-0 b-0 l-0 z-1 d-n d-f bf-b6"></div>
                            <div class="sort--selected d-f ai-c cg-8 ftw-6 pn-r z-2 cr-p pby-8 pix-16 h:bc-w tn-all-3 lh-16px r-4">
                                <div class="selected--name"><?=$listSort[$sortSelected]['name']?></div>
                                <div class="d-f">
                                    <svg width="13" height="10" viewBox="0 0 13 10" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M3.57914 0.122402C3.53601 0.0836024 3.48478 0.0528191 3.42838 0.0318154C3.37197 0.0108116 3.3115 0 3.25044 0C3.18937 0 3.1289 0.0108116 3.0725 0.0318154C3.0161 0.0528191 2.96486 0.0836024 2.92174 0.122402L0.136152 2.62221C0.0489753 2.70045 0 2.80655 0 2.91719C0 3.02783 0.0489753 3.13394 0.136152 3.21217C0.223328 3.2904 0.341565 3.33435 0.464851 3.33435C0.588137 3.33435 0.706374 3.2904 0.79355 3.21217L2.78617 1.42314V9.58335C2.78617 9.69385 2.83509 9.79983 2.92215 9.87796C3.00922 9.95609 3.12731 9.99999 3.25044 9.99999C3.37357 9.99999 3.49166 9.95609 3.57872 9.87796C3.66579 9.79983 3.7147 9.69385 3.7147 9.58335V1.42314L5.70733 3.21217C5.7945 3.2904 5.91274 3.33435 6.03602 3.33435C6.15931 3.33435 6.27755 3.2904 6.36472 3.21217C6.4519 3.13394 6.50088 3.02783 6.50088 2.91719C6.50088 2.80655 6.4519 2.70045 6.36472 2.62221L3.57914 0.122402ZM9.42794 9.88333C9.51447 9.95818 9.62996 10 9.75014 10C9.87032 10 9.98581 9.95818 10.0723 9.88333L12.8579 7.46685C12.9018 7.42888 12.937 7.38352 12.9613 7.33336C12.9857 7.28321 12.9988 7.22924 12.9999 7.17453C13.001 7.11983 12.9901 7.06546 12.9679 7.01453C12.9456 6.96361 12.9123 6.91712 12.87 6.87773C12.8277 6.83833 12.7771 6.8068 12.7213 6.78494C12.6654 6.76307 12.6052 6.7513 12.5443 6.7503C12.4833 6.74929 12.4227 6.75907 12.366 6.77908C12.3092 6.79908 12.2574 6.82892 12.2135 6.86689L10.2144 8.60093V0.418213C10.2144 0.307715 10.1655 0.201742 10.0784 0.123608C9.99136 0.0454735 9.87327 0.00157801 9.75014 0.00157801C9.62701 0.00157801 9.50892 0.0454735 9.42185 0.123608C9.33479 0.201742 9.28588 0.307715 9.28588 0.418213V8.60093L7.28768 6.86773C7.24402 6.82915 7.19228 6.79871 7.13545 6.77817C7.07862 6.75762 7.01781 6.74738 6.95655 6.74802C6.89528 6.74866 6.83476 6.76018 6.77848 6.78191C6.7222 6.80364 6.67127 6.83516 6.62862 6.87464C6.58597 6.91412 6.55246 6.96078 6.53 7.01194C6.50755 7.0631 6.4966 7.11774 6.49779 7.17272C6.49898 7.22769 6.51229 7.28191 6.53694 7.33225C6.56159 7.38259 6.5971 7.42805 6.64143 7.46601L9.42794 9.88333Z"/></svg>
                                </div>
                            </div>
                            <div class="sort--list d-n d-f fd-c bc-w r-8 pn-a mnw-100 t-0 l-0 bxs-3 pt-32 z-1 of-h">
                                <?foreach($listSort as $key => $sort):
                                    $modItem = ($sort['selected'] == 'y') ? "bc-py c-w" : "bc-w h:bc-py h:c-w";
                                    ?>
                                    <label for="sort<?=$key?>" class="sort--item m-0 cr-p pby-8 pix-16 <?=$modItem?> tn-all-3 <?=($key == 'new') ? 'btw-1 bts-s btc-EFEFEF' : ''?>">
                                        <input id="sort<?=$key?>" type="radio" value="<?=$key?>" name="sort" class="d-n" <?=($sort['selected'] == 'y') ? "checked" : "";?>>
                                        <div class="sort--name ws-nw"><?=$sort['name']?></div>
                                    </label>
                                <?endforeach;?>
                            </div>
                        </div>
                    </div>
                </div>
                */?>

                <div class="w-100 <?=comp::get('setcol8')?> d-f jc-e">
                    <button class="filter--reset <?=comp::get("btnGreenSsm", 'lh-18px', 'lh-1')?>">Сбросить</button>
                </div>
            </div>
        </div>
        <div class="lang-order--items w-100 d-f fd-c rg-32">
            <div class="w-100 d-f fd-c rg-32"><?$APPLICATION->includeComponent('lang:get.experts','',[],false,['HIDE_ICONS' => 'Y'])?></div>
        </div>
    </div>
</div>

<script><?=file_get_contents("{$templatePath}/js/main/util.js")?></script>
<script>
    LG.FilterSort = class extends LG.Util
    {
        sort
        sortCloseBtn
        sortSelected
        sortSelectedName
        sortList
        sortItem
        sortItems
        sortRadio
        sortName

        constructor() {
            super()

            this.sortInit()
        }

        sortInit() {
            this.sort = document.querySelector('.filter--sort')
            this.sortCloseBtn = this.sort?.querySelector('.sort--close')
            this.sortSelected = this.sort?.querySelector('.sort--selected')
            this.selectedName = this.sortSelected?.querySelector('.selected--name')
            this.sortList = this.sort?.querySelector('.sort--list')
            this.sortItems = this.sortList?.querySelectorAll(".sort--item")

            this.sortSelected?.addEventListener('click', this.sortToggle.bind(this))
            this.sortCloseBtn?.addEventListener('click', this.sortClose.bind(this))

            this.sortItems?.forEach(item => {
                item.querySelector('input')
                    ?.addEventListener('input', this.sortSelectedItem.bind(this, item))
            })

            this.receive('onResetFilterOrder', window, this.onResetSortOrder.bind(this))
        }

        onResetSortOrder() {
            this.sortItems.forEach((item, k) => {
                this.sortInactive(item)
                
                if(k == 0) {
                    this.sortItem = item;
                }
            })
            
            this.sortRadio = this.sortItem.querySelector('input')
            this.sortName = this.sortItem.querySelector('.sort--name')
            this.selectedName.innerText = this.sortName.innerText

            this.sortActive()
        }

        sortShow() {
            this.sortCloseBtn.classList.remove('d-n')
            this.sortList.classList.remove('d-n')
        }

        sortClose() {
            this.sortCloseBtn.classList.add('d-n')
            this.sortList.classList.add('d-n')
        }

        sortToggle() {
            if(!this.sortList.classList.contains('d-n')) {
                this.sortClose()
            } else {
                this.sortShow()
            }
        }

        sortActive() {
            this.sortItem.classList.remove('bc-w', 'h:bc-py', 'h:c-w')
            this.sortItem.classList.add('bc-py', 'c-w')
        }

        sortInactive(item) {
            item.classList.add('bc-w', 'h:bc-py', 'h:c-w')
            item.classList.remove('bc-py', 'c-w')
        }

        sortSelectedItem(item) {
            this.sortItems.forEach(i => this.sortInactive(i))
            this.sortItem = item;
            this.sortRadio = this.sortItem.querySelector('input')
            this.sortName = this.sortItem.querySelector('.sort--name')
            this.selectedName.innerText = this.sortName.innerText

            this.sortActive()
            this.sortClose()
        }
    }

    LG.FilterOrder = class extends LG.FilterSort
    {
        filter
        filterIput
        formData = {sessid: BX.bitrix_sessid()}
        input
        resetBtn

        constructor() {
            super()

            this.filter = document.querySelector(".lang-order--filter")
            this.filterIput = this.filter.querySelectorAll('input, select')
            this.resetBtn = this.filter.querySelector('.filter--reset')

            this.initFilter()
            this.getFilterData()
        }

        initFilter() {
            this.filterIput?.forEach(input => input.addEventListener("change", this.applyFilter.bind(this, input)))
            this.resetBtn.addEventListener('click', this.reset.bind(this))
        }

        getFilterData() {
            this.filterIput?.forEach(input => {
                switch(input.tagName) {
                    case 'INPUT':
                        switch(input.type) {
                            case 'radio':
                                if(input.checked)
                                    this.formData[input.name] = input.value
                                break
                            case 'checkbox':
                                break
                            case 'text':
                                this.formData[input.name] = input.value
                                break
                        }
                        break;
                    case 'SELECT':
                        this.formData[input.name] = input.options[input.selectedIndex].value
                        break;
                }
            })
        }

        applyFilter(input) {
            this.input = input
            this.getFilterData()
            this.send('onApplyFilterOrder', window);
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
            this.send('onResetFilterOrder', window)
        }
    }

    LG.OrderPublic = class extends LG.FilterOrder
    {
        container
        orders
        pack
        
        nav
        btnNav
        page = 2

        constructor() {
            super()

            this.container = document.querySelector('.lang-order--items')

            this.initEvent()
            this.initNav()
        }

        initEvent() {
            this.receive('onApplyFilterOrder', window, this.onApplyFilterOrder.bind(this))
            this.receive('onResetFilterOrder', window, this.onResetFilterOrder.bind(this))
            this.receive('onOrderPageLoad', this.container, this.onOrderPageLoad.bind(this))
        }

        rebootContainer() {
            this.page = 1
            this.orders = ''
            this.container.innerHTML = ''
        }

        onApplyFilterOrder() {
            this.rebootContainer()
            this.orderPage()
        }

        onResetFilterOrder() {
            this.rebootContainer()
            this.orderPage()
        }

        onOrderPageLoad() {
            this.removeNav()
            this.createPage()
            this.initNav()
        }

        initNav() {
            this.nav = this.container?.querySelector('.lang-order--nav')
            this.btnNav = this.nav?.querySelector('button')

            this.btnNav?.addEventListener("click", this.orderPage.bind(this))
        }

        removeNav() {
            this.nav?.remove()
            this.btnNav?.remove()
        }

        orderPage() {
            this.pack = document.createElement('div')

            BX.ajax.runAction(`lang:ajax.page.getExperts`, {
                getParameters: this.formData,
                navigation: {page: this.page},
                method: 'GET'
            }).then((data) => {
                if(data.status == 'success') {
                    this.page = data.data.componentResult.next
                    this.orders = data.data.html.replace(/[\r\n\t]/gi, '')
                    this.send('onOrderPageLoad', this.container)
                }
            },
            (response) => {
                console.log(response)
            });
        }

        createPage() {
            this.pack.className = 'w-100 d-f fd-c rg-32'
            this.pack.innerHTML = this.orders
            this.container.append(this.pack)
            this.nav = this.container?.querySelector('.lang-order--nav')
            this.btnNav = this.nav?.querySelector('button')
        }
    }

    new LG.OrderPublic()
</script>