<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Currency\CurrencyManager;

$currentUser = CurrentUser::get();
$budgetOut = 0;
$budgetData = CSaleUserAccount::GetList(
    ["CURRENCY" => "ASC"],
    ["USER_ID" => intval($currentUser->getId())],
    false,
    false,
    ["ID", "CURRENT_BUDGET", 'CURRENCY']
);

if(!empty($budget = $budgetData->Fetch())) {
    $budgetOut = $budget["CURRENT_BUDGET"];
}

$budgetOut = \SaleFormatCurrency($budgetOut, $budget['CURRENCY'] ?? CurrencyManager::getBaseCurrency());

$image = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => 248]);
$blockTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT'], ['ID' => 448]);
$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;
$jsonPaySystem = [];

$payList = Data::getTableString("LangBalansPaySystem")::query()
    ->setOrder(['SORT' => 'ASC', 'ID' => 'DESC'])
    ->setSelect(['ID', 'NAME', 'PREVIEW_PICTURE', 'com.VALUE', 'mncom.VALUE', 'mn.VALUE', 'mx.VALUE'])
    ->where('ACTIVE', 'Y')
    ->setCacheTtl(360000)
    ->cacheJoins(true)
    ->fetchCollection();
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
                    <div class="w-100 f-14 lg:f-16"><?=$blockTitle->getPreviewText()?></div>
                <?endif;?>
            </div>

            <div class="w-100">
                <div class="pix-32 pby-16 d-f ai-c cg-16 r-12 bc-479ef5 c-w f-14 lg:f-16">
                    <div class="d-f">
                        <svg width="24" height="24" viewBox="0 0 160 160" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M80 0C64.1775 0 48.7103 4.69192 35.5544 13.4824C22.3985 22.2729 12.1447 34.7672 6.08967 49.3853C0.0346625 64.0034 -1.5496 80.0887 1.53721 95.6072C4.62403 111.126 12.2433 125.38 23.4315 136.569C34.6197 147.757 48.8743 155.376 64.3928 158.463C79.9113 161.55 95.9966 159.965 110.615 153.91C125.233 147.855 137.727 137.602 146.518 124.446C155.308 111.29 160 95.8225 160 80C159.974 58.7908 151.537 38.4579 136.539 23.4607C121.542 8.46351 101.209 0.0264619 80 0ZM88.57 120C88.57 122.273 87.6671 124.453 86.0599 126.06C84.4528 127.667 82.2729 128.57 80 128.57C77.7271 128.57 75.5473 127.667 73.9401 126.06C72.3329 124.453 71.43 122.273 71.43 120V80C71.43 77.7271 72.3329 75.5473 73.9401 73.9401C75.5473 72.3329 77.7271 71.43 80 71.43C82.2729 71.43 84.4528 72.3329 86.0599 73.9401C87.6671 75.5473 88.57 77.7271 88.57 80V120ZM80 57.14C78.4601 57.2045 76.9231 56.9568 75.4814 56.412C74.0396 55.8671 72.723 55.0363 71.6106 53.9695C70.4982 52.9027 69.6131 51.622 69.0084 50.2043C68.4037 48.7866 68.092 47.2613 68.092 45.72C68.092 44.1787 68.4037 42.6534 69.0084 41.2357C69.6131 39.818 70.4982 38.5373 71.6106 37.4705C72.723 36.4037 74.0396 35.5729 75.4814 35.028C76.9231 34.4832 78.4601 34.2355 80 34.3C82.9716 34.3879 85.792 35.6302 87.8629 37.7632C89.9337 39.8962 91.092 42.7521 91.092 45.725C91.092 48.6979 89.9337 51.5538 87.8629 53.6868C85.792 55.8198 82.9716 57.0521 80 57.14Z"/></svg>
                    </div>
                    <div class="lh-sd">Доступно к выводу: <span class="ftw-6"><?=$budgetOut?></span></div>
                </div>
            </div>

            <div class="w-100 d-f fd-c rg-16">
                <?foreach($payList as $pay):
                    $jsonPaySystem[$pay->getId()] = [
                        'com' => $pay->getCom()?->getValue(),
                        'mn' => $pay->getMn()?->getValue(),
                        'mncom' => $pay->getMncom()?->getValue(),
                        'mx' => $pay->getMx()?->getValue(),
                        'budget' => $budget["CURRENT_BUDGET"] ?? 0
                    ];
                    ?>
                    <div class="w-100 d-f">
                        <input class="lang-acount--pay d-n" name="paySystem" type="radio" id="paySystem<?=$pay->getId()?>" value="<?=$pay->getId()?>">
                        <label for="paySystem<?=$pay->getId()?>" class="w-100 m-0 pby-16 pix-24 f-14 lg:f-16 bc-w bw-1 bs-s bcr-py r-8 h:bc-py h:c-w tn-all-3 cr-p" <?=Data::iblockElementEditAddId($pay)?>>
                            <div class="d-f fw-w mi--16">
                                <div class="w-6/12 xl:w-6/12 pix-16 fg-0 fs-0 d-f ai-c cg-8">
                                    <div class="d-f">
                                        <?=Data::getImageId($pay->getPreviewPicture(), [
                                            "alt" => strip_tags($pay->getName()),
                                            "fetchpriority" => "high",
                                            "class" => "lang-paysystem--img w-30px h-30px oft-cv op-c"
                                        ])?>
                                    </div>
                                    <div class="lang-paysystem--name lh-1 ftw-5 ls--1"><?=$pay->getName()?></div>
                                </div>
                                <div class="w-4/12 xl:w-5/12 pix-16 fg-0 fs-0 d-f ai-c">
                                    <div class="lang-paysystem--description f-14 ftw-6 ls--1 ws-nw">
                                        <span><?=$pay->getCom()?->getValue()?>%</span>
                                        <?if(!empty($pay->getMncom()?->getValue()) ?? intval($pay->getMncom()?->getValue()) > 0):?>
                                            <span> (мин. <?=\SaleFormatCurrency($pay->getMncom()->getValue(), $budget['CURRENCY'] ?? CurrencyManager::getBaseCurrency())?>)</span>
                                        <?endif;?>
                                    </div>
                                </div>
                                <div class="w-2/12 xl:w-1/12 pix-16 fg-0 fs-0 d-f ai-c jc-e">
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
<style class="h:bc-CECECE w-51px h-51px atc-cc h:atc-py atc-py cg-4"></style>
<script>LG.PaySystemOut = <?=json_encode($jsonPaySystem, JSON_NUMERIC_CHECK)?></script>
<script><?=file_get_contents("{$templatePath}/js/main/util.js")?></script>
<script><?=file_get_contents("{$templatePath}/js/main/modal.js")?></script>
<script><?=file_get_contents("{$templatePath}/js/lk/lkBalansOut.js")?></script>
<script>new LG.LKBalansOut()</script>