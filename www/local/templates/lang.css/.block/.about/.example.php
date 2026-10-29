<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['elementId'])):

    $example = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'],['ID' => $arParams['elementId']]);

    if(!empty($example) && !empty($example->getId())):?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>" <?=Data::iblockElementEditAddId($example)?>>
                <div class="<?=comp::get("row")?> jc-c ai-c rg-32">
                    <div class="w-100 xl:w-4/12 <?=comp::get("setcol")?>">
                        <h2 class="<?=comp::get("h2")?>"><?=$example->getName()?></h2>
                    </div>
                    <div class="w-100 md:w-10/12 lg:w-8/12 xl:w-6/12 <?=comp::get("setcol")?>">
                        <?if(!empty($example->getPreviewPicture())):?>
                            <?=Data::getImageId($example->getPreviewPicture(), [
                                "alt" => strip_tags($example->getName()),
                                "loading" => "lazy",
                                "class" => "w-100 h-100 oft-cv op-c"
                            ])?>
                        <?endif?>
                    </div>
                </div>
            </div>
        </section>
    <?endif?>
<?endif?>