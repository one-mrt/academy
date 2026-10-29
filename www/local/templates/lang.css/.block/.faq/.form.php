<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";


if(!empty($arParams['elementId'])):
    $formTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'], ['ID' => $arParams['elementId']]);

    if(!empty($formTitle) && !empty($formTitle->getId())):?>
        <section class="w-100 of-h">
            <div class="<?=comp::get('container-full')?>">
                <div class="w-100 bc-ffffff80 r-24 pn-r" <?=Data::iblockElementEditAddId($formTitle)?>>
                    <div class="<?=comp::get('row')?>">
                        <div class="w-100 md:w-4/12 lg:w-5/12 xl:w-4/12 xxl:w-6/12 <?=comp::get("setcol")?> o-1 md:o-2 xxl:o-1">
                            <?if(!empty($formTitle->getPreviewPicture())):?>
                                <?=Data::getImageId($formTitle->getPreviewPicture(), [
                                    "alt" => strip_tags($formTitle->getName()),
                                    "loading" => "lazy",
                                    "class" => "w-100 h-100 oft-cv op-c d-n xxl:d-f"
                                ])?>
                            <?endif?>

                            <?if(!empty($formTitle->getDetailPicture())):?>
                                <?=Data::getImageId($formTitle->getDetailPicture(), [
                                    "alt" => strip_tags($formTitle->getName()),
                                    "loading" => "lazy",
                                    "class" => "w-100 h-500px sm:h-600px md:h-100 pn-a rt-0 b-0 xl:pn-s oft-c op-r xl:op-b d-f xxl:d-n fr-b4 md:fr-b2 xxl:fr-n"
                                ])?>
                            <?endif?>
                        </div>
                        <div class="w-100 md:w-8/12 lg:w-7/12 xl:w-8/12 xxl:w-5/12 <?=comp::get("setcol")?> o-2 md:o-1 xxl:o-2 pn-r z-1">
                            <div class="w-100 h-100 d-f fd-c jc-c pix-32 pby-32 xl:pby-64 rg-32">
                                <div class="w-100 sm:w-8/12 md:w-100 d-f fd-c rg-16">
                                    <h2 class="<?=comp::get("h2")?>"><?=$formTitle->getName()?></h2>
                                    <?if(!empty($formTitle->getPreviewText())):?>
                                        <div class="f-12 xl:f-14"><?=$formTitle->getPreviewText()?></div>
                                    <?endif?>
                                </div>
                                <div class="w-100">
                                    <?$APPLICATION->IncludeComponent(
                                        "lang:request",
                                        "block",
                                        Array(
                                            "AJAX_FORM_ACTIVE" => "Y",
                                            "AJAX_MODE" => "Y",
                                            "AJAX_OPTION_JUMP" => "N",
                                            "AJAX_OPTION_STYLE" => "N",
                                            "AJAX_OPTION_ADDITIONAL" => "",
                                            "CACHE_TIME" => "36000000",
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                                            "EVENT_MESSAGE_ID" => [42],
                                            "EVENT_NAME" => "FEEDBACK_FORM",
                                            "FORM_FIELD" => ["NAME"=>"Y","PHONE"=>"Y",'EMAIL'=>'Y',"MESSAGE"=>"Y"],
                                            "GOOGLE_RECAPTCHA" => "N",
                                            "API_CODE" => "LangFormCalc",
                                            "REQUIRED_FIELDS" => ['NAME', 'PHONE','EMAIL', 'MESSAGE'],
                                            "YANDEX_EVENT" => "console.log('Yandex Event')"
                                        ),
                                        false,
                                        ['HIDE_ICONS' => 'Y']
                                    );?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?endif?>
<?endif?>