<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;
$aMenuLinksExt = [];

use Bitrix\Main\Loader;

Loader::includeModule('iblock');
Loader::includeModule('lang.iblock');

if(defined("BX_COMP_MANAGED_CACHE")) {
	$GLOBALS["CACHE_MANAGER"]->RegisterTag("iblock_id_LangMenu");
}

$aMenuLinks = $APPLICATION->IncludeComponent(
	"lang:menu.element", "",
	["API_CODE" => "LangService","CACHE_TYPE" => "Y"],
	false,['HIDE_ICONS' => 'Y']
);
?>