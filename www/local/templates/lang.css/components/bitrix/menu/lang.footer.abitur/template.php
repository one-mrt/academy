<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Lang\Css\Component as comp;
?>

<?if(!empty($arResult)):?>
	<nav class="w-100d-f fg-1 ai-c">
		<ul class="<?=comp::get("row")?> lst-n mt-0 mb-0 p-0 f-14 ftw-4 rg-8">
			<?foreach($arResult as $arItem):
				if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
					continue;
				?>
				<li class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0 d-f">
					<a class="h:tdl-u tdt-1 tuo-2 lh-1 <?if($arItem["SELECTED"]):?>c-py<?else:?>h:c-py<?endif?> tn-all-3" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
				</li>
			<?endforeach?>
		</ul>
	</nav>
<?endif?>
