<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($section->getUfDoc())):
    $doc = Util::getElementObject($textSiteTableString, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'el', 'link'], ['ID' => $section->getUfDoc()]);
    if(!empty($doc)):?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="w-100 r-24 xl:r-48 p-32 xl:p-64 bc-py">
                    <div class="<?=comp::get("row")?> rg-32">

                        <?
                        $docs = $doc->getEl()->getAll();
                        $countDoc = 0;

                        if(!empty($docs))
                        {
                            $countDoc = count($doc->getEl()->getAll());
                        }
                        ?>

                        <div class="w-100 xl:w-<?=($countDoc > 1) ? '6' : '9'?>/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-32 c-w">

                            <h2 class="<?=comp::get("titleh2")?> lg:ta-l"><?=$doc->getName()?></h2>

                            <?if(!empty($doc->getPreviewText())):?>
                                <div class="f-14 xl:f-16"><?=$doc->getPreviewText()?></div>
                            <?endif;?>

                            <?if(!empty($doc->getLink())):?>
                                <div class="w-100">
                                    <button class="<?=comp::get('btnDt')?>" data-fancybox="" data-type="ajax"
                                        data-src="<?=SITE_TEMPLATE_PATH?>/ajax/order/"><?=$doc->getLink()->getDescription()?></button>
                                </div>
                            <?endif;?>

                        </div>

                        <?if(!empty($docs)):?>
                            <div class="w-100 xl:w-<?=($countDoc > 1) ? '6' : '3'?>/12 pr-16 pl-16 fg-0 fs-0">
                                <?
                                $docs = [];
                                foreach($doc->getEl()->getAll() as $dEl)
                                {
                                    $docs[] = $dEl->getValue();
                                }
                                if(!empty($docs)):
                                    $docs = Util::getElementsObject($textSiteTableString, [
                                        'select' => ['NAME', 'PREVIEW_PICTURE', 'file'],
                                        'filter' => ['ID' => $docs]
                                    ]);
                                    if(!empty($docs->getIdList())):?>
                                        <div class="<?=comp::get("row")?> rg-32">
                                            <?foreach($docs as $dc):
                                                if(!empty($dc->getFile())):
                                                    foreach($dc->getFile()->getAll() as $file):?>
                                                        <div class="w-100 md:w-6/12 lg:w-4/12 xl:w-<?=($countDoc > 1) ? '6/12' : '100'?> pr-16 pl-16 fg-0 fs-0">
                                                            <a href="<?=\CFile::GetPath($file->getValue())?>" target="_blank" class="w-100 h-100 pn-r r-24 p-32 d-f fd-c ai-c rg-16 bc-w h:c-py h:bxs-3 h:tm--ty5 tn-all-3">
                                                                <div class="pn-a t-0 rt-0 pt-16 pr-16 d-f">
                                                                    <svg width="16" height="16" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="7" cy="7" r="7" fill="currentColor"/><line x1="2.64258" y1="6.88281" x2="11.3596" y2="6.88281" stroke="white" stroke-width="0.5"/><line x1="6.88281" y1="11.3594" x2="6.88281" y2="2.64239" stroke="white" stroke-width="0.5"/></svg>
                                                                </div>
                                                                <?if(!empty($dc->getPreviewPicture())):?>
                                                                    <div class="w-80 ar-100x115 bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=\CFile::GetPath($dc->getPreviewPicture())?>"></div>
                                                                <?endif;?>
                                                                <div class="f-12 ta-c"><?=$dc->getName()?></div>
                                                            </a>
                                                        </div>
                                                    <?break;?>
                                                    <?endforeach;?>
                                                <?endif;?>
                                            <?endforeach;?>
                                        </div>

                                    <?endif;?>
                                <?endif;?>
                            </div>
                        <?endif;?>
                    </div>
                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>