<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::registerAutoLoadClasses(
    'lang.region',
    [
        "Lang\Region" => "lib/region.php",
        "Lang\Region\Page" => "lib/routes.php",
    ]
);

?>