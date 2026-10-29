<?
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langReview = "LangReview{$lang}";

if(!empty($arParams['elementsId'])):
    $reviewList = Data::getElementsObject($langReview, [
        'select' => ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_TEXT'],
        'filter' => ['ID' => $arParams['elementsId']],
        'limit' => 10
    ]);
    if(!empty($reviewList) && !empty($reviewList->getIdList())):
        $ID = rand();
        ?>
        <section class="w-100">
            <style>.swiper-scrollbar-drag {background-color: #E1E1E1}</style>
            <div class="<?=comp::get("container")?>">
                <div class="w-100 pn-r d-f fd-c rg-32 of-h">
                    <div class="swiper lang-review-card--<?=$ID?> w-100">
                        <div class="swiper-wrapper">
                            <?foreach($reviewList as $review):?>
                                <div class="swiper-slide md:w-6/12 xl:w-4/12">
                                    <div class="w-100 h-100 d-f fd-c rg-32 of-h" <?=Data::iblockElementEditAddId($review)?>>
                                        <div class="w-100 d-f ai-c cg-24">
                                            <div class="bc-w r-100 w-60px h-60px mnw-60px mnh-60px of-h fg-0">
                                                <?if(!empty($review->getPreviewPicture())):?>
                                                    <?=Data::getImageId($review->getPreviewPicture(), [
                                                        "title" => strip_tags($review->getName()),
                                                        "alt" => strip_tags($review->getName()),
                                                        "loading" => "lazy",
                                                        "class" => "w-60px h-60px mnw-60px mnh-60px oft-cv op-c r-100",
                                                    ])?>
                                                <?endif?>
                                            </div>
                                            <div class="fg-1 d-f fd-c rg-4">
                                                <div class="f-18 ftw-6"><?=$review->getName()?></div>
                                                <?if(!empty($review->getPreviewText())):?>
                                                    <div class="f-12"><?=$review->getPreviewText()?></div>
                                                <?endif?>
                                            </div>
                                        </div>

                                        <?if(!empty($review->getDetailText())):?>
                                            <div class="w-100 f-12 xl:f-14"><?=$review->getDetailText()?></div>
                                        <?endif?>
                                    </div>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                    <div class="swiper-scrollbar lang-review--scroll<?=$ID?> w-100 h-12px pn-r l-a b-ao bc-t"></div>
                </div>
            </div>
            <script>
                function initLangReviewCard(id)
                {
                    let viewIminSpec_<?=$ID?> = new Swiper(
                        id,
                        {
                            lazy: false,
                            slidesPerView: 1,
                            spaceBetween: 32,
                            scrollbar: {
                                el: '.lang-review--scroll<?=$ID?>',
                                draggable: true,
                                dragSize: 500,
                            },
                            breakpoints: {
                                320: {
                                    slidesPerView: 1.2,
                                    spaceBetween: 8,
                                    scrollbar: {
                                        dragSize: 100,
                                    }
                                },
                                400: {
                                    slidesPerView: 1.2,
                                    spaceBetween: 8,
                                    scrollbar: {
                                        dragSize: 100,
                                    }
                                },
                                576: {
                                    slidesPerView: 1.5,
                                    spaceBetween: 16,
                                    scrollbar: {
                                        dragSize: 100,
                                    }
                                },
                                768: {
                                    slidesPerView: 1.8,
                                    spaceBetween: 32,
                                    scrollbar: {
                                        dragSize: 150,
                                    }
                                },
                                992: {
                                    slidesPerView: 2.5,
                                    spaceBetween: 32,
                                    scrollbar: {
                                        dragSize: 300,
                                    }
                                },
                                1200: {
                                    slidesPerView: 2.5,
                                    spaceBetween: 32,
                                    scrollbar: {
                                        dragSize: 400,
                                    }
                                },
                                1320: {
                                    slidesPerView: 2.5,
                                    spaceBetween: 32
                                },
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
                    if(Swiper) {
                        window.addEventListener('DOMContentLoaded', initLangReviewCard.bind(false, '.lang-review-card--<?=$ID?>'));
                    }
                } catch(e) {
                    window.addEventListener('onLangSwiperScriptLoad', initLangReviewCard.bind(false, '.lang-review-card--<?=$ID?>'));
                }
            </script>
        </section>
    <?endif?>
<?endif?>