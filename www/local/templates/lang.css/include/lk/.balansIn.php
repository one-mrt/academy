<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$image = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => 248]);
$blockTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT'], ['ID' => 447]);
$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;

// LangPaySystemIn
// $paySystemList = Data
$payList = Data::getTableString("LangPaySystemIn")::query()
    ->setOrder(['SORT' => 'ASC', 'ID' => 'DESC'])
    ->setSelect(['ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'com.VALUE', 'mncom.VALUE', 'mn.VALUE', 'mx.VALUE', 'pays.VALUE'])
    ->where('ACTIVE', 'Y')
    ->setCacheTtl(360000)
    ->cacheJoins(true)
    ->fetchCollection();

$jsonPaySystem = [];
?>


<div class="w-100 xl:w-8/12 <?=comp::get("setcol")?>">
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

            <div class="w-100 d-f fd-c rg-16">
                <?foreach($payList as $pay):
                    $jsonPaySystem[$pay->getId()] = [
                        'com' => $pay->getCom()?->getValue(),
                        'mn' => $pay->getMn()?->getValue(),
                        'mncom' => $pay->getMncom()?->getValue(),
                        'mx' => $pay->getMx()?->getValue(),
                    ];
                    ?>
                    <div class="w-100 d-f">
                        <input class="lang-acount--pay d-n" name="paySystem" type="radio" id="paySystem<?=$pay->getId()?>" value="<?=$pay->getId()?>">
                        <label for="paySystem<?=$pay->getId()?>" class="w-100 m-0 pby-16 pix-24 bc-w bw-1 bs-s bcr-py r-8 h:bc-py h:c-w tn-all-3 cr-p" <?=Data::iblockElementEditAddId($pay)?>>
                            <div class="d-f fw-w mi--16">
                                <div class="w-100 xl:w-6/12 pix-16 fg-0 fs-0 d-f ai-c cg-8">
                                    <div class="d-f">
                                        <?=Data::getImageId($pay->getPreviewPicture(), [
                                            "alt" => strip_tags($pay->getName()),
                                            "fetchpriority" => "high",
                                            "class" => "lang-paysystem--img w-30px h-30px oft-cv op-c"
                                        ])?>
                                    </div>
                                    <div class="lang-paysystem--name lh-1 ftw-5 ls--1"><?=$pay->getName()?></div>
                                </div>
                                <div class="w-100 xl:w-5/12 pix-16 fg-0 fs-0 d-f ai-c">
                                    <div class="lang-paysystem--description f-14 ftw-6 ls--1">
                                        <span><?=$pay->getCom()?->getValue()?>%</span>
                                        <?if(!empty($pay->getMncom()?->getValue()) ?? intval($pay->getMncom()?->getValue()) > 0):?>
                                            <span> (мин. <?=\SaleFormatCurrency($pay->getMncom()->getValue(), $budget['CURRENCY'] ?? CurrencyManager::getBaseCurrency())?>)</span>
                                        <?endif;?>
                                    </div>
                                </div>
                                <div class="w-100 xl:w-1/12 pix-16 fg-0 fs-0 d-f ai-c jc-e">
                                    <div class="d-f">
                                        <svg width="9" height="16" viewBox="0 0 9 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M9 8L0 16V0L9 8Z"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </div>
</div>

<?
$APPLICATION->SetTitle(strip_tags($blockTitle->getName()));
$APPLICATION->SetPageProperty("title", strip_tags($blockTitle->getName()));
$APPLICATION->SetPageProperty("description", strip_tags($blockTitle->getPreviewText()));
// $APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
?>

<div class="lang-edit--wrap w-100 h-100 pn-f t-0 rt-0 b-0 l-0 d-f d-n ai-s jc-e bc-f4eff95e bf-b6 z-3">
    <div class="lang-edit--container w-100 mxh-80 sm:mw-540px mnh-250px of-h pn-r t-0 t--100% mr-a ml-a d-f fd-c bxs-au tp-t tnd-500ms bc-w sm:brr-12 sm:blr-12">

        <div class="lang-edit--content w-100 h-100 pn-r ofx-h ofy-a d-f fd-c fg-1"></div>

        <button class="lang-edit--close pn-a t-32px rt-32px m-0 p-0 d-f bw-0 c-dt h:c-py bc-t tn-all-3">
            <svg width="24" height="24" viewBox="0 0 13 13" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                <line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"></line>
                <line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"></line>
            </svg>
        </button>
        <div class="lang-edit--preloader w-100 h-100 d-f fd-c m-0 z-2 ai-c jc-c pn-a t-0 rt-0 b-0 l-0 ofw-h bc-w">
            <style>@-webkit-keyframes side-panel-loader-rotate{100% {-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@keyframes side-panel-loader-rotate{100%{-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@-webkit-keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}@keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}.loader--animation {-webkit-animation: side-panel-loader-rotate 2s linear infinite;animation: side-panel-loader-rotate 2s linear infinite;-webkit-transform-origin: center center;transform-origin: center center}.loader--path{stroke: #bfc3c8;stroke-width: 1.5;stroke-dasharray: 20, 200;stroke-dashoffset: 0;-webkit-animation: side-panel-loader-dash 1.5s ease-in-out infinite;animation: side-panel-loader-dash 1.5s ease-in-out infinite;stroke-linecap: round}</style>
            <svg class="loader--animation w-100 h-100 mw-150px mxh-150px" viewBox="25 25 50 50"><circle class="loader--path" cx="50" cy="50" r="20" fill="none" stroke-miterlimit="10"></circle></svg>
            <div class="mt-8 f-12 md:f-14">Пожалуйста подождите</div>
        </div>
    </div>
</div>

<style class="d-n|fm"></style>
<script>LG.PaySystemIn = <?=json_encode($jsonPaySystem, JSON_FORCE_OBJECT)?></script>
<script><?=file_get_contents("{$templatePath}/js/main/util.js")?></script>
<script><?=file_get_contents("{$templatePath}/js/main/modal.js")?></script>
<script><?=file_get_contents("{$templatePath}/js/lk/lkBalansIn.js")?></script>
<script>new LG.LKBalansIn()</script>