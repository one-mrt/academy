<?
use Lang\Main\Iblock\Util;
use Bitrix\Main\Type\DateTime;
use Lang\Css\Component as comp;

if(!empty($element->getBan())):

    $bannerList = Util::getElementsObject($textSiteTableString, [
        'order' => ["SORT" => 'ASC', 'ID' => 'DESC', 'ACTIVE_FROM' => 'ASC'],
        'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_PICTURE', 'text'],
        'filter' => [
            'IBLOCK_SECTION_ID' => $element->getBan()->getValue(),
            'LOGIC' => 'AND',
            ['LOGIC' => 'OR', '>=ACTIVE_TO' => new DateTime(), 'ACTIVE_TO' => null],
            ['LOGIC' => 'OR', '<=ACTIVE_FROM' => new DateTime(), 'ACTIVE_FROM' => null],
        ]
    ]);

    if(!empty($bannerList->getIdList())):
        $ID = rand();
        ?>

        <h1 class="d-n"><?=$APPLICATION->ShowTitle(false)?></h1>

        <section class="w-100 pn-r">
            <div class="<?=comp::get("container")?>">
                <div class="swiper lang-main--b<?=$ID?> of-h r-48">
                    <div class="swiper-wrapper">
                        <?foreach($bannerList as $k => $item):

                            if(!empty($item->getDetailPicture()))
                            {
                                $det = CFile::GetPath($item->getDetailPicture());
                                $modPicture = "bgs-cr bgr-nr bp-c bi-load lazyload";
                            }
                            else
                            {
                                $det = "";
                                $modPicture = "";
                            }

                            if(!empty($item->getPreviewPicture()))
                            {
                                $prev = CFile::GetPath($item->getPreviewPicture());
                            }
                            else
                            {
                                $prev = "";
                            }

                            ?>
                            <div class="swiper-slide">

                                <div class="w-100 pn-r ar-4x6 sm:ar-100x125 md:ar-10x8 lg:ar-10x6 xl:ar-100x41 of-h p-32 xl:p-64 bc-w bi-b <?=$modPicture?>" <?=(!empty($det)) ? "data-bg=\"{$det}\"" : ""?> <?=Util::iblockElementEditAddId($item)?>>

                                    <div class="<?=comp::get("row")?> pt-64 xl:pt-0 xl:ai-c h-100 pn-r z-2">
                                        <div class="w-100 lg:w-8/12 xl:w-7/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-16">

                                            <div class="f-24 md:f-48 xl:f-60 ftw-5 lh-1 ff-ot"><?=$item->getName()?></div>

                                            <?if(!empty($item->getPreviewText())):?>
                                                <div class=""><?=$item->getPreviewText()?></div>
                                            <?endif;?>

                                            <?
                                            $link = $item->getText()->getAll();
                                            $index = 0;

                                            if(!empty($link)):?>
                                                <div class="w-100 d-f fw-w xl:fw-n g-16 xl:g-24 pt-24">
                                                    <?foreach($link as $btn):?>
                                                        <?if($index == 0):?>
                                                            <a class="<?=comp::get("btnPy")?> ws-nw" href="<?=$btn->getValue()?>" rel="nofollow" target="_blank"><?=$btn->getDescription()?></a>
                                                        <?else:?>
                                                            <a class="<?=comp::get("btnPy", "bc-w c-py h:bc-py h:c-w", "bc-py c-w h:bc-w h:c-dt")?> ws-nw" href="<?=$btn->getValue()?>" rel="nofollow" target="_blank"><?=$btn->getDescription()?></a>
                                                        <?endif;?>
                                                        <?$index++;?>
                                                    <?endforeach;?>
                                                </div>
                                            <?endif;?>
                                        </div>
                                    </div>

                                    <?if(!empty($prev)):?>
                                        <div class="w-100 h-100 pn-a i-0">
                                            <div class="<?=comp::get("row")?> h-100 jc-e">
                                                <div class="w-100 md:w-8/12 lg:w-7/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0 d-f ai-e">
                                                    <div class="w-100 h-100 bgs-cn bgr-nr bp-bc bi-load lazyload" data-bg="<?=$prev?>"></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?endif;?>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
            </div>

            <section class="w-100 pn-a t-0 l-0 z-1">
                <div class="<?=comp::get("container")?>">
                    <div class="w-100 pt-32 pl-32 xl:pt-64 xl:pl-64">
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
                    </div>
                </div>
            </section>

            <script>
                function initBanCard(id)
                {
                    let viewIminSpec_<?=$ID?> = new Swiper(
                        id,
                        {
                            lazy: false,
                            loop: true,
                            slidesPerView: 1,
                            spaceBetween: 0,
                            navigation: {
                                nextEl: '.gallery-select--next--<?=$ID?>',
                                prevEl: '.gallery-select--prev--<?=$ID?>',
                                disabledClass : 'd-none',
                                hiddenClass : 'gallery--disabled',
                                lockClass : 'gallery--disabled',
                            },
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
                        window.addEventListener('DOMContentLoaded', initBanCard.bind(false, '.lang-main--b<?=$ID?>'));
                    }
                }
                catch(e) {
                    window.addEventListener('onLangSwiperScriptLoad', initBanCard.bind(false, '.lang-main--b<?=$ID?>'));
                }
            </script>
        </section>

    <?endif;?>

<?endif;?>