<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Bitrix\Main\Localization\Loc;

Loader::includeModule('iblock');
Loader::includeModule('lang.iblock');

$lang = (SITE_ID == 'en' || SITE_ID == 'tt') ? ucfirst(SITE_ID) : '';
$textTable = "Bitrix\Iblock\Elements\ElementLangText{$lang}Table";

if (!empty($arResult)):
	if(!empty($arParams['titleID'])):
		$text = Util::getElement("LangText{$lang}", ['ID', 'IBLOCK_ID', 'NAME', 'l' => 'link.VALUE'], ['ID' => $arParams['titleID'], 'ACTIVE' => 'Y']);
		?>
		<div class="w-100 lh-nl c-py f-14 ftw-7" id="<?=Util::iblockElementEditAdd($text)?>">
			<?if(!empty($text['l'])):?><a href="<?=$text['l']?>" class="hover:tdl-u tuo-4 tn-all-3 tdt-1"><?endif;?>
				<?=$text['NAME']?>
			<?if(!empty($text['l'])):?></a><?endif;?>
		</div>
	<?endif;?>

	<nav class="w-100 grow-1">
		<ul class="d-f fd-c rg-8 lst-n m-0 p-0">
			<?foreach($arResult as $arItem):
				if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
				{
					continue;
				}
				?>
				<li class="w-100">
					<a class="h:tdl-u tdt-1 tuo-4 tdc-w tn-all-3 f-14" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
				</li>
			<?endforeach;?>
		</ul>
	</nav>

	<?if(!empty($arParams['link'])):
		$link = $textTable::getList([
			'select' => ['ID', 'IBLOCK_ID', 'NAME', 'l' => 'link.VALUE'],
			'filter' => ['ID' => $arParams['link'], 'ACTIVE' => 'Y'],
			'cache' => ['ttl' => $arParams['MENU_CACHE_TIME'], 'cache_joins' => true]
		])->fetch();
		?>
		<div class="w-100 mt-8">
			<a href="<?=$link['l']?>" class="hover:tdl-u tuo-4 tn-all-3 flex gap-8 tdt-1 lh-sd c-sy hover:c-we">
				<div class="f-14 xl:f-20 pt-3 c-we"><?=$link['NAME']?></div>
				<div>
					<svg width="12" height="12" viewBox="0 0 12 11" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M1.5 10L10.5 1M10.5 1H1.5M10.5 1V10" stroke-width="1.5"></path></svg>
				</div>
			</a>
		</div>
	<?endif;?>

<?endif;?>