<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;

Loader::includeModule('lang.css');

$parser = new CTextParser();
$parser->allow = array(
    "HTML" => "N",
    "ANCHOR" => "Y",  // ссылки
    "BIU" => "Y",    // жирный, курсив, подчеркнутый
    // "IMG" => "Y",   // изображения
    // "QUOTE" => "Y", // цитаты
    // "CODE" => "Y",   // блоки кода
    // "FONT" => "Y",   // шрифты
    "LIST" => "Y",   // списки
    // "SMILES" => "Y", // смайлики
    // "NL2BR" => "Y", // перенос строк
    // "VIDEO" => "Y", // видео
    // "TABLE" => "Y", // таблицы
    "CUT_ANCHOR" => "N", // запрет на вставку закрывающего тега ссылки
    // "ALIGN" => "Y"  // выравнивание
);
?>

<?if(!empty($arResult['order']) && !$arResult['order']->isEmpty()):?>
	<?foreach ($arResult['order'] as $order):?>
		<a href="/lk<?=Data::getDetailPageUrl($arResult['detailPage'], $order)?>" class="w-100 p-32 bc-w r-12 bw-1 bs-s bcr-CECECE h:bcr-py tn-all-3">
			<div class="<?=comp::get("row")?>">
				<div class="w-100 xl:w-7/12 <?=comp::get("setcol")?> d-f fd-c rg-24 xl:rg-32">
					<div class="w-100 d-f fd-c rg-16">
						<div class="d-f ai-c cg-16">
							<div class="ftw-6">#<?=$order?->getOrder()?->getValue()?></div>
							<div class="pt-4 pr-8 pb-4 pl-8 tt-u f-10 r-4 bi-st c-w"><?=$order->get('S_ID')?->getName()?></div>
							<div class="f-12">создан <?=FormatDate('j F Y в H:i', $order->getDateCreate());?></div>
						</div>

						<div class="xl:f-20 ftw-6 ls--1"><?=$order->getName()?></div>

						<?if(!empty($order->getPreviewText())):
							$preview = strip_tags($parser->convertText($order->getPreviewText()));

							if(strlen($preview) >= 100)
								$preview = mb_strimwidth(strip_tags($preview), 0, 100) . '...';
							?>
							<div class="f-12 lh--1"><?=$preview?></div>
						<?endif?>
					</div>
				</div>
				<div class="w-100 xl:w-5/12 <?=comp::get("setcol")?> d-f fd-c jc-e">
					<div class="d-f fd-c ai-e jc-e rg-16">
						<div class="f-16 ftw-6 ta-r">
							<?=(intval($order->get('PRICE')?->getPrice()) > 0) ?
								SaleFormatCurrency($order->get('PRICE')?->getPrice(), $order->get('PRICE')?->getCurrency()) :
								"Договорная";?>
						</div>
						<div class="<?=comp::get("btnBlueFillSm")?>">Открыть</div>
					</div>
				</div>
			</div>
		</a>
	<?endforeach;?>

	<?if($arResult['nav']->getPageCount() > $arResult['nav']->getCurrentPage()):?>
		<div class="lang-order--nav w-100 d-f jc-c">
			<button class="<?=comp::get("btnGreenSm")?>">Показать еще</button>
		</div>
	<?endif;?>
<?else:?>
	<div class=""><?=$arResult['message']?></div>
<?endif?>