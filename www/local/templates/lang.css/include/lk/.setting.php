<?
use Lang\Main\Iblock\Util as Data;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util;
use Bitrix\Main\UserTable;
use Bitrix\Main\Engine\CurrentUser;

$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;
$image = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => 248]);

$name = "Настройки";
$title = "Настройки";
?>

<div class="w-100 xl:w-8/12 pr-16 pl-16 fg-0 fs-0">
    <div class="w-100 h-100 pn-r of-h <?/*p-32 bc-w r-24*/?>">
        <?/*if(!empty($image) && !empty($image->getPreviewPicture())) {
            echo Data::getImageId($image->getPreviewPicture(), [
                "alt" => strip_tags($image->getName()),
                "fetchpriority" => "high",
                "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
            ]);
        }*/?>

        <div class="w-100 d-f fd-c rg-32 pn-r">
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.profile",
                "lang.profile",
                Array(
                    "CHECK_RIGHTS" => "N",
                    "SEND_INFO" => "N",
                    "SET_TITLE" => "N",
                    "USER_PROPERTY" => [],
                    "USER_PROPERTY_NAME" => ""
                ),
                false,
                ['HIDE_ICONS' => 'Y']
            );?>

        </div>
    </div>
</div>

<div class="lang-edit--wrap w-100 h-100 pn-f t-0 rt-0 b-0 l-0 d-f d-n ai-c jc-e bc-f4eff95e bf-b6 z-3">
    <div class="lang-edit--container h-100 h-100%-110px w-100 md:mw-720px of-h pn-a t-110 t-200% mr-a ml-a d-f fd-c bxs-au tp-t tnd-500ms bc-w md:btlr-12">

        <div class="lang-edit--content w-100 h-100 ofx-h ofy-a pn-r p-32 md:p-32"></div>

        <button class="lang-edit--close pn-a t-32px rt-32px m-0 p-0 bw-0 c-dt h:c-py tn-all-3 bc-t">
            <svg width="24" height="24" viewBox="0 0 13 13" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                <line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"></line>
                <line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"></line>
            </svg>
        </button>
        <div class="lang-edit--preloader w-100 h-100 d-f fd-c m-0 z-2 ai-c jc-c pn-a t-0 rt-0 b-0 l-0 ofw-h bc-w">
            <style>@-webkit-keyframes side-panel-loader-rotate{100% {-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@keyframes side-panel-loader-rotate{100%{-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@-webkit-keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}@keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}.loader--animation {-webkit-animation: side-panel-loader-rotate 2s linear infinite;animation: side-panel-loader-rotate 2s linear infinite;-webkit-transform-origin: center center;transform-origin: center center}.loader--path{stroke: #bfc3c8;stroke-width: 1.5;stroke-dasharray: 20, 200;stroke-dashoffset: 0;-webkit-animation: side-panel-loader-dash 1.5s ease-in-out infinite;animation: side-panel-loader-dash 1.5s ease-in-out infinite;stroke-linecap: round}</style>
            <svg class="loader--animation w-100 h-100 mw-200px mxh-200px" viewBox="25 25 50 50"><circle class="loader--path" cx="50" cy="50" r="20" fill="none" stroke-miterlimit="10"></circle></svg>
            <div class="mt-8 f-12 md:f-14">Пожалуйста подождите</div>
        </div>
    </div>
</div>

<script><?=file_get_contents("{$templatePath}/js/main/modal.js")?></script>
<script><?=file_get_contents("{$templatePath}/js/lk/edit.setting.js")?></script>

<?
$APPLICATION->SetTitle(strip_tags($title));
$APPLICATION->SetPageProperty("title", strip_tags($name));
// $APPLICATION->SetPageProperty("description", strip_tags($seo['ELEMENT_META_DESCRIPTION']));
// $APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
?>