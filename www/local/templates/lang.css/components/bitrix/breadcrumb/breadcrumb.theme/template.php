<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Lang\Main\Css\Modifier;

if(empty($arResult)) return "";

$strReturn = '';
$schemaData = [
	'@context' => 'http://schema.org',
	'@type' => 'BreadcrumbList',
	'itemListElement' => []
];
$strReturn .= '<div class="breadcrumb-list ' . Modifier::get("flex fw-w lh-nl f-14 xl:f-16 ftw-4 c-we ff-vm gap-8") . '">';
$itemSize = count($arResult);

for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$arrow = ($index > 0 ? '<i class="' . Modifier::get("") . '"><svg width="9" height="13" viewBox="0 0 9 13" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M3.536 0.0399995H4.346L0.89 13H0.08L3.536 0.0399995ZM7.81592 0.0399995H8.62592L5.16992 13H4.35992L7.81592 0.0399995Z"/></svg></i>' : '');
	$schemaData['itemListElement'][$index]['@type'] = 'ListItem';
	$schemaData['itemListElement'][$index]['position'] = $index+1;
	$schemaData['itemListElement'][$index]['item']['@id'] = $arResult[$index]["LINK"];
	$schemaData['itemListElement'][$index]['item']['name'] = $title;

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1)
	{
		$strReturn .= $arrow . '<div class="breadcrumb--item ' . Modifier::get("flex") . '">
			<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" class="' . Modifier::get("tdl-u tuo-4 tdc-py hover:c-py tn-all-3") . '">
				<span class="">'.$title.'</span>
			</a>
		</div>';
	}
	else
	{
		$strReturn .= $arrow . '<div class="breadcrumb--item ' . Modifier::get("flex") . '">
			<a href="'.$arResult[$index]["LINK"].'" title="'.$title.'" class="' . Modifier::get("tdl-u tuo-4 tdc-py hover:c-py tn-all-3") . '"><span class="">'.$title.'</span></a>
		</div>';
	}
}

$strReturn .= '</div><script type="application/ld+json">'.json_encode($schemaData).'</script>';

return $strReturn;