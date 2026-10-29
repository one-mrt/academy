<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Lang\Main\Iblock\Util;
use Bitrix\Main\Localization\Loc;

if(!empty($arResult)):?>
	<nav class="w-100 grow-1">
		<ul class="d-f fd-c rg-24 lst-n m-0 p-0">
			<?foreach($arResult as $arItem):
				if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
				{
					continue;
				}
				?>
				<li class="w-100" <?=$arItem['PARAMS']['edit']?>>
					<a class="d-f ai-c cg-16 f-14 xl:f-18 ftw-6 lh-1 tn-all-3 <?if($arItem["SELECTED"]):?>c-py<?else:?>h:c-py<?endif?>" href="<?=$arItem["LINK"]?>">
						<?if(!empty($arItem['PARAMS']['svg'])):?>
							<div class=""><?=$arItem['PARAMS']['svg']?></div>
						<?endif;?>
						<div class=""><?=$arItem["TEXT"]?></div>
					</a>
				</li>
			<?endforeach;?>
		</ul>
	</nav>
<?endif;?>