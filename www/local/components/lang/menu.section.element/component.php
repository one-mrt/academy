<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;

if(!isset($arParams["CACHE_TIME"]))
{
	$arParams["CACHE_TIME"] = 36000000;
}

$arParams["ID"] = intval(($arParams["ID"] ?? 0));
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
$arParams["DEPTH_LEVEL"] = intval($arParams["DEPTH_LEVEL"]);

if($arParams["DEPTH_LEVEL"] <= 0)
{
	$arParams["DEPTH_LEVEL"] = 1;
}

$arResult["SECTIONS"] = [];
$arResult["ELEMENT_LINKS"] = [];

if($this->StartResultCache())
{
	if(!Loader::includeModule("iblock"))
	{
		$this->AbortResultCache();
	}
	else
	{
		$iblockApiCode = $arParams['API_CODE'];
		$iblock = Util::getIblock(['SECTION_PAGE_URL', 'DETAIL_PAGE_URL'], ['API_CODE' => $arParams['API_CODE']]);
		$iblockTable = Util::getTableString($iblockApiCode);

		// array(
		// 	'IBLOCK_ID' => 2,
		// 	'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],
		// 	'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],
		// 	'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']
		// )

		$filter = [];
		
		if(!empty($arParams['SECTION_ID']))
		{
			$parentSection = Util::getSection($iblockApiCode, ['LEFT_MARGIN', 'RIGHT_MARGIN', 'DEPTH_LEVEL'], ['ID' => $arParams['SECTION_ID']]);
			$filter = [
				'>LEFT_MARGIN' => $parentSection['LEFT_MARGIN'],
				'<RIGHT_MARGIN' => $parentSection['RIGHT_MARGIN'],
				'>DEPTH_LEVEL' => $parentSection['DEPTH_LEVEL']
			];
		}

		$sectionList = Util::getSections($iblockApiCode, [
			'order' => ["left_margin" => "ASC"],
			'select' => ['ID', 'NAME', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID', 'CODE', 'UF_LINK'],
			'filter' => $filter,
		]);
		// $arFilter = [
		// 	"IBLOCK_ID"=>$arParams["IBLOCK_ID"],
		// 	"GLOBAL_ACTIVE"=>"Y",
		// 	"IBLOCK_ACTIVE"=>"Y",
		// 	"<=DEPTH_LEVEL" => $arParams["DEPTH_LEVEL"],
		// ];
		// $arOrder = ["left_margin" => "asc"];

		// $rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, [
		// 	"ID",
		// 	"DEPTH_LEVEL",
		// 	"NAME",
		// 	// "UF_MENU",
		// 	// "UF_HOVER",
		// 	'UF_LINK',
		// 	"SECTION_PAGE_URL",
		// ]);

		foreach($sectionList as $arSection)
		{
			$arResult["SECTIONS"][$arSection["ID"]] = [
				"ID" => $arSection["ID"],
				"DEPTH_LEVEL" => intval($arSection["DEPTH_LEVEL"]) - (intval($parentSection['DEPTH_LEVEL']) ?: 0),
				"NAME" => $arSection["NAME"],
				"SECTION_PAGE_URL" => $arSection['UF_LINK'] ?: Util::getDetailPageUrl($iblock['SECTION_PAGE_URL'], $arSection, true, 'S')
			];
		}



		// while($arSection = $rsSections->GetNext())
		// {
		// 	$arResult["SECTIONS"][$arSection["ID"]] = [
		// 		"ID" => $arSection["ID"],
		// 		"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
		// 		"~NAME" => $arSection["~NAME"],
		// 		"~SECTION_PAGE_URL" => $arSection['~UF_LINK'] ? $arSection['~UF_LINK'] : $arSection["~SECTION_PAGE_URL"],
		// 		// 'hover' => $arSection['UF_HOVER'] ? 'Y' : 'N'
		// 	];

		// 	$arResult["ELEMENT_LINKS"][$arSection["ID"]] = array();
		// }

		$sectionList = [];

		foreach($arResult["SECTIONS"] as $arSection)
		{
			$sectionList[] = $arSection["ID"];
		}

		$elementLink = $iblockTable::getList([
			"order" => ['SORT' => 'ASC', "NAME" => 'ASC'],
			'select' => ['ID', 'IBLOCK_ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'l' => 'link.VALUE'],
			'filter' => ['IBLOCK_SECTION_ID' => $sectionList, 'ACTIVE' => 'Y'],
			'cache' => Util::getCache($arParams['CACHE_TIME'])
		])->fetchAll();

		foreach($elementLink as $item)
		{
			$item["DETAIL_PAGE_URL"] = $item['l'] ?: Util::getDetailPageUrl($iblock['DETAIL_PAGE_URL'], $item);
			$arResult["SECTIONS"][$item["IBLOCK_SECTION_ID"]]["item"][] = $item;
		}

		$this->EndResultCache();
	}
}

//In "SEF" mode we'll try to parse URL and get ELEMENT_ID from it
// if($arParams["IS_SEF"] === "Y")
// {
// 	$engine = new CComponentEngine($this);

// 	if (CModule::IncludeModule('iblock'))
// 	{
// 		$engine->addGreedyPart("#SECTION_CODE_PATH#");
// 		$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
// 	}

// 	$componentPage = $engine->guessComponentPath(
// 		$arParams["SEF_BASE_URL"],
// 		[
// 			"section" => $arParams["SECTION_PAGE_URL"],
// 			"detail" => $arParams["DETAIL_PAGE_URL"],
// 		],
// 		$arVariables
// 	);

// 	if($componentPage === "detail")
// 	{
// 		CComponentEngine::InitComponentVariables(
// 			$componentPage,
// 			array("SECTION_ID", "ELEMENT_ID"),
// 			array(
// 				"section" => array("SECTION_ID" => "SECTION_ID"),
// 				"detail" => array("SECTION_ID" => "SECTION_ID", "ELEMENT_ID" => "ELEMENT_ID"),
// 			),
// 			$arVariables
// 		);
// 		$arParams["ID"] = intval($arVariables["ELEMENT_ID"]);
// 	}
// }

// if(($arParams["ID"] > 0) && (intval($arVariables["SECTION_ID"]) <= 0))
// {
// 	$arSelect = ["ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID"];
// 	$arFilter = [
// 		"ID" => $arParams["ID"],
// 		"ACTIVE" => "Y",
// 		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
// 	];

// 	$rsElements = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);

// 	if(($arParams["IS_SEF"] === "Y") && ($arParams["DETAIL_PAGE_URL"] <> ''))
// 	{
// 		$rsElements->SetUrlTemplates($arParams["SEF_BASE_URL"].$arParams["DETAIL_PAGE_URL"]);
// 	}

// 	while($arElement = $rsElements->GetNext())
// 	{
// 		$arResult["ELEMENT_LINKS"][$arElement["IBLOCK_SECTION_ID"]][] = $arElement["~DETAIL_PAGE_URL"];
// 	}
// }

$aMenuLinksNew = [];
$menuIndex = 0;
$previousDepthLevel = 1;

foreach($arResult["SECTIONS"] as $arSection)
{
	if($menuIndex > 0)
	{
		$aMenuLinksNew[$menuIndex - 1][3]["IS_PARENT"] = $arSection["DEPTH_LEVEL"] > $previousDepthLevel;
	}

	$previousDepthLevel = $arSection["DEPTH_LEVEL"];

	$arResult["ELEMENT_LINKS"][$arSection["ID"]][] = urldecode($arSection["SECTION_PAGE_URL"]);
	$aMenuLinksNew[$menuIndex++] = [
		htmlspecialcharsbx($arSection["NAME"]),
		$arSection["SECTION_PAGE_URL"],
		[],
		[
			"FROM_IBLOCK" => true,
			"IS_PARENT" => false,
			"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
			"type" => 'section',
		],
	];

	if(!empty($arSection['item']))
	{
		$aMenuLinksNew[$menuIndex - 1][3]["IS_PARENT"] = true;
	}

	foreach($arSection['item'] as $item)
	{
		$aMenuLinksNew[$menuIndex++] = [
			htmlspecialcharsbx($item['NAME']),
			$item["DETAIL_PAGE_URL"],
			[],
			[
				"FROM_IBLOCK" => true,
				"IS_PARENT" => false,
				"DEPTH_LEVEL" => intval($arSection["DEPTH_LEVEL"]) + 1,
				"type" => 'element'
			],
		];
	}
}

return $aMenuLinksNew;
?>
