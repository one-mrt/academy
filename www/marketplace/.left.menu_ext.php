<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/rest/install/public/marketplace/.left.menu_ext.php");

$arMenu = array();

$extranetSite = (
	\Bitrix\Main\Loader::includeModule('extranet')
	&& \CExtranet::isExtranetSite(SITE_ID)
);

if(
	!$extranetSite
	&& (
		SITE_TEMPLATE_ID == 'bitrix24'
		|| \Bitrix\Main\ModuleManager::isModuleInstalled('bitrix24')
	)
)
{
	$arMenu[] = Array(
		GetMessage("MENU_MARKETPLACE_ALL"),
		SITE_DIR."marketplace/",
		Array(),
		Array("menu_item_id" => "menu_marketplace"),
		""
	);
}

if(
	!$extranetSite
	&& CModule::IncludeModule("rest")
)
{
	if (\CRestUtil::isAdmin())
	{
		$arMenu[] = Array(
			GetMessage("MENU_MARKETPLACE_INSTALLED"),
			SITE_DIR."marketplace/installed/",
			Array(),
			Array("menu_item_id" => "menu_marketplace_installed"),
			""
		);
	}

	$arMenuApps = (new \Bitrix\Rest\Infrastructure\Menu\MenuApplicationProvider())
		->getLeftMenuItems((int)$USER->GetID())
	;

	if ($USER->IsAuthorized())
	{
		$urlDevOps = \Bitrix\Rest\Url\DevOps::getInstance()->getIndexUrl();
		$arMenu[] = [
			GetMessage("REST_MENU_MARKETPLACE_DEVOPS"),
			$urlDevOps,
			[],
			[
				"menu_item_id" => "menu_marketplace_hook"
			],
			"",
		];
	}

	$arMenu = array_merge($arMenu, $arMenuApps);
}


$aMenuLinks = array_merge($arMenu, $aMenuLinks);
