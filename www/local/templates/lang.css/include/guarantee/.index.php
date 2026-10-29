<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";

$section = Data::getSectionObject($langText, ['select' => ['NAME', 'PICTURE'], 'filter' => ['ID' => 45]]);
$element = Data::getElementsObject($langText, [ 'select' => ['NAME', 'PREVIEW_TEXT'], 'filter' => ['IBLOCK_SECTION_ID' => 45]]);

?>

<section class="w-100">
    <div class="<?=comp::get('container-full')?> h-100 d-f fd-c rg-0">
        <div class="w-100 h-100 of-h pn-r">

            <?if(!empty($section) && !empty($section->getPicture())):?>
                <?=Data::getImageId($section->getPicture(), [
                    'title' => strip_tags($section->getName()),
                    'class' => 'w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c r-24 bc-w',
                    'fetchpriority' => 'high'
                ])?>
            <?endif?>

            <div class="w-100 h-100 pn-r xl:pb-64">
                <div class="<?=comp::get("container-p")?> h-100">
                    <div class="w-100 h-100 xl:pt-32 d-f fd-c xl:rg-48">
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
                        <div class="w-100 d-f fd-c rg-32">
                            <h1 class="<?=comp::get("h1")?>"><?=$APPLICATION->ShowTitle(false)?></h1>
                            <?if(!empty($element) && !empty($element->getIdList())):?>
                                <div class="w-100 d-f fd-c rg-32">
                                    <?foreach ($element as $item):?>
                                        <div class="w-100 d-f fd-c rg-16">
                                            <div class="xl:f-16 ftw-6"><?=$item->getName()?></div>
                                            <?if(!empty($item->getPreviewText())):?>
                                                <div class=""><?=$item->getPreviewText()?></div>
                                            <?endif?>
                                        </div>
                                    <?endforeach?>
                                </div>
                            <?endif?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>