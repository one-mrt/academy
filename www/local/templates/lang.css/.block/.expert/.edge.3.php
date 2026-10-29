<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$user = CurrentUser::get();
$langText = "LangText{$lang}";

if(!empty($arParams['sectionId'])):

    $helpTitle = Data::getSectionObject($langText, ['select' => ['NAME', 'DESCRIPTION', 'PICTURE'], 'filter' => ['ID' => $arParams['sectionId']]]);
    $helpList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT', 'svg.VALUE'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);

    if(!empty($helpList) && !empty($helpList->getIdList())):?>
        <section class="w-100">
            <div class="<?=comp::get('container')?> d-f fd-c rg-32 xl:rg-48">

                <?if(!empty($helpTitle) && !empty($helpTitle->getId())):?>
                    <div class="w-100 xl:w-8/12 d-f fd-c rg-16" <?=Data::iblockSectionEditAddId($helpTitle)?>>
                        <h2 class="<?=comp::get("h2")?>"><?=$helpTitle->getName()?></h2>
                        <?if(!empty($helpTitle->getDescription())):?>
                            <div class="f-14"><?=$helpTitle->getDescription()?></div>
                        <?endif?>
                    </div>
                <?endif?>

                <div class="w-100">
                    <div class="<?=comp::get('row')?> rg-32">
                        <?foreach ($helpList as $help):?>
                            <div class="w-100 lg:w-4/12 <?=comp::get("setcol")?>">
                                <div class="w-100 h-100 d-f fd-c rg-24 jc-s ai-s" <?=Data::iblockElementEditAddId($help)?>>
                                    <?if(!empty($help->getSvg())):
                                        $svg = \CFile::GetPath($help->getSvg()->getValue());
                                        ?>
                                        <div class="d-f"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$svg}")?></div>
                                    <?endif?>

                                    <div class="f-20 ftw-5 ls--1 tt-l ff-ud"><?=$help->getName()?></div>

                                    <?if(!empty($help->getPreviewText())):?>
                                        <div class="f-14 fg-1"><?=$help->getPreviewText()?></div>
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