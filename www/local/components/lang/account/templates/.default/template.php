<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$langText = "LangText";
$listData = Data::getElementsObject($langText, ['select' => ['NAME', 'link'],'filter' => ['ID' => [310,311,312]]]);
$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;

?>

<div class="w-100 md:p-24 d-f fd-c rg-16 xl:rg-24 md:r-12 md:bc-w">
	<?foreach($listData as $sumData):?>
		<div class="w-100 p-24 md:p-0 r-12 md:r-0 bc-w md:bc-t">
			<div class="<?=comp::get("row")?> ai-c rg-24 xl:f-16">
				<div class="w-7/12 md:w-4/12 xl:w-4/12 <?=comp::get("setcol")?> ftw-5"><?=$sumData->getName()?></div>
				<div class="w-5/12 md:w-3/12 xl:w-3/12 <?=comp::get("setcol")?> ftw-5 ws-nw"><?=$arResult['list'][$sumData->getId()]['sum']?></div>
				<?if(!empty($sumData->getLink()) && !empty($sumData->getLink()->getValue())):?>
					<div class="w-100 md:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> d-f jc-e">
						<a href="<?=$sumData->getLink()->getValue()?>" class="<?=comp::get("btnBlueFillSm")?>"><?=$sumData->getLink()->getDescription()?></a>
					</div>
				<?endif;?>
				<?/*elseif($sumData->getId() == 311):?>
					<div class="w-100 md:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> d-f jc-e">
						<button class="lang-balans--out <?=comp::get("btnBlueFillSm")?>"><?=$sumData->getLink()->getDescription()?></button>
					</div>
				<?endif;*/?>
			</div>
		</div>
	<?endforeach;?>
</div>