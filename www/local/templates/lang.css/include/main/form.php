<?
use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;

// $site = ucfirst($_SESSION["SOTBIT_REGIONS"]["UF_PREFIX"]);
?>

<?$APPLICATION->IncludeComponent(
    "lang:request",
    "block",
    [
        "IBLOCK_ID" => "29",
        'ELEMENT_ID' => 13180,
        "REQUIRED_FIELDS" => ["NAME", "PHONE"],
        "EVENT_MESSAGE_ID" => [],
        "EVENT_NAME" => "FEEDBACK_FORM",
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO",
        "FORM_FIELD" => [
            "NAME" => "Y",
            "PHONE" => "Y",
        ],
        "YANDEX_EVENT" => "",
        "AJAX_FORM_ACTIVE" => "Y",
        "GOOGLE_RECAPTCHA" => "N",
    ]
);?>