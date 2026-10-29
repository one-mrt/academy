<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$user = CurrentUser::get();
$langText = "LangText{$lang}";

if(!empty($arParams['sectionId'])):

    $sumTitle = Data::getSectionObject($langText, ['select' => ['NAME', 'DESCRIPTION', 'PICTURE'], 'filter' => ['ID' => $arParams['sectionId']]]);
    $sumList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);

    if(!empty($sumList) && !empty($sumList->getIdList())):?>
        <section class="w-100">
            <div class="<?=comp::get('container')?> d-f fd-c rg-32 xl:rg-48">

                <?if(!empty($sumTitle) && !empty($sumTitle->getId())):?>
                    <div class="w-100 xl:w-5/12 d-f fd-c rg-16" <?=Data::iblockSectionEditAddId($sumTitle)?>>
                        <h2 class="<?=comp::get("h2")?>"><?=$sumTitle->getName()?></h2>
                        <?if(!empty($sumTitle->getDescription())):?>
                            <div class="f-14"><?=$sumTitle->getDescription()?></div>
                        <?endif?>
                    </div>
                <?endif?>

                <div class="w-100">
                    <div class="<?=comp::get('row', 'mi--8 md:mi--16', 'mi--16')?> rg-16 md:rg-32">
                        <?foreach ($sumList as $sum):?>
                            <div class="w-6/12 lg:w-3/12 <?=comp::get("setcol", 'pix-8 md:pix-16', 'pix-16')?>">
                                <div class="w-100 h-100 d-f fd-c rg-8 jc-s ai-s bc-w p-24 xl:p-32 r-12" <?=Data::iblockElementEditAddId($sum)?>>

                                    <div class="ff-ud xl:f-20 xxl:f-24 ftw-6 ls--1 tt-l c-527CC8"><?=$sum->getName()?></div>

                                    <?if(!empty($sum->getPreviewText())):?>
                                        <div class="f-12 xxl:f-14 fg-1"><?=$sum->getPreviewText()?></div>
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