<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($section) && !empty($section->getUfCre())):
    $creditEl = Util::getElementObject($textSiteTableString, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'link'], ['ID' => $section->getUfCre()]);
    if(!empty($creditEl)):?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>" <?=Util::iblockElementEditAddId($creditEl)?>>
                <div class="<?=comp::get("row")?> rg-32">

                    <div class="w-100 xl:w-7/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-32">
                        <div class="w-100 d-f fd-c rg-16">
                            <h2 class="mb-0 f-24 md:f-32 xl:f-42 ftw-5 lh-1"><?=$creditEl->getName()?></h2>

                            <?if(!empty($creditEl->getPreviewText())):?>
                                <div class="f-14 lg:f-16"><?=$creditEl->getPreviewText()?></div>
                            <?endif;?>
                        </div>

                        <?if(!empty($creditEl->getLink())):?>
                            <div class="w-100 d-f jc-s">
                                <a href="<?=$creditEl->getLink()->getValue()?>" class="<?=comp::get('btnDt')?>"><?=$creditEl->getLink()->getDescription()?></a>
                            </div>
                        <?endif;?>
                    </div>

                    <?if(!empty($creditEl->getPreviewPicture())):?>
                        <div class="w-100 xl:w-5/12 pr-16 pl-16 fg-0 fs-0 d-n xl:d-f">
                            <div class="w-100 ar-10x6 r-24 bc-w bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=\CFile::GetPath($creditEl->getPreviewPicture())?>"></div>
                        </div>
                    <?endif;?>
                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>