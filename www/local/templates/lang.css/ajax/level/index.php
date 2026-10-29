<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
?>

<?$APPLICATION->IncludeComponent(
	"lang:quiz",
	"block.opros.slider",
	Array(
        "API_CODE" => "LangQuizMain",
		"USE_CAPTCHA" => "N",
		"REQUIRED_FIELDS" => ['NAME', 'PHONE'],
		"EVENT_MESSAGE_ID" => [7],
		"EVENT_NAME" => "FEEDBACK_FORM",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"FORM_FIELD" => [
			'NAME' => 'Y',
			"PHONE" => "Y",
			'EMAIL' => 'N'
		],
		"YANDEX_EVENT" => "",
		"AJAX_FORM_ACTIVE" => "Y",
		"GOOGLE_RECAPTCHA" => "N",
	),
	false,
	['HIDE_ICONS' => 'Y']
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>