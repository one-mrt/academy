<?
global $APPLICATION;

use Lang\Region;
use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\Type\DateTime;

$ID = rand();
$lang = Util::getLang();
$region = Region::getCurrent(['select' => ['prefix']]);
$site = ucfirst($region->getPrefix()->getValue());
$bannerTableString = "LangBanner{$site}";

$bannerList = Util::getElementsObject($bannerTableString, [
    'order' => ["SORT" => 'ASC', 'ID' => 'DESC', 'ACTIVE_FROM' => 'ASC'],
    'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_PICTURE', 'btn'],
    'filter' => [
        'LOGIC' => 'AND',
        ['LOGIC' => 'OR', '>=ACTIVE_TO' => new DateTime(), 'ACTIVE_TO' => null],
        ['LOGIC' => 'OR', '<=ACTIVE_FROM' => new DateTime(), 'ACTIVE_FROM' => null],
    ]
]);

if(!empty($bannerList)):?>
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
                                $prev = 'data-bg="' . CFile::GetPath($item->getPreviewPicture()) . '"';
                            }

                            ?>
                            <div class="swiper-slide <?=comp::get("bannerSwiperSlide")?>">

                                <div class="<?=comp::get("bannerItem")?> <?=$modPicture?>" <?=$det?> <?=Util::iblockElementEditAddId($item)?>>

                                    <div class="<?=comp::get("row")?> <?=comp::get("bannerRow")?>">
                                        <div class="<?=comp::get("col")?> <?=comp::get("bannerCol")?>">

                                            <div class="<?=comp::get("bannerContent")?>">

                                                <div class="<?=comp::get("bannerTitle")?>"><?=$item->getName()?></div>

                                                <?if(!empty($item->getPreviewText())):?>
                                                    <div class="<?=comp::get("bannerText")?>"><?=$item->getPreviewText()?></div>
                                                <?endif;?>
                                            </div>

                                            <?
                                            $link = $item->getBtn();
                                            $index = 0;

                                            if(!empty($link)):?>
                                                <div class="<?=comp::get("bannerButtonContainer")?>">
                                                    <?foreach($link->getAll() as $btn):?>
                                                        <?if($index == 0):?>
                                                            <a class="<?=comp::get("btnPy", 'ws-nw w-100 md:w-a')?>" href="<?=$btn->getValue()?>" rel="nofollow" target="_blank"><?=$btn->getDescription()?></a>
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
                                                    <div class="<?=comp::get("bannerImgRigth")?> lazyload" <?=$prev?>></div>
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