<?
use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Catalog\PriceTable;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Main\ORM\Query\Join;
use Lang\Css\Component as comp;
use Lang\Academy\Util as Academy;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Fields\Relations\Reference;

Loader::includeModule("sale");

$parser = Academy::getParser();

$lang = Data::getLang();
$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;
$langText = "LangText{$lang}";
$langServiceOrder = "LangServiceOrder";
$langOrderOffer = "LangOrderOffer";
$order = Data::getTableString($langServiceOrder)::getList([
    'select' => [
        'NAME', 'CODE', 'PREVIEW_TEXT', 'IBLOCK_SECTION_ID', 'DATE_CREATE', 'SHOW_COUNTER', 'CREATED_BY',
        'order.VALUE', 'sSpec.SECTION', 'pred.ELEMENT', 'tWork.ELEMENT', 'ded.VALUE', 'anti.VALUE', 'file.VALUE', 'order.VALUE', 'chat.VALUE', 'uex.VALUE',
        'STATUS.STATUS_ID', 'PRICE.PRICE', 'PRICE.CURRENCY', 'S_ID.NAME'
    ],
    'filter' => ['ID' => $arParams['order']],
    'runtime' => [
        (new Reference('STATUS', Order::class, Join::on('this.order.VALUE', 'ref.ID')))->configureJoinType('left'),
        (new Reference('PRICE', PriceTable::class, Join::on('this.ID','ref.PRODUCT_ID')))->configureJoinType(Join::TYPE_LEFT),
        (new Reference('S_ID', StatusLangTable::class, Join::on('this.STATUS.STATUS_ID', 'ref.STATUS_ID')->where("ref.LID", "=", 'ru')))->configureJoinType(Join::TYPE_LEFT)

    ],
    'cache' => Data::getCache()
])->fetchObject();

$user = UserTable::getList([
    'select' => ['ID', 'NAME', 'LAST_NAME', 'LOGIN', 'LAST_ACTIVITY_DATE', 'IS_ONLINE', 'PERSONAL_PHOTO'],
    'filter' => ['ID' => $order->getCreatedBy()],
])->fetchObject();

$currentUser = CurrentUser::get();
$modTableCol = "w-100 md:w-6/12";
$offerResult = [];
?>

