<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($element)):
    if(!empty($element->getImg())):
        $imgEl = Util::getElementObject($textSiteTableString,
            ['NAME', 'img'],
            ['ID' => $element->getImg()->getValue()]
        );

        if(!empty($imgEl)):
            $ID = rand();
            $img = $imgEl->getImg()->getAll();

            if(!empty($img)):?>
                <section class="w-100 pt-16">
                    <div class="<?=comp::get("container")?>" <?=Util::iblockElementEditAddId($textEl)?>>
                        <div class="swiper lang-img--b<?=$ID?> w-100 of-h">
                            <div class="swiper-wrapper d-f">
                                <?foreach($img as $im):
                                    $linkFull = \CFile::GetPath($im->getValue());
                                    $view = \CFile::ResizeImageGet($im->getValue(), ['width' => 600, 'height' => 600], BX_RESIZE_IMAGE_PROPORTIONAL);
                                    ?>
                                    <button data-fancybox="langPhotoS" data-src="<?=$linkFull?>" title="<?=$im->getDescription() ?: $imgEl->getName()?>"
                                        class="swiper-slide xl:w-4/12 ar-16x9 m-0 p-0 bw-0 d-f r-20 bc-w bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=$view['src']?>"></button>
                                <?endforeach;?>
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
                    </div>
                </section>
            <?endif;?>
        <?endif;?>
    <?endif;?>
<?endif;?>