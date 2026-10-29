<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<?if(!empty($element->getProg())):?>
    <section class="w-100">
        <?
        $secObTitle = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $element->getProg()->getValue()]]);

        if(!empty($secObTitle)):
            $listOb = Util::getElementsObject($textSiteTableString, [
                'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT'],
                'filter' => ['IBLOCK_SECTION_ID' => $secObTitle->getId()]
            ]);

            $bgProg = Util::getElement($textTableString, ['PREVIEW_PICTURE'], ['ID' => 204041]);
            if(!empty($bgProg))
            {
                $bgProg = \CFile::GetPath($bgProg['PREVIEW_PICTURE']);
            }
            ?>
            <div class="w-100">
                <div class="<?=comp::get("container")?> d-f fd-c rg-32">

                    <div class="w-100">
                        <h2 class="lg:w-100 mb-0 f-24 md:f-32 xl:f-42 ta-c ftw-5 lh-1" <?=Util::iblockSectionEditAddId($secObTitle)?>><?=$secObTitle->getName()?></h2>
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
                            <div class="w-100 md:w-6/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                <div class="w-100 h-100 p-32 d-f fd-c rg-24 xl:rg-32 r-24 of-h bc-w bw-1 bs-s bcr-C6C6C6 <?=$modBgPrev?>" <?=(!empty($iObPrev)) ? "data-bg=\"{$iObPrev}\"" : ""?> <?=Util::iblockElementEditAddId($iOb)?>>

                                    <div class="w-100 c-py">
                                        <div class="d-f"><svg width="27" height="32" viewBox="0 0 27 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M20.8116 20.566L13.2985 24.5614L6.02869 20.566V16.5241L13.2985 20.5195L20.8116 16.5241V20.566Z"></path><path d="M12.9201 6.7422L4.49237 13.6277L13.408 18.3496L22.5084 12.9953L13.2981 12.1512L12.9201 6.7422Z"></path><path d="M13.161 32L0 24.8575V1.19074e-06H26.3458V24.8578L13.161 32ZM1.92078 23.8924L13.1622 29.6074L24.425 23.8918V1.92053H1.92078V23.8924Z"></path><path d="M10.399 1.36079L5.65465 4.97852L0.919128 1.36079H10.399Z"></path><path d="M5.65439 5.36975L0 1.04966H11.3199L5.65439 5.36975ZM1.83862 1.67187L5.65469 4.58716L9.47839 1.67187H1.83862Z"></path><path d="M18.0056 1.36079L13.2619 4.97852L8.52609 1.36079H18.0056Z"></path><path d="M13.2613 5.36975L7.60663 1.04966H18.9262L13.2613 5.36975ZM9.44527 1.67187L13.2619 4.58716L17.0847 1.67187H9.44527Z"></path><path d="M25.094 1.36079L20.3499 4.97852L15.6141 1.36079H25.094Z"></path><path d="M20.3497 5.36975L14.6948 1.04966H26.0146L20.3497 5.36975ZM16.5339 1.67187L20.35 4.58716L24.1737 1.67187H16.5339Z"></path></svg></div>
                                    </div>

                                    <div class="w-100 f-18 ff-ot ftw-5">
                                        <div class=""><?=$iOb->getName()?></div>
                                        <?if(!empty($iOb->getPreviewText())):?>
                                            <div class=""><?=$iOb->getPreviewText()?></div>
                                        <?endif;?>
                                    </div>

                                    <?if(!empty($iOb->getDetailText())):?>
                                        <div class="w-100 f-14 xl:f-16"><?=$iOb->getDetailText()?></div>
                                    <?endif;?>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
            </div>
        <?endif;?>

    </section>
<?endif;?>