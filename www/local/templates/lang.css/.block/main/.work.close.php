<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Util::getLang();
$langText = "LangText{$lang}";

$dayTitle = Data::getSectionObject($langText, ['select' => ['NAME'],'filter' => ['ID' => $arParams['sectionId']]]);
$dayList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT', 'svg'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);

if(!empty($dayList) && !empty($dayList->getIdList())):?>
    <section class="w-100">
        <div class="<?=comp::get('container')?> d-f fd-c rg-32">

            <?if(!empty($dayTitle) && !empty($dayTitle->getId())):?>
                <div class="w-100 xl:w-10/12" <?=Data::iblockSectionEditAddId($dayTitle)?>>
                    <h2 class="<?=comp::get("h2")?>"><?=$dayTitle->getName()?></h2>
                </div>
            <?endif?>

            <?if(!empty($dayList) && !empty($dayList->getIdList())):?>
                <div class="<?=comp::get('row')?> rg-32">
                    <?foreach ($dayList as $day):?>
                        <div class="w-100 md:w-6/12 lg:w-4/12 xl:w-4/12 <?=comp::get('setcol')?>">
                            <div class="w-100 h-100 p-32 lg:p-24 xl:p-32 d-f cg-32 r-12 bc-w" <?=Data::iblockElementEditAddId($day)?>>
                                <?if(!empty($day->getSvg())):
                                    $svg  = \CFile::GetPath($day->getSvg()->getValue());
                                    ?>
                                    <div class="d-f"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . $svg)?></div>
                                <?endif?>
                                <div class="w-100 d-f fd-c jc-c rg-8">
                                    <div class="f-24 lg:f-24 xl:f-24 ff-ud tt-l ftw-6 lh-1 c-527CC8"><?=$day->getName()?></div>
                                    <?if(!empty($day->getPreviewText())):?>
                                        <div class="f-14 lg:f-12 xl:f-14"><?=$day->getPreviewText()?></div>
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