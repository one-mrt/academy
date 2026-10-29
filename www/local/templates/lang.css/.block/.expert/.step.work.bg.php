<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['sectionId'])):
    $cardTitle = Data::getSectionObject($langText, ['select'=>['NAME', 'PICTURE'],'filter'=>['ID' => $arParams['sectionId']]]);
    $cardList = Data::getElementsObject($langText, ['select'=>['NAME', 'PREVIEW_TEXT', 'svg', 'link'],'filter'=>['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);
    $user = CurrentUser::get();

    if(!empty($cardList) && !empty($cardList->getIdList())):?>
        <section class="w-100">
            <div class="<?=comp::get("container-full")?>">
                <div class="w-100 pn-r of-h pix-32 pby-48 xxl:pix-0 xl:pby-86 d-f fd-c rg-32 r-24 bc-w">

                    <?if(!empty($cardTitle->getPicture())):?>
                        <?=Data::getImageId($cardTitle->getPicture(), [
                            "alt" => strip_tags($cardTitle->getName()),
                            "loading" => "lazy",
                            "class" => "pn-a t-0 rt-0 b-0 l-0 w-100 h-100 oft-cv op-c r-12",
                        ])?>
                    <?endif?>

                    <div class="<?=comp::get('container-p')?> d-f fd-c rg-32 pn-r">

                        <?if(!empty($cardTitle) && !empty($cardTitle->getId())):?>
                            <div class="w-100 xl:w-8/12" <?=Data::iblockSectionEditAddId($cardTitle)?>>
                                <h2 class="<?=comp::get("h2")?>"><?=$cardTitle->getName()?></h2>
                            </div>
                        <?endif?>

                        <?if(!empty($cardList) && !empty($cardList->getIdList())):?>
                            <div class="<?=comp::get('row')?> rg-32">
                                <?foreach ($cardList as $card):?>
                                    <div class="w-100 md:w-6/12 lg:w-3/12 xl:w-3/12 <?=comp::get('setcol')?>">
                                        <div class="w-100 h-100 p-32 lg:p-24 xl:p-32 d-f fd-c rg-48 r-12 bc-w bw-1 bs-s bcr-c5c5c566" <?=Data::iblockElementEditAddId($card)?>>
                                            <?if(!empty($card->getSvg())):
                                                $svg  = \CFile::GetPath($card->getSvg()->getValue());
                                                ?>
                                                <div class="w-100"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . $svg)?></div>
                                            <?endif?>
                                            <div class="w-100 d-f fd-c rg-24 fg-1">
                                                <div class="f-20 lg:f-16 xl:f-20 ff-ud tt-l ftw-5 lh-1 ls--1 fg-1"><?=$card->getName()?></div>
                                                <?if(!empty($card->getPreviewText())):?>
                                                    <div class="f-14 lg:f-12 xl:f-14 fg-1"><?=$card->getPreviewText()?></div>
                                                <?endif;?>
                                                <?if(!empty($card->getLink())&& empty($user->getId())):?>
                                                    <button class="lang-form--reg <?=comp::get("btnGreenSm", "lg:f-10 xl:f-14 xxl:f-16", "xl:f-16")?>"><?=$card->getLink()->getDescription()?></button>
                                                <?endif?>
                                            </div>
                                        </div>
                                    </div>
                                <?endforeach?>
                            </div>
                        <?endif?>
                    </div>
                </div>
            </div>
        </section>
    <?endif?>
<?endif?>