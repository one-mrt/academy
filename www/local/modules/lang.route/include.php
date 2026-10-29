<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::registerAutoLoadClasses(
    'lang.route',
    [
        "Lang\Route\Page" => "lib/Page.php",
    ]
);

?>