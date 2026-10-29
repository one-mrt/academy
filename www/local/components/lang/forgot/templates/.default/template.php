<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

?>

<div class="w-100 d-f jc-c">
	<div class="w-100 w-100 sm:mw-540px p-48 sm:p-64 d-f fd-c rg-32 bc-w r-24">

		<?if(!empty($arResult['success'])):?>
			<div class="f-12 xl:f-14 ta-c"><?=$arResult['success'];?></div>
		<?else:?>
			<div class="w-100 d-f fd-c rg-16">
				<h1 class="mb-0 ff-ud f-24 ftw-6 tt-l ls--1 lh-1.125 ta-c">Восстановление пароля</h1>
				<div class="f-12 sm:f-14 ta-c">мы отправим вам ссылку на указанный электронный адрес, перейдя по которой вы сможете задать новый пароль.</div>
			</div>

			<form class="w-100 d-f fd-c rg-24" name="bform" method="post">
				<?=bitrix_sessid_post();?>

				<?if(!empty($arResult['error'])):?>
					<div class="w-100 d-f fd-c rg-8">
						<?foreach ($arResult['error'] as $error):?>
							<div class=""><?=$error;?></div>
						<?endforeach;?>
					</div>
				<?endif;?>

				<div class="w-100 d-f fd-c rg-16">
					<div class="">
						<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
							type="email" name="email" maxlength="255" placeholder="<?=Loc::getMessage('MAIN_AUTH_PWD_FIELD_EMAIL')?>" required>
					</div>
				</div>

				<div class="d-f jc-c">
					<button type="submit" class="<?=comp::get("btnPurpleLg")?>" value="<?=Loc::getMessage('MAIN_AUTH_PWD_FIELD_SUBMIT');?>"><?=Loc::getMessage('MAIN_AUTH_PWD_FIELD_SUBMIT');?></button>
				</div>

			</form>
		<?endif;?>

		<div class="w-100 d-f ai-c jc-c cg-24 lh-1">
			<button class="lang-auth--button m-0 p-0 bc-t bw-0 ff-ud f-14 sm:f-16 ftw-6 ls--1 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3">Войти</button>
			<button class="lang-form--reg m-0 p-0 bc-t bw-0 ff-ud f-14 sm:f-16 ftw-6 ls--1 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3">Зарегистрироваться</button>
		</div>
	</div>
	<script>document.bform.<?=$arResult['FIELDS']['login']?>.focus()</script>
</div>
