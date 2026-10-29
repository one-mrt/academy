<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
?>

<form class="lang-form--success w-100 h-100 d-f fd-c rg-24 lg:rg-32 jc-b fg-1" method="post" enctype="multipart/form-data">
	<?=$arResult["BX_SESSION_CHECK"]?>
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="hidden" name="ID" value="<?=$arResult["ID"]?>" />
	<input type="hidden" name="save" value="y" />

	<?
	ShowError($arResult["strProfileError"]);

	if (isset($arResult['DATA_SAVED']) && $arResult['DATA_SAVED'] == 'Y') {
		ShowNote(GetMessage('PROFILE_DATA_SAVED'));
	}
	?>

	<div class="w-100">
		<h2 class="mb-0 ff-ud f-18 sm:f-24 ftw-5 tt-l ls--1 lh-1.125">Аватар</h2>
	</div>

	<div class="w-100 d-f fd-c rg-24 fg-1">
		<div class="w-100 d-f fd-c rg-16 fg-1">

			<div class="w-100 pn-r h:c-py h:bcr-py">
				<input id="<?=strtolower("PERSONAL_PHOTO")?>" name="PERSONAL_PHOTO" class="typefile" size="20" type="file">
			</div>

			<?/*<div class="w-100 pn-r h:c-py h:bcr-py">
				<label for="<?=strtolower("PERSONAL_PHOTO_del")?>">
					<input type="checkbox" name="PERSONAL_PHOTO_del" value="Y" id="<?=strtolower("PERSONAL_PHOTO_del")?>">
					Удалить аватар
				</label>
			</div>*/?>
		</div>

		<div class="w-100">
			<button class="<?=comp::get("btnBlueFillSm")?>"
				type="submit" name="save" value="<?=(($arResult["ID"] > 0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?>"><?=(($arResult["ID"] > 0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?></button>
		</div>
	</div>

</form>
