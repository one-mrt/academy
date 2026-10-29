<?
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['sectionId'])):
    $edge1Title = Data::getSectionObject($langText, ['select'=>['NAME', 'DESCRIPTION'],'filter'=>['ID' => $arParams['sectionId']]]);
    $edge1List = Data::getElementsObject($langText, ['select'=>['NAME', 'PREVIEW_TEXT'],'filter'=>['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);

    if(!empty($edge1List) && !empty($edge1List->getIdList())):?>
        <section class="w-100">
            <div class="<?=comp::get("container")?> d-f fd-c rg-32 xl:rg-48">

                <?if(!empty($edge1Title) && !empty($edge1Title->getId())):?>
                    <div class="w-100 d-f fd-c rg-16" <?=Data::iblockSectionEditAddId($edge1Title)?>>
                        <h2 class="<?=comp::get("h2")?>"><?=$edge1Title->getName()?></h2>
                        <?if(!empty($edge1Title->getDescription())):?>
                            <div class="f-14"><?=$edge1Title->getDescription()?></div>
                        <?endif?>
                    </div>
                <?endif?>

                <div class="w-100">
                    <div class="<?=comp::get("row")?> rg-32">
                        <?foreach($edge1List as $edge):?>
                            <div class="w-6/12 md:w-4/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-32">
                                <div class="w-100 h-100 p-24 sm:p-32 d-f fd-c ai-c rg-16 bc-w bw-1 bs-s bcr-21A78E r-12 lh-1 ta-c" <?=Data::iblockElementEditAddId($edge)?>>
                                    <div class="f-20 sm:f-24 xl:f-36 ftw-6 bi-ggn bgc-t tf-t ff-ud"><?=$edge->getName()?></div>
                                    <?if(!empty($edge->getPreviewText())):?>
                                        <div class="f-12 sm:sf-14"><?=$edge->getPreviewText()?></div>
                                    <?endif?>
                                </div>
                            </div>
                        <?endforeach?>
                    </div>
                </div>

            </div>
        </section>
    <?endif?>
<?endif?>