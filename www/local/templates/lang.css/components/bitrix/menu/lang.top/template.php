<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

?>

<?if(!empty($arResult)):?>
	<nav class="d-n xl:d-f fg-1 ai-c">
		<ul class="h-100 d-f cg-24 ai-c lst-n m-0 p-0 xl:f-14 xxl:f-16 ftw-5 ws-nw lh-1">
			<?foreach($arResult as $arItem):
				if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
					continue;
				?>
				<li class="d-f">
					<a class="bc-t h:c-py tdt-1 tuo-8 tdc-py tn-all-3 <?if($arItem["SELECTED"]):?>tdl-u<?else:?>h:tdl-u<?endif?>" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
				</li>
			<?endforeach?>
		</ul>
	</nav>
<?endif?>
