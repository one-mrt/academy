<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<?if(!empty($element->getOb()) || !empty($element->getDoc())):?>
    <section class="w-100 d-f fd-c rg-32">

        <?if(!empty($element->getOb())):
            $secObTitle = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $element->getOb()->getValue()]]);

            if(!empty($secObTitle)):
                $listOb = Util::getElementsObject($textSiteTableString, [
                    'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT'],
                    'filter' => ['IBLOCK_SECTION_ID' => $secObTitle->getId()]
                ]);
                ?>
                <div class="w-100">
                    <div class="<?=comp::get("container")?> d-f fd-c rg-32">

                        <div class="w-100">
                            <h2 class="lg:w-100 mb-0 f-24 md:f-32 xl:f-42 ta-c ftw-5 lh-1" <?=Util::iblockSectionEditAddId($secObTitle)?>><?=$secObTitle->getName()?></h2>
                        </div>
                        
                        <div class="<?=comp::get("row")?> rg-32">
                            <?foreach($listOb as $iOb):
                                $iObPrev = \CFile::GetPath($iOb->getPreviewPicture());
                                ?>
                                <div class="w-100 md:w-6/12 lg:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                    <div class="w-100 ar-16x9 lg:ar-4x3 p-32 d-f fd-c jc-e rg-8 r-24 of-h c-w bc-e5ebfa99 lh-1 pn-r bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=$iObPrev?>" <?=Util::iblockElementEditAddId($iOb)?>>
                                        <div class="f-24 ff-ot ftw-5 pn-r z-1"><?=$iOb->getName()?></div>
                                        <?if(!empty($iOb->getPreviewText())):?>
                                            <div class="pn-r z-1 f-14 xl:f-16"><?=$iOb->getPreviewText()?></div>
                                        <?endif;?>
                                        <div class="w-100 h-100 pn-a i-0 bi-lg180b"></div>
                                    </div>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                </div>
            <?endif;?>

        <?endif;?>

        <?if(!empty($element->getDoc())):
            $obDoc = Util::getElementObject($textSiteTableString, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'el', 'link'], ['ID' => $element->getDoc()->getValue()]);
            if(!empty($obDoc)):?>
                <div class="w-100">
                    <div class="<?=comp::get("container")?>">
                        <div class="w-100 r-24 xl:r-48 p-32 xl:p-64 bc-py">
                            <div class="<?=comp::get("row")?> rg-32">
                                <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-32 c-w">

                                    <h2 class="mb-0 f-24 md:f-32 xl:f-42 ftw-5 lh-1"><?=$obDoc->getName()?></h2>

                                    <?if(!empty($obDoc->getPreviewText())):?>
                                        <div class="f-14 xl:f-16"><?=$obDoc->getPreviewText()?></div>
                                    <?endif;?>

                                </div>

                                <?if(!empty($obDoc->getEl()->getAll())):?>
                                    <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                        <?
                                        $docs = [];
                                        foreach($obDoc->getEl()->getAll() as $dEl)
                                        {
                                            $docs[] = $dEl->getValue();
                                        }
                                        if(!empty($docs)):
                                            $docs = Util::getElements($textSiteTableString, [
                                                'select' => ['NAME', 'PREVIEW_PICTURE', 'f' => 'file.FILE.ID'],
                                                'filter' => ['ID' => $docs]
                                            ]);
                                            ?>
                                            <div class="<?=comp::get("row")?> rg-32">
                                                <?foreach($docs as $dc):?>
                                                    <div class="w-100 md:w-6/12 lg:w-4/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                                        <a href="<?=\CFile::GetPath($dc['f'])?>" target="_blank" class="w-100 pn-r r-24 p-32 d-f fd-c ai-c rg-16 bc-w h:c-py h:bxs-3 h:tm--ty5 tn-all-3">
                                                            <div class="pn-a t-0 rt-0 pt-16 pr-16 d-f">
                                                                <svg width="16" height="16" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="7" cy="7" r="7" fill="currentColor"/><line x1="2.64258" y1="6.88281" x2="11.3596" y2="6.88281" stroke="white" stroke-width="0.5"/><line x1="6.88281" y1="11.3594" x2="6.88281" y2="2.64239" stroke="white" stroke-width="0.5"/></svg>
                                                            </div>
                                                            <div class="w-80 ar-100x115 bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=\CFile::GetPath($dc['PREVIEW_PICTURE'])?>"></div>
                                                            <div class="f-12 ta-c"><?=$dc['NAME']?></div>
                                                        </a>
                                                    </div>
                                                <?endforeach;?>
                                            </div>

                                        <?endif;?>
                                    </div>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            <?endif;?>
        <?endif;?>

    </section>
<?endif;?>
