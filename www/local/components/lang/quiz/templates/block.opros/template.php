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

use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Bitrix\Main\Localization\Loc;

Loader::includeModule('iblock');
Loader::includeModule('lang.iblock');

$id = rand();
$lang = (SITE_ID == 'en' || SITE_ID == 'tt') ? ucfirst(SITE_ID) : '';
$textCode = "LangText";
$textTable = Util::getTableString("{$textCode}");

$element = Util::getElement("{$textCode}", ['ID', "IBLOCK_ID", 'NAME', "PREVIEW_TEXT"], ['ID' => $arParams['ELEMENT_ID'], 'ACTIVE' => 'Y']);
?>

<section id="langRequestBlock" class="lang-request-main--block w-100 h-100 <?if((strlen($arResult["OK_MESSAGE"]) > 0) || !empty($arResult["ERROR_MESSAGE"])):?><?endif?>">

	<?if(!empty($arResult["ERROR_MESSAGE"])):?>
		<div class="w-100 flex f-col jc-c ai-s">
			<?foreach($arResult["ERROR_MESSAGE"] as $v):?>
				<div class="rele pt-8 pr-11 pb-8 pl-11 b-2 b-sd b-er r-8 mb-11"><?=$v?></div>
			<?endforeach?>
		</div>
	<?endif?>

	<?if(strlen($arResult["OK_MESSAGE"]) > 0):?>
		<div class="w-100 h-100 flex jc-c ai-c">
			<?if($arParams["YANDEX_EVENT"]):?><script><?=$arParams["YANDEX_EVENT"]?></script><?endif?>
			<div class="rele pt-8 pr-11 pb-8 pl-11 b-2 b-sd b-ss r-8">
				Спасибо! Мы свяжемся с Вами в ближайшее время.
			</div>
		</div>
	<?endif?>

	<?if(empty($arResult["OK_MESSAGE"])):?>
		<form id="recapcha-<?=$id;?>" class="lang-form--ajax w-100" name="<?=$arResult["arForm"]["SID"]?>" action="<?=$APPLICATION->GetCurPageParam("form_id=".$arResult["arForm"]["ID"], array("FORM_ID"))?>#langRequestBlock" method="POST" enctype="multipart/form-data">
			<?=bitrix_sessid_post()?>

			<input type="hidden" name="WEB_FORM_ID" value="<?=$arResult["arForm"]["ID"]?>">
			<input type="hidden" value="<?=$arParams['AJAX_OPTION_ADDITIONAL'];?>" name="ajax_opt_add">
			<input type="hidden" value="<?=$_REQUEST['product']?>" name="product">

			<?if(!empty($arParams['lidName'])):?>
				<input type="hidden" value="<?=$arParams['lidName']?>" name="lidName">
			<?endif;?>

			<div class="flex fw-w mr--8 ml--8">

				<div class="w-100 pr-8 pl-8 grow-0 shrink-0 order-1 xl:order-0">
					<div class="w-100 h-100 flex f-col gap-16 lg:gap-32 b-2 b-sd b-ECECEF p-11 r-4">

						<?/*if(!empty($element)):?>
							<div class="w-100 flex f-col gap-16" id="<?=Util::iblockElementEditAdd($element)?>">
								<h2 class="f-20 md:f-24 mb-0 ftw-5 lh-nl"><?=$element['NAME']?></h2>

								<?if(!empty($element['PREVIEW_TEXT'])):?>
									<div class="f-14 md:f-16"><?=$element['PREVIEW_TEXT']?></div>
								<?endif;?>
							</div>
						<?endif;*/?>

						<div class="flex fw-w mr--8 ml--8">
							<?$fieldClass = "c-dt app-n w-100 pt-8 pr-11 pb-8 pl-11 b-1 b-sd b-ECECEF r-4 b-we f-16 xl:f-18 hover:b-0048d0 tn-all-3";?>

							<?foreach($arParams["FORM_FIELD"] as $key => $itemField):?>
								<?if($itemField == "Y"):?>
									<?switch($key):
										case "NAME":?>
											<div class="w-100 xl:w-6/12 grow-0 shrink-0 pr-8 pl-8 flex f-col mb-8 md:mb-11">
												<input placeholder="<?=Loc::getMessage('MFT_NAME')?>" value="<?=$arResult['AUTHOR_NAME']?>"
													class="<?=$fieldClass?>" id="FEEDBACK[<?=$key?>][<?=$id?>]" type="text" name="user_name" data-valid="name"
													<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array($key, $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
											</div>
											<?break;?>
										<?case "EMAIL":?>
											<div class="w-100 grow-0 shrink-0 pr-8 pl-8 flex f-col mb-8 md:mb-11">
												<input id="FEEDBACK[EMAIL]" class="js-field-form--email-<?=$id?> <?=$fieldClass?>" value="<?=$arResult['AUTHOR_EMAIL']?>"
													type="text" name="user_email" placeholder="<?=Loc::getMessage('MFT_EMAIL')?>"
													data-valid="email" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
											</div>
											<?break?>
										<?case "PHONE":?>
											<div class="w-100 xl:w-6/12 grow-0 shrink-0 pr-8 pl-8 flex f-col mb-8 md:mb-11">
												<input class="js-field-form--phone-faq <?=$fieldClass?>" id="FEEDBACK[PHONE][<?=$id?>]" type="tel"
													placeholder="+7 (999) 999-99-99" name="user_phone" data-inputmask="'mask': '+7 (999) 999-99-99'"
													value="<?=$arResult["AUTHOR_PHONE"]?>" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
											</div>
											<?break?>
										<?case 'MESSAGE':?>
											<div class="w-100 grow-0 shrink-0 pr-8 pl-8 flex f-col mb-8 md:mb-11">
												<textarea id="FEEDBACK[MESSAGE]" name="MESSAGE" rows="4" cols="60" value="<?=$arResult["MESSAGE"]?>"
													class="<?=$fieldClass?> h-ao re-n grow-1" placeholder="<?=Loc::getMessage('MFT_MESSAGE')?>"><?=$arResult["MESSAGE"]?></textarea>
											</div>
										<?break;?>
										<?case 'file':?>
											<div class="w-100 lg:w-6/12 grow-0 shrink-0 pr-8 pl-8 flex f-col mb-8 md:mb-11">
												<label class="f-16 md:f-18 ftw-5 mb-3" for="FEEDBACK[<?=$key?>]">Загрузите опросный лист</label>
												<input type="file" id="FEEDBACK[<?=$key?>]" name="<?=$key?>" class="<?=$fieldClass?>">
											</div>
										<?break;?>
									<?endswitch?>
								<?endif?>
							<?endforeach?>

							<div class="w-100 lg:w-6/12 grow-0 shrink-0 pr-8 pl-8 flex jc-c">
								<div class="w-100 flex f-col gap-16">
									<div class="w-100 grow-0 shrink-0">
										<style>
											.policy--checkbox input[type="checkbox"] + .lang-checkbox--request .lang-checkbox--request-no {
												stroke: #B59C86;
											}
											.policy--checkbox input[type="checkbox"]:hover + .lang-checkbox--request .lang-checkbox--request-no {
												stroke: #B59C86;
											}
											.policy--checkbox input[type="checkbox"]:checked + .lang-checkbox--request .lang-checkbox--request-no {
												stroke: #B59C86;
											}

											.policy--checkbox input[type="checkbox"] + .lang-checkbox--request .lang-checkbox--request-checked {
												opacity: 0;
											}
											.policy--checkbox input[type="checkbox"]:hover + .lang-checkbox--request .lang-checkbox--request-checked {
												opacity: 0;
												fill: #484D53;
											}
											.policy--checkbox input[type="checkbox"]:checked + .lang-checkbox--request .lang-checkbox--request-checked {
												opacity: 1;
											}
											
										</style>

										<div class="policy--checkbox flex ai-s lh-sd rele">
											<div for="policy--checkbox" class="mb-0 f-12 md:f-14 lh-nl">
												Я принимаю 
												<a href="#langPolicy" rel="nofollow" class="m-0 p-0 b-0 bg-tt lh-sd tdl-u tuo-4 tdt-1 tdc-0048d0 tn-all-3 c-dt" data-fancybox="" data-src="#langPolicy" data-type="inline">условия передачи информации</a>
											</div>
										</div>

									</div>

									<div class="w-100 grow-0 shrink-0">

										<button type="submit" class="w-100 bg-0048d0 hover:bg-we r-4 ta-c lh-nl pt-8 pr-11 pb-8 pl-11 f-16 xl:f-20 b-2 b-sd b-0048d0 c-we hover:c-dt active:tm-c-95 tn-all-3"
											name="submit" id="feedback-<?=$id?>" value="<?=$arParams['buttonName'] ? $arParams['buttonName'] : Loc::getMessage("MFT_SUBMIT")?>">
											Получить консультацию
										</button>

										<?if($arParams["GOOGLE_RECAPTCHA"] == "Y"):?>
											<input type="hidden" name="g-recaptcha-response" class="g-recaptcha-response-faq">
										<?endif?>

										<input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	<?endif;?>

</section>


<style>.grecaptcha-badge{display:none!important}</style>