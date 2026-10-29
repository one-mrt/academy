<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

\Bitrix\Main\Loader::registerAutoLoadClasses(
    'lang.iblock',
    [
        "Lang\Main\Iblock\Util" => "lib/main/iblock/util.php",
    ]
);

?>