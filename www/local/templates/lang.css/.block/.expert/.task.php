<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$user = CurrentUser::get();
$langText = "LangText{$lang}";

if(!empty($arParams['sectionId'])):

    $seaTitle = Data::getSectionObject($langText, ['select' => ['NAME', 'DESCRIPTION', 'PICTURE'], 'filter' => ['ID' => $arParams['sectionId']]]);
    $seaList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);
    $orderLink = Data::getElementObject($langText, ['link'], ['ID' => $arParams['sectionId']]);

    if(!empty($seaList) && !empty($seaList->getIdList())):?>
        <section class="w-100">
            <div class="<?=comp::get('container-full')?> d-f fd-c rg-0">
                <div class="w-100 of-h r-24 pix-32 pby-48 xl:pby-64 bi-pyg">
                    <div class="<?=comp::get('container-p')?> d-f fd-c rg-32 xl:rg-48">

                        <?if(!empty($seaTitle) && !empty($seaTitle->getId())):?>
                            <div class="w-100 xl:w-7/12 xxl:w-5/12 d-f fd-c rg-16 c-w" <?=Data::iblockSectionEditAddId($seaTitle)?>>
                                <h2 class="<?=comp::get("h2")?>"><?=$seaTitle->getName()?></h2>
                                <?if(!empty($seaTitle->getDescription())):?>
                                    <div class="f-14"><?=$seaTitle->getDescription()?></div>
                                <?endif?>
                            </div>
                        <?endif?>

                        <div class="w-100">
                            <div class="<?=comp::get('row')?> rg-16 md:rg-32">
                                <?foreach ($seaList as $sea):?>
                                    <div class="w-100 lg:w-3/12 <?=comp::get("setcol")?>">
                                        <div class="w-100 h-100 d-f fd-c rg-8 jc-s ai-s bc-w p-32 r-12" <?=Data::iblockElementEditAddId($sea)?>>

                                            <div class="xl:f-20 ftw-6 tt-l"><?=$sea->getName()?></div>

                                            <?if(!empty($sea->getPreviewText())):?>
                                                <div class="f-14 fg-1"><?=$sea->getPreviewText()?></div>
                                            <?endif?>
                                        </div>
                                    </div>
                                <?endforeach?>
                            </div>
                        </div>

                        <?if(!empty($orderLink) && !empty($orderLink->getId()) && !empty($orderLink->getLink())):?>
                            <div class="w-100 d-f jc-c" <?=Data::iblockElementEditAddId($orderLink)?>>
                                <a href="<?=$orderLink->getLink()->getValue()?>" class="<?=comp::get("btnGreenLg")?>"><?=$orderLink->getLink()->getDescription()?></a>
                            </div>
                        <?endif?>

                    </div>
                </div>
            </div>
        </section>
    <?endif?>

<?endif?>