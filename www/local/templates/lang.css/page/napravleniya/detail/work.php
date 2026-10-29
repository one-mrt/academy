<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<?if(!empty($element->getWork())):?>
    <section class="w-100">
        <?
        $secObTitle = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $element->getWork()->getValue()]]);

        if(!empty($secObTitle)):
            $listOb = Util::getElementsObject($textSiteTableString, [
                'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT'],
                'filter' => ['IBLOCK_SECTION_ID' => $secObTitle->getId()]
            ]);
            ?>
            <div class="w-100">
                <div class="<?=comp::get("container")?> d-f fd-c rg-32">

                    <div class="w-100 d-f fd-c rg-32 r-24 xl:r-48 p-32 xl:p-64 bc-dt">

                        <div class="w-100">
                            <h2 class="lg:w-100 mb-0 f-24 md:f-32 xl:f-42 ta-c ftw-5 lh-1 c-w" <?=Util::iblockSectionEditAddId($secObTitle)?>><?=$secObTitle->getName()?></h2>
                        </div>

                        <div class="<?=comp::get("row")?> rg-32">
                            <?foreach($listOb as $iOb):
                                $modBgPrev = "";

                                if(!empty($iOb->getPreviewPicture()))
                                {
                                    $iObPrev = \CFile::GetPath($iOb->getPreviewPicture());
                                    $modBgPrev = 'bgs-cr bgr-nr bp-c bi-load lazyload';
                                }
                                elseif(!empty($bgProg))
                                {
                                    $modBgPrev = 'bgs-cr bgr-nr bp-c bi-load lazyload';
                                    $iObPrev = $bgProg;
                                }
                                
                                ?>
                                <div class="w-100 md:w-6/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                    <div class="w-100 h-100 p-32 d-f fd-c rg-24 r-24 of-h bc-w" <?=Util::iblockElementEditAddId($iOb)?>>

                                        <div class="w-100">
                                            <div class="w-100 ar-1x1 bc-e5ebfa99 r-100% w-100px h-100px bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=$iObPrev?>"></div>
                                        </div>

                                        <div class="w-100 d-f fd-c rg-16">
                                            <div class="f-18 xl:f-24 ff-ot ftw-5"><?=$iOb->getName()?></div>

                                            <?if(!empty($iOb->getPreviewText())):?>
                                                <div class="w-100 f-14 lg:f-16"><?=$iOb->getPreviewText()?></div>
                                            <?endif;?>
                                        </div>
                                    </div>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>

                </div>
            </div>
        <?endif;?>

    </section>
<?endif;?>