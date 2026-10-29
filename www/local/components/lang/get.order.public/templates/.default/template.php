<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;

Loader::includeModule('lang.css');
?>

<?if(!empty($arResult['order']) && !$arResult['order']->isEmpty()):?>
	<?foreach ($arResult['order'] as $order):?>
		<a href="<?=Data::getDetailPageUrl($arResult['detailPage'], $order)?>" class="w-100 p-32 bc-w r-12 bw-1 bs-s bcr-CECECE h:bcr-py tn-all-3">
            <div class="<?=comp::get("row")?> rg-16">
                <div class="w-100 <?=comp::get("setcol")?> d-f fd-c rg-24 xl:rg-16">

					<div class="<?=comp::get("row")?> rg-16">
						<div class="w-100 xl:w-7/12 <?=comp::get("setcol")?> d-f ai-c">
							<div class="w-100 d-f fd-c rg-16">

								<div class="d-f fw-w ai-c g-16">
									<div class="pt-4 pr-8 pb-4 pl-8 tt-u f-10 r-4 bi-st c-w ws-nw"><?=$order->get('S_ID')?->getName()?></div>
									<div class="f-12 c-636363">создан <?=FormatDate('j F Y в H:i', $order->getDateCreate());?></div>
								</div>

								<div class="xl:f-20 ftw-6 ls--1"><?=$order->getName()?></div>

								<object class="w-100 d-f fw-w rg-8 cg-8 f-12 ws-nw ftw-6">
									<div class="c-py"><?=$order->getTwork()->getElement()->getName()?></div>
									<div class="c-py"><?=$order->getSspec()->getSection()->getName()?></div>
									<div class="c-py"><?=$order->getPred()->getElement()->getName()?></div>
								</object>
							</div>
						</div>
						<div class="w-100 xl:w-5/12 <?=comp::get("setcol")?> d-f ai-e jc-e">
							<div class="f-18 ftw-6"><?=(intval($order?->get('PRICE')?->getPrice()) > 0) ? SaleFormatCurrency($order?->get('PRICE')?->getPrice(), $order->get('PRICE')?->getCurrency()) : 'Договорная'?></div>
						</div>
					</div>

                    <div class="<?=comp::get("row")?> rg-16">
                        <div class="w-100 md:w-5/12 xl:w-7/12 <?=comp::get("setcol")?> d-f ai-c">
                            <div class="w-100 f-12"><?=FormatDate('срок сдачи j F Y', strtotime($order?->getDed()?->getValue()));?></div>
                        </div>
                        <div class="w-100 md:w-7/12 xl:w-5/12 <?=comp::get("setcol")?> d-f fd-c jc-e">
                            <div class="d-f ai-c jc-e cg-16">

                                <div class="d-f cg-8 f-12">
                                    <div class="d-f">
                                        <svg width="21" height="14" viewBox="0 0 21 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20.3636 7C20.3636 7 16.5455 0 10.1818 0C3.81818 0 0 7 0 7C0 7 3.81818 14 10.1818 14C16.5455 14 20.3636 7 20.3636 7ZM1.49291 7C2.10829 6.0647 2.81609 5.1936 3.60564 4.39982C5.24364 2.75927 7.48364 1.27273 10.1818 1.27273C12.88 1.27273 15.1187 2.75927 16.7593 4.39982C17.5488 5.1936 18.2566 6.0647 18.872 7C18.7982 7.11073 18.7167 7.23291 18.6238 7.36655C18.1975 7.97745 17.5675 8.792 16.7593 9.60018C15.1187 11.2407 12.8787 12.7273 10.1818 12.7273C7.48364 12.7273 5.24491 11.2407 3.60436 9.60018C2.81482 8.80639 2.1083 7.93529 1.49291 7Z"/>
                                            <path d="M10.1818 3.8182C9.33797 3.8182 8.52866 4.15342 7.93196 4.75013C7.33525 5.34684 7.00002 6.15615 7.00002 7.00002C7.00002 7.84389 7.33525 8.6532 7.93196 9.2499C8.52866 9.84661 9.33797 10.1818 10.1818 10.1818C11.0257 10.1818 11.835 9.84661 12.4317 9.2499C13.0284 8.6532 13.3637 7.84389 13.3637 7.00002C13.3637 6.15615 13.0284 5.34684 12.4317 4.75013C11.835 4.15342 11.0257 3.8182 10.1818 3.8182ZM5.72729 7.00002C5.72729 5.8186 6.19661 4.68557 7.032 3.85018C7.86739 3.01479 9.00042 2.54547 10.1818 2.54547C11.3633 2.54547 12.4963 3.01479 13.3317 3.85018C14.1671 4.68557 14.6364 5.8186 14.6364 7.00002C14.6364 8.18144 14.1671 9.31447 13.3317 10.1499C12.4963 10.9852 11.3633 11.4546 10.1818 11.4546C9.00042 11.4546 7.86739 10.9852 7.032 10.1499C6.19661 9.31447 5.72729 8.18144 5.72729 7.00002Z"/>
                                        </svg>
                                    </div>
                                    <div class=""><?=$order->getShowCounter()?></div>
                                </div>

                                <div class="d-f cg-8 f-12">
                                    <div class="d-f">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor" stroke="currentColor" stroke-width="0.4" xmlns="http://www.w3.org/2000/svg"><path d="M12.8694 0.200453H0.53046C0.348019 0.200453 0.199951 0.348516 0.199951 0.53095V10.2255C0.199951 10.408 0.348019 10.556 0.53046 10.556H3.72537V12.8695C3.72537 12.9982 3.79897 13.1097 3.90649 13.1639L3.90826 13.1648C3.95056 13.1868 4.0008 13.2 4.05412 13.2H4.05588C4.1308 13.2 4.19954 13.1749 4.25507 13.133L4.25419 13.1335L7.69148 10.5556H12.8694C13.0519 10.5556 13.2 10.4075 13.2 10.2251V0.530509C13.2 0.348075 13.0519 0.200012 12.8694 0.200012V0.200453ZM12.5389 9.89504H7.58131C7.50639 9.89504 7.43765 9.92016 7.38212 9.96202L7.383 9.96158L4.38639 12.209V10.226C4.38639 10.0435 4.23832 9.89548 4.05588 9.89548H0.860968V0.861888H12.5389V9.89504Z"/></svg>
                                    </div>
                                    <div class="">0</div>
                                </div>

                                <div class="<?=comp::get("btnBlueFillSm")?>">Открыть</div>
                            </div>
                        </div>
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
	<div class="w-100"><?=$arResult['message']?></div>
<?endif?>