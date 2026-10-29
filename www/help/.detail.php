<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Справочник");?>

<?$APPLICATION->IncludeComponent(
    "lang:include",
    "",
    [
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => '/help/.detail.php',
        "code" => $code
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>