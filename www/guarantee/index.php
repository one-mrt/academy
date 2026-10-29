<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Гарантии");?>

<?$APPLICATION->IncludeComponent(
    "lang:include",
    "",
    [
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => '/guarantee/.index.php',
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>