<div class="w-100 d-f fd-c rg-32">
    <div class="w-100 d-f fd-c rg-32 p-16 sm:p-32 r-12 bc-w">
        <div class="w-100">
            <h1 class="<?=comp::get("h2")?>"><?=$APPLICATION->ShowTitle(false)?></h1>
        </div>
        <div class="w-100 d-f ai-c cg-16">
            <?if(!empty($user->getPersonalPhoto())):?>
                <?=Data::getImageId($user->getPersonalPhoto(), [
                    "alt" => $user->getName(),
                    "fetchpriority" => "high",
                    "class" => "w-60px h-60px xl:w-90px xl:h-90px r-100 oft-cv op-c",
                ])?>
            <?else:?>
                <div class="w-60px h-60px xl:w-90px xl:h-90px bc-w r-100"></div>
            <?endif?>

            <div class="d-f fd-c rg-24 fg-1">
                <div class="d-f fd-c rg-8 fg-1">
                    <a href="/experts/<?=$user->getLogin()?>/" target="_blank" class="d-f fd-c bi-st bgc-t tf-t ff-ud xl:f-20 ftw-6 ls--1 lh-1.125 tdt-1 tuo-4 tdc-py h:tdl-u"><?=$user->getName()?> <?=$user->getLastName()?></a>
                    <div class="ftw-5 c-636363 f-14"><?=(!empty($user->getIsOnline())) ? 'Онлайн' : 'Офлайн'?></div>
                </div>
            </div>

            <div class="d-f jc-s">
                <button class="<?=(!empty($currentUser->getId())) ? 'lang-user--messenger' : 'lang-auth--button'?> <?=comp::get("btnLkLg")?>">Написать</button>
            </div>
        </div>
    </div>

    <div class="w-100 w-100 d-f fd-c rg-16">
        <div class="w-100 bc-w sm:p-16 r-12 f-14">
            <div class="<?=comp::get("row")?>">

                <div class="<?=$modTableCol?> <?=comp::get('setcol')?>">
                    <div class="w-100 d-f bbw-1 bbs-s bbc-E5E5E5">
                        <div class="w-40 ftw-6 pby-8 pix-16 brw-1 brs-s brc-E5E5E5">Заказ</div>
                        <div class="w-60 pby-8 pix-16"><?=$order->getOrder()?->getValue()?></div>
                    </div>
                </div>

                <div class="<?=$modTableCol?> <?=comp::get('setcol')?>">
                    <div class="w-100 d-f bbw-1 bbs-s bbc-E5E5E5">
                        <div class="w-40 ftw-6 pby-8 pix-16 brw-1 brs-s brc-E5E5E5 ws-nw">Срок сдачи</div>
                        <div class="w-60 pby-8 pix-16"><?=FormatDate('j F Y', strtotime($order->getDed()->getValue()))?></div>
                    </div>
                </div>

                <div class="<?=$modTableCol?> <?=comp::get('setcol')?>">
                    <div class="w-100 d-f bbw-1 bbs-s bbc-E5E5E5">
                        <div class="w-40 ftw-6 pby-8 pix-16 brw-1 brs-s brc-E5E5E5 ws-nw">Раздел</div>
                        <div class="w-60 pby-8 pix-16"><?=$order->getSspec()->getSection()->getName()?></div>
                    </div>
                </div>

                <div class="<?=$modTableCol?> <?=comp::get('setcol')?>">
                    <div class="w-100 d-f bbw-1 bbs-s bbc-E5E5E5">
                        <div class="w-40 ftw-6 pby-8 pix-16 brw-1 brs-s brc-E5E5E5 ws-nw">Цена</div>
                        <div class="w-60 pby-8 pix-16"><?=(intval($order->get("PRICE")?->getPrice()) > 0) ? \SaleFormatCurrency($order->get("PRICE")?->getPrice(), $order->get("PRICE")?->getCurrency()) : 'Договорная'?></div>
                    </div>
                </div>

                <div class="<?=$modTableCol?> <?=comp::get('setcol')?>">
                    <div class="w-100 d-f bbw-1 bbs-s bbc-E5E5E5">
                        <div class="w-40 ftw-6 pby-8 pix-16 brw-1 brs-s brc-E5E5E5 ws-nw">Тип работы</div>
                        <div class="w-60 pby-8 pix-16"><?=$order?->getTwork()?->getElement()?->getName()?></div>
                    </div>
                </div>

                <div class="<?=$modTableCol?> <?=comp::get('setcol')?>">
                    <div class="w-100 d-f bbw-1 bbs-s bbc-E5E5E5">
                        <div class="w-40 ftw-6 pby-8 pix-16 brw-1 brs-s brc-E5E5E5 ws-nw">Размещен</div>
                        <div class="w-60 pby-8 pix-16"><?=FormatDate('j F Y', strtotime($order->getDateCreate()))?></div>
                    </div>
                </div>

                <div class="<?=$modTableCol?> <?=comp::get('setcol')?>">
                    <div class="w-100 d-f bbw-1 bbs-s bbc-E5E5E5 md:bbw-0">
                        <div class="w-40 ftw-6 pby-8 pix-16 brw-1 brs-s brc-E5E5E5 ws-nw">Антиплагиат</div>
                        <div class="w-60 pby-8 pix-16"><?=(!empty($order?->getAnti())) ? $order?->getAnti()?->getValue() : 'Нет'?></div>
                    </div>
                </div>

                <div class="<?=$modTableCol?> <?=comp::get('setcol')?>">
                    <div class="w-100 d-f">
                        <div class="w-40 ftw-6 pby-8 pix-16 brw-1 brs-s brc-E5E5E5 ws-nw">Просмотров</div>
                        <div class="w-60 pby-8 pix-16"><?=$order?->getShowCounter()?></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?if(!empty($order->getPreviewText())):?>
        <div class="w-100 d-f fd-c rg-16 p-24 xl:p-32 bc-w r-12">
            <h2 class="<?=comp::get("ud-f20")?>">Описание работы</h2>
            <div class="f-14 c-636363 mb-0|lc:p"><?=$parser->convertText($order->getPreviewText())?></div>
        </div>
    <?endif;?>

    <?if(!empty($currentUser->getId())):?>
        <?if(!empty($order->getFile()?->getAll())):?>
            <div class="w-100 d-f fd-c rg-16 p-24 xl:p-32 bc-w r-12">
                <h2 class="<?=comp::get("ud-f20")?>">Файлы</h2>
                <div class="w-100">
                    <div class="d-f fw-w mi--4 rg-16">
                        <?
                        $fileType = Academy::getAcceptFileIcon();
                        foreach($order->getFile()->getAll() as $iFile):
                            $file = \CFile::GetFileArray($iFile->getValue());
                            $svg = $fileType[$file['CONTENT_TYPE']];
                            ?>
                            <div class="w-4/12 sm:w-3/12 md:w-2/12 lg:w-3/12 xxl:w-2/12 pix-8 fg-0 fs-0">
                                <a href="<?=$file['SRC']?>" class="w-100 h-100 pix-12 pby-16 d-f fd-c ai-c rg-8 bw-1 bs-s bcr-f5f6f6 r-8 bc-w c-dt h:bc-py h:c-w tn-all-3" title="<?=$file['ORIGINAL_NAME']?>" target="_blank">
                                    <div class="d-f w-a>svg h-48px>svg se-cc>svg se-cc|pa"><?=$svg?></div>
                                    <div class="w-100 f-10 of-h ws-nw of-h to-e"><?=$file['ORIGINAL_NAME']?></div>
                                </a>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
            </div>
        <?endif;?>
    
        <div class="lang-offer--content w-100">
            <?$offerResult = $APPLICATION->IncludeComponent(
                "lang:order.offer", "",
                [
                    'customer' => $user->getId(),
                    'orderId' => $order->getOrder()->getValue(),
                    'serviceId' => $order->getId(),
                ],
                false,["HIDE_ICONS"=>"Y"]
            );?>
        </div>
    <?else:
        $formEl = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'link'], ['ID' => 397]);
        $list = Data::getElementsObject("LangLkTypeWork", ['select' => ['NAME']]);
        ?>
        <div class="w-100 pn-r of-h r-12">
            <?
            if(!empty($formEl) && !empty($formEl->getPreviewPicture())) {
                echo Data::getImageId($formEl->getPreviewPicture(), [
                    "alt" => strip_tags($formEl->getName()),
                    "fetchpriority" => "high",
                    "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
                ]);
            }
            ?>
            <div class="w-100 d-f fd-c p-32 md:p-32 pn-r rg-24 xl:rg-32">

                <div class="w-100 d-f fd-c rg-16">
                    <div class="<?=comp::get("h2page")?>"><?=$formEl->getName()?></div>
                    <?if(!empty($formEl->getPreviewText())):?>
                        <div class="f-12 xl:f-14"><?=$formEl->getPreviewText()?></div>
                    <?endif;?>
                </div>

                <div class="w-100 d-f fd-c rg-16">
                    <div class="w-100 d-f fd-c">
                        <input placeholder="Тема работы" value="" class="c-dt ap-n w-100 pt-16 pr-32 pb-16 pl-32 bc-w bw-1 bs-s bcr-C5C5C5 h:bcr-py r-12 f-16 tn-all-3"
                            id="theme" type="text" name="theme" required="">
                    </div>
                    <div class="w-100">
                        <div class="<?=comp::get("row")?> rg-16">
                            <div class="w-100 lg:w-6/12 xl:w-6/12 <?=comp::get("setcol")?>">
                                <div class="w-100 pn-r h:c-py h:bcr-py">
                                    <select name="type" class="w-100 pt-16 pr-48 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 ap-n" placeholder="Тип работы" required>
                                        <option value="">Тип работы</option>
                                        <?foreach($list as $type):?>
                                            <option value="<?=$type->getId()?>"><?=$type->getName()?></option>
                                        <?endforeach?>
                                    </select>
                                </div>
                            </div>
                            <div class="w-100 lg:w-6/12 xl:w-6/12 <?=comp::get("setcol")?>">
                                <input placeholder="Ваш email" value="" class="c-dt ap-n w-100 pt-16 pr-32 pb-16 pl-32 bc-w bw-1 bs-s bcr-C5C5C5 h:bcr-py r-12 f-16 tn-all-3" id="email" type="text" name="email" required="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-100 d-f jc-e">
                    <button class="<?=comp::get("btnGreenSm")?>"><?=$formEl->getLink()->getDescription()?></button>
                </div>
            </div>
        </div>
    <?endif?>
