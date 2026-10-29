<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use Lang\Main\Css\Modifier;
use Bitrix\Main\Localization\Loc;

$prop =& $arResult['PROPERTIES'];
$ID = rand();
?>

<section class="<?=Modifier::get("w-100 pt-8 lg:pt-11 pb-0 md:pb-11 lg:pb-13 c-2B2B2B")?>">
    <div class="<?=Modifier::get("w-100 sm:mw-540 md:mw-720 lg:mw-1140 xl:mw-1277 pr-8 pl-8 mr-ao ml-ao flex f-col")?>">
        <div class="<?=Modifier::get("flex fw-w ml--8 mr--8")?>">
            <div class="<?=Modifier::get("w-100 pr-8 pl-8 grow-0 shrink-0 lg:mb-11 f-14 md:f-16 ftw-4 c-2B2B2B")?>">

                <div class="<?=Modifier::get("w-100 flex mb-8 md:mb-11 lg:mb-13")?>">
                    <div class="<?=Modifier::get("grow-1 flex")?>">
                        <i class="lang-title--icon <?=Modifier::get("c-py pt-2 lg:pt-4 pr-5 flex lh-sd")?>"><svg width="13" height="23" viewBox="0 0 13 23" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M13 0H11.0248L0 23H1.97515L13 0Z"/></svg></i>
                        <?/*<h2 class="<?=Modifier::get('c-dt ftw-6 f-14 md:f-32 mb-0 upp')?>"><?=$section['NAME']?></h2>*/?>
                        <h1 class="<?=Modifier::get("mb-0 lh-nl f-14 md:f-32 upp")?>"><?=$arResult['~NAME']?></h1>
                    </div>
                    <div class="<?=Modifier::get("grow-0 flex gap-8 c-dt f-6 md:f-14")?>">
                        <time class="<?=Modifier::get("c-sy ws-nw")?>"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></time>
                    </div>
                </div>

                <?if(!empty($prop['autor']['VALUE'])):?>
                    <div class="<?=Modifier::get("c-sy mt-3 d-none")?>">
                        <span><?=$prop['autor']['NAME']?></span>
                        <span><?=$prop['autor']['~VALUE']?></span>
                    </div>
                <?endif;?>

                <?/*if($from = $arResult['DISPLAY_PROPERTIES']['FROM']['VALUE']):?>
                    <div class="<?=Modifier::get("")?>">
                        <?=Loc::getMessage('DETAIL_FROM_FRASE')?>:
                        <?if($link = $arResult['DISPLAY_PROPERTIES']['LINK']['VALUE']):?>
                            <a href="<?=$link?>" target="_blank" rel="nofollow noopener noreferrer" class="<?=Modifier::get("")?>">
                        <?endif;?>
                        <span class="<?=Modifier::get("")?>"><?=$from?></span>
                        <?if($link):?></a><?endif;?>
                    </div>
                <?endif;*/?>


                <?/*<div class="<?=Modifier::get("w-100 atr-3x1 bg-cn bg-c bg-nr bg-load mb-11")?> lazyload" data-bg="<?=$img['SRC']?>"></div>*/?>

                <?/*if(!empty($arResult["~DETAIL_TEXT"])):?>
                    <div class="<?=Modifier::get("w-100 mb-11 f-14 md:f-16 lg:f-18")?>"><?=$arResult["~DETAIL_TEXT"]?></div>
                <?endif;*/?>

                <?if(!empty($arResult['DISPLAY_PROPERTIES']['DATA']['VALUE'])):?>
                    <div class="<?=Modifier::get("w-100 flex f-col gap-32 mb-8 md:mb-11 lg:mb-13")?>">
                        <?foreach($arResult['DISPLAY_PROPERTIES']['DATA']['~VALUE'] as $key => $val):?>
                            <div class="<?=Modifier::get("w-100 lg:w-7/12 f-10 md:f-16 lg:f-20 lh-nl")?>">
                                <?/*if(!empty($arResult['DISPLAY_PROPERTIES']['DATA']["DESCRIPTION"][$key])):?><div class="<?=Modifier::get("")?>"><?=$arResult['DISPLAY_PROPERTIES']['DATA']["DESCRIPTION"][$key]?></div><?endif;*/?>
                                <?=str_replace(['<a'], '<a class="'.Modifier::get("tdl-u tuo-4 tdc-py hover:c-py tn-all-3").'"', $val["TEXT"])?>
                            </div>
                        <?endforeach;?>
                    </div>
                <?endif;?>

                <?if(!empty($arResult['DISPLAY_PROPERTIES']['GALLERY']['FILE_VALUE'])):?>
                    <div class="<?=Modifier::get("w-100 mb-8 md:mb-11 lg:mb-13")?>">

                        <div class="<?=Modifier::get("w-100 d-none md:flex jc-b mb-8 lg:mb-11 c-py")?>">
                            <button class="gallery-select--prev-- navigation--review gallery-select--prev--<?=$ID?> <?=Modifier::get("grow-0 shrink-0 m-0 p-0 bg-tt b-0 c-py hover:c-bk tn-all-3")?>">
                                <svg width="168" height="17" viewBox="0 0 168 17" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M168 8.72656L14 8.72655" stroke-width="1.5"/><path d="M14 1L0.999999 6.72656" stroke-width="1.5"/><path d="M14 15.7266L1.28042 9.40156" stroke-width="1.5"/></svg>
                            </button>
                            <button class="gallery-select--next-- navigation--review gallery-select--next--<?=$ID?> <?=Modifier::get("grow-0 shrink-0 m-0 p-0 bg-tt b-0 c-py hover:c-bk tn-all-3")?>">
                                <svg width="168" height="17" viewBox="0 0 168 17" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M0 8L154 8" stroke-width="1.5"/><path d="M154 15.7266L167 10" stroke-width="1.5"/><path d="M154 1L166.72 7.325" stroke-width="1.5"/></svg>
                            </button>
                        </div>

                        <div class="swiper review-doc-card--<?=$ID?>">
                            <div class="swiper-wrapper f-col gap-8 lg:f-row lg:gap-0">
                                <?if(!empty($arResult['DISPLAY_PROPERTIES']['GALLERY']['FILE_VALUE']['SRC'])):
                                    $imgBig = \CFile::ResizeImageGet($arResult['DISPLAY_PROPERTIES']['GALLERY']['FILE_VALUE']['ID'], ['width'=>800,'height'=>800], BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 95);
                                    ?>
                                    <a href="<?=$imgBig['src']?>" data-fancybox="gallery--<?=$ID?>" class="swiper-slide flex f-col"
                                        <?if(!empty($arResult['DISPLAY_PROPERTIES']['GALLERY']['FILE_VALUE']['DESCRIPTION'])):?>data-caption="<?=$arResult['DISPLAY_PROPERTIES']['GALLERY']['FILE_VALUE']['DESCRIPTION']?>"<?endif;?>>
                                        <div class="<?=Modifier::get("w-100 atr-3x2 bg-cr bg-nr bg-c bg-load")?> lazyload" data-bg="<?=$arResult['DISPLAY_PROPERTIES']['GALLERY']['FILE_VALUE']['SRC']?>"></div>
                                        <?if(!empty($arResult['DISPLAY_PROPERTIES']['GALLERY']['FILE_VALUE']['DESCRIPTION'])):?>
                                            <div class="<?=Modifier::get("c-txd f-8 lg:f-14 mt-8")?>"><?=$arResult['DISPLAY_PROPERTIES']['GALLERY']['FILE_VALUE']['DESCRIPTION']?></div>
                                        <?endif;?>
                                    </a>
                                <?else:?>
                                    <?foreach($arResult['DISPLAY_PROPERTIES']['GALLERY']['FILE_VALUE'] as $image):
                                        $imgBig = \CFile::ResizeImageGet($image['ID'], ['width'=>800,'height'=>800], BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 95);
                                        ?>
                                        <a href="<?=$imgBig['src']?>" data-fancybox="gallery--<?=$ID?>"
                                            <?if(!empty($image['DESCRIPTION'])):?>data-caption="<?=$image['DESCRIPTION']?>"<?endif;?> class="swiper-slide flex f-col">
                                            <div class="<?=Modifier::get("w-100 atr-3x2 bg-cr bg-nr bg-c bg-load")?> lazyload" data-bg="<?=$image['SRC']?>"></div>
                                            <?if(!empty($image['DESCRIPTION'])):?>
                                                <div class="<?=Modifier::get("c-txd f-8 lg:f-14 mt-3 md:mt-8")?>"><?=$image['DESCRIPTION']?></div>
                                            <?endif;?>
                                        </a>
                                    <?endforeach;?>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                    <script>
                        function initPartnerReview(id)
                        {
                            let viewIminSpec_<?=$ID?> = new Swiper(
                                id,
                                {
                                    lazy: false,
                                    slidesPerView: 1,
                                    spaceBetween: 0,
                                    pagination: {
                                        el: ".bMainPagination-<?=$ID?>",
                                        clickable: true,
                                    },
                                    navigation: {
                                        nextEl: '.gallery-select--next--<?=$ID?>',
                                        prevEl: '.gallery-select--prev--<?=$ID?>',
                                        disabledClass : 'd-none md:d-none',
                                        hiddenClass : 'd-none md:d-none',
                                        lockClass : 'd-none md:d-none',
                                    },
                                    breakpoints: {
                                        320: {
                                            slidesPerView: 1,
                                            spaceBetween: 0
                                        },
                                        400: {
                                            slidesPerView: 1,
                                            spaceBetween: 0
                                        },
                                        576: {
                                            slidesPerView: 2,
                                            spaceBetween: 32
                                        },
                                        768: {
                                            slidesPerView: 3,
                                            spaceBetween: 32
                                        },
                                        992: {
                                            slidesPerView: 3,
                                            spaceBetween: 32
                                        },
                                        1200: {
                                            slidesPerView: 2,
                                            spaceBetween: 0
                                        },
                                        1320: {
                                            slidesPerView: 2,
                                            spaceBetween: 0
                                        }
                                    }
                                }
                            );
                        }

                        if(window.innerWidth >= 992)
                        {
                            window.addEventListener('DOMContentLoaded',(e) => {initPartnerReview('.review-doc-card--<?=$ID?>')});
                        }
                    </script>
                <?endif;?>

                <div class="<?=Modifier::get("w-100 flex jc-e")?>">
                    <a href="<?=$arResult["LIST_PAGE_URL"];?>"
                        class="<?=Modifier::get("bg-tt m-0 p-0 b-0 flex ai-c jc-e gap-8 hover:c-py tn-all-3 upp")?>">
                        <span class="<?=Modifier::get("d-none md:block c-py")?>">
                            <svg class="lang-team--svg" width="168" height="17" viewBox="0 0 168 17" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M168 8.72656L14 8.72655" stroke-width="1.5"/><path d="M14 1L0.999999 6.72656" stroke-width="1.5"/><path d="M14 15.7266L1.28042 9.40156" stroke-width="1.5"/>
                            </svg>
                        </span>
                        <span class="<?=Modifier::get("block md:d-none c-py")?>">
                            <svg width="17" height="5" viewBox="0 0 17 5" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M17 2.5L4 2.5" stroke-width="0.5"/><path d="M3.72461 0.914062L1.00006 2.11424" stroke-width="0.5"/><path d="M3.72461 4L1.05883 2.6744" stroke-width="0.5"/></svg>
                        </span>
                        <span class="hover:c-py upp f-6 lg:f-20 tn-all-3"><?=Loc::getMessage('ALL_NEWS_LINK')?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>