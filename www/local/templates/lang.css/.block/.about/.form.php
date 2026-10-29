<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['elementId'])):

    $formTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'], ['ID' => $arParams['elementId']]);

    if(!empty($formTitle) && !empty($formTitle->getId())):?>
        <section class="w-100">
            <div class="<?=comp::get('container')?>">
                <div class="w-100 d-f fd-c rg-32 xl:rg-48 p-32 lg:p-48 bc-w pn-r of-h r-24">

                    <?if(!empty($formTitle->getPreviewPicture())):?>
                        <?=Data::getImageId($formTitle->getPreviewPicture(), [
                            "alt" => strip_tags($formTitle->getName()),
                            "loading" => "lazy",
                            "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c"
                        ])?>
                    <?endif?>

                    <?if(!empty($formTitle) && !empty($formTitle->getId())):?>
                        <div class="w-100 d-f fd-c rg-16 pn-r" <?=Data::iblockElementEditAddId($formTitle)?>>
                            <h2 class="<?=comp::get("h2")?>"><?=$formTitle->getName()?></h2>
                            <?if(!empty($formTitle->getPreviewText())):?>
                                <div class="f-12 lg:f-14"><?=$formTitle->getPreviewText()?></div>
                            <?endif?>
                        </div>
                    <?endif?>

                    <div class="w-100 pn-r">
                        <?$APPLICATION->IncludeComponent(
                            "lang:request",
                            "inline.block",
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
                                "FORM_FIELD" => ["NAME"=>"Y","PHONE"=>"Y",'EMAIL'=>'N',"MESSAGE"=>"Y"],
                                "GOOGLE_RECAPTCHA" => "N",
                                "API_CODE" => "LangFormCalc",
                                "REQUIRED_FIELDS" => ['NAME', 'PHONE', 'MESSAGE'],
                                "YANDEX_EVENT" => "console.log('Yandex Event')"
                            ),
                            false,
                            ['HIDE_ICONS' => 'Y']
                        );?>
                    </div>
                </div>
            </div>
        </section>
    <?endif?>
<?endif?>