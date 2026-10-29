<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$fields = $arResult['FIELDS'];
?>

<div class="w-100 d-f jc-c">
	<div class="w-100 w-100 sm:mw-540px p-48 sm:p-64 d-f fd-c rg-32 bc-w r-24">

		<?if($arResult['SUCCESS']):?>
			<div class="f-12 xl:f-14"><?=$arResult['SUCCESS']?></div>
		<?else:?>
			<div class="w-100 d-f fd-c rg-16">
				<h1 class="mb-0 ff-ud f-24 ftw-6 tt-l ls--1 lh-1.125 ta-c"><?= Loc::getMessage('MAIN_AUTH_CHD_HEADER');?></h1>
			</div>

			<form class="w-100 d-f fd-c rg-24" name="bform" method="post">
				<?=bitrix_sessid_post();?>
				<input type="hidden" name="AUTH_ACTION" value="y">
				<input type="hidden" name="<?=$fields['login']?>" maxlength="255" value="<?=\htmlspecialcharsbx($arResult['LAST_LOGIN']);?>">
				<input type="hidden" name="<?= $fields['checkword'];?>" maxlength="255" value="<?= \htmlspecialcharsbx($arResult[$fields['checkword']]);?>">

				<?if($arResult['ERRORS']):?>
					<div class="w-100 d-f fd-c rg-8 f-12 xl:f-14">
						<?foreach ($arResult['ERRORS'] as $error):?>
							<div class=""><?=$error?></div>
						<?endforeach;?>
					</div>
				<?endif;?>

				<div class="w-100 d-f fd-c rg-16">
					<div class="">
						<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3" type="password" name="<?=$fields['password'];?>" required
							value="<?=\htmlspecialcharsbx($arResult[$fields['password']])?>" maxlength="255" autocomplete="off" placeholder="<?=Loc::getMessage('MAIN_AUTH_CHD_FIELD_PASS')?>">
					</div>
					<div class="">
						<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3" type="password" name="<?=$fields['confirm_password'];?>" required
							value="<?=\htmlspecialcharsbx($arResult[$fields['confirm_password']]);?>" maxlength="255" autocomplete="off" placeholder="<?=Loc::getMessage('MAIN_AUTH_CHD_FIELD_PASS2')?>">
					</div>
				</div>

				<div class="w-100 d-f jc-c">
					<button class="<?=comp::get("btnPurpleLg")?>" name="<?= $fields['action'];?>" value="<?=Loc::getMessage('MAIN_AUTH_CHD_FIELD_SUBMIT')?>"><?=Loc::getMessage('MAIN_AUTH_CHD_FIELD_SUBMIT')?></button>
				</div>

				<div class="w-100 f-12 xl:f-14"><?=$arResult['GROUP_POLICY']['PASSWORD_REQUIREMENTS'];?></div>

			</form>

			<script>document.bform.<?= $fields['login'];?>.focus();</script>
		<?endif;?>
	</div>
</div>