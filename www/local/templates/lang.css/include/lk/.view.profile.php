<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\UserTable;
use Bitrix\Main\Engine\CurrentUser;

$parser = new CTextParser();
$parser->allow = array(
    "HTML" => "N",
    "ANCHOR" => "Y",  // ссылки
    "BIU" => "Y",    // жирный, курсив, подчеркнутый
    // "IMG" => "Y",   // изображения
    // "QUOTE" => "Y", // цитаты
    // "CODE" => "Y",   // блоки кода
    // "FONT" => "Y",   // шрифты
    "LIST" => "Y",   // списки
    // "SMILES" => "Y", // смайлики
    // "NL2BR" => "Y", // перенос строк
    // "VIDEO" => "Y", // видео
    // "TABLE" => "Y", // таблицы
    "CUT_ANCHOR" => "N", // запрет на вставку закрывающего тега ссылки
    // "ALIGN" => "Y"  // выравнивание
);

$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;
$langSpec = "LangLkSpec{$lang}";

$image = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => 248]);

$name = "Профиль";
$title = "Профиль";

?>  

<div class="w-100 xl:w-8/12 <?=comp::get("setcol")?>">
    <div class="w-100 h-100 pn-r of-h <?/*p-24 sm:p-32 bc-w r-24*/?>">
        <?/*if(!empty($image) && !empty($image->getPreviewPicture())) {
            echo Data::getImageId($image->getPreviewPicture(), [
                "alt" => strip_tags($image->getName()),
                "fetchpriority" => "high",
                "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
            ]);
        }*/?>

        <div class="w-100 d-f fd-c rg-32 pn-r">
            <div class="w-100 d-f fd-c rg-48 fg-1">
                <?include_once("{$templatePath}/.block/.lk/.userInfo.php")?>
            </div>

            <?
            $aboutTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'link'], ['ID' => 259]);
            $aboutAuth = Data::getElementObject($langText, ['link'], ['ID' => 781]);

            if(!empty($aboutTitle)):?>
                <div class="w-100 d-f fd-c rg-24 bc-w r-12 p-24" <?=Data::iblockElementEditAddId($aboutTitle)?>>

                    <div class="w-100 d-f ai-c jc-b">
                        <h2 class="mb-0 f-16 sm:f-20 <?=comp::get("fontUd")?>"><?=$aboutTitle->getName()?></h2>

                        <?if(!empty($aboutAuth->getLink()) && !empty($aboutAuth->getLink()->getValue())):?>
                            <button class="lang-description--add bc-t bw-0 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3 f-12 sm:f-14 ftw-5"><?=$aboutAuth->getLink()->getDescription()?></button>
                        <?endif;?>
                    </div>

                    <?if(!empty($user->getPersonalNotes())):?>
                        <div class="lc:mb-0|* h:c-py|a tdt-1|a tuo-4|a tdc-py|a tdl-u|a h:tdl-u|a f-14"><?=$parser->convertText($user->getPersonalNotes())?></div>
                    <?else:?>
                        <div class="w-100 d-f fd-c rg-24">
                            <div class="tt-l f-14"><?=$aboutTitle->getPreviewText()?></div>
                        </div>
                    <?endif;?>
                </div>
            <?endif;?>

            <?
            $specTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'link'], ['ID' => 258]);

            if(!empty($specTitle)):?>
                <div class="w-100 d-f fd-c rg-24 bc-w r-12 p-24" <?=Data::iblockElementEditAddId($specTitle)?>>
                    <div class="w-100 d-f ai-c jc-b">
                        <h2 class="mb-0 f-16 sm:f-20 <?=comp::get("fontUd")?>"><?=$specTitle->getName()?></h2>
                        <?if(!empty($aboutAuth->getLink()) && !empty($aboutAuth->getLink()->getValue())):?>
                            <button class="lang-spec--edit bc-t bw-0 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3 f-12 sm:f-14 ftw-5"><?=$aboutAuth->getLink()->getDescription()?></button>
                        <?endif?>
                    </div>

                    <div class="w-100 d-f fd-c rg-24">
                        <?if(!empty($user->getUfSpec())):?>
                            <div class="w-100 f-14">
                                <div class="<?=comp::get("row")?> rg-24">
                                    <?
                                    $specList = Data::getElementsObject($langSpec, [
                                        'select' => ['NAME', 'IBLOCK_SECTION_ID'],
                                        'filter' => ['ID' => $user->getUfSpec()]
                                    ]);
                                    $specCatList = Data::getSectionsObject($langSpec, [
                                        'select' => ['NAME'],
                                        'filter' => ['ID' => $specList->getIblockSectionIdList()]
                                    ]);
                                    foreach($specCatList as $cat):?>
                                        <div class="w-100 xl:w-4/12 <?=comp::get("setcol")?> d-f fd-c rg-24 lh-1">
                                            <div class="w-100 ftw-6" <?=Data::iblockSectionEditAddId($cat)?>><?=$cat->getName()?></div>
                                            <div class="w-100 d-f fd-c rg-4">
                                                <?foreach($specList as $spec):?>
                                                    <?if($spec->getIblockSectionId() == $cat->getId()):?>
                                                        <div class="c-595959" <?=Data::iblockElementEditAddId($spec)?>><?=$spec->getName()?></div>
                                                    <?endif;?>
                                                <?endforeach;?>
                                            </div>
                                        </div>
                                    <?endforeach?>
                                </div>
                            </div>
                        <?else:?>
                            <div class="tt-l f-14"><?=$specTitle->getPreviewText()?></div>
                        <?endif;?>
                    </div>
                </div>
            <?endif;?>

            <?include_once("{$templatePath}/.block/.lk/.statWork.php")?>

            <?
            /*
            $portfTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'svg', 'link'], ['ID' => 261]);
            $portfIcon = file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($portfTitle->getSvg()->getValue()));
            ?>

            <div class="w-100 d-f fd-c rg-24" <?=Data::iblockElementEditAddId($portfTitle)?>>
                <h2 class="mb-0 f-16 sm:f-20 <?=comp::get("fontUd")?>"><?=$portfTitle->getName()?></h2>
                <div class="w-100 d-f fd-c rg-32">

                    <div class="w-100 d-f ai-c cg-24">
                        <div class="tt-l fg-1 f-14"><?=$portfTitle->getPreviewText()?></div>
                        <div class="d-f"><?=$portfIcon?></div>
                    </div>

                    <div class="w-100">
                        <div class="<?=comp::get("row")?>">
                            <div class="w-100 sm:w-6/12 lg:w-4/12 xl:w-4/12 <?=comp::get("setcol")?>">
                                <a href="<?=$portfTitle->getLink()->getValue()?>" class="w-100 ar-1x1 d-f ai-c jc-c bc-w r-12" title="<?=$portfTitle->getLink()->getDescription()?>"><?=$portfIcon?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            */?>
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

<script><?=file_get_contents("{$templatePath}/js/lk/edit.profile.js")?></script>


<?
$APPLICATION->SetTitle(strip_tags($title));
$APPLICATION->SetPageProperty("title", strip_tags($name));
// $APPLICATION->SetPageProperty("description", strip_tags($seo['ELEMENT_META_DESCRIPTION']));
// $APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
?>