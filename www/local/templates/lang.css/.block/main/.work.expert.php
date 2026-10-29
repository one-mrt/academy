<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Util::getLang();
$langText = "LangText{$lang}";

$wTitle = Data::getSectionObject($langText, ['select' => ['NAME'],'filter' => ['ID' => $arParams['sectionId']]]);
$workList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);

if(!empty($workList) && !empty($workList->getIdList())):?>
    <section class="w-100">
        <div class="<?=comp::get('container')?> d-f fd-c rg-32">

            <?if(!empty($wTitle) && !empty($wTitle->getId())):?>
                <div class="w-100 xl:w-8/12" <?=Data::iblockSectionEditAddId($wTitle)?>>
                    <h2 class="<?=comp::get("h2")?>"><?=$wTitle->getName()?></h2>
                </div>
            <?endif?>

            <?if(!empty($workList) && !empty($workList->getIdList())):?>
                <div class="<?=comp::get('row')?> rg-32">
                    <?foreach ($workList as $wwork):?>
                        <div class="w-100 md:w-6/12 lg:w-3/12 xl:w-3/12 <?=comp::get('setcol')?>">
                            <div class="w-100 h-100 p-16 lg:p-8 xl:p-16 d-f fd-c rg-32 r-12 bc-w" <?=Data::iblockElementEditAddId($wwork)?>>
                                <?if(!empty($wwork->getPreviewPicture())):?>
                                    <?=Data::getImageId($wwork->getPreviewPicture(), [
                                        "title" => strip_tags($wwork->getName()),
                                        "alt" => strip_tags($wwork->getName()),
                                        "loading" => "lazy",
                                        "class" => "w-100 h-a ar-16x9 md:ar-10x9 oft-cv op-c r-12",
                                    ])?>
                                <?endif?>

                                <div class="w-100 d-f fd-c rg-24 pix-16 lg:pix-8 xl:pix-16 pb-16">
                                    <div class="f-20 lg:f-16 xl:f-20 ff-ud tt-l ftw-5 lh-1"><?=$wwork->getName()?></div>
                                    <?if(!empty($wwork->getPreviewText())):?>
                                        <div class="f-14 lg:f-12 xl:f-14"><?=$wwork->getPreviewText()?></div>
                                    <?endif?>
                                </div>

                            </div>
                        </div>
                    <?endforeach?>
                </div>
            <?endif?>
        </div>
    </section>
<?endif?>