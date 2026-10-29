<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$user = CurrentUser::get();
$lang = Util::getLang();
$langText = "LangText{$lang}";

$humanTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'link'],['ID' => $arParams['elementId']]);
$humanList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT', 'svg'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);

if(!empty($humanList) && !empty($humanList->getIdList())):?>
    <section class="w-100">
        <div class="<?=comp::get("container")?>">
            <div class="<?=comp::get("row")?>">
                <div class="w-100 lg:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> d-f fd-c rg-48">

                    <?if(!empty($humanTitle)):?>
                        <div class="w-100 d-f fd-c rg-24" <?=Data::iblockElementEditAddId($humanTitle)?>>
                            <h2 class="<?=comp::get("h2")?>"><?=$humanTitle->getName()?></h2>
                            <?if(!empty($humanTitle->getPreviewText())):?>
                                <div class="f-14 xl:f-18"><?=$humanTitle->getPreviewText()?></div>
                            <?endif?>
                        </div>
                    <?endif?>

                    <div class="w-100 d-f fd-c rg-32">
                        <?foreach($humanList as $human):?>
                            <div class="w-100 d-f cg-24" <?=Data::iblockElementEditAddId($human)?>>
                                <?if(!empty($human->getSvg())):?>
                                    <div class="d-f"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($human->getSvg()->getValue()))?></div>
                                <?endif?>
                                <div class="d-f fd-c rg-16 fg-1">
                                    <div class="xl:f-24 ff-ud ftw-6 lh-1 tt-l"><?=$human->getName()?></div>
                                    <div class="f-14 lg:f-12 xl:f-14"><?=$human->getPreviewText()?></div>
                                </div>
                            </div>
                        <?endforeach?>
                    </div>

                    <?if(!empty($humanTitle) && !empty($humanTitle->getLink())):?>
                        <div class="w-100 d-f jc-s">
                            <?if(!empty($user->getId())):?>
                                <a href="<?=$humanTitle->getLink()->getValue()?>" class="<?=comp::get("btnPurpleLg")?>"><?=$humanTitle->getLink()->getDescription()?></a>
                            <?else:?>
                                <button class="lang-form--order-add <?=comp::get("btnPurpleLg")?>"><?=$humanTitle->getLink()->getDescription()?></button>
                            <?endif?>
                        </div>
                    <?endif?>
                </div>

                <?if(!empty($humanTitle)):?>
                    <div class="w-100 lg:w-7/12 xl:w-7/12 <?=comp::get("setcol")?> d-n lg:d-f">
                        <?if(!empty($humanTitle->getPreviewPicture())):?>
                            <div class="w-100 h-100 pn-r">
                                <?=Data::getImageId($humanTitle->getPreviewPicture(), [
                                    "title" => strip_tags($humanTitle->getName()),
                                    "alt" => strip_tags($humanTitle->getName()),
                                    "loading" => "lazy",
                                    "class" => "w-100 h-100 oft-cv lg:oft-c xl:oft-cv op-c",
                                ])?>
                            </div>
                        <?endif?>
                    </div>
                <?endif?>
            </div>
        </div>
    </section>
<?endif?>