<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<?if(!empty($element)):
    if(!empty($element->getFaq())):?>
        <section class="w-100">
            <?
            $secObTitle = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $element->getFaq()->getValue()]]);
            $faqList = Util::getElementsObject($textSiteTableString, ['select' => ['NAME', 'PREVIEW_TEXT', 'file'], 'filter' => ['IBLOCK_SECTION_ID' => $element->getFaq()->getValue()]]);

            if(!empty($faqList)):?>
                <div class="<?=comp::get("container")?> d-f fd-c rg-32">
                    
                    <?if(!empty($secObTitle)):?>
                        <div class="w-100 d-f jc-c">
                            <h2 class="<?=comp::get("titleh2")?>" <?=Util::iblockSectionEditAddId($secObTitle)?>><?=$secObTitle->getName()?></h2>
                        </div>
                    <?endif;?>

                    <div class="<?=comp::get("row")?> rg-16 jc-c">

                        <?foreach($faqList as $item):?>
                            <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                                <div class="lang-faq--item w-100 d-f fd-c r-20 bc-EDF0F9 of-h" <?=Util::iblockElementEditAddId($item)?>>
                                    <div class="lang-faq--title d-f ai-c cg-16 pt-16 pr-24 pb-16 pl-24 r-20 cr-p h:bc-dt h:c-w tn-all-3">
                                        <div class="w-100 fg-1 ff-ot xl:f-18 ta-l"><?=$item->getName()?></div>
                                        <div class="fg-0">
                                            <div class="lang-faq--icon d-f r-100% bc-dt h:bc-py c-w p-8 tn-all-3">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><line y1="8" x2="16" y2="8"/><line x1="8" y1="16" x2="8"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lang-faq--description w-100 of-a d-n pt-16 pr-24 pb-16 pl-24">
                                        <?if(!empty($item->getPreviewText())):?>
                                            <?=$item->getPreviewText()?>
                                        <?elseif(!empty($item->getFile()) && !empty($item->getFile()->getAll())):?>
                                            <?foreach($item->getFile()->getAll() as $file):
                                                $url = \CFile::GetPath($file->getValue());?>
                                                <?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$url}");?>
                                                <?break;?>
                                            <?endforeach;?>
                                        <?endif;?>
                                    </div>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>

                <script>
                    document.querySelectorAll('.lang-faq--item').forEach((item) => {
                        item.querySelector('.lang-faq--title').addEventListener('click', (e) => {
                            item.querySelector('.lang-faq--icon').classList.toggle('tm-r135');
                            item.querySelector('.lang-faq--description').classList.toggle('d-n');
                        })
                    });
                </script>
                
            <?endif;?>
            

        </section>
    <?endif;?>
<?endif;?>