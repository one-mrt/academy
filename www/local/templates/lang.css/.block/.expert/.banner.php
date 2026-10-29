<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$user = CurrentUser::get();
$lang = Data::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['elementId'])):
    $ban = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', "DETAIL_PICTURE", 'text'], ['ID' => $arParams['elementId']]);

    if(!empty($ban) && !empty($ban->getId())):?>
        <section class="w-100">
            <div class="<?=comp::get('container-full')?> d-f fd-c rg-0">
                <div class="w-100 xl:ar-100x43 of-h pn-r">

                    <div class="w-100 h-100 pn-a t-0 rt-0 b-0 l-0">
                        <div class="h-100 <?=comp::get("row")?>">
                            <div class="w-100 h-100 lg:w-6/12 <?=comp::get("setcol")?>">
                                <div class="w-100 h-100 of-h bc-w r-24">
                                    <?if(!empty($ban->getDetailPicture())):?>
                                        <?=Data::getImageId($ban->getDetailPicture(), [
                                            "alt" => strip_tags($ban->getName()),
                                            "title" => strip_tags($ban->getName()),
                                            "fetchpriority" => "high",
                                            "class" => "w-100 h-100 oft-cv op-c r-24",
                                        ])?>
                                    <?endif?>
                                </div>
                            </div>
                            <?if(!empty($ban->getPreviewPicture())):?>
                                <div class="w-100 h-100 lg:w-6/12 <?=comp::get("setcol")?> d-n lg:d-f">
                                    <div class="w-100 h-100 of-h bc-w r-24">
                                        <?=Data::getImageId($ban->getPreviewPicture(), [
                                            "alt" => strip_tags($ban->getName()),
                                            "title" => strip_tags($ban->getName()),
                                            "fetchpriority" => "high",
                                            "class" => "w-100 h-100 oft-cv op-c r-24",
                                        ])?>
                                    </div>
                                </div>
                            <?endif?>
                        </div>
                    </div>

                    <div class="w-100 h-100 pn-r">
                        <div class="<?=comp::get("container-p")?> h-100">
                            <div class="<?=comp::get("row")?> h-100">
                                <div class="w-100 lg:w-6/12 <?=comp::get("setcol")?>">
                                    <div class="w-100 h-100 pt-32 pb-32 pix-32 xl:pr-64 xxl:pl-0 d-f fd-c rg-32">
                                        <div class="w-100">
                                            <?$APPLICATION->IncludeComponent(
                                                "bitrix:breadcrumb",
                                                "breadcrumb.dark",
                                                [
                                                    "PATH" => "",
                                                    "SITE_ID" => SITE_ID,
                                                    "START_FROM" => "0"
                                                ],
                                                false,
                                                ['HIDE_ICONS' => 'Y']
                                            );?>
                                        </div>

                                        <div class="w-100 d-f fd-c jc-c rg-24 xl:pby-32 xxl:pby-86 fg-1">
                                            <h2 class="<?=comp::get("h2")?>"><?=$ban->getName()?></h2>
                                            <?if(!empty($ban->getPreviewText())):?>
                                                <div class="f-12 md:f-14 xl:f-18"><?=$ban->getPreviewText()?></div>
                                            <?endif?>

                                            <?if(!empty($ban->getText()) && !empty($ban->getText()->getAll())):
                                                $indBtn = 0;
                                                ?>
                                                <div class="w-100 d-f jc-s fw-w md:fw-n rg-16 sm:cg-24">
                                                    <?foreach ($ban->getText()->getAll() as $btn):
                                                        $modBtn = ($indBtn > 0) ? comp::get('btnPurpleBorderLg') : comp::get("btnPurpleLg");
                                                        
                                                        if(!empty($user->getId())):?>
                                                            <a href="<?=$btn->getValue()?>" class="<?=$modBtn?>"><?=$btn->getDescription()?></a>
                                                        <?else:?>
                                                            <button class="<?=($indBtn > 0) ? 'lang-form--reg' : 'lang-form--order-add'?> <?=$modBtn?>"><?=$btn->getDescription()?></button>
                                                        <?endif?>

                                                        <?$indBtn++;?>
                                                    <?endforeach?>
                                                </div>
                                            <?endif?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?endif?>
<?endif?>