<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Lang\Main\Iblock\Util;
use Bitrix\Iblock\PropertyTable;

if(!isset($arParams["CACHE_TIME"])) {
	$arParams["CACHE_TIME"] = 36000000;
}

$iblockApiCode = $arParams['API_CODE'];
$iblock = Util::getIblockObject($iblockApiCode, ['select' => ['DETAIL_PAGE_URL']]);
$iblockTable = Util::getTableString($iblockApiCode);

$filter = ['ACTIVE' => 'Y'];
$select = ['NAME', 'CODE', 'IBLOCK_SECTION_ID'];

if(!empty($arParams["SECTION_ID"])) {
	$filter["IBLOCK_SECTION_ID"] = $arParams["SECTION_ID"];
}

$hasPropertyLink = PropertyTable::getList(['select' => ['ID'],'filter' => ['IBLOCK_ID' => $iblock->getId(), 'CODE' => 'link'], 'cache' => Util::getCache($arParams['CACHE_TIME'])])->fetchObject();
$hasPropertySvg = PropertyTable::getList(['select' => ['ID'],'filter' => ['IBLOCK_ID' => $iblock->getId(), 'CODE' => 'svg'], 'cache' => Util::getCache($arParams['CACHE_TIME'])])->fetchObject();

if(!empty($hasPropertyLink)) {
	$select[] = 'link';
}

if(!empty($hasPropertySvg)) {
	$select[] = 'svg';
}

$elementLink = Util::getElementsObject($iblockApiCode, [
	"order" => ['SORT' => 'ASC', "ID" => 'DESC'],
	'select' => $select,
	'filter' => $filter,
	'cache' => Util::getCache($arParams['CACHE_TIME'])
]);


$aMenuLinksNew = [];
$menuIndex = 0;
$previousDepthLevel = 1;

foreach($elementLink as $item) {
	if($menuIndex > 0) {
		$aMenuLinksNew[$menuIndex - 1][3]["IS_PARENT"] = 1 > $previousDepthLevel;
	}

	$previousDepthLevel = 1;

	$link = "";
	$param = [
		"FROM_IBLOCK" => true,
		"IS_PARENT" => false,
		"DEPTH_LEVEL" => 1,
		"edit" => Util::iblockElementEditAddId($item)
	];

	if(!empty($hasPropertyLink)) {
		$link = !empty($item->getLink()) ? $item->getLink()->getValue() : Util::getDetailPageUrl($iblock->getDetailPageUrl(), $item);
	} else {
		$link = Util::getDetailPageUrl($iblock->getDetailPageUrl(), $item);
	}

	if(!empty($hasPropertySvg)) {
		if(!empty($item->getSvg()) && !empty($item->getSvg()->getValue())) {
			$param['svg'] = file_get_contents($_SERVER["DOCUMENT_ROOT"].\CFile::GetPath($item->getSvg()->getValue()));
		}
	}

	$aMenuLinksNew[$menuIndex++] = [
		htmlspecialcharsbx($item->getName()),
		$link,
		[],
		$param,
	];
}


return $aMenuLinksNew;
?>
