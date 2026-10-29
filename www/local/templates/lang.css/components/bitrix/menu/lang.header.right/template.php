<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Region;
use Lang\Css\Component as comp;

$phone = Region::getCurrent(['select' => ['phone']])->getPhone();

$ID = rand();
?>

<?if(!empty($arResult)):?>

	<button class="lang-header--right m-0 p-0 bw-0 bc-t d-f ai-c jc-e cg-8 ftw-5 f-14 lh-1 h:c-py tn-all-3" title="Открыть общее меню сайта" aria-label="Открыть общее меню сайта">
		<div class="">Меню</div>
		<div class="d-f">
			<svg width="19" height="12" viewBox="0 0 19 12" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
				<line y1="1" x2="19" y2="1" stroke-width="2"/>
				<line y1="6" x2="19" y2="6" stroke-width="2"/>
				<line y1="11" x2="19" y2="11" stroke-width="2"/>
			</svg>
		</div>
	</button>

	<div class="w-100 h-100 pn-f i-0 l--100% md:l-0 bc-ffffff08 bf-b5 md:d-n tp-l tnd-0.3S tn-all-3" id="LangHeaderRight<?=$ID?>">
		<div class="<?=comp::get("container")?> h-100 md:h-a d-f jc-e ai-s pr-0 pl-0 md:pr-16 md:pl-16">
			<div class="lang-header--menu w-100 d-f fd-c rg-16 bc-EDF0F9 md:r-20 p-32 xl:p-48 md:w-700px pn-r z-1 lg:bxs-py h-100 md:h-a">

				<button class="lang-header--right bc-t bw-0 m-0 p-0 mt-16 mr-16 pn-a t-0 rt-0 d-f md:d-n ai-c jc-c h:c-py tn-all-3" aria-label="Закрыть общее меню сайта" title="Закрыть общее меню сайта">
					<svg width="16" height="16" viewBox="0 0 17 17" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
						<line x1="0.354454" y1="16.436" x2="16.4369" y2="0.353568"/>
						<line x1="16.4355" y1="16.6547" x2="0.35308" y2="0.572272"/>
					</svg>
				</button>

				<div class="w-100 fg-0 d-f jc-b cg-32 md:d-n pt-16">

					<?foreach($phone->getAll() as $value):?>
						<a class="ftw-5 f-14 h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3 ws-nw" href="tel:<?=$value->getValue()?>"><?=$value->getDescription()?></a>
						<?break;?>
					<?endforeach;?>

					<button onclick="sFRuk()" class="m-0 p-0 bw-0 tdl-u tdt-1 tuo-2 bc-t h:c-py cr-p tn-all-3 lh-1" title="Открыть список филиалов" aria-label="Открыть список филиалов">Филиалы</button>
				</div>

				<nav class="<?=comp::get("row")?> fd-c md:fd-r fw-n md:fw-w rg-16 fg-1 of-a">

					<div class="lang-tabs--header w-100 md:w-6/12 lg:w-7/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-16" data-tab-header="">
						<?foreach($arResult as $k => $arItem):

							if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
							{
								continue;
							}

							$modActive = "bc-w c-dt bcr-w h:bc-py h:c-w h:bcr-py";

							if($k == 0)
							{
								$modActive = "bc-py c-w bcr-py";
							}
							?>
							<?if($arItem['PARAMS']['type'] == 'section'):?>
								<button class="lang-tab--header m-0 pt-16 pr-32 pb-16 pl-32 lg:f-20 ftw-7 ta-l lh-1 bw-1 bs-s r-12 <?=$modActive?> a:tm-s0.9 tn-all-3"><?=$arItem['TEXT']?></button>
							<?endif;?>
						<?endforeach?>
					</div>
					
					<section class="w-100 md:w-6/12 lg:w-5/12 pr-16 pl-16 fg-1 md:fg-0 fs-0 d-f fd-c" data-pan-header="">
						<?foreach($arResult as $key => $arItem):
							if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
								continue;
							?>
							<?if($arItem['PARAMS']['type'] == 'section' && $key > 0):?>
								</ul></div><div class="w-100 h-100 d-f fd-c"><ul class="w-100 h-100 d-f fd-c rg-12 lst-n m-0 p-0 f-14 ftw-4">
							<?elseif($arItem['PARAMS']['type'] == 'section'):?>
								<div class="w-100 h-100 d-f fd-c"><ul class="h-100 d-f fd-c rg-12 lst-n m-0 p-0 f-14 ftw-4">
							<?endif;?>

							<?if($arItem['PARAMS']['type'] == 'element'):?>
								<li class="">
									<?if(!empty($arItem["LINK"])):?>
										<a class="h:tdl-u tdt-1 tuo-2 lh-1 h:c-py tn-all-3" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
									<?else:?>
										<button class=""><?=$arItem['TEXT']?></button>
									<?endif;?>
								</li>
							<?endif;?>

						<?endforeach?>
						</ul></div>
					</section>
				</nav>

				<div class="w-100 fg-0 d-f jc-e md:d-n">
					<button class="pt-8 pr-16 pb-8 pl-16 bw-1 bs-s r-999 f-14 lh-1 ftw-5 ws-nw bc-dt c-w bcr-dt h:bc-t h:c-dt a:tm-s0.9 tn-all-3"
                        data-fancybox="" data-src="<?=SITE_TEMPLATE_PATH?>/ajax/order/" data-type="ajax">Напишите нам</button>
				</div>

			</div>
		</div>
	</div>

	<script>
		hM = (btn) => {
			let top = btn.getBoundingClientRect().bottom + 16;

			btn.classList.toggle('pn-r');
			btn.classList.toggle('z-1');
			LangHeaderRight<?=$ID?>.querySelector(".lang-header--menu").style.marginTop = `${top/16}rem`;
			LangHeaderRight<?=$ID?>.classList.toggle('md:d-n');
			document.body.classList.toggle('md:pr-15');
			document.body.classList.toggle('of-h');
		};

		document.addEventListener('DOMContentLoaded', () => {
			document.querySelectorAll('.lang-header--right').forEach((btn) => {
				if(window.innerWidth >= 768)
				{
					btn.addEventListener('click', () => {
						hM(btn)
					});
				}
				else
				{
					btn.addEventListener('click', () => {
						LangHeaderRight<?=$ID?>.classList.toggle('l-0');
						document.body.classList.toggle('md:pr-15');
						document.body.classList.toggle('of-h');
					});
					
				}
				
			});
		});
		

		LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/tabbis.es5.min.js', () => {
			tabbis({
				tabGroup: '[data-tab-header]',
				paneGroup: '[data-pan-header]',
				prefix: 'header',
			});
		});
        
        document.addEventListener("tabbis", e => {

            if(e.detail.tab.classList.contains('lang-tab--header'))
            {
                document.querySelectorAll('.lang-tabs--header > button').forEach(el => {
                    el.classList.add('bc-w', 'c-dt', 'bcr-w', 'h:bc-py', 'h:c-w', 'h:bcr-py');
                    el.classList.remove("bc-py", 'c-w', 'bcr-py');
                });

                e.detail.tab.classList.remove('bc-w', 'c-dt', 'bcr-w', 'h:bc-py', 'h:c-w', 'h:bcr-py');
                e.detail.tab.classList.add("bc-py", 'c-w', 'bcr-py');
            }

        }, false );
    </script>
<?endif?>
