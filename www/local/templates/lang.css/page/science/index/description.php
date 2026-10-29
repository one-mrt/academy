<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<section class="w-100 pt-16">
    <div class="<?=comp::get("container")?> d-f fd-c ai-c rg-32">

        <?$APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "breadcrumb.dark",
            [
                "PATH" => "",
                "SITE_ID" => SITE_ID,
                "START_FROM" => "0"
            ],
            false,
            ['HIDE_ICONS' => 'Y']
        );?>

        <div class="w-100">
            <div class="<?=comp::get("row")?> rg-32 jc-c">

                <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                    <h1 class="<?=comp::get("titleh1")?>"><?=$APPLICATION->ShowTitle(false)?></h1>
                </div>

                <?if(!empty($section->getUfText())):
                    $ID = rand();
                    $textEl = Util::getElementObject($textSiteTableString, ['NAME', 'PREVIEW_TEXT', 'img'], ['ID' => $section->getUfText()]);
                    
                    if(!empty($textEl)):?>

                        <?if(!empty($textEl->getPreviewText())):?>
                            <div class="w-100 xl:w-8/12 pr-16 pl-16 fg-0 fs-0 ta-c f-14 xl:f-16" <?=Util::iblockElementEditAddId($textEl)?>><?=$textEl->getPreviewText()?></div>
                        <?endif;?>

                        <?
                        $img = $textEl->getImg()->getAll();
                        if(!empty($img)):?>
                            <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                                <div class="<?=comp::get("row")?>">
                                    <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                                        <div class="swiper lang-img--b<?=$ID?> of-h">
                                            <div class="swiper-wrapper d-f">
                                                <?foreach($img as $im):
                                                    $linkFull = \CFile::GetPath($im->getValue());
                                                    $view = \CFile::ResizeImageGet($im->getValue(), ['width' => 600, 'height' => 600], BX_RESIZE_IMAGE_PROPORTIONAL);
                                                    ?>
                                                    <button data-fancybox="langPhotoS" data-src="<?=$linkFull?>" title="<?=$im->getDescription() ?: $textEl->getName()?>"
                                                        class="swiper-slide xl:w-4/12 ar-16x9 m-0 p-0 bw-0 d-f r-20 bc-w bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=$view['src']?>"></button>
                                                <?endforeach;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                initImgCard = (id) => {
                                    let viewIminSpec_<?=$ID?> = new Swiper(
                                        id,
                                        {
                                            lazy: false,
                                            slidesPerView: 3,
                                            spaceBetween: 32,
                                            navigation: {
                                                nextEl: '.gallery-select--next--<?=$ID?>',
                                                prevEl: '.gallery-select--prev--<?=$ID?>',
                                                disabledClass : 'd-none',
                                                hiddenClass : 'gallery--disabled',
                                                lockClass : 'gallery--disabled',
                                            },
                                            breakpoints: {
                                                320: {
                                                    slidesPerView: 1.2,
                                                    spaceBetween: 8
                                                },
                                                576: {
                                                    slidesPerView: 1.5,
                                                    spaceBetween: 8
                                                },
                                                768: {
                                                    slidesPerView: 2.5,
                                                    spaceBetween: 8
                                                },
                                                992: {
                                                    slidesPerView: 2.5,
                                                    spaceBetween: 32
                                                },
                                                1200: {
                                                    slidesPerView: 3,
                                                    spaceBetween: 32
                                                },
                                                1400: {
                                                    slidesPerView: 3,
                                                    spaceBetween: 32
                                                }
                                            }
                                        }
                                    );

                                    var curHeight = 0;

                                    for(var k = 0; k < viewIminSpec_<?=$ID?>.slides.length; k++)
                                    {
                                        if(viewIminSpec_<?=$ID?>.slides[k].clientHeight > curHeight)
                                        {
                                            curHeight = viewIminSpec_<?=$ID?>.slides[k].clientHeight
                                        }
                                    }
                                    for(var k = 0; k < viewIminSpec_<?=$ID?>.slides.length; k++)
                                    {
                                        viewIminSpec_<?=$ID?>.slides[k].style.height = curHeight + 'px';
                                    }
                                }

                                try {
                                    if(Swiper)
                                    {
                                        window.addEventListener('DOMContentLoaded', initImgCard.bind(false, '.lang-img--b<?=$ID?>'));
                                    }
                                }
                                catch(e) {
                                    window.addEventListener('onLangSwiperScriptLoad', initImgCard.bind(false, '.lang-img--b<?=$ID?>'));
                                }
                            </script>
                        <?endif;?>

                    <?endif;?>
                <?endif;?>
            </div>
        </div>
    </div>
</section>