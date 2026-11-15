<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
use Bitrix\Main\Config\Option;
$id = rand();

?>
<div class="main-feedback">
	<?if(!empty($arResult["ERROR_MESSAGE"])):
	
		foreach($arResult["ERROR_MESSAGE"] as $v):?>
			<div class="alert alert-danger"><?=$v?></div>
		<?endforeach;
	endif;
	if(strlen($arResult["OK_MESSAGE"]) > 0):?>
		<div class="alert alert-success"><?=$arResult["OK_MESSAGE"]?></div>
	<?endif?>

<form id="recapcha-<?=$id;?>" action="<?=POST_FORM_ACTION_URI?>" method="POST" onsubmit="ym(83590630,'reachGoal','send'); gtag('event', 'click', {'event_category': 'send'});">
<?=bitrix_sessid_post()?>
	<div class="field-form mb-4">
		<input id="FEEDBACK[NAME]" type="text" name="user_name" value="<?=$arResult["AUTHOR_NAME"]?>">
		<label for="FEEDBACK[NAME]">
			<?=GetMessage("MFT_NAME")?>
			<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])):?>
				<span class="c-danger">*</span>
			<?endif?>
		</label>
	</div>
    <?/*
	<div class="field-form mb-4">
		<input id="FEEDBACK[EMAIL]" type="text" name="user_email" value="<?=$arResult["AUTHOR_EMAIL"]?>">
		<label for="FEEDBACK[EMAIL]">
			<?=GetMessage("MFT_EMAIL")?>
			<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?>
				<span class="c-danger">*</span>
			<?endif?>
		</label>
	</div>
    */?>
	<?//if($arParams["REQUIRED_FIELDS"]["PHONE"]):?>
		<div class="field-form mb-4">
			<input id="FEEDBACK[PHONE]" type="text" name="user_phone" value="<?=$arResult["AUTHOR_PHONE"]?>">
			<label for="FEEDBACK[PHONE]">
				<?=GetMessage("MFT_PHONE")?>
				<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])):?>
					<span class="c-danger">*</span>
				<?endif?>
			</label>
		</div>
	<?//endif?>
	<div class="field-form mb-4">
		<textarea id="FEEDBACK[MESSAGE]" name="MESSAGE" rows="5" cols="40"><?=$arResult["MESSAGE"]?></textarea>
		<label for="FEEDBACK[MESSAGE]">
			<?=GetMessage("MFT_MESSAGE")?>
			<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])):?>
				<span class="c-danger">*</span>
			<?endif?>
		</label>
	</div>

	<div class="recaptcha-container mb-4">
		<div class="d-flex align-items-center justify-content-start" id="recaptcha-<?=$id?>"></div>
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
	</div>
	<input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
	<input type="submit" id="register-<?=$id?>" class="btn btn-primary mr-3" name="submit" value="<?=GetMessage("MFT_SUBMIT")?>">
</form>
</div>

<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('6LfIZowUAAAAAJ-Ki2UoI5PreJ_28HBd_25JohnD', {action: 'homepage'})
        .then(function(token) {
            console.log(token);
            console.log(document.getElementById("g-recaptcha-response"));
            document.getElementById("g-recaptcha-response").value=token;
        });
    });
</script>