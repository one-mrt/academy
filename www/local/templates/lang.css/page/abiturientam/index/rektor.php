<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($section->getUfQuo())):
    $rektor = Util::getElementObject($textSiteTableString, ['NAME', "PREVIEW_TEXT", 'PREVIEW_PICTURE', 'text', 'link'], ['ID' => $section->getUfQuo()]);

    if(!empty($rektor)):?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>" <?=Util::iblockElementEditAddId($rektor)?>>
                <div class="<?=comp::get("row")?> rg-32 ai-c h-100 pn-r z-1">

                    <div class="w-100 md:w-8/12 xl:w-8/12 pr-16 pl-16 d-f fd-c rg-24 ai-s js-c fg-0 fs-0 o-2 lg:o-1 ta-c lg:ta-l">

                        <h2 class="<?=comp::get('titleh2', '', 'ta-c')?> d-n lg:d-b"><?=$rektor->getName()?></h2>

                        <div class="w-100 pn-r d-f fd-c">
                            <div class="pn-a i-0 d-f c-ECECEC">
                                <svg width="120" height="86" viewBox="0 0 448 320" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M0 120C0 53.7 53.7 0 120 0H128C145.7 0 160 14.3 160 32C160 49.7 145.7 64 128 64H120C89.1 64 64 89.1 64 120V128H128C163.3 128 192 156.7 192 192V256C192 291.3 163.3 320 128 320H64C28.7 320 0 291.3 0 256V120ZM256 120C256 53.7 309.7 0 376 0H384C401.7 0 416 14.3 416 32C416 49.7 401.7 64 384 64H376C345.1 64 320 89.1 320 120V128H384C419.3 128 448 156.7 448 192V256C448 291.3 419.3 320 384 320H320C284.7 320 256 291.3 256 256V120Z"/></svg>
                            </div>

                            <?if(!empty($rektor->getPreviewText())):?>
                                <div class="w-100 xl:w-8/12 pn-r z-1 d-f fd-c rg-16 p-32 xl:pt-32 xl:pr-32 xl:pb-32 xl:pl-64">
                                    <blockquote class="m-0">
                                        <p class="mb-0 f-14 lg:f-18 xl:f-22 ff-ot"><?=$rektor->getPreviewText()?></p>
                                    </blockquote>
                                    <?
                                    $text = $rektor->getText();
                                    if(!empty($text)):?>
                                        <?foreach($text->getAll() as $value):?>
                                            <p class="mb-0 f-12 lg:f-16"><?=$value->getValue()?></p>
                                            <?break;?>
                                        <?endforeach;?>
                                    <?endif;?>
                                </div>
                            <?endif;?>

                            <?if(!empty($rektor->getLink())):?>
                                <div class="w-100 pn-r z-1 d-f jc-c lg:jc-s">
                                    <button class="<?=comp::get("btnPy")?>" aria-label="Открыть форму - <?=$rektor->getLink()->getDescription()?>"
                                        data-fancybox="" data-src="<?=SITE_TEMPLATE_PATH?>/ajax/order/" data-type="ajax"><?=$rektor->getLink()->getDescription()?></button>
                                </div>
                            <?endif;?>
                        </div>
                    </div>

                    <div class="w-100 md:w-4/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0 o-1 lg:o-2 d-f fd-c rg-32">
                        <div class="<?=comp::get('titleh2')?> d-b lg:d-n"><?=$rektor->getName()?></div>
                        <?if(!empty($rektor->getPreviewPicture())):?>
                            <div class="w-100 ar-90x100 md:ar-70x100 lg:ar-80x100 xl:ar-90x100 r-20 bc-w bgs-cr bgr-nr bp-bc bi-load lazyload" data-bg="<?=\CFile::GetPath($rektor->getPreviewPicture())?>"></div>
                        <?endif;?>
                    </div>
                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>