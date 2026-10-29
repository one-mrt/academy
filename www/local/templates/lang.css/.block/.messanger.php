<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;

use Bitrix\Main\Loader;

Loader::includeModule('im');
?>

<?$APPLICATION->IncludeComponent(
    "bitrix:im.messenger",
    "iframe",
    Array(
        "CONTEXT" => "FULLSCREEN",
        // "CURRENT_TAB" => "order",
    ),
    false, Array("HIDE_ICONS" => "Y")
)?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>