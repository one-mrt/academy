<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<?if(!empty($element->getUd())):?>
    <section class="<?=comp::get("faqBlock")?>">
        <?
        $secObTitle = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $element->getUd()->getValue()]]);

        if(!empty($secObTitle)):
            $faqList = Util::getElementsObject($textSiteTableString, ['select' => ['NAME', 'PREVIEW_TEXT', 'text'], 'filter' => ['IBLOCK_SECTION_ID' => $secObTitle->getId()]]);
            ?>
            <div class="<?=comp::get("container")?> <?=comp::get("faqContainer")?>">

                <div class="<?=comp::get("faqTitleContainer")?>">
                    <h2 class="<?=comp::get("faqTitle")?>" <?=Util::iblockSectionEditAddId($secObTitle)?>><?=$secObTitle->getName()?></h2>
                </div>

                <div class="<?=comp::get("row")?> <?=comp::get("faqRow")?>">
                    <?foreach($faqList as $item):?>
                        <div class="<?=comp::get("faqCol")?>">
                            <div class="lang-ud--item <?=comp::get("faqItem")?>" <?=Util::iblockElementEditAddId($item)?>>
                                <div class="lang-ud--title <?=comp::get("faqItemTitle")?>">
                                    <div class="<?=comp::get("faqItemName")?>"><?=$item->getName()?></div>
                                    <div class="lang-ud--icon <?=comp::get("faqItemIcon")?>">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><line y1="8" x2="16" y2="8"/><line x1="8" y1="16" x2="8"/></svg>
                                    </div>
                                </div>
                                <div class="lang-ud--description <?=comp::get("faqItemText")?>">
                                    <?if(!empty($item->getPreviewText())):?>
                                        <div class=""><?=$item->getPreviewText()?></div>
                                    <?endif;?>

                                    <?if(!empty($item->getText()->getAll())):
                                        $tCount = round(count($item->getText()->getAll()) / 2);
                                        $tIndex = 0;
                                        ?>
                                        <div class="<?=comp::get("row")?>">
                                            <div class="<?=comp::get("col")?> xl:w-6/12">
                                                <ul class="mb-0 pl-24">
                                                <?foreach($item->getText()->getAll() as $iText):?>
                                                    <li class=""><?=$iText->getValue()?></li>
                                                    <?$tIndex++;?>
                                                    <?if($tIndex == $tCount):?></ul></div><div class="<?=comp::get("col")?> xl:w-6/12"><ul class="mb-0 pl-24"><?endif;?>
                                                <?endforeach;?>
                                            </div>
                                        </div>
                                    <?endif;?>
                                </div>
                            </div>
                        </div>
                    <?endforeach;?>
                </div>
            </div>

            <script>
                document.querySelectorAll('.lang-ud--item').forEach((item) => {
                        item.querySelector('.lang-ud--title').addEventListener('click', (e) => {
                            item.querySelector('.lang-ud--icon').classList.toggle('tm-r135');
                            item.querySelector('.lang-ud--description').classList.toggle('d-n');
                        })
                    }
                );
            </script>
            
        <?endif;?>

    </section>
<?endif;?>