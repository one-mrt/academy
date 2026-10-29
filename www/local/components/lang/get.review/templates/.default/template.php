<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

Loader::includeModule('lang.css');
?>

<?if(!empty($arResult['review'])):?>
	<?foreach ($arResult['review'] as $review):?>
		<div class="w-100 lg:w-6/12 <?=comp::get('setcol')?>">
			<div class="w-100 h-100 p-32 xxl:p-24 d-f fd-c rg-16 r-12 bc-w of-h bw-1 bs-s bcr-w">
				<div class="w-100 d-f ai-c cg-24">
					<div class="bc-w r-100 w-60px h-60px mnw-60px mnh-60px of-h fg-0 bw-1 bs-s bcr-c5c5c566">
						<?if(!empty($review['picture'])):?>
							<?=Data::getImageId($review['picture'], [
								"alt" => strip_tags($review['name']),
								"loading" => "lazy",
								"class" => "w-60px h-60px mnw-60px mnh-60px oft-cv op-c r-100",
							])?>
						<?endif?>
					</div>
					<div class="fg-1 d-f fd-c rg-4">
						<div class="f-18 xxl:f-16 ftw-6"><?=$review['name']?></div>
						<?if(!empty($review['dol'])):?>
							<div class="f-12 xxl:f-10"><?=$review['dol']?></div>
						<?endif?>
					</div>
				</div>

				<?if(!empty($review['text'])):?>
					<div class="w-100 f-12 xxl:f-12 mb-0>lc:p fg-1"><?=$review['text']?></div>
				<?endif?>

				<?if(!empty($review['score'])):?>
					<div class="lang--review d-f ai-c jc-e" title="Оценка отзыва от <?=$review['name']?>">
						<?
						$check = false;
						for($k = 1; $k < 6; $k++):?>
							<div class="score--wrap pn-r d-f <?=(!$check) ? 'c-e64231' : 'c-t'?> tn-all-3">
								<svg class="lang-review--score-radio-svg" width="24" height="24" viewBox="0 0 46 44" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
									<path d="M43.7378 17.2735L29.9638 15.2929L23.7816 2.77855L23.3333 1.8711L22.885 2.77855L16.7029 15.2929L2.92884 17.2735L1.92495 17.4179L2.65077 18.1262L12.6445 27.8799L10.2737 41.6248L10.1005 42.629L11.0007 42.1515L23.3333 35.6101L35.666 42.1515L36.5662 42.629L36.393 41.6248L34.0221 27.8799L44.0159 18.1262L44.7417 17.4179L43.7378 17.2735Z" stroke="#E64231"/>
								</svg>
							</div>
							<?
							if(empty($review['score']) && $k == 1) {
								$check = true;
							} elseif($review['score'] == $k) {
								$check = true;
							}
							?>
						<?endfor;?>
					</div>
				<?endif;?>
			</div>
		</div>
	<?endforeach;?>

	<?if($arResult['nav']->getPageCount() > $arResult['nav']->getCurrentPage()):?>
		<div class="lang-review--nav w-100 d-f jc-e <?=comp::get('setcol')?>">
			<button class="m-0 p-0 bc-t bw-0 bc-t h:c-py tt-l ftw-6 tdt-1 tuo-4 tdc-py tn-all-3 tdl-u">Показать еще</button>
		</div>
	<?endif;?>
<?else:?>
	<div class="w-100 <?=comp::get('setcol')?>"><?=$arResult['message']?></div>
<?endif?>