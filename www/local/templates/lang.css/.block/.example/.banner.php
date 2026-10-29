<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['elementId'])):
    $user = CurrentUser::get();
    $banner = Data::getElementObject($langText,
        ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_PICTURE', 'text'],
        ['ID' => $arParams['elementId']]
    );

    if(!empty($banner) && !empty($banner->getId())):
        $indexBanner = 0;
        ?>
        <section class="w-100">
            <div class="<?=comp::get('container-full')?> d-f fd-c rg-0">

                <div class="w-100 md:ar-100x55 lg:ar-2x1 xxl:ar-100x43 r-24 bc-w of-h pn-r" <?=Data::iblockElementEditAddId($banner)?>>
                    <?if(!empty($banner->getDetailPicture())):?>
                        <?=Data::getImageId($banner->getDetailPicture(), [
                            "alt" => strip_tags($banner->getName()),
                            "fetchpriority" => "high",
                            "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 w-100 h-100 oft-cv op-c"
                        ])?>
                    <?endif?>

                    <div class="<?=comp::get('container-p')?> h-100 pn-r z-1">
                        <div class="<?=comp::get("row")?> h-100">
                            <div class="w-100 md:w-8/12 xl:w-7/12 xxl:w-6/12 <?=comp::get("setcol")?> pn-r z-1">

                                <div class="w-100 h-100 pix-32 xxl:pix-0 pt-32 d-f fd-c rg-32 xl:rg-48">
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

                                    <div class="w-100 sm:w-8/12 md:w-100 fg-1 d-f fd-c jc-c rg-24 pby-48 xl:pby-86">

                                        <h2 class="<?=comp::get("h2")?>"><?=$banner->getName()?></h2>
                                        <?if(!empty($banner->getPreviewText())):?>
                                            <div class="f-14 xl:f-18"><?=$banner->getPreviewText()?></div>
                                        <?endif?>

                                        <?if(!empty($banner->getText()) && !empty($banner->getText()->getAll())):
                                            $indBtn = 0;
                                            ?>
                                            <div class="w-100 d-f jc-s fw-w md:fw-n rg-16 sm:cg-24">
                                                <?foreach ($banner->getText()->getAll() as $btn):
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

                            <?if(!empty($banner->getPreviewPicture())):?>
                                <div class="w-100 md:w-4/12 xl:w-5/12 xxl:w-6/12 <?=comp::get("setcol")?> d-f ai-e">
                                    <?=Data::getImageId($banner->getPreviewPicture(), [
                                        "alt" => strip_tags($banner->getName()),
                                        "fetchpriority" => "high",
                                        "class" => "w-100 sm:w-80 md:w-a md:h-100 xl:h-a xl:w-6/12 xxl:w-100 pn-a xxl:pn-s b-0 rt-0 h-a oft-cv op-c fr-b8 md:fr-n"
                                    ])?>
                                </div>
                            <?endif?>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    <?endif?>
<?endif?>