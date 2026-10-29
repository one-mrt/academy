<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(false);

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
?>


<div class="w-100 h-100 d-f ai-c jc-c bc-w">

	<div class="w-100 h-100 sm:mw-540px p-48 sm:p-64 d-f fd-c rg-32 r-24">

		<div class="w-100 d-f fd-c rg-16">
			<h1 class="mb-0 f-24 ta-c <?=comp::get("fontUd")?>">Регистрация</h1>
		</div>

		<?if(!empty($arResult['error'])):?>
			<div class="w-100 d-f fd-c rg-8 f-12 xl:f-14">
				<?foreach($arResult['error'] as $mError):?>
					<div class=""><?=$mError?></div>
				<?endforeach;?>
			</div>
		<?endif;?>

		<form class="w-100 d-f fd-c rg-24" method="post" action="<?=$arResult["AUTH_URL"]?>">
			<?=bitrix_sessid_post();?>

			<input type="hidden" name="reg" value="y">

			<div class="w-100 d-f fd-c rg-16">
				<div class="">
					<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
						type="email" name="email" maxlength="255" value="<?=$arResult["login"]?>" placeholder="Email" required>
				</div>

				<div class="">
					<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
						type="password" name="password" maxlength="255" autocomplete="off" placeholder="Пароль" required>
				</div>
			</div>

			<div class="d-f jc-c">
				<button class="<?=comp::get("btnPurpleLg")?>" type="submit" name="reg" value="Зарегистрироваться">Зарегистрироваться</button>
			</div>

		</form>

		<div class="w-100 d-f ai-c jc-c cg-24 lh-1">
			<div class="f-14">Уже есть аккаунт?</div>
			<button class="lang-auth--button m-0 p-0 bc-t bw-0 ff-ud ftw-6 ls--1 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3">Войти</button>
		</div>
	</div>
</div>