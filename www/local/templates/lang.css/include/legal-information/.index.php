<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";

$element = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', "PREVIEW_PICTURE", 'text'], ['ID' => 209]);
?>

<section class="w-100 fg-1 d-f fd-c">
    <div class="<?=comp::get('container-full')?> h-100 d-f fd-c rg-0 d-f fd-c">
        <div class="w-100 h-100 of-h pn-r fg-1">

            <?if(!empty($element) && !empty($element->getPreviewPicture())):?>
                <?=Data::getImageId($element->getPreviewPicture(), [
                    'title' => strip_tags($element->getName()),
                    'class' => 'w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c r-24 bc-w',
                    'fetchpriority' => 'high'
                ])?>
            <?endif?>

            <div class="w-100 h-100 pn-r xl:pb-64 d-f fd-c fg-1">
                <div class="<?=comp::get("container-p")?> h-100 fg-1">
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

                            <?if(!empty($element)):?>
                                <?if(!empty($element->getText()->getAll())):?>
                                    <div calss="w-100 d-f fd-c rg-8">
                                        <?foreach($element->getText()->getAll() as $item):?>
                                            <div class="">
                                                <span class="ftw-6"><?=$item->getValue()?>: </span>
                                                <span class=""><?=$item->getDescription()?></span>
                                            </div>
                                        <?endforeach?>
                                    </div>
                                <?endif?>

                                <?if(!empty($element->getPreviewText())):?>
                                    <div class="w-100"><?=$element->getPreviewText()?></div>
                                <?endif?>
                            <?endif?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>