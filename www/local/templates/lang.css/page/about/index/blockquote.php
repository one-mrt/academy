<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($section->getUfQuo())):
    $rektor = Util::getElementObject($textSiteTableString, ['NAME', "PREVIEW_TEXT", 'PREVIEW_PICTURE', 'text', 'link'], ['ID' => $section->getUfQuo()]);

    if(!empty($rektor)):
        $prev = "";

        if(!empty($rektor->getPreviewPicture()))
        {
            $prev = 'data-bg="' . CFile::GetPath($rektor->getPreviewPicture()) . '"';
            $rectorModPrev = "bgs-cr bgr-nr bp-bc bi-load lazyload";
        }
        ?>
        <section class="w-100 xl:spt-100_hm" id="LangRuk">
            <div class="<?=comp::get("container")?>" <?=Util::iblockElementEditAddId($rektor)?>>
                <div class="<?=comp::get("row")?> ai-c rg-32 h-100 pn-r z-1">

                    <div class="w-100 xl:w-7/12 pr-16 pl-16 d-f fd-c rg-24 ai-s js-c fg-0 fs-0 o-2 lg:o-1">
                        <div class="w-100 pn-r d-f fd-c">
                            <div class="pn-a i-0 d-f c-ECECEC">
                                <svg width="120" height="86" viewBox="0 0 448 320" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M0 120C0 53.7 53.7 0 120 0H128C145.7 0 160 14.3 160 32C160 49.7 145.7 64 128 64H120C89.1 64 64 89.1 64 120V128H128C163.3 128 192 156.7 192 192V256C192 291.3 163.3 320 128 320H64C28.7 320 0 291.3 0 256V120ZM256 120C256 53.7 309.7 0 376 0H384C401.7 0 416 14.3 416 32C416 49.7 401.7 64 384 64H376C345.1 64 320 89.1 320 120V128H384C419.3 128 448 156.7 448 192V256C448 291.3 419.3 320 384 320H320C284.7 320 256 291.3 256 256V120Z"/></svg>
                            </div>
                            <div class="w-100 pn-r z-1 d-f fd-c rg-16 xl:p-32">
                                <?if(!empty($rektor->getPreviewText())):?>
                                    <blockquote class="m-0">
                                        <p class="mb-0 xl:f-24 ff-ot ftw-5"><?=$rektor->getPreviewText()?></p>
                                    </blockquote>
                                <?endif;?>
                                <p class="f-14 lg:f-16 mb-0"><?=$rektor->getName()?></p>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 xl:w-5/12 pr-16 pl-16 fg-0 fs-0 o-1 lg:o-2">
                        <div class="w-100 ar-100x67 r-20 bc-w <?=$rectorModPrev?>" <?=$prev?>></div>
                    </div>
                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>