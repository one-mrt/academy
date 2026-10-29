<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
?>

<form class="lang-form--success w-100 h-100 d-f fd-c rg-24 lg:rg-32 jc-b fg-1" method="post" name="formPassword" enctype="multipart/form-data">
	<?=$arResult["BX_SESSION_CHECK"]?>
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
	<input type="hidden" name="save" value="y" />

	<?
	ShowError($arResult["strProfileError"]);

	if (isset($arResult['DATA_SAVED']) && $arResult['DATA_SAVED'] == 'Y') {
		ShowNote(GetMessage('PROFILE_DATA_SAVED'));
	}
	?>

	<div class="w-100">
		<h2 class="mb-0 ff-ud f-18 sm:f-24 ftw-5 tt-l ls--1 lh-1.125">Изменить пароль</h2>
	</div>

	<?if($arResult['CAN_EDIT_PASSWORD']):?>
		<div class="w-100 d-f fd-c rg-24 fg-1">

			<div class="w-100 d-f fd-c rg-16 fg-1">

				<div class="w-100 d-f fd-c rg-8">
					<div class="d-f ai-c ftw-6">
						<div class="lh-1"><?=Loc::getMessage("NEW_PASSWORD_REQ")?></div>
					</div>
					<div class="w-100 pn-r h:c-py h:bcr-py">
						<input id="<?=strtolower("NEW_PASSWORD")?>" class="w-100 pt-12 pr-48 pb-12 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
							type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="new-password">
						<label for="<?=strtolower("NEW_PASSWORD")?>" class="pn-a t-0 rt-0 b-0 d-f ai-c pt-12 pr-24 pb-12 mb-0 cr-p">
							<svg class="tn-all-3" width="14" height="14" viewBox="0 0 14 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M0 11.0837V14H2.91627L11.5173 5.39895L8.60106 2.48268L0 11.0837ZM13.7726 3.1437C13.8447 3.07175 13.9019 2.98629 13.9409 2.89222C13.9799 2.79814 14 2.69729 14 2.59544C14 2.49359 13.9799 2.39274 13.9409 2.29866C13.9019 2.20458 13.8447 2.11912 13.7726 2.04718L11.9528 0.227426C11.8809 0.155333 11.7954 0.0981367 11.7013 0.059112C11.6073 0.0200872 11.5064 0 11.4046 0C11.3027 0 11.2019 0.0200872 11.1078 0.059112C11.0137 0.0981367 10.9282 0.155333 10.8563 0.227426L9.43316 1.65057L12.3494 4.56684L13.7726 3.1437Z"/></svg>
						</label>
					</div>
				</div>

				<div class="w-100 d-f fd-c rg-8">
					<div class="d-f ai-c ftw-6">
						<div class="lh-1"><?=Loc::getMessage("NEW_PASSWORD_CONFIRM")?></div>
					</div>
					<div class="w-100 pn-r h:c-py h:bcr-py">
						<input id="<?=strtolower("NEW_PASSWORD_CONFIRM")?>" class="w-100 pt-12 pr-48 pb-12 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
							type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="new-password">
						<label for="<?=strtolower("NEW_PASSWORD_CONFIRM")?>" class="pn-a t-0 rt-0 b-0 d-f ai-c pt-12 pr-24 pb-12 mb-0 cr-p">
							<svg class="tn-all-3" width="14" height="14" viewBox="0 0 14 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M0 11.0837V14H2.91627L11.5173 5.39895L8.60106 2.48268L0 11.0837ZM13.7726 3.1437C13.8447 3.07175 13.9019 2.98629 13.9409 2.89222C13.9799 2.79814 14 2.69729 14 2.59544C14 2.49359 13.9799 2.39274 13.9409 2.29866C13.9019 2.20458 13.8447 2.11912 13.7726 2.04718L11.9528 0.227426C11.8809 0.155333 11.7954 0.0981367 11.7013 0.059112C11.6073 0.0200872 11.5064 0 11.4046 0C11.3027 0 11.2019 0.0200872 11.1078 0.059112C11.0137 0.0981367 10.9282 0.155333 10.8563 0.227426L9.43316 1.65057L12.3494 4.56684L13.7726 3.1437Z"/></svg>
						</label>
					</div>
				</div>
			</div>

			<div class="w-100">
				<button class="<?=comp::get("btnBlueFillSm")?>"
					type="submit" name="save" value="<?=(($arResult["ID"] > 0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?>"><?=(($arResult["ID"] > 0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?></button>
			</div>
		</div>
	<?endif?>

</form>