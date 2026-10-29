<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Bitrix\Main\Application;
use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\PropertyEnumerationTable;

Loader::includeModule('iblock');
Loader::includeModule('lang.iblock');
Loader::includeModule('lang.css');

$id = rand();
$textTableString = "LangText{$lang}";
$quizTableString = "LangQuizMain";
$iblockQuiz = Util::getIblockObject($quizTableString, []);
$textPolicy = Util::getElementObject($textTableString, ['NAME', 'PREVIEW_TEXT', 'text'], ['ID' => 205258]);


$app = Application::getInstance();
$request = $app->getContext()->getRequest();
?>

<style>
	.lang-quiz--input {
		[type="radio"] {
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
						stroke: #fff;
					}
					.checked {
						fill: #fff;
					}
				}
			}
		}
	}

	.lang-policy--input {
		[type="checkbox"], [type="radio"] {
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
</style>

<style>
	.lang-ruk-form--window .fancybox__backdrop {
		background-color: rgba(221, 68, 90, 0.06);
    	backdrop-filter: blur(6px);
	}

	.lang-ruk-form--window .fancybox__content {
		max-width: 100%;
		/* min-height: 32rem; */
		padding: 2rem;
		border-radius: 1.5rem;
		background-color: #EDF0F9;
		box-shadow: 0 16px 32px rgb(221 68 90 / 12%), 0 12px 24px rgb(221 68 90 / 22%);
	}

	.lang-ruk-form--window .fancybox__content > div {
		width: 100%;
		height: 100%;
	}

	@media (min-width: 48rem) {
		.lang-ruk-form--window .fancybox__content {
			max-width: 45rem;
			min-height: 32rem;
			padding: 4rem;
			border-radius: 3rem;
		}
	}

	@media (min-width: 62rem) {
		.lang-ruk-form--window .fancybox__content {
			max-width: 60rem;
			width: 60rem;
		}
	}

	.swiper-pagination-progressbar .swiper-pagination-progressbar-fill {
		background-color: #DD445A;
	}

	.swiper-pagination-progressbar {
		background-color: #E7E7F2;
	}

	.lang-opros--btn-next,
	.lang-opros--btn-prev {

	}

	.lang-opros--btn-next:not([aria-disabled="false"]),
	.lang-opros--btn-prev:not([aria-disabled="false"]) {
		background-color: #E7E7F2;
		border-color: #E7E7F2;
		color: #FFF;
		cursor: default;
	}

	.lang-opros--btn-next:not([aria-disabled="true"]),
	.lang-opros--btn-prev:not([aria-disabled="true"]) {
		/* background-color: #0048d0;
		border-color: #0048d0; */
		color: #FFF
	}

	.lang-opros--btn-next:not([aria-disabled="true"]):hover,
	.lang-opros--btn-prev:not([aria-disabled="true"]):hover {
		background-color: transparent;
		color: #484F51;
	}
</style>

<section id="langFormOpros" class="lang-request-main--block w-100 h-100 <?if((strlen($arResult["OK_MESSAGE"]) > 0) || !empty($arResult["ERROR_MESSAGE"])):?><?endif?>">
	<button class="pn-a t-32px rt-32px m-0 p-0 bw-0 c-dt h:c-py tn-all-3 z-1" data-fancybox-close="">
		<svg width="24" height="24" viewBox="0 0 13 13" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
			<line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"/>
			<line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"/>
		</svg>
	</button>
	
	<div class="w-100 h-100 d-f fd-c">

		<?include_once $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/include/header/successError.php"?>

		<?if(empty($arResult["OK_MESSAGE"])):?>
			<form id="recapcha-<?=$id;?>" class="lang-form--ajax w-100 h-100 d-f fd-c rg-32" name="<?=$arResult["arForm"]["SID"]?>" action="<?=$APPLICATION->GetCurPageParam("form_id=".$arResult["arForm"]["ID"], array("FORM_ID"))?>#langFormOpros" method="POST" enctype="multipart/form-data">
				<?=bitrix_sessid_post()?>

				<input type="hidden" name="WEB_FORM_ID" value="<?=$arResult["arForm"]["ID"]?>">
				<input type="hidden" value="<?=$arParams['AJAX_OPTION_ADDITIONAL'];?>" name="ajax_opt_add">

				<?if(!empty($arParams['lidName'])):?>
					<input type="hidden" value="<?=$arParams['lidName']?>" name="lidName">
				<?endif;?>

				<div class="w-100 d-f fd-c ai-c rg-16 pn-r fg-0">
					<div class="fg-0 d-f ai-c jc-c cg-4 xl:f-24 lh-1 ftw-5 ff-ot">
						<div class="lang-opros--pagi-number">1</div>
						<div class="">/</div>
						<div class="lang-quiz--pagiEnd"></div>
						<div class="">вопросов</div>
					</div>
					<div class="swiper-pagination pn-r"></div>
				</div>

				<?
				$btnNav = comp::get("btnDt"); // "bw-2 bs-sd pt-6 pr-10 pb-6 pl-10 md:pt-8 md:pr-11 md:pb-8 md:pl-11 r-8 lh-sd tn-all-3 f-12 xl:f-16";
				$slideMod = "d-f fd-c rg-32 xl:rg-48";
				$propertyETable = "Bitrix\Iblock\PropertyEnumerationTable";
				?>

				<div class="swiper mySwiper w-100 h-100 fg-1">
					<div class="swiper-wrapper">
						<?
						$questionList = \Bitrix\Iblock\PropertyTable::getList([
							'order' => ['SORT' => 'ASC', 'ID' => 'DESC'],
							'select' => ['ID', 'NAME', 'CODE'],
							'filter' => ['IBLOCK_ID' => $iblockQuiz->getId(), 'CODE' => ['lev', 'dis', 'forma', 'kred'], 'ACTIVE' => 'Y'],
							"cache"  => Util::getCache(),
						])->fetchCollection();

						if(!empty($questionList->getIdList())):
							foreach($questionList as $question):
								$modeIndex = false;

								$propValueList = Util::getEnumElementObject([
									'select' => ['VALUE', 'XML_ID'],
									'filter' => [
										'PROPERTY.IBLOCK_ID' => $iblockQuiz->getId(),
										'PROPERTY.CODE' => $question->getCode()
									],
								]);

								if(!empty($propValueList->getIdList())):
									$enumCount = count($propValueList->getIdList());
									?>
									<div class="swiper-slide <?=$slideMod?>">

										<div class="d-f fd-c rg-32 fg-1 lh-1">
											<div class="f-24 lg:f-32 ftw-5 ff-ot ta-c"><?=$question->getName()?></div>
											<div class="w-100 d-f fd-c rg-8">
												<div class="<?=comp::get("row")?> jc-c rg-16 f-12 xl:f-18">
													<?foreach($propValueList as $modeFields):
														$code = $question->getCode();
														$fieldId = "{$code}_{$modeFields->getId()}";
														?>
														
														<?if($modeFields->getXmlId() == 'empty'):?>
															<div class="<?=comp::get("col")?> <?=($enumCount > 3) ? "md:w-6/12" : "md:w-10/12"?> o-1 md:o-i">
																<script>
																	<?=$fieldId?>.addEventListener('input', () => {
																		if(<?=$fieldId?>.value.length > 0)
																		{
																			document.querySelectorAll('.quiz--<?=$code?>').forEach((item) => {
																				item.checked = false;
																			});
																		}
																	});
																</script>
																<input type="text" name="<?=$code?>empty" placeholder="<?=$modeFields->getValue()?>" id="<?=$fieldId?>"
																	class="ap-n w-100 h-100 pt-12 pb-12 md:pt-16 pr-24 md:pb-16 pl-24 bc-ffffff bw-2 bs-s bcr-E5E5E5 h:bcr-py r-12 tn-all-3 lh-1">
															</div>
														<?else:?>
															<div class="<?=comp::get("col")?> <?=($enumCount > 3) ? "md:w-6/12" : "md:w-10/12"?>">
																<label for="<?=$fieldId?>" class="lang-quiz--input w-100 h-100 mb-0 pt-12 pb-12 md:pt-16 pr-24 md:pb-16 pl-24 d-f ai-c bw-2 bs-s bcr-E5E5E5 r-12 lh-1 bc-w h:bc-py h:c-w h:bcr-py cr-p tn-all-3">
																	<div class="w-100 pn-r d-f ai-c g-16">
																		<input class="quiz--<?=$code?> mr-16 mt-0 w-24px h-24px mnw-24px mnh-24px oy-0 z-2 pn-r cr-p" type="radio" <?=!$modeIndex ? 'checked' : ''?>
																			name="<?=$code?>" value="<?=$modeFields->getId()?>" id="<?=$fieldId?>">

																		<span class="flag--view d-f pn-a l-0 tn-all-3 cr-p">
																			<svg class="" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
																				<circle class="no tn-all-3" cx="12" cy="12" r="11.5" transform="matrix(-1 0 0 1 24 0)"/>
																				<circle class="checked tn-all-3" cx="7" cy="7" r="7" transform="matrix(-1 0 0 1 19 5)" fill="#DD445A"/>
																			</svg>
																		</span>
																		<div class="mb-0"><?=$modeFields->getValue()?></div>
																	</div>
																</label>
															</div>
														<?endif;?>
														
													<?
													$modeIndex = true;
													endforeach;?>
												</div>
											</div>
										</div>
										
										<div class="w-100 d-f jc-b">
											<button class="lang-opros--btn-prev <?=$btnNav?>">Назад</button>
											<button class="lang-opros--btn-next <?=$btnNav?>">Далее</button>
										</div>
									</div>
								<?endif;?>
							<?endforeach;?>
						<?endif;?>

						<div class="swiper-slide <?=$slideMod?>">
							<div class="d-f fd-c rg-32 fg-1 lh-nl">
								<div class="d-f fd-c rg-32 fg-1 lh-1">

									<div class="f-24 lg:f-32 ftw-5 ff-ot ta-c">Готовы узнать, куда вам лучше поступить</div>

									<?$fieldClass = "ap-n w-100 pt-16 pr-24 pb-16 pl-24 bc-ffffff bw-2 bs-s bcr-E5E5E5 h:bcr-py r-12 tn-all-3 lh-1";?>

									<div class="<?=comp::get("row")?> jc-c rg-16 f-12 xl:f-18">

										<?foreach($arParams["FORM_FIELD"] as $key => $itemField):?>
											<?if($itemField == "Y"):?>
												<div class="<?=comp::get("col")?> xl:w-5/12">
													<?switch($key):
														case "NAME":?>
															<input placeholder="Имя или название компании" value="<?=$arResult['AUTHOR_NAME']?>"
																class="<?=$fieldClass?>" id="FEEDBACK[<?=$key?>][<?=$id?>]" type="text" name="user_name" data-valid="name"
																<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array($key, $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
															<?break;?>
														<?case "EMAIL":?>
															<input id="FEEDBACK[EMAIL]" class="js-field-form--email-<?=$id?> <?=$fieldClass?>" value="<?=$arResult['AUTHOR_EMAIL']?>"
																type="text" name="user_email" placeholder="E-mail (необязательно)"
																data-valid="email" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
															<?break?>
														<?case "PHONE":?>
															<input class="js-field-form--phone-faq <?=$fieldClass?>" id="FEEDBACK[PHONE][<?=$id?>]" type="tel"
																placeholder="+7 (999) 999-99-99" name="user_phone" data-inputmask="'mask': '+7 (999) 999-99-99'"
																value="<?=$arResult["AUTHOR_PHONE"]?>" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
															<?break?>
													<?endswitch?>
												</div>
											<?endif?>
										<?endforeach?>

									</div>
								</div>
							</div>

							<div class="w-100 d-f fd-c rg-16">
								<div class="w-100">
									<div class="lang-policy--input d-f ai-s lh-1 pn-r cg-16">
										<input class="mr-0 mt-0 w-20px h-20px mnw-20px mnh-20px oy-0 z-2 pn-r cr-p"
											<?=!empty($request->get('policy')) ? 'checked' : $request->get('policy')?>
											required="" id="policy--checkbox<?=$id?>" type="checkbox" name="policy">

										<span class="flag--view pn-a l-0 tn-all-3 cr-p mt-4">
											<svg class="" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
												<circle class="no tn-all-3" cx="12" cy="12" r="11.5" transform="matrix(-1 0 0 1 24 0)"></circle>
												<circle class="checked tn-all-3" cx="7" cy="7" r="7" transform="matrix(-1 0 0 1 19 5)" fill="#DD445A"></circle>
											</svg>
										</span>

										<?
										if(!empty($textPolicy)):
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

											<label for="policy--checkbox<?=$id?>" class="mb-0 f-10 sm:f-12 md:f-14 lh-1.5 cr-p"><?=$previewText?></label>
										<?endif;?>
									</div>
								</div>
								<div class="w-100 d-f fw-w jc-b">

									<button class="lang-opros--btn-prev <?=$btnNav?>">Назад</button>

									<div class="">
										<div class="w-100 grow-0 shrink-0">

											<button type="submit" class="<?=$btnNav?> b-0048d0 bg-0048d0 hover:bg-tt c-we hover:c-dt"
												name="submit" id="feedback-<?=$id?>" value="<?=$arParams['buttonName'] ? $arParams['buttonName'] : Loc::getMessage("MFT_SUBMIT")?>">
												Получить
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

				<script>
					function recOpros()
					{
						let pagi = document.querySelector('.lang-opros--pagi-number');
						let pagiNum = document.querySelector(".lang-quiz--pagiEnd");

						var swiper = new Swiper(".mySwiper", {
							pagination: {
								el: ".swiper-pagination",
								type: "progressbar",
							},
							navigation: {
								nextEl: ".lang-opros--btn-next",
								prevEl: ".lang-opros--btn-prev",
								// disabledClass: "",
							},
							allowTouchMove: false
						});

						swiper.on('activeIndexChange', () => {
							pagi.innerText = swiper.activeIndex + 1;
						});
						pagiNum.innerText = swiper.slides.length;

						// document.querySelectorAll('.lang-opros--btn-prev').forEach(e => {
						// 	e.addEventListener('click', () => {
						// 		swiper.slidePrev();
						// 	});
						// });

						// document.querySelectorAll('.lang-opros--btn-next').forEach(e => {
						// 	e.addEventListener('click', () => {
						// 		swiper.slideNext();
						// 	});
						// });
					}

					try {
						if(Swiper)
						{
							window.addEventListener('DOMContentLoaded', recOpros);
							recOpros();
						}
					}
					catch(e) {
						window.addEventListener('onLangSwiperScriptLoad', recOpros);
					}
					LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/swiper.min.js', () => {
                        LG.send('onLangSwiperScriptLoad', window);
                    }, true, 'body', 'append');
				</script>
			</form>
		<?endif;?>
	</div>
</section>


<style>.grecaptcha-badge{display:none!important}</style>