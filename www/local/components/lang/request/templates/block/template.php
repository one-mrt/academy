<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;

Loader::includeModule('iblock');
Loader::includeModule('lang.iblock');

$id = rand();
$lang = (SITE_ID == 'en') ? ucfirst(SITE_ID) : '';

$textTableString = "LangText{$lang}";
// $element = Util::getElementObject($textTableString, ["NAME", 'link'], ["ID" => 173879]);
$textPolicy = Util::getElementObject($textTableString, ['NAME', 'PREVIEW_TEXT', 'text'], ['ID' => 108]);
?>

<section id="langRequestBlock" class="lang-request-main--block w-100">

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

	<div class="w-100">

		<?//include_once $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/include/header/successError.php"?>

		<form class="lang-form--ajax w-100 d-f fd-c rg-24 xl:rg-32" name="" action="<?=$APPLICATION->GetCurPageParam("", [])?>" method="POST" enctype="multipart/form-data">

			<?=bitrix_sessid_post()?>
			<input type="hidden" value="<?=$arParams['AJAX_OPTION_ADDITIONAL'];?>" name="ajax_opt_add">

			<div class="w-100 d-f fd-c rg-24 md:rg-16">
				<?$fieldClass = "c-dt ap-n w-100 pt-16 pr-32 pb-16 pl-32 bc-w bw-1 bs-s bcr-C5C5C5 h:bcr-py r-12 f-16 tn-all-3";?>

				<?foreach($arParams["FORM_FIELD"] as $key => $itemField):?>
					<?if($itemField == "Y"):?>
						<?switch($key):
							case "NAME":?>
								<div class="w-100 d-f fd-c">
									<input placeholder="<?=Loc::getMessage('MFT_NAME')?>" value="<?=$arResult['FULL_NAME']?>"
										class="<?=$fieldClass?>" id="FEEDBACK[<?=$key?>][<?=$id?>]" type="text" name="user_name" data-valid="name"
										<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array($key, $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
								</div>
								<?break;?>
							<?case "PHONE":?>
								<div class="w-100 d-f fd-c">
									<input class="js-field-form--phone-faq <?=$fieldClass?>" id="FEEDBACK[PHONE][<?=$id?>]" type="tel"
										placeholder="+7 (999) 999-99-99" name="user_phone" data-inputmask="'mask': '+7 (999) 999-99-99'"
										value="<?=$arResult["PHONE"]?>" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
								</div>
								<?break?>
							<?case "EMAIL":?>
								<div class="w-100 d-f fd-c">
									<input id="FEEDBACK[EMAIL]" class="js-field-form--email-<?=$id?> <?=$fieldClass?>"
										type="text" name="user_email" placeholder="<?=Loc::getMessage('MFT_EMAIL')?>"
										data-valid="email" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
								</div>
								<?break?>
							<?case "MESSAGE":?>
								<textarea class="<?=$fieldClass?> h-ao re-n grow-1" placeholder="Ваш вопрос" id="FEEDBACK[MESSAGE]" name="MESSAGE" rows="5" cols="40" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])):?>required<?endif?>><?=$arResult["MESSAGE"]?></textarea>
							<?break?>
							<?/*case 'DATE':?>
								<div class="w-100 md:w-6/12 lg:w-6/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c">
									<label for="FEEDBACK[<?=$key?>][<?=$id?>]" class="f-18 ftw-5 mb-8">Желаемая дата заезда</label>
									<input class="js-field-form--date-faq <?=$fieldClass?>" id="FEEDBACK[<?=$key?>][<?=$id?>]"
										placeholder="" name="user_date" value="<?=$arResult[$key]?>" type="date"
										<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array($key, $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
								</div>
								<?break;*/?>
						<?endswitch?>
					<?endif?>
				<?endforeach?>
			</div>

			<div class="w-100 d-f fw-w rg-16 xl:fw-n cg-16">

				<div class="w-100 sm:w-8/12 md:w-100 d-f ai-c fg-1 sm:fg-0 md:fg-1">
					<div class="lang-policy--input d-f ai-s lh-1 pn-r">
						<input class="mr-16 mt-0 w-20px h-20px mnw-20px mnh-20px oy-0 z-2 pn-r cr-p" required="" id="policy--checkbox<?=$id?>" type="checkbox" value="Y" name="policy">

						<span class="flag--view pn-a l-0 tn-all-3 cr-p mt-4">
							<svg class="" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle class="no tn-all-3" cx="12" cy="12" r="11.5" transform="matrix(-1 0 0 1 24 0)"></circle>
								<circle class="checked tn-all-3" cx="7" cy="7" r="7" transform="matrix(-1 0 0 1 19 5)" fill="#DD445A"></circle>
							</svg>
						</span>

						<?
						$previewText = $textPolicy->getPreviewText();
						$iText = 1;

						if(!empty($textPolicy->getText()))
						{
							foreach($textPolicy->getText()->getAll() as $text)
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
					<button type="submit" class="<?=comp::get('btnPurpleSm')?>"
						name="submit" id="feedback-<?=$id?>" value="<?=Loc::getMessage("MFT_SUBMIT")?>">Задать вопрос</button>
					
					<?if($arParams["GOOGLE_RECAPTCHA"] == "Y"):?>
						<input class="g-recaptcha-response-faq" type="hidden" name="g-recaptcha-response-faq" id="g-recaptcha-response-<?=$id?>">
					<?endif?>

					<input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
					
				</div>

			</div>

		</form>
	</div>

	<script>
		window.addEventListener('scroll', () => {
			LG.blockVisible(document.querySelector('.lang-request-main--block'), 'langMainPhoneMask', () => {
				LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/inputmask.min.js', LG.fieldMaskPhone, true, 'body');

				<?if($arParams['GOOGLE_RECAPTCHA'] == 'Y'):?>
					LG.loadJS("https://www.google.com/recaptcha/api.js?render=6LfViO4rAAAAAILAqx0lexj3A4UnH5khHg2RYLOQ", LG.addGoogleReCaptcha, true,'body');
				<?endif;?>
			});
		});
		LG.blockVisible(document.querySelector('.lang-request-main--block'), 'langMainPhoneMask', () => {
			LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/inputmask.min.js', LG.fieldMaskPhone, true, 'body');

			<?if($arParams['GOOGLE_RECAPTCHA'] == 'Y'):?>
				LG.loadJS("https://www.google.com/recaptcha/api.js?render=6LfViO4rAAAAAILAqx0lexj3A4UnH5khHg2RYLOQ", LG.addGoogleReCaptcha, true,'body');
			<?endif;?>
		});
	</script>
</section>