<?
use Lang\Region;
use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$ID = rand();


if(!empty($section->getUfBan())):
    $bannerList = Util::getElementsObject($textSiteTableString, [
        'select' => ['NAME', "PREVIEW_TEXT", 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'text'],
        'filter' => ['IBLOCK_SECTION_ID' => $section->getUfBan()]
    ]);
    ?>

    <h1 class="d-n"><?=$APPLICATION->ShowTitle(false)?></h1>

    <?if(!empty($bannerList->getIdList())):?>
        <section class="w-100 pn-r">
            <div class="<?=comp::get("container")?>">
                <div class="swiper lang-main--b<?=$ID?> of-h r-24 xl:r-48">
                    <div class="swiper-wrapper">
                        <?foreach($bannerList as $banner):
                            $det = "";
                            $prev = "";
                            $modPicture = "";

                            if(!empty($banner->getDetailPicture()))
                            {
                                $det = 'data-bg="' . CFile::GetPath($banner->getDetailPicture()) . '"';
                                $modPicture = "bgs-cr bgr-nr bp-c bi-load lazyload";
                            }

                            if(!empty($banner->getPreviewPicture()))
                            {
                                $prev = CFile::GetPath($banner->getPreviewPicture());
                            }
                            ?>
                            <div class="swiper-slide">
                                <div class="w-100 d-f fd-c pn-r ar-3x4 xl:ar-100x40 p-32 pt-32 xl:pt-64 xl:p-64 bc-EDF0F9 bi-b <?=$modPicture?>" <?=$det?> <?=Util::iblockElementEditAddId($banner)?>>
                                    <div class="d-f fd-c rg-32 jc-c pn-r z-1 fg-1">

                                        <div class="w-100 xl:w-60 fg-1 d-f fd-c jc-c rg-16 c-w ta-c lg:ta-l">

                                            <div class="f-24 xl:f-40 ftw-5 lh-1 ff-ot"><?=$banner->getName()?></div>

                                            <?if(!empty($banner->getPreviewText())):?>
                                                <div class=""><?=$banner->getPreviewText()?></div>
                                            <?endif;?>
                                        </div>

                                        <div class="w-100 fg-0">
                                            <?
                                            $link = $banner->getText();
                                            $index = 0;

                                            if(!empty($link)):?>
                                                <div class="w-100 d-f fw-w xl:fw-n ai-e jc-b rg-16 cg-32 pt-16 pr-24 pb-16 pl-24 bc-ffffff4d bf-b5 r-12 c-w f-14 lg:f-16">
                                                    <div class="w-100 md:w-a f-16 xl:f-20 ftw-5 mb-0 ta-c md:ta-l ws-nw"><?=$APPLICATION->ShowTitle(false)?></div>
                                                    <?foreach($link->getAll() as $btn):?>
                                                        <?if($index == 0):?>
                                                            <a class="w-100 md:w-a ta-c md:ta-l ws-nw" href="<?=$btn->getValue()?>"><?=$btn->getDescription()?></a>
                                                        <?else:?>
                                                            <a class="w-100 md:w-a ta-c md:ta-l ws-nw" href="<?=$btn->getValue()?>"><?=$btn->getDescription()?></a>
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
                                                <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0 d-f ai-e">
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

            <div class="w-100 pn-a t-0 l-0 z-1">
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

<?endif;?>