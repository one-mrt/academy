<?
use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Web\Uri;
use Bitrix\Main\UserTable;
// use Bitrix\Catalog\Model\Price;
use Bitrix\Catalog\PriceTable as Price;
use Bitrix\Main\ORM\Query\Join;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Sale\Internals\StatusTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\Application;

Loader::includeModule('sale');
Loader::includeModule('catalog');

$user = CurrentUser::get();
$lang = Data::getLang();
$langServiceOrder = "LangServiceOrder{$lang}";
$langText = "LangText{$lang}";
$request = Context::getCurrent()->getRequest();
$uri = new Uri($request->getRequestUri());

$sessionFilter = Application::getInstance()->getLocalSession('lkOrderFilter');
// $sessionFilter = $session->get('lkOrderFilter');

$image = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => 248]);
$blockTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT'], ['ID' => 450]);
$typeWorkList = Data::getElementsObject("LangLkTypeWork", ['select' => ['NAME']]);
$specList = Data::getElementsObject("LangLkSpec", ['select' => ['NAME']]);

$name = (!empty($blockTitle)) ? $blockTitle->getName() : "Заказы";
$title = (!empty($blockTitle)) ? $blockTitle->getName() : "Заказы";


$statusList = StatusLangTable::query()
    ->setOrder(['TP.SORT' => 'ASC', 'TP.ID' => 'DESC'])
    ->setSelect(['NAME', 'STATUS_ID'])
    ->where('LID', SITE_ID)
    ->where('TP.TYPE', "O")
    ->registerRuntimeField((new Reference('TP', StatusTable::class, Join::on('this.STATUS_ID', 'ref.ID')))->configureJoinType(Join::TYPE_LEFT))
    ->setCacheTtl(60*60*24*7)
    ->cacheJoins(true)
    ->fetchCollection();

$menuLink = [
    'work' => [
        'name' => 'Мои работы',
        'link' => '/lk/order/',
        'func' => '',
        'active' => ($sessionFilter['tab'] == 'work' || empty($sessionFilter['tab'])) ? 'y' : 'n'
    ],
    'buy' => [
        'name' => 'Мои покупки',
        'link' => '/lk/order/buy/',
        'func' => "orderPage(1, 'getOrderBuy')",
        'active' => ($sessionFilter['tab'] == 'buy') ? 'y' : 'n'
    ]
];
?>

<div class="w-100 xl:w-8/12 pr-16 pl-16 fg-0 fs-0">
    <div class="w-100 h-100 pn-r of-h p-32 bc-w r-24">
        <?if(!empty($image) && !empty($image->getPreviewPicture())) {
            echo Data::getImageId($image->getPreviewPicture(), [
                "alt" => strip_tags($image->getName()),
                "fetchpriority" => "high",
                "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
            ]);
        }?>

        <div class="w-100 d-f fd-c rg-32 pn-r">

            <div class="w-100 d-f fd-c rg-16" <?=Data::iblockElementEditAddId($blockTitle)?>>
                <h1 class="<?=comp::get('h2')?>"><?=$APPLICATION->ShowTitle(false)?></h1>
                <?if(!empty($blockTitle->getPreviewText())):?>
                    <div class="w-100"><?=$blockTitle->getPreviewText()?></div>
                <?endif;?>
            </div>

            <div class="w-100 d-f cg-32">
                <?foreach($menuLink as $type => $item):?>
                    <?
                        $modSelected = " tdt-2 tuo-4 tdc-py tn-all-3";

                        if($item['active'] == 'y') {
                            $modSelected .= ' c-py tdl-u';
                        } else {
                            $modSelected .= ' h:c-py h:tdl-u';
                        }
                    ?>
                    <button class="lang-order--tab m-0 p-0 bc-t bw-0 xl:f-18 ftw-6 tt-l <?=$modSelected?>" <?=$type?><?=($item['active'] == 'y') ? '="active"' : ''?>><?=$item['name']?></button>
                <?endforeach;?>
            </div>

            <div class="lang-order--filter w-100">
                <div class="<?=comp::get("row8")?> rg-16 f-14">
                    <div class="w-100 xl:w-7/12 <?=comp::get('setcol8')?>">
                        <input class="w-100 pby-12 pix-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3" type="text" name="name" placeholder="поиск по ключевым словам" value="<?=$sessionFilter['name']?>">
                    </div>
                    <div class="w-100 xl:w-5/12 <?=comp::get('setcol8')?>">
                        <select name="status" class="w-100 pby-12 pix-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 ap-n">
                            <option value="" <?=(empty($sessionFilter['status'])) ? "selected" : ""?>>статус заказа</option>
                            <?foreach($statusList as $status):?>
                                <option value="<?=$status->getStatusId()?>" <?=($sessionFilter['status'] == $status->getStatusId()) ? "selected" : ""?>><?=$status->getName()?></option>
                            <?endforeach;?>
                        </select>
                    </div>

                    <div class="w-100 xl:w-5/12 <?=comp::get('setcol8')?>">
                        <select name="spec" class="w-100 pby-12 pix-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 ap-n">
                            <option value="" <?=(empty($sessionFilter['spec'])) ? "selected" : ""?>>предмет</option>
                            <?foreach($specList as $type):?>
                                <option value="<?=$type->getId()?>" <?=($sessionFilter['spec'] == $type->getId()) ? "selected" : ""?>><?=$type->getName()?></option>
                            <?endforeach?>
                        </select>
                    </div>

                    <div class="w-100 xl:w-a <?=comp::get('setcol8', 'fg-1', 'fg-0')?>">
                        <select name="type" class="w-100 pby-12 pix-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 ap-n">
                            <option value="" <?=(empty($sessionFilter['type'])) ? "selected" : ""?>>тип работы</option>
                            <?foreach($typeWorkList as $type):?>
                                <option value="<?=$type->getId()?>" <?=($sessionFilter['type'] == $type->getId()) ? "selected" : ""?>><?=$type->getName()?></option>
                            <?endforeach?>
                        </select>
                    </div>

                    <div class="w-100 xl:w-a <?=comp::get('setcol8')?>">
                        <button class="filter--reset <?=comp::get("btnGreenSsm", 'lh-18px', 'lh-1')?>">Сбросить</button>
                    </div>
                </div>
            </div>
            <div class="lang-order--items w-100 d-f fd-c rg-32">
                <div class="w-100 d-f fd-c rg-32"><?$APPLICATION->includeComponent('lang:get.order', '', ['type' => 'work'])?></div>
            </div>
        </div>
    </div>
