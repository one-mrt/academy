<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$user = CurrentUser::get();

if(!empty($arParams['elementId'])):
    $banBottom = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'link'], ['ID' => $arParams['elementId']]);

    if(!empty($banBottom) && !empty($banBottom->getId())):?>
        <section class="w-100">
            <div class="<?=comp::get('container-full')?>">
                <div class="w-100 pn-r of-h r-24 bc-w">

                    <?if(!empty($banBottom->getDetailPicture())):?>
                        <?=Data::getImageId($banBottom->getDetailPicture(), [
                            "alt" => strip_tags($banBottom->getName()),
                            "loading" => "lazy",
                            "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c r-12",
                        ]);?>
                    <?endif?>

                    <?if(!empty($banBottom->getPreviewPicture())):?>
                        <div class="w-100 h-100 pn-a t-0 rt-0 b-0 l-0">
                            <div class="<?=comp::get("row")?> h-100 jc-e">
                                <div class="w-100 sm:w-10/12 md:w-8/12 lg:w-6/12 xl:w-8/12 xxl:w-6/12 <?=comp::get('setcol')?> d-f ai-e pn-r">
                                    <?=Data::getImageId($banBottom->getPreviewPicture(), [
                                        "alt" => strip_tags($banBottom->getName()),
                                        "fetchpriority" => "high",
                                        "class" => "w-100 h-a xxl:h-100 oft-cv op-c fr-b4 md:fr-n",
                                    ]);?>
                                </div>
                            </div>
                        </div>
                    <?endif?>

                    <div class="<?=comp::get("container-p")?> pn-r z-1" <?=Data::iblockElementEditAddId($banBottom)?>>
                        <div class="<?=comp::get('row')?>">
                            <div class="w-100 md:w-8/12 xl:w-6/12 <?=comp::get('setcol')?>">
                                <div class="w-100 h-100 d-f fd-c rg-24 jc-c pl-32 pby-48 lg:pby-48 xl:pby-86 xxl:pl-0">
                                    <h2 class="<?=comp::get("h2")?>"><?=$banBottom->getName()?></h2>
                                    <?if(!empty($banBottom->getPreviewText())):?>
                                        <div class="sm:w-8/12 f-14 md:f-12 lg:f-14 xl:f-16"><?=$banBottom->getPreviewText()?></div>
                                    <?endif?>
                                    <?if(!empty($banBottom->getLink())):?>
                                        <div class="w-100 d-f jc-s">
                                            <?if(!empty($user->getId())):?>
                                                <a href="<?=$banBottom->getLink()->getValue()?>" class="<?=comp::get('btnGreenLg')?>"><?=$banBottom->getLink()->getDescription()?></a>
                                            <?else:?>
                                                <button class="lang-form--order-add <?=comp::get("btnGreenLg")?>"><?=$banBottom?->getLink()?->getDescription()?></button>
                                            <?endif?>
                                        </div>
                                    <?endif?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?endif?>
<?endif?>