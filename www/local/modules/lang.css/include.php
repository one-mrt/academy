<?
use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'lang.css',
    [
        "Lang\Css\BitrixEvent" => "lib/css/bitrixEvent.php",
        "Lang\Css\Component" => "lib/css/component.php",
    ]
);

?>