<?
use Lang\Main\Iblock\Util;
use Bitrix\Main\Type\DateTime;
use Lang\Css\Component as comp;

if(!empty($section) && !empty($section->getUfBan())):

    $bannerList = Util::getElementsObject($textSiteTableString, [
        'order' => ["SORT" => 'ASC', 'ID' => 'DESC', 'ACTIVE_FROM' => 'ASC'],
        'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_PICTURE', 'text'],
        'filter' => [
            'IBLOCK_SECTION_ID' => $section->getUfBan(),
            'LOGIC' => 'AND',
            ['LOGIC' => 'OR', '>=ACTIVE_TO' => new DateTime(), 'ACTIVE_TO' => null],
            ['LOGIC' => 'OR', '<=ACTIVE_FROM' => new DateTime(), 'ACTIVE_FROM' => null],
        ]
    ]);

    if(!empty($bannerList)):
        $ID = rand();
        ?>

        <h1 class="d-n"><?=$APPLICATION->ShowTitle(false)?></h1>

        <section class="<?=comp::get("bannerWrapper")?>">
            <style>
                .lang-main-page<?=$ID?>.swiper-pagination {
                    bottom: 2rem;

                    .swiper-pagination-bullet {
                        width: 1rem;
                        height: 1rem;
                        background-color: #FFF;
                        opacity: 1;

                        &.swiper-pagination-bullet-active {
                            background-color: #1e234c;
                        }
                    }
                }
            </style>
            <div class="<?=comp::get("container")?> <?=comp::get("bannerContainer")?>">
                <div class="<?=comp::get("bannerBlock")?>">
                    <div class="swiper lang-main--b<?=$ID?> <?=comp::get("bannerSwiper")?>">
                        <div class="swiper-wrapper <?=comp::get("bannerSwiperWrapper")?>">
                            <?foreach($bannerList as $k => $item):
                                $det = "";
                                $prev = "";
                                $modPicture = "";

                                if(!empty($item->getDetailPicture()))
                                {
                                    $det = 'data-bg="' . CFile::GetPath($item->getDetailPicture()) . '"';
                                    $modPicture = "bgs-cr bgr-nr bp-c bi-load lazyload";
                                }

                                if(!empty($item->getPreviewPicture()))
                                {
                                    $prev = CFile::GetPath($item->getPreviewPicture());
                                }
                                ?>
                                <div class="swiper-slide <?=comp::get("bannerSwiperSlide")?>">

                                    <div class="<?=comp::get("bannerItem")?> <?=$modPicture?>" <?=$det?> <?=Util::iblockElementEditAddId($item)?>>

                                        <div class="<?=comp::get("row")?> <?=comp::get("bannerRow")?>">
                                            <div class="<?=comp::get("col")?> <?=comp::get("bannerCol")?>">

                                                <div class="<?=comp::get("bannerContent")?>">
                                                    <div class="<?=comp::get("bannerTitle")?>"><?=$item->getName()?></div>

                                                    <?if(!empty($item->getPreviewText())):?>
                                                        <div class="<?=comp::get("bannerText")?> "><?=$item->getPreviewText()?></div>
                                                    <?endif;?>
                                                </div>

                                                <?if(!empty($item->getText())):
                                                    $index = 0;
                                                    ?>
                                                    <div class="<?=comp::get("bannerButtonContainer")?>">
                                                        <?foreach($item->getText()->getAll() as $btn):?>
                                                            <?if($index == 0):?>
                                                                <a class="<?=comp::get("btnPy", 'ws-nw w-100 md:w-a', '')?>" href="<?=$btn->getValue()?>" rel="nofollow" target="_blank"><?=$btn->getDescription()?></a>
                                                            <?else:?>
                                                                <a class="<?=comp::get("btnPy", "bc-w c-py h:bc-py h:c-w ws-nw w-100 md:w-a", "bc-py c-w h:bc-w h:c-dt")?>" href="<?=$btn->getValue()?>" rel="nofollow" target="_blank"><?=$btn->getDescription()?></a>
                                                            <?endif;?>
                                                            <?$index++;?>
                                                        <?endforeach;?>
                                                    </div>
                                                <?endif;?>
                                            </div>
                                        </div>

                                        <?if(!empty($prev)):?>
                                            <div class="<?=comp::get("bannerImgRigthBlock")?>">
                                                <div class="<?=comp::get("row")?> <?=comp::get("bannerImgRigthRow")?>">
                                                    <div class="<?=comp::get("col")?> <?=comp::get("bannerImgRigthCol")?>">
                                                        <div class="<?=comp::get("bannerImgRigth")?> lazyload" data-bg="<?=$prev?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?endif;?>
                                    </div>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>

                    <div class="lang-main-page<?=$ID?> swiper-pagination <?=comp::get("bannerSwiperPagination")?>"></div>
                </div>
            </div>

            <section class="<?=comp::get("bannerBreadcrumbBlock")?>">
                <div class="<?=comp::get("container")?> <?=comp::get("bannerBreadcrumbContainer")?>">
                    <div class="<?=comp::get("bannerBreadcrumbContent")?>">
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
                initBanCard = (id) =>
                    LG.addSwiper(id, {
                        lazy: false,
                        loop: true,
                        slidesPerView: 1,
                        spaceBetween: 0,
                        pagination: {
                            el: '.lang-main-page<?=$ID?>',
                        }
                    })

                try {
                    if(Swiper) {
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