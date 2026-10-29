<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langSpec = "LangLkSpec{$lang}";
$langWorkType = "LangLkTypeWork{$lang}";

$image = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => 280]);

$specSectionList = Data::getSectionsObject($langSpec, [
	'select' => ['NAME'],
	'cache' => Data::getCache(0)
]);

$specElementList = Data::getElementsObject($langSpec, [
	'select' => ['NAME', 'IBLOCK_SECTION_ID'],
	'cache' => Data::getCache(0)
]);

$workTypeList = Data::getElementsObject($langWorkType, [
	'select' => ['NAME'],
	'cache' => Data::getCache(0)
]);

$propData =& $arResult["USER_PROPERTIES"]["DATA"];

$specData = !empty($propData['UF_SPEC']['VALUE']) ? $propData['UF_SPEC']['VALUE'] : [];
$workTypeData = !empty($propData['UF_WORKTYPE']['VALUE']) ? $propData['UF_WORKTYPE']['VALUE'] : [];
$modTab = "bc-F4F4FD c-dt h:bi-st h:c-w";
$modTabActive = "bi-st c-w h:bc-F4F4FD h:bi-n h:c-dt";
$modIndexTab = 0;

?>

<div class="w-100 h-100">
	<?
	ShowError($arResult["strProfileError"]);
	if (isset($arResult['DATA_SAVED']) && $arResult['DATA_SAVED'] == 'Y')
		ShowNote(GetMessage('PROFILE_DATA_SAVED'));
	?>


	<form class="w-100 h-100 d-f fd-c rg-24 lg:rg-32 jc-b fg-1 f-14" method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
		<?=$arResult["BX_SESSION_CHECK"]?>
		<input type="hidden" name="lang" value="<?=LANG?>" />
		<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
		<input type="hidden" name="save" value="y">

		<div class="w-100">
			<h2 class="mb-0 ff-ud f-18 lg:f-24 ftw-5 tt-l ls--1 lh-1.125">Специализация</h2>
		</div>

		<div class="w-100 fg-1 d-f fd-c rg-24">

			<div class="w-100">
				<div class="<?=comp::get("row")?> rg-32">
					<div class="w-100 pr-16 pl-16 fg-0 fs-0">
						<div class="w-100 d-f fw-w g-16 ai-s" data-tab-categoryspec>
							<?foreach($specSectionList as $specSection):?>
								<div class="pby-8 pix-16 r-4 bw-0 ws-nw cr-p <?=($modIndexTab == 0) ? $modTabActive : $modTab?>" <?=Data::iblockSectionEditAddId($specSection)?>><?=$specSection->getName()?></div>
								<?$modIndexTab++?>
							<?endforeach;?>
						</div>
					</div>
					<div class="w-100 pr-16 pl-16 fg-0 fs-0">
						<div class="w-100 h-100 pn-r of-h">
							<?/*if(!empty($image) && !empty($image->getPreviewPicture())) {
								echo Data::getImageId($image->getPreviewPicture(), [
									"alt" => strip_tags($image->getName()),
									"fetchpriority" => "high",
									"class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
								]);
							}*/?>
							<div class="w-100 h-100 pn-r z-1 r-12 p-24 bw-1 bs-s bcr-F4F4FD" data-pan-categoryspec>
								<?foreach($specSectionList as $specSection):?>
									<div class="w-100">
										<div class="<?=comp::get("row")?> rg-16">
											<?foreach($specElementList as $spec):?>
												<?if($spec->getIblockSectionId() == $specSection->getId()):?>
													<div class="w-100 md:w-6/12 pr-16 pl-16 fg-0 fs-0">
														<label for="spec<?=$spec->getId()?>" class="mb-0 lh-1">
															<input type="checkbox" name="UF_SPEC[]" value="<?=$spec->getId()?>" id="spec<?=$spec->getId()?>" <?=(in_array($spec->getId(), $specData)) ? 'checked' : ''?>>
															<?=$spec->getName()?>
														</label>
													</div>
												<?endif;?>
											<?endforeach;?>
										</div>
									</div>
								<?endforeach;?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="w-100 d-f fd-c rg-24">
				<div class="w-100 ftw-6 xl:f-18">Выполняемые типы работ:</div>
				<div class="w-100 r-12 p-24 bw-1 bs-s bcr-F4F4FD">
					<div class="<?=comp::get("row")?> rg-16">
						<?foreach($workTypeList as $workType):?>
							<div class="w-100 md:w-4/12 pr-16 pl-16 fg-0 fs-0">
								<label for="wortType<?=$workType->getId()?>" class="mb-0 lh-1">
									<input type="checkbox" name="UF_WORKTYPE[]" value="<?=$workType->getId()?>" id="wortType<?=$workType->getId()?>" <?=(in_array($workType->getId(), $workTypeData)) ? 'checked' : ''?>>
									<?=$workType->getName()?>
								</label>
							</div>
						<?endforeach;?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="w-100 d-f cg-24">
			<button class="<?=comp::get("btnBlueFillSm")?>"
				type="submit" name="save" value="<?=(($arResult["ID"] > 0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?>"><?=(($arResult["ID"] > 0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?></button>
			<?/*<button class="" type="reset" value="<?=Loc::getMessage('MAIN_RESET');?>"><?=Loc::getMessage('MAIN_RESET');?></button>*/?>
		</div>

	</form>

	<?/*<script>
		LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/tabbis.es5.min.js', () => {
			tabbis({
				tabGroup: '[data-tab-categoryspec]',
				paneGroup: '[data-pan-categoryspec]',
				prefix: 'spec',
			});
		});

		document.addEventListener("tabbis", e => {

			if(e.detail.tab.classList.contains('lang-tab--programm'))
			{
				document.querySelectorAll('.lang-tabs--programm > button').forEach(el => {
					el.classList.add("bc-w", 'c-py', 'h:bc-py', 'h:c-w');
					el.classList.remove("bc-py", 'c-w', 'h:bc-w', 'h:c-dt');
				});

				e.detail.tab.classList.remove("bc-w", 'c-py', 'h:bc-py', 'h:c-w', 'h:c-dt');
				e.detail.tab.classList.add("bc-py", 'c-w');
			}

		}, false );
	</script>*/?>
</div>
