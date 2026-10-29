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
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Localization\Loc;

Loader::includeModule('iblock');
Loader::includeModule('lang.iblock');

$id = rand();
$lang = (SITE_ID == 'en' || SITE_ID == 'tt') ? ucfirst(SITE_ID) : '';
$textCode = "LangText";
$textTable = Util::getTableString("{$textCode}");
?>


<?
$recOprosTitle = Util::getElement("{$textCode}", ['ID', 'IBLOCK_ID', 'NAME', "PREVIEW_TEXT"], ['ACTIVE' => 'Y', 'ID' => 7869]);
$recOprosMan = $textTable::getList([
	'select' => ['ID', 'IBLOCK_ID', 'NAME', 'PREVIEW_PICTURE', "PREVIEW_TEXT", 'DETAIL_TEXT', 'text'],
	'filter' => ['ACTIVE' => 'Y', 'ID' => 7870],
	'cache' => Util::getCache(),
])->fetchObject();

$recOprosSale = Util::getElement("{$textCode}", ['ID', 'IBLOCK_ID', 'NAME', 'PREVIEW_PICTURE', "PREVIEW_TEXT"], ['ACTIVE' => 'Y', 'ID' => 7871]);
$recOprosHeader = Util::getElement("{$textCode}", ['ID', 'IBLOCK_ID', 'NAME', 's' => 'svg.VALUE'], ['ACTIVE' => 'Y', 'ID' => 7872]);
?>

<style>
	.swiper-pagination-progressbar .swiper-pagination-progressbar-fill {
		background-color: #49a6e9;
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
		background-color: #0048d0;
		border-color: #0048d0;
		color: #FFF
	}

	.lang-opros--btn-next:not([aria-disabled="true"]):hover,
	.lang-opros--btn-prev:not([aria-disabled="true"]):hover {
		background-color: transparent;
		color: #484F51;
	}
</style>

