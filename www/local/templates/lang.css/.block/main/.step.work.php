<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Util::getLang();
$langText = "LangText{$lang}";

$title = Data::getSectionObject($langText, ['select' => ['NAME'],'filter' => ['ID' => $arParams['sectionId']]]);
$cardList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT', 'svg', 'link'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);
$user = CurrentUser::get();

if(!empty($cardList)):?>
    <section class="w-100">
        <div class="<?=comp::get('container')?> d-f fd-c rg-32">

            <?if(!empty($title) && !empty($title->getId())):?>
                <div class="w-100 xl:w-8/12" <?=Data::iblockSectionEditAddId($title)?>>
                    <h2 class="<?=comp::get("h2")?>"><?=$title->getName()?></h2>
                </div>
            <?endif?>

            <?if(!empty($cardList) && !empty($cardList->getIdList())):?>
                <div class="<?=comp::get('row')?> rg-32">
                    <?foreach ($cardList as $card):?>
                        <div class="w-100 md:w-6/12 lg:w-3/12 xl:w-3/12 pr-16 pl-16 fg-0 fs-0">
                            <div class="w-100 h-100 p-32 lg:p-24 xl:p-32 d-f fd-c rg-48 r-12 bc-w" <?=Data::iblockElementEditAddId($card)?>>
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
                                    <?if(!empty($card->getLink()) && empty($user->getId())):?>
                                        <button class="lang-form--reg <?=comp::get("btnBlueFillSm", "lg:f-10")?>"><?=$card->getLink()->getDescription()?></button>
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