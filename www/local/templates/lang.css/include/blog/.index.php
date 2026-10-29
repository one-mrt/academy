<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langTypeWork = "LangLkTypeWork{$lang}";

$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;

$session = Application::getInstance()->getSession();
$sessionFilter = $session->get('blogFilter');

$typeBlog = Data::getElementsObject($langTypeWork, ['select' => ['NAME']]);
?>

<h1 class="d-n"><?=$APPLICATION->ShowTitle(false)?></h1>

<section class="w-100 d-f fd-c rg-48">
    <div class="lang--filter <?=comp::get('container')?> d-f fd-c rg-32 o-1">

        <div class="w-100">
            <?$APPLICATION->IncludeComponent(
                "bitrix:breadcrumb",
                "breadcrumb.dark",
                [
                    "PATH" => "",
                    "SITE_ID" => SITE_ID,
                    "START_FROM" => "0"
                ],
                false,
                ['HIDE_ICONS' => 'Y']
            );?>
        </div>

        <div class="w-100 p-24 lg:p-32 d-f fd-c rg-16 bi-pyg r-12">

            <div class="w-100 d-f cg-16">
                <div class="fg-1">
                    <input name="name" class="c-dt ap-n w-100 pt-16 pr-32 pb-16 pl-32 lg:pt-24 lg:pr-48 lg:pb-24 lg:pl-48 bc-w bw-1 bs-s bcr-C5C5C5 h:bcr-py r-12 f-16 tn-all-3"
                        type="search" placeholder="Поиск по ключевым словам" value="<?=$sessionFilter['name']?>">
                </div>
                <div class="d-n md:d-f">
                    <button class="<?=comp::get("btnGreenLg")?>">Найти</button>
                </div>
            </div>

            <?/*<div class="w-100 d-n ai-c cg-24 c-w">
                <div class="xl:f-16 ftw-6">Популярные статьи:</div>
                <div class="d-f cg-24">
                    <a href="/" class="f-14 ">Как разместить заказ?</a>
                    <a href="/" class="f-14 ">Как разместить заказ?</a>
                    <a href="/" class="f-14 ">Как разместить заказ?</a>
                </div>
            </div>*/?>
        </div>

        <div class="w-100 d-f fw-w g-8 lg:g-16">
            <?foreach($typeBlog as $type):?>
                <div class="d-f cr-p">
                    <input id="type<?=$type->getId()?>" type="checkbox" name="type[<?=$type->getId()?>]" value="<?=$type->getId()?>"
                        class="d-n bc-w&lb bc-527CC8&h:lb c-dt&lb bc-w&h:lb c-fff&h:lb c:bc-527CC8&lb c:c-fff&h:lb c:c-fff&lb r-32&lb r-6&h:lb c:r-6&lb" <?=(!empty($sessionFilter['type'][$type->getId()])) ? 'checked' : ''?>>
                    <label class="m-0 pby-8 pix-16 lg:pby-12 lg:pix-24 f-12 lg:f-14 ftw-5 ta-c lh-1 bw-1 bs-s bcr-527CC8 h:bc-527CC8 tt-l ws-nw a:tm-s0.9 cr-p tn-all-3" for="type<?=$type->getId()?>"><?=$type->getName()?></label>
                </div>
            <?endforeach;?>
        </div>
    </div>

    <div class="lang--items <?=comp::get('container')?> d-f fd-c rg-32 o-2">
        <div class="<?=comp::get("row")?> rg-32">
            <?$blogList = $APPLICATION->IncludeComponent("lang:blog","",[],false, ['HIDE_ICONS' => 'Y']);?>
        </div>
    </div>
</section>

<script><?=file_get_contents("{$templatePath}/js/main/util.js")?></script>
<script><?=file_get_contents("{$templatePath}/js/main/filter.js")?></script>
<script><?=file_get_contents("{$templatePath}/js/main/navFilter.js")?></script>
<script>new LG.NavFilter({method: "getBlog", packClass: '<?=comp::get("row")?> rg-32'})</script>