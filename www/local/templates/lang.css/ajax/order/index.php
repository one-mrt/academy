<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
?>

<?$APPLICATION->IncludeComponent(
    "lang:request",
    "",
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
        "FORM_FIELD" => ["NAME"=>"Y","PHONE"=>"Y",'EMAIL'=>'Y',"DATE"=>"Y", "CALL" => "Y"],
        "GOOGLE_RECAPTCHA" => "N",
        "API_CODE" => "LangFormCalc",
        "REQUIRED_FIELDS" => ['NAME','PHONE','EMAIL', 'DATE', 'CALL'],
        "YANDEX_EVENT" => "console.log('Yandex Event')"
    ),
    false,
    ['HIDE_ICONS' => 'Y']
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>