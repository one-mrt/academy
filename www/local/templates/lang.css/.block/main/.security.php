<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Util::getLang();
$langText = "LangText{$lang}";

$securityTitle = Data::getSectionObject($langText, ['select' => ['NAME', 'PICTURE'],'filter' => ['ID' => $arParams['sectionId']]]);
$securityList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT', 'svg'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);

if(!empty($securityList) && !empty($securityList->getIdList())):?>
    <section class="w-100">
        <div class="<?=comp::get('container')?> d-f fd-c rg-48 lg:rg-64">

            <?if(!empty($securityTitle) && !empty($securityTitle->getId())):?>
                <div class="w-100 xl:w-38 d-f fd-c rg-48">
                    <h2 class="<?=comp::get("h2")?> d-f fd-c" <?=Data::iblockSectionEditAddId($securityTitle)?>><?=$securityTitle->getName()?></h2>
                </div>
            <?endif?>

            <div class="<?=comp::get('row')?> rg-32 lg:rg-48">

                <div class="w-100 md:w-76 lg:w-37 xl:w-38 <?=comp::get('setcol')?> d-f ai-e fd-c rg-48 lg:pb-48 o-0">
                    <?
                    $indSec = 1;
                    foreach($securityList as $security):?>
                        <?if($indSec % 2 == 1):?>
                            <div class="w-100 d-f cg-24 lg:ta-r" <?=Data::iblockElementEditAddId($security)?>>
                                <div class="d-f fd-c rg-16 fg-1 o-1 lg:o-0">
                                    <div class="xl:f-20 ftw-6 lh-1 tt-l ff-ud"><?=$security->getName()?></div>
                                    <?if(!empty($security->getPreviewText())):?>
                                        <div class="f-14 lg:f-12 xl:f-14"><?=$security->getPreviewText()?></div>
                                    <?endif;?>
                                </div>
                                <?if(!empty($security->getSvg())):?>
                                    <div class="d-f o-0 lg:o-1"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($security->getSvg()->getValue()))?></div>
                                <?endif;?>
                            </div>
                        <?endif?>
                        <?$indSec++?>
                    <?endforeach?>
                </div>

                <div class="w-100 md:w-24 lg:w-26 xl:w-24 <?=comp::get('setcol')?> d-f fd-c o-2 md:o-1">
                    <?if(!empty($securityTitle->getPicture())):?>
                        <div class="w-100 h-100 pn-r fg-1 d-f jc-c">
                            <?=Data::getImageId($securityTitle->getPicture(), [
                                "title" => strip_tags($securityTitle->getName()),
                                "alt" => strip_tags($securityTitle->getName()),
                                "loading" => "lazy",
                                "class" => "w-60 md:w-100 h-a lg:pn-a b-0 l-0",
                            ])?>
                        </div>
                    <?endif?>
                </div>

                <div class="w-100 md:w-76 lg:w-37 xl:w-38 <?=comp::get('setcol')?> d-f ai-s fd-c rg-48 lg:pb-48 o-1 md:o-2">
                    <?
                    $indSec = 1;
                    foreach($securityList as $security):?>
                        <?if($indSec % 2 == 0):?>
                            <div class="w-100 d-f cg-24 ta-l" <?=Data::iblockElementEditAddId($security)?>>
                                <?if(!empty($security->getSvg())):?>
                                    <div class="d-f"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($security->getSvg()->getValue()))?></div>
                                <?endif?>
                                <div class="d-f fd-c rg-16 fg-1">
                                    <div class="xl:f-20 ftw-6 lh-1 tt-l ff-ud"><?=$security->getName()?></div>
                                    <?if(!empty($security->getPreviewText())):?>
                                        <div class="f-14 lg:f-12 xl:f-14"><?=$security->getPreviewText()?></div>
                                    <?endif;?>
                                </div>
                            </div>
                        <?endif?>
                        <?$indSec++?>
                    <?endforeach?>
                </div>
            </div>
        </div>
    </section>
<?endif?>