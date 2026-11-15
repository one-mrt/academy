<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;

Loader::includeModule('iblock');
Loader::includeModule('lang.css');
Loader::includeModule('lang.iblock');

$id = rand();
$lang = Util::getLang();
$textTableString = "LangText{$lang}";
$titleForm = Util::getElementObject($textTableString, ['NAME', 'PREVIEW_TEXT', 'text'], ['ID' => 203998]);
?>

<style>
	.lang-policy--input {
		[type="checkbox"] {
			+ .flag--view {
				.no {
					stroke: #DD445A;
				}
				.checked {
					opacity: 0;
				}
			}

			&:checked {
				+ .flag--view {
					.no {
						stroke: #DD445A;
					}
					.checked {
						opacity: 1;
					}
				}
			}

			&:hover {
				+ .flag--view {
					.no {
						stroke: #1e234c;
					}
					.checked {
						fill: #1e234c;
					}
				}
			}
		}
	}

	.lang-ruk-form--window .fancybox__backdrop {
		background-color: rgba(221, 68, 90, 0.06);
    	backdrop-filter: blur(6px);
	}

	.lang-ruk-form--window .fancybox__content {
		max-width: 100%;
		min-height: 32rem;
		padding: 2rem;
		border-radius: 1.5rem;
		background-color: #EDF0F9;
		box-shadow: 0 16px 32px rgb(221 68 90 / 12%), 0 12px 24px rgb(221 68 90 / 22%);
	}

	@media (min-width: 48rem) {
		.lang-ruk-form--window .fancybox__content {
			max-width: 45rem;
			min-height: 32rem;
			padding: 4rem;
			border-radius: 3rem;
		}
	}
</style>