<section id="langFormOpros" class="lang-request-main--block lang-form--opros w-100 pb-11 xl:pb-13 <?if((strlen($arResult["OK_MESSAGE"]) > 0) || !empty($arResult["ERROR_MESSAGE"])):?><?endif?>">

	<div class="w-100 sm:mw-540 md:mw-720 lg:mw-960 xl:mw-1192 pr-8 pl-8 mr-ao ml-ao">

		<?if(!empty($recOprosTitle)):?>
			<div class="w-100 flex f-col gap-16 mb-11" id="<?=Util::iblockElementEditAdd($recOprosTitle)?>">
				<h2 class="f-22 xl:f-35 mb-0 ftw-5"><?=$recOprosTitle['NAME']?></h2>
				<?if(!empty($recOprosTitle['PREVIEW_TEXT'])):?>
					<div class="w-100 xl:f-24"><?=$recOprosTitle['PREVIEW_TEXT']?></div>
				<?endif;?>
			</div>
		<?endif;?>

		<div class="flex fw-w mr--8 ml--8">

			<div class="w-100 lg:w-8/12 pr-8 pl-8 grow-0 shrink-0 flex f-col gap-16 order-1">

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
					<form id="recapcha-<?=$id;?>" class="lang-form--ajax w-100 h-100 flex f-col b-2 b-sd b-E7E7F2 r-16" name="<?=$arResult["arForm"]["SID"]?>" action="<?=$APPLICATION->GetCurPageParam("form_id=".$arResult["arForm"]["ID"], array("FORM_ID"))?>#langFormOpros" method="POST" enctype="multipart/form-data">
						<?=bitrix_sessid_post()?>

						<input type="hidden" name="WEB_FORM_ID" value="<?=$arResult["arForm"]["ID"]?>">
						<input type="hidden" value="<?=$arParams['AJAX_OPTION_ADDITIONAL'];?>" name="ajax_opt_add">
						<input type="hidden" value="<?=$_REQUEST['product']?>" name="product">

						<?if(!empty($arParams['lidName'])):?>
							<input type="hidden" value="<?=$arParams['lidName']?>" name="lidName">
						<?endif;?>

						<div class="w-100 flex p-10 md:p-11 lh-sd">
							<?$recOprosHeader['s'] = \CFile::GetPath($recOprosHeader['s']);?>
							<div class="grow-1 flex gap-8 ai-c f-14 lg:f-16">
								<div class=""><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$recOprosHeader['s']}")?></div>
								<div class=""><?=$recOprosHeader['NAME']?></div>
							</div>
							<div class="grow-0 flex ai-c gap-4">
								<div class="lang-opros--pagi-number">1</div>
								<div class="">/</div>
								<div class="">7</div>
							</div>
						</div>

						<?
						$btnNav = "b-2 b-sd pt-6 pr-10 pb-6 pl-10 md:pt-8 md:pr-11 md:pb-8 md:pl-11 r-8 lh-sd tn-all-3 f-12 xl:f-16";
						$slideMod = "p-10 xl:p-11 flex f-col gap-24";
						$propertyETable = "Bitrix\Iblock\PropertyEnumerationTable";
						?>

						<div class="swiper mySwiper w-100 h-100">
							<div class="swiper-wrapper">

								<? // Назначение // ?>
								<div class="swiper-slide <?=$slideMod?>">
									<div class="flex f-col gap-16 grow-1 lh-nl">

										<?
										$title = PropertyTable::getList([
											'select' => ['NAME', 'HINT'],
											'filter' => ['CODE' => 'naz', 'IBLOCK_ID' => $arParams['IBLOCK_ID']],
											'cache' => Util::getCache()
										])->fetch();

										$nazEnums = CIBlockPropertyEnum::GetList([], ["IBLOCK_ID" => $arParams['IBLOCK_ID'], "CODE" => 'naz']);
										$nazIndex = false;
										?>

										<div class="xl:f-24 ftw-5"><?=$title['NAME']?>:</div>

										<div class="w-100 flex f-col gap-8">
											<?if(!empty($title['HINT'])):?>
												<div class="f-12 ftw-4"><?=$title['HINT']?></div>
											<?endif;?>
											<div class="w-100 flex f-col gap-8 md:gap-16 f-12 xl:f-16">
												<?while($nazFields = $nazEnums->GetNext()):?>
													<div class="flex ai-c gap-16">
														<input type="radio" <?=!$nazIndex ? 'checked' : ''?> class="w-20px h-20px" name="<?=$nazFields['PROPERTY_CODE']?>" value="<?=$nazFields['ID']?>" id="<?=$nazFields['PROPERTY_CODE']?>_<?=$nazFields['ID']?>">
														<label for="<?=$nazFields['PROPERTY_CODE']?>_<?=$nazFields['ID']?>" class="mb-0"><?=$nazFields['VALUE']?></label>
													</div>
												<?
												$nazIndex = true;
												endwhile;?>
											</div>
										</div>
									</div>
									<div class="w-100 flex jc-b">
										<button class="lang-opros--btn-prev <?=$btnNav?>">Назад</button>
										<button class="lang-opros--btn-next <?=$btnNav?>">Далее</button>
									</div>
								</div>

								<? // Варианты исполнения // ?>
								<div class="swiper-slide <?=$slideMod?>">

									<div class="flex f-col gap-16 grow-1 lh-nl">
										<?
										$title = PropertyTable::getList([
											'select' => ['NAME', 'HINT'],
											'filter' => ['CODE' => 'vrnt', 'IBLOCK_ID' => $arParams['IBLOCK_ID']],
											'cache' => Util::getCache()
										])->fetch();
										$vrntEnums = CIBlockPropertyEnum::GetList([], ["IBLOCK_ID" => $arParams['IBLOCK_ID'], "CODE" => 'vrnt']);
										$vrntIndex = false;
										?>

										<div class="xl:f-24 ftw-5"><?=$title['NAME']?>:</div>

										<div class="w-100 flex f-col gap-8">
											<?if(!empty($title['HINT'])):?>
												<div class="f-12 ftw-4"><?=$title['HINT']?></div>
											<?endif;?>
											<div class="w-100 flex f-col gap-8 md:gap-16 f-12 xl:f-16">
												<?while($vrntFields = $vrntEnums->GetNext()):?>
													<div class="flex ai-c gap-16">
														<input type="radio" <?=!$vrntIndex ? 'checked' : ''?> class="w-20px h-20px" name="<?=$vrntFields['PROPERTY_CODE']?>" value="<?=$vrntFields['ID']?>" id="<?=$vrntFields['PROPERTY_CODE']?>_<?=$vrntFields['ID']?>">
														<label for="<?=$vrntFields['PROPERTY_CODE']?>_<?=$vrntFields['ID']?>" class="mb-0"><?=$vrntFields['VALUE']?></label>
													</div>
												<?
												$vrntIndex = true;
												endwhile;?>
											</div>
										</div>
									</div>
									
									<div class="w-100 flex jc-b">
										<button class="lang-opros--btn-prev <?=$btnNav?>">Назад</button>
										<button class="lang-opros--btn-next <?=$btnNav?>">Далее</button>
									</div>
								</div>

								<? // Варианты исполнения 2 // ?>
								<div class="swiper-slide <?=$slideMod?>">
									<div class="flex f-col gap-16 grow-1 lh-nl">
										<?
										$title = PropertyTable::getList([
											'select' => ['NAME', 'HINT'],
											'filter' => ['CODE' => 'var', 'IBLOCK_ID' => $arParams['IBLOCK_ID']],
											'cache' => Util::getCache()
										])->fetch();
										$varEnums = CIBlockPropertyEnum::GetList([], ["IBLOCK_ID" => $arParams['IBLOCK_ID'], "CODE" => 'var']);
										$varIndex = false;
										?>

										<div class="xl:f-24 ftw-5"><?=$title['NAME']?>:</div>

										<div class="w-100 flex f-col gap-8">
											<?if(!empty($title['HINT'])):?>
												<div class="f-12 ftw-4"><?=$title['HINT']?></div>
											<?endif;?>

											<div class="w-100 flex f-col gap-8 md:gap-16 f-12 xl:f-16">
												<?while($varFields = $varEnums->GetNext()):?>
													<div class="flex ai-c gap-16">
														<input <?=!$varIndex ? 'checked' : ''?> type="radio" class="w-20px h-20px" name="<?=$varFields['PROPERTY_CODE']?>" value="<?=$varFields['ID']?>" id="<?=$varFields['PROPERTY_CODE']?>_<?=$varFields['ID']?>">
														<label for="<?=$varFields['PROPERTY_CODE']?>_<?=$varFields['ID']?>" class="mb-0"><?=$varFields['VALUE']?></label>
													</div>
												<?
												$varIndex = true;
												endwhile;?>
											</div>
										</div>
									</div>

									<div class="w-100 flex jc-b">
										<button class="lang-opros--btn-prev <?=$btnNav?>">Назад</button>
										<button class="lang-opros--btn-next <?=$btnNav?>">Далее</button>
									</div>
								</div>

								<? // Опции блок-контейнера // ?>
								<div class="swiper-slide <?=$slideMod?>">
									
									<div class="flex f-col gap-16 grow-1 lh-nl">
										<?
										$title = PropertyTable::getList([
											'select' => ['NAME', 'HINT'],
											'filter' => ['CODE' => 'opt', 'IBLOCK_ID' => $arParams['IBLOCK_ID']],
											'cache' => Util::getCache()
										])->fetch();
										$optEnums = CIBlockPropertyEnum::GetList([], ["IBLOCK_ID" => $arParams['IBLOCK_ID'], "CODE" => 'opt']);
										$optIndex = false;
										?>

										<div class="xl:f-24 ftw-5"><?=$title['NAME']?>:</div>

										<div class="w-100 flex f-col gap-8">
											<?if(!empty($title['HINT'])):?>
												<div class="f-12 ftw-4"><?=$title['HINT']?></div>
											<?endif;?>

											<div class="w-100 flex f-col gap-8 md:gap-16 f-12 xl:f-16">
												<?while($optFields = $optEnums->GetNext()):?>
													<div class="flex ai-c gap-16">
														<input <?=!$optIndex ? 'checked' : ''?> type="radio" class="w-20px h-20px" name="<?=$optFields['PROPERTY_CODE']?>" value="<?=$optFields['ID']?>" id="<?=$optFields['PROPERTY_CODE']?>_<?=$optFields['ID']?>">
														<label for="<?=$optFields['PROPERTY_CODE']?>_<?=$optFields['ID']?>" class="mb-0"><?=$optFields['VALUE']?></label>
													</div>
												<?
												$optIndex = true;
												endwhile;?>
											</div>
										</div>
									</div>

									<div class="w-100 flex jc-b">
										<button class="lang-opros--btn-prev <?=$btnNav?>">Назад</button>
										<button class="lang-opros--btn-next <?=$btnNav?>">Далее</button>
									</div>
								</div>

								<? // Категория по взрывопожарной опасности // ?>
								<div class="swiper-slide <?=$slideMod?>">

									<div class="flex f-col gap-16 grow-1 lh-nl">
										<?
										$title = PropertyTable::getList([
											'select' => ['NAME', 'HINT'],
											'filter' => ['CODE' => 'cat', 'IBLOCK_ID' => $arParams['IBLOCK_ID']],
											'cache' => Util::getCache()
										])->fetch();

										$catEnums = CIBlockPropertyEnum::GetList([], ["IBLOCK_ID" => $arParams['IBLOCK_ID'], "CODE" => 'cat']);
										$catIndex = false;
										?>

										<div class="xl:f-24 ftw-5"><?=$title['NAME']?>:</div>

										<div class="w-100 flex f-col gap-8">
											<?if(!empty($title['HINT'])):?>
												<div class="f-12 ftw-4"><?=$title['HINT']?></div>
											<?endif;?>
											<div class="w-100 flex f-col gap-8 md:gap-16 f-12 xl:f-16">
												<?//foreach($catFields as $key => $item):?>
												<?while($catFields = $catEnums->GetNext()):?>
													<div class="flex ai-c gap-16">
														<input <?=!$catIndex ? 'checked' : ''?> type="radio" class="w-20px h-20px" name="<?=$catFields['PROPERTY_CODE']?>" value="<?=$catFields['ID']?>" id="<?=$catFields['PROPERTY_CODE']?>_<?=$catFields['ID']?>">
														<label for="<?=$catFields['PROPERTY_CODE']?>_<?=$catFields['ID']?>" class="mb-0"><?=$catFields['VALUE']?></label>
													</div>
												<?
												$catIndex = true;
												endwhile;?>
											</div>
										</div>
									</div>

									<div class="w-100 flex jc-b">
										<button class="lang-opros--btn-prev <?=$btnNav?>">Назад</button>
										<button class="lang-opros--btn-next <?=$btnNav?>">Далее</button>
									</div>
								</div>

								<div class="swiper-slide <?=$slideMod?>">

									<?
									$title = PropertyTable::getList([
										'select' => ['NAME', 'HINT'],
										'filter' => ['CODE' => 'dt', 'IBLOCK_ID' => $arParams['IBLOCK_ID']],
										'cache' => Util::getCache()
									])->fetch();
									?>

									<div class="flex f-col gap-16 grow-1 lh-nl">

										<div class="xl:f-24 ftw-5"><?=$title["NAME"]?>:</div>

										<div class="w-100 flex f-col gap-8 f-12 xl:f-16">
											<?if(!empty($title['HINT'])):?>
												<div class="f-12 ftw-4"><?=$title['HINT']?></div>
											<?endif;?>
											<textarea placeholder="<?=$title["NAME"]?>" rows="5" name="dt" class="app-n b-1 b-sd b-E7E7F2 r-2 pt-8 pr-11 pb-8 pl-11"></textarea>
										</div>
									</div>

									<div class="w-100 flex jc-b">
										<button class="lang-opros--btn-prev <?=$btnNav?>">Назад</button>
										<button class="lang-opros--btn-next <?=$btnNav?>">Последний вопрос</button>
									</div>
								</div>

								<div class="swiper-slide <?=$slideMod?>">
									<div class="flex f-col gap-16 grow-1 lh-nl">

										<?
										$text = Util::getElement("LangText{$lang}", ['ID', 'IBLOCK_ID', 'NAME', "PREVIEW_TEXT"], ['ID' => '8148', 'ACTIVE' => 'Y']);
										?>

										<?if(!empty($text)):?>
											<div class="w-100 flex f-col gap-16" id="<?=Util::iblockElementEditAdd($text)?>">
												<div class="f-18 xl:f-24 ftw-5"><?=$text['NAME']?></div>
												<?if(!empty($text['PREVIEW_TEXT'])):?>
													<div class="f-12 md:f-14 xl:f-18"><?=$text['PREVIEW_TEXT']?></div>
												<?endif;?>
											</div>
										<?endif;?>

										<div class="w-100 flex f-col gap-8">
											<?$fieldClass = "c-dt app-n w-100 pt-8 pr-11 pb-8 pl-11 b-1 b-sd b-ECECEF r-4 b-we f-14 md:f-16 xl:f-18 hover:b-0048d0 tn-all-3";?>

											<?foreach($arParams["FORM_FIELD"] as $key => $itemField):?>
												<?if($itemField == "Y"):?>
													<?switch($key):
														case "NAME":?>
															<div class="w-100 flex f-col">
																<input placeholder="Имя или название компании" value="<?=$arResult['AUTHOR_NAME']?>"
																	class="<?=$fieldClass?>" id="FEEDBACK[<?=$key?>][<?=$id?>]" type="text" name="user_name" data-valid="name"
																	<?if(empty($arParams["REQUIRED_FIELDS"]) || in_array($key, $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
															</div>
															<?break;?>
														<?case "EMAIL":?>
															<div class="w-100 flex f-col">
																<input id="FEEDBACK[EMAIL]" class="js-field-form--email-<?=$id?> <?=$fieldClass?>" value="<?=$arResult['AUTHOR_EMAIL']?>"
																	type="text" name="user_email" placeholder="E-mail (необязательно)"
																	data-valid="email" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
															</div>
															<?break?>
														<?case "PHONE":?>
															<div class="w-100 flex f-col">
																<input class="js-field-form--phone-faq <?=$fieldClass?>" id="FEEDBACK[PHONE][<?=$id?>]" type="tel"
																	placeholder="+7 (999) 999-99-99" name="user_phone" data-inputmask="'mask': '+7 (999) 999-99-99'"
																	value="<?=$arResult["AUTHOR_PHONE"]?>" <?if(empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])):?>required<?endif?> />
															</div>
															<?break?>
													<?endswitch?>
												<?endif?>
											<?endforeach?>

										</div>
									</div>

									<div class="w-100 flex fw-w jc-b">

										<button class="lang-opros--btn-prev <?=$btnNav?>">Назад</button>

										<div class="">
											<div class="w-100 grow-0 shrink-0">

												<button type="submit" class="<?=$btnNav?> b-0048d0 bg-0048d0 hover:bg-tt c-we hover:c-dt"
													name="submit" id="feedback-<?=$id?>" value="<?=$arParams['buttonName'] ? $arParams['buttonName'] : Loc::getMessage("MFT_SUBMIT")?>">
													Получить расчет<br class="block sm:d-none"> и скидку 5%
												</button>

												<?if($arParams["GOOGLE_RECAPTCHA"] == "Y"):?>
													<input type="hidden" name="g-recaptcha-response" class="g-recaptcha-response-faq">
												<?endif?>

												<input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
											</div>
										</div>

										<div class="w-100 flex jc-e mt-8">
											<div class="">
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
										</div>
									</div>
								</div>
							</div>

							<div class="swiper-pagination"></div>
						</div>

						<script>
							function recOpros()
							{
								let pagi = document.querySelector('.lang-opros--pagi-number');

								var swiper = new Swiper(".mySwiper", {
									pagination: {
										el: ".swiper-pagination",
										type: "progressbar",
									},
									navigation: {
										nextEl: ".lang-opros--btn-next",
										prevEl: ".lang-opros--btn-prev",
									},
									allowTouchMove: false
								});

								swiper.on('activeIndexChange', () => {
									pagi.innerText = swiper.activeIndex + 1;
								});
							}

							try {
								if(Swiper)
								{
									window.addEventListener('DOMContentLoaded', recOpros);
								}
							}
							catch(e) {
								window.addEventListener('onLangSwiperScriptLoad', recOpros);
							}
						</script>
					</form>
				<?endif;?>
			</div>

			<div class="w-100 lg:w-4/12 pr-8 pl-8 grow-0 shrink-0 flex f-col md:f-row lg:f-col gap-32 md:gap-0 lg:gap-32 order-0 lg:order-2 mb-11 lg:mb-0 md:jc-b lg:jc-s md:ai-c lg:ai-s">

				<?
				$edit = ['ID' => $recOprosMan->getId(), 'IBLOCK_ID' => $recOprosMan->getIblockId()];
				?>

				<div class="w-100 md:w-6/12 lg:w-100 b-1 b-sd b-E7E7F2 p-11 r-16 flex f-col gap-16" id="<?=Util::iblockElementEditAdd($edit)?>">
					<div class="w-100 flex gap-16">
						<?
						$imgMan = \CFile::GetPath($recOprosMan->getPreviewPicture());
						?>
						<div class="w-70px h-70px r-100 bg-cr bg-c bg-nr bg-load lazyload" data-bg="<?=$imgMan?>"></div>
						<div class="flex f-col gap-8 lh-nl">
							<div class="xl:f-20 ftw-5"><?=$recOprosMan->getName()?></div>
							<div class="f-14"><?=$recOprosMan->getPreviewText()?></div>
						</div>
					</div>

					<div class="w-100 ta-c">
						<div class=""><?=$recOprosMan->getDetailText()?></div>
					</div>

					<div class="w-100 flex f-col ai-c">
						<?foreach($recOprosMan->getText()->getAll() as $k => $item):?>
							<a href="<?=$item->getValue()?>" class="xl:f-24 ftw-5 hover:tdl-u tuo-2 tdt-1 tdc-py hover:c-py tn-all-3"><?=$item->getDescription()?></a>
						<?endforeach?>
					</div>
				</div>

				<?if(!empty($recOprosSale)):
					$recOprosSale['PREVIEW_PICTURE'] = \CFile::GetPath($recOprosSale['PREVIEW_PICTURE']);
					?>
					<div class="w-100 md:w-6/12 lg:w-100 r-16 atr-3x1 flex jc-e bg-cr bg-c bg-nr bg-load lazyload md:ml-8 lg:ml-0" data-bg="<?=$recOprosSale['PREVIEW_PICTURE']?>" id="<?=Util::iblockElementEditAdd($recOprosSale)?>">
						<div class="w-6/12 xl:w-6/12 flex f-col gap-8 jc-c lh-nl">
							<div class=""><?=$recOprosSale['NAME']?></div>
							<div class="ftw-5"><?=$recOprosSale['PREVIEW_TEXT']?></div>
						</div>
					</div>
				<?endif;?>
			</div>

		</div>
	</div>

</section>


<style>.grecaptcha-badge{display:none!important}</style>
<script>
	window.addEventListener('scroll', () => {
        LG.blockVisible(document.querySelector('.lang-request-main--block'), 'langMainPhoneMask', () => {
			LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/inputmask.min.js', LG.fieldMaskPhone, true, 'body');
		})
    });

	window.addEventListener('DOMContentLoaded', () => {
        LG.blockVisible(document.querySelector('.lang-request-main--block'), 'langMainPhoneMask', () => {
			LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/inputmask.min.js', LG.fieldMaskPhone, true, 'body');
		})
    });
</script>