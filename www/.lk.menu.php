<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;
$aMenuLinksExt = [];

use Bitrix\Main\Loader;

$lang = (SITE_ID == 'en') ? ucfirst(SITE_ID) : '';

Loader::includeModule('iblock');
Loader::includeModule('lang.iblock');

if(defined("BX_COMP_MANAGED_CACHE")) {
	$GLOBALS["CACHE_MANAGER"]->RegisterTag("LangMenuLk");
}

$aMenuLinks = $APPLICATION->IncludeComponent(
	"lang:menu.element",
	"",
	[
		"API_CODE" => "LangText",
		"SECTION_ID" => 66,
		"CACHE_TYPE" => "Y",
	],
	false,
	['HIDE_ICONS' => 'Y']
);