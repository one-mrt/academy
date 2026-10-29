<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

if(empty($arResult)) return "";

$strReturn = '';
$schemaData = [
	'@context' => 'http://schema.org',
	'@type' => 'BreadcrumbList',
	'itemListElement' => []
];
$strReturn .= '<div class="f-10 xl:f-14 ftw-5">';
$itemSize = count($arResult);

for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$arrow = ($index > 0 ? '<i> – </i>' : '');
	$schemaData['itemListElement'][$index]['@type'] = 'ListItem';
	$schemaData['itemListElement'][$index]['position'] = $index+1;
	$schemaData['itemListElement'][$index]['item']['@id'] = $arResult[$index]["LINK"];
	$schemaData['itemListElement'][$index]['item']['name'] = $title;

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1)
	{
		$strReturn .= "{$arrow}<span><a href=\"{$arResult[$index]["LINK"]}\" title=\"{$title}\" class=\"h:tdl-u tuo-4 tdc-py h:c-py tn-all-3\">{$title}</a></span>";
	}
	else
	{
		$strReturn .= "{$arrow}<span class=\"c-py\">{$title}</span>";
	}
}

$strReturn .= '</div><script type="application/ld+json">'.json_encode($schemaData).'</script>';

return $strReturn;