<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['sectionId'])):
    $user = CurrentUser::get();
    $helpTitle = Data::getSectionObject($langText, ['select' => ['NAME', 'DESCRIPTION', 'PICTURE'], 'filter' => ['ID' => $arParams['sectionId']]]);
    $helpList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT', 'svg'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);
    $orderLink = Data::getElementObject($langText, ['link'], ['ID' => $arParams['titleElementId']]);

    if(!empty($helpList) && !$helpList->isEmpty()):?>
        <section class="w-100">
            <div class="<?=comp::get('container-full')?> d-f fd-c">
                <div class="w-100 r-24 bc-w of-h pn-r">

                    <?if(!empty($helpTitle->getPicture())):?>
                        <?=Data::getImageId($helpTitle->getPicture(), [
                            "alt" => strip_tags($helpTitle->getName()),
                            "loading" => "lazy",
                            "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c"
                        ])?>
                    <?endif?>

                    <div class="<?=comp::get('container')?> d-f fd-c rg-32 xl:rg-48 pn-r pix-32 pby-48 xxl:pix-8 xl:pby-86">

                        <?if(!empty($helpTitle) && !empty($helpTitle->getId())):?>
                            <div class="w-100 xl:w-6/12 d-f fd-c rg-16" <?=Data::iblockSectionEditAddId($helpTitle)?>>
                                <h2 class="<?=comp::get("h2")?>"><?=$helpTitle->getName()?></h2>
                                <?if(!empty($helpTitle->getDescription())):?>
                                    <div class="f-12 lg:f-14"><?=$helpTitle->getDescription()?></div>
                                <?endif?>
                            </div>
                        <?endif?>

                        <div class="w-100">
                            <div class="<?=comp::get('row')?> rg-32">
                                <?foreach ($helpList as $help):?>
                                    <div class="w-100 md:w-6/12 lg:w-4/12 xl:w-4/12 <?=comp::get("setcol")?>">
                                        <div class="w-100 h-100 d-f fd-c rg-24 jc-s ai-s bc-w bw-1 bs-s bcr-c5c5c566 r-12 p-32" <?=Data::iblockElementEditAddId($help)?>>
                                            <?if(!empty($help->getSvg())):
                                                $svg = \CFile::GetPath($help->getSvg()->getValue());
                                                ?>
                                                <div class="d-f"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$svg}")?></div>
                                            <?endif?>

                                            <div class="f-20 lg:f-18 xl:f-20 ftw-5 ls--1 tt-l ff-ud"><?=$help->getName()?></div>

                                            <?if(!empty($help->getPreviewText())):?>
                                                <div class="f-12 lg:f-14 fg-1"><?=$help->getPreviewText()?></div>
                                            <?endif?>
                                        </div>
                                    </div>
                                <?endforeach?>
                            </div>
                        </div>

                        <?if(!empty($orderLink) && !empty($orderLink->getLink())):?>
                            <div class="w-100 d-f jc-c" <?=Data::iblockElementEditAddId($orderLink)?>>
                                <?if(!empty($user->getId())):?>
                                    <a href="<?=$orderLink->getLink()->getValue()?>" class="<?=comp::get("btnGreenLg")?>"><?=$orderLink->getLink()->getDescription()?></a>
                                <?else:?>
                                    <button class="lang-form--order-add <?=comp::get("btnGreenLg")?>"><?=$orderLink->getLink()->getDescription()?></button>
                                <?endif?>
                            </div>
                        <?endif?>

                    </div>
                </div>
            </div>
        </section>
    <?endif?>
<?endif?>