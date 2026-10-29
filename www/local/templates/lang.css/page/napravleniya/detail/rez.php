<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($element->getRez()) || !empty($element->getForm())):?>
    <section class="w-100 d-f fd-c rg-32">

        <?if(!empty($element->getRez())):
            $rez = Util::getElementObject($textSiteTableString, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DETAIL_TEXT', 'el', 'text'], ['ID' => $element->getRez()->getValue()]);

            if(!empty($rez)):?>
                <section class="w-100">
                    <div class="<?=comp::get("container")?>">
                        <div class="<?=comp::get("row")?> rg-32">

                            <div class="w-100 lg:w-4/12 pr-16 pl-16 fg-0 fs-0 d-n lg:d-f">
                                <div class="w-100 h-100 ar-1x1 xl:ar-100x160 r-24 bc-e5ebfa99 bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=\CFile::GetPath($rez->getPreviewPicture())?>"></div>
                            </div>

                            <div class="w-100 lg:w-8/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-32">

                                <div class="w-100 d-f fd-c rg-16">
                                    <h2 class="xl:w-8/12 mb-0 f-24 md:f-32 xl:f-42 ftw-5 lh-1 ta-c lg:ta-l"><?=$rez->getName()?></h2>

                                    <?if(!empty($rez->getPreviewText())):?>
                                        <div class="xl:w-6/12 f-14 xl:f-16 ta-c lg:ta-l"><?=$rez->getPreviewText()?></div>
                                    <?endif;?>

                                    <?
                                    $mobilePicture = !empty($rez->getDetailPicture()) ? \CFile::GetPath($rez->getDetailPicture()) : \CFile::GetPath($rez->getPreviewPicture());
                                    ?>

                                    <div class="w-100 ar-16x9 lg:ar-100x160 d-f lg:d-n r-24 bc-e5ebfa99 bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=$mobilePicture?>"></div>
                                </div>

                                <?if(!empty($rez->getText())):?>
                                    <div class="<?=comp::get('row')?> rg-32">
                                        <?foreach($rez->getText()->getAll() as $tValue):?>
                                            <div class="w-100 md:w-6/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                                <div class="w-100 d-f fd-c rg-8 bc-w p-24 bw-1 bs-s bcr-C6C6C6 r-12">
                                                    <div class="f-18 ftw-5 ff-ot"><?=$tValue->getValue()?></div>
                                                    <div class="f-14 xl:f-16"><?=$tValue->getDescription()?></div>
                                                </div>
                                            </div>
                                        <?endforeach;?>
                                    </div>
                                <?endif;?>


                                <?if(!empty($rez->getEl()->getAll())):?>
                                    <div class="w-100 r-24 p-32 xl:p-32 bc-py">
                                        <div class="<?=comp::get('row')?> rg-32">
                                            <div class="w-100 xl:w-5/12 pr-16 pl-16 fg-0 fs-0 c-w f-24 ff-ot ftw-5 ta-c xl:ta-l"><?=$rez->getDetailText()?></div>
                                            <?foreach($rez->getEl()->getAll() as $rElValue):
                                                $elRez = Util::getElementObject($textSiteTableString, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'text'], ['ID' => $rElValue->getValue()]);
                                                if(!empty($elRez)):?>
                                                    <div class="w-100 xl:w-7/12 pr-16 pl-16 fg-0 fs-0">
                                                        <div class="w-100 d-f fd-c rg-16 bc-w r-24 p-32">
                                                            <div class="w-100 d-f fw-w md:fw-n g-32 ai-c lh-1">
                                                                <div class="w-86px h-86px ar-1x1 r-100% bc-e5ebfa99 bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=\CFile::GetPath($elRez->getPreviewPicture())?>"></div>
                                                                <div class="w-100 md:w-a f-24 ff-ot ftw-5"><?=$elRez->getName()?></div>
                                                            </div>
                                                            <div class="w-100 d-f fd-c rg-16 f-14 xl:f-16">
                                                                <div class="ftw-5"><?=$elRez->getPreviewText()?></div>
                                                                <ul class="mb-0 pl-32">
                                                                    <?foreach($elRez->getText()->getAll() as $tValue):?>
                                                                        <li class="xl:f-14"><?=$tValue->getValue()?></li>
                                                                    <?endforeach;?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?endif;?>
                                                <?break;?>
                                            <?endforeach;?>
                                        </div>
                                    </div>
                                <?endif;?>

                            </div>
                        </div>
                    </div>
                </section>
            <?endif;?>
        <?endif;?>

        <?if(!empty($element->getForm())):
            $button = Util::getElementObject($textSiteTableString, ['NAME', 'PREVIEW_TEXT', 'link'], ['ID' => $element->getForm()->getValue()]);
            if(!empty($button)):?>
                <section class="w-100">
                    <div class="<?=comp::get('container')?>" <?=Util::iblockElementEditAdd($button)?>>
                        <div class="w-100 d-f jc-c ai-c fw-w lg:fw-n g-32 r-20 bc-dt p-32">
                            <div class="w-100 lg:w-6/12 xl:w-4/12 d-f fd-c rg-16 lh-1 c-w ta-c lg:ta-l">
                                <div class="f-24 ftw-5"><?=$button->getName()?></div>
                                <?if(!empty($button->getPreviewText())):?>
                                    <div class=""><?=$button->getPreviewText()?></div>
                                <?endif;?>
                            </div>
                            <div class="">
                                <button class="<?=comp::get("btnPy")?>" data-fancybox="" data-type="ajax"
                                    data-src="<?=SITE_TEMPLATE_PATH?>/ajax/order/"><?=$button->getLink()->getDescription()?></button>
                            </div>
                        </div>
                    </div>
                </section>
            <?endif;?>
        <?endif;?>

    </section>
<?endif;?>