<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;

$modifierField = "field--code pby-8 pix-4 bw-1 bs-s bcr-C5C5C5 r-4 f-32 ta-c h:bcr-py c-dt tn-all-3";
?>

<div class="w-100 h-100 d-f ai-c jc-c bc-w">

	<div class="w-100 h-100 sm:mw-540px p-32 md:p-64 d-f fd-c rg-32 r-24">

		<div class="w-100 d-f fd-c rg-16">
			<h1 class="mb-0 ff-ud xl:f-24 ftw-6 tt-l ls--1 lh-1.125 ta-c">Подтверждение размещения заказа</h1>
            <div class="ta-c f-14 c-8c94a8">отправили на вашу почту письмо, введите код из письма</div>
		</div>

        <form class="w-100 d-f fd-c rg-24" method="post" action="<?=$arResult["AUTH_URL"]?>">
			<?=bitrix_sessid_post();?>

			<input type="hidden" name="email" value="<?=$arResult['email']?>">
			<input type="hidden" name="type" value="<?=$arResult['type']?>">
			<input type="hidden" name="name" value="<?=$arResult['name']?>">
			<input type="hidden" name="code" value="y">

			<div class="w-100 d-f fd-c rg-16">
				<div class="w-100 d-f fd-c rg-16">
					<input class="lang--code-logic" type="hidden" name="confirmCode" placeholder="Код" required>
					<div class="w-100 d-f ai-c jc-c cg-8">
						<input class="<?=$modifierField?>" type="text" maxlength="1" size="1" autofocus="true">
						<input class="<?=$modifierField?>" type="text" maxlength="1" size="1">
						<input class="<?=$modifierField?> mr-8" type="text" maxlength="1" size="1">
						<input class="<?=$modifierField?>" type="text" maxlength="1" size="1">
						<input class="<?=$modifierField?>" type="text" maxlength="1" size="1">
						<input class="<?=$modifierField?>" type="text" maxlength="1" size="1">
					</div>
				</div>

				<?if(!empty($arResult['error'])):?>
					<div class="w-100 d-f fd-c ai-c rg-8">
						<?foreach($arResult['error'] as $mError):?>
							<div class="c-c90536 f-12 ta-c"><?=$mError?></div>
						<?endforeach;?>
					</div>
				<?endif;?>

				<div class="lang-reg--new-code w-100 d-f jc-c">
					<?if($arResult['timeM'] > 0 || $arResult['timeS'] > 0):?>
						<div class="reg--timer ta-c f-12 c-8c94a8">
							<span>Отправить код повторно можно через</span>
							<span class="ws-nw f-14">
								<?=$arResult['timeM'] >= 0 ? "<span class=\"reg--minute\">{$arResult['timeM']}</span>" : 0?>:<?=$arResult['timeS'] >= 0 ? "<span class=\"reg--seconds\">{$arResult['timeS']}</span>" : 0?>
							</span>
						</div>
					<?else:?>
						<input type="hidden" name="newCode" value="y">
						<button class="bw-0 bc-t c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3 f-12" type="submit">Отправить код повторно</button>
					<?endif;?>
				</div>

			</div>

			<div class="d-f jc-c">
				<button class="<?=comp::get("btnPurpleLg")?>" type="submit" name="code" value="Подтвердить">Подтвердить</button>
			</div>

		</form>

    </div>
</div>