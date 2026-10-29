<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
?>

<form class="lang-form--success w-100 h-100 d-f fd-c rg-24 lg:rg-32 jc-b fg-1" method="post" enctype="multipart/form-data">
	<?=$arResult["BX_SESSION_CHECK"]?>
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
	<input type="hidden" name="save" value="y">

	<?
	ShowError($arResult["strProfileError"]);

	if (isset($arResult['DATA_SAVED']) && $arResult['DATA_SAVED'] == 'Y') {
		ShowNote(Loc::getMessage('PROFILE_DATA_SAVED'));
	}
	?>

	<div class="w-100">
		<h2 class="mb-0 ff-ud f-18 lg:f-24 ftw-5 tt-l ls--1 lh-1.125">О себе</h2>
	</div>

	<div class="w-100 h-100 d-f fd-c rg-24 fg-1">
		<div class="lang-order--description w-100 d-f fd-c fg-1">
			<input name="PERSONAL_NOTES" class="description--input w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 d-n" value="<?=$arResult['arUser']['PERSONAL_NOTES']?>">
		</div>

		<div class="w-100 pn-sy b-0">
			<button class="<?=comp::get("btnBlueFillSm")?>"
				type="submit" name="save" value="<?=(($arResult["ID"] > 0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?>"><?=(($arResult["ID"] > 0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?></button>
		</div>
	</div>

</form>