</div>

<?if(!empty($currentUser->getId())):?>
    <div class="lang-offer--wrap w-100 h-100 pn-f t-0 rt-0 b-0 l-0 d-f d-n ai-s jc-e bc-f4eff95e bf-b6 z-3">
        <div class="lang-offer--container w-100 sm:mw-540px mnh-250px of-h pn-r t-0 t--100% mr-a ml-a d-f fd-c bxs-au tp-t tnd-500ms bc-w sm:brr-12 sm:blr-12">

            <div class="lang-offer--content w-100 h-100 ofx-h ofy-a pn-r p-32 md:p-32 d-f ai-c jc-c fg-1"></div>

            <button class="lang-offer--close pn-a t-32px rt-32px m-0 p-0 z-1 d-f bw-0 c-dt h:c-py bc-t tn-all-3">
                <svg width="24" height="24" viewBox="0 0 13 13" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"></line>
                    <line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"></line>
                </svg>
            </button>
            <div class="lang-offer--preloader w-100 h-100 d-f fd-c m-0 z-2 ai-c jc-c pn-a t-0 rt-0 b-0 l-0 ofw-h bc-w">
                <style>@-webkit-keyframes side-panel-loader-rotate{100% {-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@keyframes side-panel-loader-rotate{100%{-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@-webkit-keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}@keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}.loader--animation {-webkit-animation: side-panel-loader-rotate 2s linear infinite;animation: side-panel-loader-rotate 2s linear infinite;-webkit-transform-origin: center center;transform-origin: center center}.loader--path{stroke: #bfc3c8;stroke-width: 1.5;stroke-dasharray: 20, 200;stroke-dashoffset: 0;-webkit-animation: side-panel-loader-dash 1.5s ease-in-out infinite;animation: side-panel-loader-dash 1.5s ease-in-out infinite;stroke-linecap: round}</style>
                <svg class="loader--animation w-100 h-100 mw-150px mxh-150px" viewBox="25 25 50 50"><circle class="loader--path" cx="50" cy="50" r="20" fill="none" stroke-miterlimit="10"></circle></svg>
                <div class="mt-8 f-12 md:f-14">Пожалуйста подождите</div>
            </div>
        </div>
    </div>

    <?
    $param = [
        'customer' => $user->getId(),
        'orderId' => $order->getOrder()->getValue(),
        'serviceId' => $order->getId(),
        'offer' => $offerResult['offer'] ?? false
    ];
    ?>

    <script>LG.OrderOfferData = <?=json_encode($param)?></script>
    <script>
        LG.MessageUser = class {
            btn
            user

            constructor() {
                this.user = LG.OrderOfferData.customer
                this.btn = document.querySelector('.lang-user--messenger')
                this.btn?.addEventListener('click', this.open.bind(this))
            }

            open() {
                BXIM.openMessenger(this.user)
            }
        }
        new LG.MessageUser()
    </script>
    <script><?=file_get_contents("{$templatePath}/js/order/orderOfferAdd.js")?></script>
<?endif;?>

<?
$seo = Data::getSeoPropertyElement($langServiceOrder, $order->getId());

$APPLICATION->SetTitle(strip_tags($seo['ELEMENT_PAGE_TITLE']));
$APPLICATION->SetPageProperty("title", strip_tags($seo['ELEMENT_META_TITLE']));
$APPLICATION->SetPageProperty("description", strip_tags($seo['ELEMENT_META_DESCRIPTION']));
$APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
$APPLICATION->AddChainItem(strip_tags($seo['ELEMENT_PAGE_TITLE']), Data::getDetailPageUrlApiCode($langServiceOrder, $order));
\CIBlockElement::CounterInc($order->getId());
?>