<section class="lang-block-calc w-100">

	<button class="pn-a t-32px rt-32px m-0 p-0 bw-0 c-dt h:c-py tn-all-3" data-fancybox-close="">
		<svg width="24" height="24" viewBox="0 0 13 13" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
			<line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"/>
			<line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"/>
		</svg>
	</button>

	<?include_once $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/include/header/successError.php"?>

	<form id="recapcha-<?=$id;?>" class="lang-form--ajax w-100 flex ai-c f-col gap-24 xl:gap-32" name="<?=$arResult["arForm"]["SID"]?>" action="<?=$APPLICATION->GetCurPageParam("form_id={$arResult["arForm"]["ID"]}", array("FORM_ID"))?>&<?=$arParams["REQUEST_TYPE"]?>" method="POST" enctype="multipart/form-data">

		<?=bitrix_sessid_post()?>
		<input type="hidden" value="<?=$arParams['AJAX_OPTION_ADDITIONAL'];?>" name="ajax_opt_add">

		<div class="lang-calc--form w-100 d-f ai-c fd-c rg-32">

			<div class="ta-c ftw-5 f-24 xl:f-32 lh-1 c-dt" <?=Util::iblockElementEditAddId($titleForm)?>><?=$titleForm->getName()?></div>

			<div class="w-100 d-f fd-c rg-32">
				<?foreach($arParams["FORM_FIELD"] as $key => $itemField):?>
					<?switch($key):
						case "NAME":?>
							<input class="lang-field--calc <?=comp::get("inputText")?>" placeholder="Имя" id="FEEDBACK[NAME][<?=$id?>]" type="text" name="user_name" value="<?=$arResult["AUTHOR_NAME"]?>" data-valid="name" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
							<?break;?>
						<?case "PHONE":?>
							<input class="lang-field--calc js-field-form--phone-faq js-field-form--phone-<?=$id?> <?=comp::get("inputText")?>" id="FEEDBACK[PHONE][<?=$id?>]" type="tel" placeholder="+7 (999) 999-99-99" data-inputmask="'mask': '+7 (999) 999-99-99'" name="user_phone" value="<?=$arResult["AUTHOR_PHONE"]?>" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
							<?break?>
						<?case "EMAIL":?>
							<input placeholder="Email" class="lang-field--calc js-field-form--email-faq js-field-form--email-<?=$id?> <?=comp::get("inputText")?>" id="FEEDBACK[EMAIL]" type="text" name="user_email" value="<?=$arResult["AUTHOR_EMAIL"]?>" data-valid="email" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
							<?break?>
						<?case "MESSAGE":?>
							<textarea class="<?=comp::get("inputText")?> h-ao re-n grow-1" id="FEEDBACK[MESSAGE]" name="MESSAGE" rows="5" cols="40" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])):?>required<?endif?>><?=$arResult["MESSAGE"]?></textarea>
							<?break?>
					<?endswitch?>
				<?endforeach?>
			</div>

			<div class="w-100 d-f fw-w md:fw-n g-32">

				<div class="w-100 d-f ai-c fg-1">
					<div class="lang-policy--input d-f ai-s lh-1 pn-r">
						<input class="mr-16 mt-0 w-20px h-20px mnw-20px mnh-20px oy-0 z-2 pn-r cr-p" required="" id="policy--checkbox<?=$id?>" type="checkbox" value="Y" name="policy">

						<span class="flag--view pn-a l-0 tn-all-3 cr-p mt-4">
							<svg class="" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle class="no tn-all-3" cx="12" cy="12" r="11.5" transform="matrix(-1 0 0 1 24 0)"></circle>
								<circle class="checked tn-all-3" cx="7" cy="7" r="7" transform="matrix(-1 0 0 1 19 5)" fill="#DD445A"></circle>
							</svg>
						</span>

						<?
						$previewText = $titleForm->getPreviewText();
						$iText = 1;

						if(!empty($titleForm->getText()))
						{
							foreach($titleForm->getText()->getAll() as $text)
							{
								$link = "<a href=\"{$text->getValue()}\" class=\"tdl-u tuo-4 tdc-py h:c-py tn-all-3\">{$text->getDescription()}</a>";
								$previewText = str_replace("#TEXT_{$iText}#", $link, $previewText);
								$iText++;
							}
						}
						?>

						<label for="policy--checkbox<?=$id?>" class="mb-0 f-12 lh-1.5 cr-p"><?=$previewText?></label>
					</div>
				</div>

				<div class="fg-0">
					<button type="submit" class="<?=comp::get('btnPy')?>"
						name="submit" id="feedback-<?=$id?>" value="<?=Loc::getMessage("MFT_SUBMIT")?>">Оставить заявку</button>
					
					<?if($arParams["GOOGLE_RECAPTCHA"] == "Y"):?>
						<input class="g-recaptcha-response-faq" type="hidden" name="g-recaptcha-response-faq" id="g-recaptcha-response-<?=$id?>">
					<?endif?>

					<input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
					
				</div>

			</div>
		</div>
	</form>
	<div class="lang-dpo--preloader animation-none image-load--animation w-100 h-100 d-f d-n fd-c m-0 z-1 ai-c jc-c bc-t pn-a t-0 rt-0 b-0 l-0 bw-1 b-sd bcr-F9F9F9 bc-w of-h">
		<style>@-webkit-keyframes side-panel-loader-rotate{100% {-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@keyframes side-panel-loader-rotate{100%{-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@-webkit-keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}@keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}.loader--animation {-webkit-animation: side-panel-loader-rotate 2s linear infinite;animation: side-panel-loader-rotate 2s linear infinite;-webkit-transform-origin: center center;transform-origin: center center}.loader--path{stroke: #bfc3c8;stroke-width: 1.5;stroke-dasharray: 20, 200;stroke-dashoffset: 0;-webkit-animation: side-panel-loader-dash 1.5s ease-in-out infinite;animation: side-panel-loader-dash 1.5s ease-in-out infinite;stroke-linecap: round}</style>
		<svg class="loader--animation w-100 h-100 mw-200px mxh-200px" viewBox="25 25 50 50"><circle class="loader--path" cx="50" cy="50" r="20" fill="none" stroke-miterlimit="10"></circle></svg>
		<div class="mt-8 f-12 md:f-14 xl:f-16">Пожалуйста подождите</div>
	</div>
</section>