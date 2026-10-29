<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Lang\Css\Component as comp;

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
	{
		return;
	}
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$btnModifier = "pt-16 pr-32 pb-16 pl-32 lg:pt-24 lg:pr-48 lg:pb-24 lg:pl-48 bi-ggn bw-0 bs-s bcr-527CC8 r-12 c-w h:c-dt f-20 ff-ud tt-l ftw-6 lh-1 tn-all-3";
?>

<?if($arResult["bDescPageNumbering"] === true):?>
	<?if($arResult["NavPageNomer"] > 1):?>
		<button class="<?=$btnModifier?>" onclick="loadPageEvents(this)" id="ajax_next_page" aria-label="Показать еще статьи"
			data-src="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>">
			Показать еще
		</button>
	<?endif?>
<?else:?>
	<?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
		<button class="<?=$btnModifier?>" onclick="loadPageEvents(this)" id="ajax_next_page" aria-label="Показать еще статьи"
			data-src="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>">
			Показать еще
		</button>
	<?endif?>
<?endif?>