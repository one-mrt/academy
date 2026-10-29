<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\Type\DateTime;

if(!empty($section) && !empty($section->getUfBan())):
    $bannerList = Util::getElementsObject($textSiteTableString, [
        'order' => ["SORT" => 'ASC', 'ID' => 'DESC', 'ACTIVE_FROM' => 'ASC'],
        'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_PICTURE', "DETAIL_TEXT", 'text', 'el', 'link'],
        'filter' => [
            "IBLOCK_SECTION_ID" => [$section->getUfBan()],
            'LOGIC' => 'AND',
            ['LOGIC' => 'OR', '>=ACTIVE_TO' => new DateTime(), 'ACTIVE_TO' => null],
            ['LOGIC' => 'OR', '<=ACTIVE_FROM' => new DateTime(), 'ACTIVE_FROM' => null],
        ]
    ]);
    ?>

    <h1 class="d-n"><?=$APPLICATION->ShowTitle(false)?></h1>

    <?if(!empty($bannerList->getIdList())):?>
        <section class="<?=comp::get("bannerWrapper")?> <?=!empty($section->getUfCol()) ? "c-w" : ""?>">
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
                            <?foreach($bannerList as $item):
                                $prev = "";
                                $det = "";
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
                                    <div class="<?=comp::get("bannerItem", 'md:ar-10x5 lg:ar-10x5 xl:ar-10x4 pb-32', 'md:ar-10x5 lg:ar-10x6 xl:ar-100x49 pb-0')?> <?=$modPicture?>" <?=$det?> <?=Util::iblockElementEditAddId($item)?>>

                                        <div class="<?=comp::get("row")?> <?=comp::get("")?> pt-32 lg:pt-64 xl:pt-0 h-100 pn-r z-2 rg-32">
                                            <?
                                            if(!empty($section->getUfBb()))
                                            {
                                                $modWidth = "md:w-5/12 lg:w-7/12";
                                            }
                                            else
                                            {
                                                $modWidth = "md:w-9/12 lg:w-8/12";
                                            }
                                            ?>
                                            <div class="<?=comp::get("col")?> <?=comp::get("")?> <?=$modWidth?> xl:w-8/12 d-f fd-c <?=(empty($item->getLink())) ? 'lg:jc-c' : 'jc-e'?> rg-24 ta-c md:ta-l">

                                                <div class="<?=comp::get("bannerContent")?>">
                                                    <?if(!empty($item->getEl()->getAll())):?>
                                                        <div class="<?=comp::get("")?> d-f jc-c md:jc-s">
                                                            <?foreach($item->getEl()->getAll() as $text):?>
                                                                <div class="<?=comp::get("")?> d-f ai-c lh-1 cg-8 pt-8 pr-12 pb-8 pl-12 bc-dt r-8 c-w">
                                                                    <?
                                                                    $text = Util::getElementObject($textSiteTableString, ['NAME', 'PREVIEW_PICTURE'], ['ID' => $text->getValue()]);
                                                                    if(!empty($text)):?>
                                                                        <div class="<?=comp::get("")?> w-18px h-18px lg:w-24px lg:h-24px bgs-cn bgr-nr bp-c lazyload" data-bg="<?=\CFile::GetPath($text->getPreviewPicture())?>"></div>
                                                                        <div class=" <?=comp::get("")?> f-12 lg:f-16"><?=$text->getName();?></div>
                                                                    <?endif;?>
                                                                </div>
                                                                <?break;?>
                                                            <?endforeach;?>
                                                        </div>
                                                    <?endif;?>

                                                    <div class="<?=comp::get("bannerTitle")?>"><?=$item->getName()?></div>

                                                    <?if(!empty($item->getPreviewText())):?>
                                                        <div class="<?=comp::get("bannerText")?>"><?=$item->getPreviewText()?></div>
                                                    <?endif;?>
                                                </div>

                                                <?if(empty($section->getUfBb())):
                                                    if(!empty($item->getText()->getAll())):
                                                        $index = 0;
                                                        ?>
                                                        <div class="<?=comp::get("")?> w-100 d-f fw-w xl:fw-n g-16 xl:g-24 lg:pt-24">
                                                            <?foreach($item->getText()->getAll() as $btn):?>
                                                                <?if($index == 0):?>
                                                                    <a class="<?=comp::get("btnPy", 'ws-nw w-100 md:w-a')?>" href="<?=$btn->getValue()?>" rel="nofollow" target="_blank"><?=$btn->getDescription()?></a>
                                                                <?else:?>
                                                                    <a class="<?=comp::get("btnPy", "bc-w c-py h:bc-py h:c-w ws-nw w-100 md:w-a", "bc-py c-w h:bc-w h:c-dt")?> ws-nw" href="<?=$btn->getValue()?>" rel="nofollow" target="_blank"><?=$btn->getDescription()?></a>
                                                                <?endif;?>
                                                                <?$index++;?>
                                                            <?endforeach;?>
                                                        </div>
                                                    <?endif;?>
                                                <?endif;?>
                                            </div>

                                            <?if(!empty($section->getUfBb())):?>
                                                <div class="<?=comp::get("")?> w-100 lg:h-100 md:w-7/12 lg:w-5/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-24 ai-e jc-e">
                                                    
                                                    <?if(!empty($item->getLink())):?>
                                                        <a href="<?=$item->getLink()->getValue()?>" target="_blank" rel="nofollow" class="<?=comp::get("")?> w-100 m-0 d-f ai-c cg-16 pt-12 pr-24 pb-12 pl-24 lg:pt-16 lg:pr-32 lg:pb-16 lg:pl-32 f-14 xl:f-16 ftw-5 lg:ftw-7 ta-c lh-1 bc-py c-w bw-1 bs-s bcr-py r-12 h:bc-w h:c-dt a:tm-s0.9 tn-all-3">
                                                            <div class="<?=comp::get("")?> fg-1 ta-l"><?=$item->getLink()->getDescription()?></div>
                                                            <div class="<?=comp::get("")?> d-f">
                                                                <svg width="24" height="24" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <rect class="fil-py" width="23" height="23" rx="5" fill="white"></rect>
                                                                    <path class="fil-w" d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z"></path>
                                                                </svg>
                                                            </div>
                                                        </a>
                                                    <?endif;?>

                                                    <?if(!empty($item->getText()->getAll())):?>
                                                        <?foreach($item->getText()->getAll() as $btn):?>
                                                            <a class="<?=comp::get("btnPy", "w-100 bc-w c-py h:bc-py h:c-w r-12 d-f ai-c cg-16 xl:f-16 ws-nw", "bc-py c-w h:bc-w h:c-dt r-999 lg:f-16")?>" href="<?=$btn->getValue()?>" rel="nofollow" target="_blank">
                                                                <div class="<?=comp::get("")?> fg-1 ta-l"><?=$btn->getDescription()?></div>
                                                                <div class="<?=comp::get("")?> d-f">
                                                                    <svg width="24" height="24" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <rect class="fil-w" width="23" height="23" rx="5"></rect>
                                                                        <path class="fil-py" d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z"></path>
                                                                    </svg>
                                                                </div>
                                                            </a>
                                                        <?endforeach;?>
                                                    <?endif;?>
                                                </div>
                                            <?endif;?>
                                        </div>

                                        <?if(!empty($prev)):?>
                                            <div class="<?=comp::get("bannerImgRigthBlock")?>">
                                                <div class="<?=comp::get("row")?> <?=comp::get("bannerImgRigthRow")?>">
                                                    <div class="<?=comp::get('col')?> <?=comp::get("bannerImgRigthCol")?>">
                                                        <div class="<?=comp::get("bannerImgRigth")?> lazyload" data-bg="<?=$prev?>"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?endif;?>

                                        <?if(!empty($det)):?>
                                            <div class="<?=comp::get("bannerImageWrapper")?>"></div>
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
                } catch(e) {
                    window.addEventListener('onLangSwiperScriptLoad', initBanCard.bind(false, '.lang-main--b<?=$ID?>'));
                }
            </script>
        </section>
    <?endif;?>
<?endif;?>