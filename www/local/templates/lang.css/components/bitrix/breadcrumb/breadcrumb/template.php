<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

if(empty($arResult)) return "";

$strReturn = '';
$schemaData = [
	'@context' => 'http://schema.org',
	'@type' => 'BreadcrumbList',
	'itemListElement' => []
];
$strReturn .= '<div class="breadcrumb-list flex fw-w lh-cy f-12 c-we'.$arResult['modifier'].'">';

$itemSize = count($arResult);

for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$arrow = ($index > 0 ? '<i class="mr-3 ml-3">-</i>' : '');
	$schemaData['itemListElement'][$index]['@type'] = 'ListItem';
	$schemaData['itemListElement'][$index]['position'] = $index+1;
	$schemaData['itemListElement'][$index]['item']['@id'] = $arResult[$index]["LINK"];
	$schemaData['itemListElement'][$index]['item']['name'] = $title;

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1)
	{
		$strReturn .= $arrow . '<div class="breadcrumb--item flex">
			<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" class="tdl-u tuo-4 tdc-py hover:c-py tn-all-3">
				<span class="">'.$title.'</span>
			</a>
		</div>';
	}
	else
	{
		$strReturn .= $arrow . '<div class="breadcrumb--item flex">
			<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" class="tdl-u tuo-4 tdc-py hover:c-py tn-all-3"><span class="">'.$title.'</span></a>
		</div>';
	}
}

$strReturn .= '</div><script type="application/ld+json">'.json_encode($schemaData).'</script>';

return $strReturn;