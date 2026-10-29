<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!empty($arResult)):?>
	<?foreach($arResult as $arItem):
		if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) {
			continue;
		}
		?>
		<a class="h:c-py tdt-1 tuo-4 tdc-py tn-all-3 h:tdl-u <?if($arItem["SELECTED"]):?>c-py<?else:?>h:c-py<?endif?>" href="<?=$arItem["LINK"]?>" <?=$arItem['PARAMS']['edit']?>>
			<div class=""><?=$arItem["TEXT"]?></div>
		</a>
	<?endforeach;?>
<?endif;?>