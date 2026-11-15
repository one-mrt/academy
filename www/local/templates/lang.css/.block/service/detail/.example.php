<?
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langExample = "LangExample{$lang}";

if(!empty($arParams['elementsId'])):
    $exampleTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'link'], ['ID' => 302]);
    $exampleList = Data::getElementsObject($langExample, [
        'select' => ['NAME', 'spec.ELEMENT', 'time.VALUE', 'origin.VALUE', 'tp.ITEM', 'price.VALUE', 'file.VALUE'],
        'filter' => ['ID' => $arParams['elementsId']],
        'limit' => 10
    ]);
    $priceTitle = Data::getElementObject($langText, ['NAME', 'svg'], ["ID" => 303]);
    $originTitle = Data::getElementObject($langText, ['NAME', 'svg'], ["ID" => 304]);
    $ID = rand();

    if(!empty($exampleList) && !empty($exampleList->getIdList())):?>
        <section class="w-100">
            <style>
                .swiper-pagination-bullet {
                    width: 1rem;
                    height: 1rem;
                    border: 2px solid #724A98;
                    background-color: #FFF0;
                }
                .swiper-pagination-bullet-active {
                    background: #724A98;
                }
            </style>
            <div class="<?=comp::get("container")?> d-f fd-c rg-32 xl:rg-48">

                <?if(!empty($exampleTitle) && !empty($exampleTitle->getId())):?>
                    <div class="w-100" <?=Data::iblockElementEditAddId($exampleTitle)?>>
                        <h2 class="<?=comp::get("h2")?>"><?=$exampleTitle->getName()?></h2>
                        <?if(!empty($exampleTitle->getPreviewText())):?>
                            <div class="f-14"><?=$exampleTitle->getPreviewText()?></div>
                        <?endif?>
                    </div>
                <?endif?>

                <div class="w-100 pn-r d-f fd-c rg-32">
                    <div class="swiper lang-example-card--<?=$ID?> w-100">
					    <div class="swiper-wrapper d-f">
                            <?foreach($exampleList as $example):
                                if(!empty($example->getFile()?->getValue())) {
                                    $file = \CFile::GetPath($example->getFile()?->getValue());
                                }
                                ?>
                                <div class="swiper-slide lg:w-6/12 xl:w-4/12">
                                    <a href="<?=$file?>" target="_blank" class="w-100 h-100 p-32 md:p-24 d-f fd-c rg-24 bc-w bw-1 bs-s bcr-C5C5C5 r-12 h:bcr-dt h:c-w tn-all-3 h:bc-dt" <?=Data::iblockElementEditAddId($example)?>>
                                        <?if(!empty($example->getTp()->getItem()) && !empty($example->getTp()->getItem()->getValue())):?>
                                            <div class="d-f jc-s">
                                                <div class="f-8 pt-4 pr-8 pb-4 pl-8 r-4 bi-sbb c-w lh-1 tt-l"><?=$example->getTp()->getItem()->getValue()?></div>
                                            </div>
                                        <?endif?>
                                        <div class="w-100 d-f fd-c rg-8 fg-1">
                                            <?if(!empty($example->getSpec()) && !empty($example->getSpec()->getElement())):?>
                                                <div class="f-12"><?=$example->getSpec()->getElement()->getName()?></div>
                                            <?endif?>
                                            <div class="ftw-6 md:f-14 xl:f-18"><?=$example->getName()?></div>
                                        </div>

                                        <div class="w-100 d-f fd-c rg-16">
                                            <?if(!empty($example->getTime()) && !empty($example->getTime()->getValue())):?>
                                                <div class="w-100 d-f jc-s">
                                                    <div class="d-f cg-12">
                                                        <div class="d-f"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($priceTitle->getSvg()->getValue()))?></div>
                                                        <div class="d-f fd-c rg-8">
                                                            <div class="f-12 c-7A7A7A tt-l ftw-5"><?=$priceTitle->getName()?></div>
                                                            <div class="ftw-6"><?=$example->getTime()->getValue()?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?endif?>

                                            
                                            <div class="w-100 d-f jc-s">
                                                <?if(!empty($example->getOrigin()) && !empty($example->getOrigin()->getValue())):?>
                                                    <div class="d-f cg-12 fg-1">
                                                        <div class="d-f"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($originTitle->getSvg()->getValue()))?></div>
                                                        <div class="d-f fd-c rg-8">
                                                            <div class="f-12 c-7A7A7A tt-l ftw-5"><?=$originTitle->getName()?></div>
                                                            <div class="ftw-6"><?=$example->getOrigin()->getValue()?></div>
                                                        </div>
                                                    </div>
                                                <?endif?>

                                                <?if(!empty($example->getPrice()) && !empty($example->getPrice()->getValue())):?>
                                                    <div class="d-f cg-12">
                                                        <div class="d-f fd-c rg-8">
                                                            <div class="f-12 c-7A7A7A tt-l ftw-5">Стоимость:</div>
                                                            <div class="ff-ud ftw-6 xl:f-18 bi-st bgc-t tf-t"><?=$example->getPrice()->getValue()?></div>
                                                        </div>
                                                    </div>
                                                <?endif?>
                                            </div>
                                            
                                        </div>

                                    </a>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                    <div class="w-100 d-f ai-c jc-c cg-32">
                        <button title="Предыдущий" class="example-prev--<?=$ID?> d-f bw-0 bc-t m-0 p-0 c-py h:c-dt tn-all-3">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M1.921 7.5L7.714 13.292L7 14L0 7L7 0L7.714 0.708L1.92 6.5H14V7.5H1.921Z"/></svg>
                        </button>
                        <div class="swiper-pagination w-a pn-r b-ao l-a d-f jc-c lang-example--page-<?=$ID?>"></div>
                        <button title="Следующий" class="example-next--<?=$ID?> d-f bw-0 bc-t m-0 p-0 c-py h:c-dt tn-all-3">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12.079 6.5L6.286 0.708L7 0L14 7L7 14L6.286 13.292L12.08 7.5L0 7.5L0 6.5L12.079 6.5Z"/></svg>
                        </button>
                    </div>
                </div>

                <?if(!empty($exampleTitle->getLink()) && $exampleTitle->getLink()->getValue()):?>
                    <div class="w-100 d-f ai-c jc-c">
                        <a href="<?=$exampleTitle->getLink()->getValue()?>" class="<?=comp::get("btnPurpleLg")?>"><?=$exampleTitle->getLink()->getDescription()?></a>
                    </div>
                <?endif?>

            </div>
            <script>
                function initLangReviewSlider(id) {
                    let viewIminSpec_<?=$ID?> = new Swiper(
                        id,
                        {
                            lazy: false,
                            slidesPerView: 1,
                            spaceBetween: 32,
                            pagination: {
                                el: ".lang-example--page-<?=$ID?>",
                                clickable: true,
                            },
                            navigation: {
                                nextEl: '.example-next--<?=$ID?>',
                                prevEl: '.example-prev--<?=$ID?>',
                                disabledClass : 'd-n md:d-n',
                                hiddenClass : 'd-n md:d-n',
                                lockClass : 'd-n md:d-n',
                            },
                            breakpoints: {
                                320: {
                                    slidesPerView: 1.2,
                                    spaceBetween: 8
                                },
                                400: {
                                    slidesPerView: 1.2,
                                    spaceBetween: 8
                                },
                                576: {
                                    slidesPerView: 1.5,
                                    spaceBetween: 16
                                },
                                768: {
                                    slidesPerView: 2.1,
                                    spaceBetween: 16
                                },
                                992: {
                                    slidesPerView: 2.5,
                                    spaceBetween: 32
                                },
                                1200: {
                                    slidesPerView: 3,
                                    spaceBetween: 32
                                },
                                1320: {
                                    slidesPerView: 3,
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
                        window.addEventListener('DOMContentLoaded', initLangReviewSlider.bind(false, '.lang-example-card--<?=$ID?>'));
                    }
                } catch(e) {
                    window.addEventListener('onLangSwiperScriptLoad', initLangReviewSlider.bind(false, '.lang-example-card--<?=$ID?>'));
                }
            </script>
        </section>
    <?endif?>
<?endif?>