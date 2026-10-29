<?
use Lang\Academy\Util as Academy;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$user = CurrentUser::get();
$lang = Data::getLang();
$langText = "LangText{$lang}";

$bannerList = Data::getElementsObject($langText, [
    'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_PICTURE', 'text'],
    'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]
]);

if(!empty($bannerList) && !empty($bannerList->getIdList())):
    $indexBanner = 0;
    ?>
    <section class="w-100">
        <div class="<?=comp::get('container-full')?> d-f fd-c rg-0">
            <div class="swiper w-100">
                <div class="swiper-wrapper">
                    <?foreach($bannerList as $banner):?>
                        <div class="swiper-slide">
                            <div class="w-100 md:ar-100x55 lg:ar-2x1 xxl:ar-100x43 r-24 bc-w of-h pn-r" <?=Data::iblockElementEditAddId($banner)?>>

                                <?if(!empty($banner->getDetailPicture())):?>
                                    <?if($indexBanner == 0):?>
                                        <?=Data::getImageId($banner->getDetailPicture(), [
                                            "alt" => strip_tags($banner->getName()),
                                            "title" => strip_tags($banner->getName()),
                                            "fetchpriority" => "high",
                                            "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
                                        ])?>
                                    <?else:?>
                                        <?=Data::getImageId($banner->getDetailPicture(), [
                                            "alt" => strip_tags($banner->getName()),
                                            "title" => strip_tags($banner->getName()),
                                            "loading" => "lazy",
                                            "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
                                        ])?>
                                    <?endif?>
                                <?endif?>

                                <div class="<?=comp::get('container-p')?> h-100 pn-r z-1">
                                    <div class="<?=comp::get("row")?> h-100">
                                        <div class="w-100 sm:w-10/12 md:w-7/12 lg:w-7/12 xl:w-7/12 xxl:w-6/12 pr-16 xl:pr-0 pl-16 fg-0 fs-0 pn-r z-1">
                                            <div class="w-100 h-100 d-f fd-c jc-c rg-24 pix-32 pby-48 md:pl-16 lg:pl-32 xxl:pl-0 xl:pby-86">
                                                <h2 class="<?=comp::get("h2")?>"><?=$banner->getName()?></h2>
                                                <?if(!empty($banner->getPreviewText())):?>
                                                    <div class="w-100 sm:w-8/12 md:w-100 f-12 lg:f-14 xxl:f-18"><?=$banner->getPreviewText()?></div>
                                                <?endif?>

                                                <?if(!empty($banner->getText()) && !empty($banner->getText()->getAll())):
                                                    $indBtn = 0;
                                                    ?>
                                                    <div class="w-100 d-f jc-s fw-w md:fw-n rg-16 sm:cg-24">
                                                        <?foreach ($banner->getText()->getAll() as $btn):
                                                            $modBtn = ($indBtn > 0) ? comp::get('btnPurpleBorderLg') : comp::get("btnPurpleLg");
                                                            
                                                            if(!empty($user->getId())):?>
                                                                <a href="<?=$btn->getValue()?>" class="<?=$modBtn?>"><?=$btn->getDescription()?></a>
                                                            <?else:?>
                                                                <button class="<?=($indBtn > 0) ? 'lang-form--reg' : 'lang-form--order-add'?> <?=$modBtn?>"><?=$btn->getDescription()?></button>
                                                            <?endif?>

                                                            <?$indBtn++;?>
                                                        <?endforeach?>
                                                    </div>
                                                <?endif?>
                                            </div>
                                        </div>

                                        <?if(!empty($banner->getPreviewPicture())):?>
                                            <div class="w-100 sm:w-2/12 md:w-5/12 lg:w-5/12 xl:w-5/12 xxl:w-6/12 <?=comp::get('setcol')?> d-f ai-e jc-e">
                                                <?if($indexBanner == 0):?>
                                                    <?=Data::getImageId($banner->getPreviewPicture(), [
                                                        "alt" => strip_tags($banner->getName()),
                                                        "title" => strip_tags($banner->getName()),
                                                        "fetchpriority" => "high",
                                                        "class" => "w-a h-300px pn-a fr-b4 b-0 sm:pn-s sm:fr-n md:w-a md:h-400px lg:h-500px xl:h-550px xxl:h-600px xxl:w-100 xxl:h-100 xl:ar-100x94 oft-cv op-c",
                                                    ])?>
                                                <?else:?>
                                                    <?=Data::getImageId($banner->getPreviewPicture(), [
                                                        "alt" => strip_tags($banner->getName()),
                                                        "title" => strip_tags($banner->getName()),
                                                        "loading" => "lazy",
                                                        "class" => "w-100 h-100 xl:ar-100x94 oft-cv op-c",
                                                    ])?>
                                                <?endif?>
                                            </div>
                                        <?endif?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?$indexBanner++?>
                    <?endforeach?>
                </div>
            </div>

            <?if($arParams['edge'] != 'n'):
                $edgeList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT', 'svg'],'filter' => ['IBLOCK_SECTION_ID' => 9]]);

                if(!empty($edgeList) && !empty($edgeList->getIdList())):?>
                    <div class="<?=comp::get('container-p', 'md:pix-16 lg:pix-32 xxl:pix-0')?> h-100">
                        <div class="w-100 bc-w r-24 bw-1 bs-s bcr-5DDCC4 p-16 lg:p-24 xl:p-24 xxl:p-32 md:mt--51 z-1 pn-r">
                            <div class="<?=comp::get("row")?> rg-24">
                                <?foreach($edgeList as $edge):?>
                                    <div class="w-100 md:w-4/12 <?=comp::get('setcol')?>">
                                        <div class="w-100 h-100 d-f ai-c cg-24" <?=Data::iblockElementEditAddId($edge)?>>
                                            <div class="d-f fd-c md:fd-r rg-16 c-5DDCC4">
                                                <?if(!empty($edge->getSvg())):?>
                                                    <div class="d-f"><?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . \CFile::GetPath($edge->getSvg()->getValue()))?></div>
                                                <?endif?>
                                                <div class="f-24 md:f-16 lg:f-18 xl:f-20 xxl:f-24 ff-ud ftw-6 ws-nw"><?=$edge->getName()?></div>
                                            </div>
                                            <div class="f-14 md:f-10 lg:f-12 xl:f-14"><?=$edge->getPreviewText()?></div>
                                        </div>
                                    </div>
                                <?endforeach?>
                            </div>
                        </div>
                    </div>
                <?endif?>
            <?endif?>

        </div>
    </section>
<?endif?>