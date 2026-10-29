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
                            "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c"
                        ])?>
                    <?endif?>

                    <?if(!empty($banner->getPreviewPicture())):?>
                        <div class="w-100 h-100 pn-a t-0 rt-0 b-0 l-0">
                            <div class="<?=comp::get("row")?> h-100 jc-e">
                                <div class="w-100 h-100 md:w-8/12 lg:w-5/12 xl:w-6/12 <?=comp::get('setcol')?> d-f ai-e jc-e pn-r">
                                    <?=Data::getImageId($banner->getPreviewPicture(), [
                                        "alt" => strip_tags($banner->getName()),
                                        "title" => strip_tags($banner->getName()),
                                        "fetchpriority" => "high",
                                        "class" => "w-a h-300px pn-a fr-b8 b-0 sm:pn-s md:fr-n md:w-a md:h-350px lg:h-450px xl:h-550px xxl:h-600px xxl:w-100 xxl:h-100 oft-cv op-r",
                                    ])?>
                                </div>
                            </div>
                        </div>
                    <?endif?>

                    <div class="<?=comp::get('container-p')?> h-100 pn-r z-1">
                        <div class="<?=comp::get("row")?> h-100">
                            <div class="pn-r z-1 w-100 sm:w-10/12 md:w-7/12 lg:w-7/12 xxl:w-6/12 pr-16 xl:pr-0 pl-16 d-f fd-c fg-0 fs-0">

                                <div class="w-100 pix-32 md:pl-16 lg:pl-32 xxl:pl-0 pt-32">
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

                                <div class="w-100 d-f fd-c jc-c rg-24 pix-32 pby-48 md:pl-16 lg:pl-32 xxl:pl-0 xl:pby-86 fg-1">

                                    <h2 class="<?=comp::get("h2")?>"><?=$banner->getName()?></h2>
                                    <?if(!empty($banner->getPreviewText())):?>
                                        <div class="w-100 sm:w-10/12 md:w-100 f-12 lg:f-14 xxl:f-18"><?=$banner->getPreviewText()?></div>
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
                                                    <button class="<?=($indBtn > 0) ? 'lang-form--order-add' : 'lang-form--reg'?> <?=$modBtn?>"><?=$btn->getDescription()?></button>
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
        </section>
    <?endif?>
<?endif;?>