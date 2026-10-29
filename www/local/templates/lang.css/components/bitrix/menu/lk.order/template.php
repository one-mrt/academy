<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!empty($arResult)):?>
	<nav class="d-f jc-c">
		<ul class="d-f fd-c rg-24 lst-n m-0 p-0">
			<?foreach($arResult as $arItem):
				if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) {
					continue;
				}
				?>
				<li class="" <?=$arItem['PARAMS']['edit']?>>
					<a class="d-f ai-c cg-16 f-12 ftw-6 lh-1 tn-all-3 h:d-f>.name pn-r <?if($arItem["SELECTED"]):?>c-py<?else:?>h:c-py<?endif?>"
							href="<?=$arItem["LINK"]?>" title="<?=strip_tags($arItem["TEXT"])?>">
						<?if(!empty($arItem['PARAMS']['svg'])):?>
							<div class=""><?=$arItem['PARAMS']['svg']?></div>
						<?endif;?>
						<div class="name d-n pn-a l-32 ws-nw lh-1 pby-4 pix-8 r-4 bc-py c-w"><?=$arItem["TEXT"]?></div>
					</a>
				</li>
			<?endforeach;?>
		</ul>
	</nav>
<?endif;?>