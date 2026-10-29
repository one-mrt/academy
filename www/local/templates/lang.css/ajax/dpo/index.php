<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Application;

$request = Application::getInstance()->getContext()->getRequest();

if(!empty(intval($request->get('idir')))):?>
    <?$APPLICATION->IncludeComponent(
        "lang:dpo",
        "",
        Array(
            "API_CODE" => "LangQuizMain",
            "USE_CAPTCHA" => "N",
            "REQUIRED_FIELDS" => ['name', 'phone', 'email', 'cnils'],
            "EVENT_MESSAGE_ID" => [7],
            "EVENT_NAME" => "FEEDBACK_FORM",
            "AJAX_MODE" => "Y",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "FORM_FIELD" => [
                'NAME' => 'Y',
                "PHONE" => "Y",
                'EMAIL' => 'N'
            ],
            "YANDEX_EVENT" => "",
            // "AJAX_FORM_ACTIVE" => "Y",
            "GOOGLE_RECAPTCHA" => "N",
        ),
        false,
        ['HIDE_ICONS' => 'Y']
    );?>
<?endif;?>

<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>