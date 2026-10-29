<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

<?$APPLICATION->IncludeComponent(
    "lang:include",
    "",
    [
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => '/expert/.detail.php',
        "code" => $code
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>