</div>

<script>
    LG.Util = class
    {
        constructor() {

        }

        receive(event, el, handler) {
            if(window.addEventListener) el.addEventListener(event, handler, false);
        }

        send(event, obj) {
            if (document.createEvent) {
                var e = document.createEvent("Events");
                e.initEvent(event, true, false);
            } else if (document.createEventObject()) {
                var e = document.createEventObject();
            } else
                return;

            if (obj.dispatchEvent) obj.dispatchEvent(e);
            else if (obj.fireEvent) obj.fireEvent(event, e);
        }
    }

    LG.FilterOrder = class extends LG.Util
    {
        filter
        filterIput
        formData = {}
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

    LG.OrderLk = class extends LG.FilterOrder
    {
        container
        tab
        tabs
        tabCurrent
        tabActive = "work"
       
        method = "getOrderLk"
        orders
        pack

        nav
        btnNav
        page = 2

        constructor() {
            super()

            this.tabs = document.querySelectorAll('.lang-order--tab')
            this.container = document.querySelector('.lang-order--items')

            this.initEvent()
            this.initTab()
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

        initNav() {
            this.nav = this.container?.querySelector('.lang-order--nav')
            this.btnNav = this.nav?.querySelector('button')

            this.btnNav?.addEventListener("click", this.orderPage.bind(this))
        }

        removeNav() {
            this.nav?.remove()
            this.btnNav?.remove()
        }

        loadOrderTab(tab) {
            this.tab = tab

            this.inactive()
            this.active()
            this.rebootContainer()
            this.clearFilter();
            this.orderPage()
        }

        initTab() {
            this.tabs?.forEach(tab => {
                
                if(tab.getAttribute("work") == 'active') {
                    this.tabActive = 'work'
                    this.method = "getOrderLk"
                } else if(tab.getAttribute("buy") == 'active') {
                    this.tabActive = 'buy'
                    this.method = "getOrderBuy"
                }

                tab.addEventListener('click', this.loadOrderTab.bind(this, tab))
            })

            this.formData.tab = this.tabActive
        }

        onOrderPageLoad() {
            this.removeNav()
            this.createPage()
            this.initNav()
        }

        inactive() {
            this.tabs?.forEach(tab => {
                if(this.tab != tab) {
                    tab.classList.remove('c-py', 'tdl-u');
                    tab.classList.add('h:c-py', 'h:tdl-u');
                    
                    if(tab.hasAttribute('work')) {
                        tab.setAttribute('work', '')
                    } else if(tab.hasAttribute('buy')) {
                        tab.setAttribute('buy', '')
                    }
                }
            })
        }

        active() {
            this.tab?.classList.add('c-py', 'tdl-u');
            this.tab?.classList.remove('h:c-py', 'h:tdl-u');

            if(this.tab.hasAttribute('work')) {
                this.tab.setAttribute('work', 'active')
                this.tabActive = "work"
                this.method = "getOrderLk"
            } else if(this.tab.hasAttribute('buy')) {
                this.tab.setAttribute('buy', 'active')
                this.tabActive = "buy"
                this.method = "getOrderBuy"
            }

            this.formData.tab = this.tabActive
        }

        orderPage() {
            this.pack = document.createElement('div')

            BX.ajax.runAction(`lang:ajax.page.${this.method}`, {
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

    new LG.OrderLk()
</script>

<?
$APPLICATION->SetTitle(strip_tags($title));
$APPLICATION->SetPageProperty("title", strip_tags($name));
$APPLICATION->SetPageProperty("description", strip_tags($blockTitle->getPreviewText()));
// $APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
?>