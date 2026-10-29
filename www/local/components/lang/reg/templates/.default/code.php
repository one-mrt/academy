<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
?>

<div class="w-100 h-100 d-f ai-c jc-c bc-w">

	<div class="w-100 h-100 sm:mw-540px p-32 md:p-64 d-f fd-c rg-32 r-24">

		<div class="w-100 d-f fd-c rg-16">
			<h1 class="mb-0 ff-ud xl:f-24 ftw-6 tt-l ls--1 lh-1.125 ta-c">Подтверждение размещения заказа</h1>
            <div class="ta-c">отправили на вашу почту письмо, введите код из письма</div>
		</div>

		<?if(!empty($arResult['error'])):?>
			<div class="w-100 d-f fd-c rg-8">
				<?foreach($arResult['error'] as $mError):?>
					<div class=""><?=$mError?></div>
				<?endforeach;?>
			</div>
		<?endif;?>

        <form class="w-100 d-f fd-c rg-24" method="post" action="<?=$arResult["AUTH_URL"]?>">
			<?=bitrix_sessid_post();?>

			<input type="hidden" name="email" value="<?=$arResult['email']?>">
			<input type="hidden" name="code" value="y">

			<div class="w-100 d-f fd-c rg-16">
				<div class="">
					<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3" type="text" name="confirmCode" placeholder="Код" required>
				</div>
			</div>

			<div class="d-f jc-c">
				<button class="<?=comp::get("btnPurpleLg")?>" type="submit" name="code" value="Подтвердить">Подтвердить</button>
			</div>

		</form>

    </div>
</div>