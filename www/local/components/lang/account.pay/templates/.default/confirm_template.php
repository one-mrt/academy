<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

?>

<div class="form--pay w-100 h-100 p-32 d-f fd-c ai-c jc-c fg-1">
	<?if (!empty($arResult["error"])):?>
        <div class="w-100 p-32 d-f fd-c rg-8 fg-0 f-12 xl:f-14">
            <?foreach($arResult['error'] as $mError):?>
                <div class="c-c90536"><?=$mError?></div>
            <?endforeach;?>
        </div>
	<?else:?>
		<?=$arResult['order']['TEMPLATE'];?>
	<?endif;?>
</div>