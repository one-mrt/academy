<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<?if(!empty($element->getFaq())):?>
    <section class="<?=comp::get("faqBlock")?>">
        <?
        $secObTitle = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $element->getFaq()->getValue()]]);

        if(!empty($secObTitle)):
            $faqList = Util::getElements($textSiteTableString, ['select' => ['NAME', 'PREVIEW_TEXT'], 'filter' => ['IBLOCK_SECTION_ID' => $secObTitle->getId()]]);
            ?>
            <div class="<?=comp::get("container")?> <?=comp::get("faqContainer")?>">

                <div class="<?=comp::get("faqTitleContainer")?>">
                    <h2 class="<?=comp::get("faqTitle")?>" <?=Util::iblockSectionEditAddId($secObTitle)?>><?=$secObTitle->getName()?></h2>
                </div>

                <div class="<?=comp::get("row")?> <?=comp::get("faqRow")?>">

                    <?foreach($faqList as $item):?>
                        <div class="<?=comp::get("faqCol")?>">
                            <div class="lang-faq--item <?=comp::get("faqItem")?>" <?=Util::iblockElementEditAddId($item)?>>
                                <div class="lang-faq--title <?=comp::get("faqItemTitle")?>">
                                    <div class="<?=comp::get("faqItemName")?>"><?=$item['NAME']?></div>
                                    <div class="lang-faq--icon <?=comp::get("faqItemIcon")?>">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><line y1="8" x2="16" y2="8"/><line x1="8" y1="16" x2="8"/></svg>
                                    </div>
                                </div>
                                <div class="lang-faq--description <?=comp::get("faqItemText")?>"><?=$item['PREVIEW_TEXT']?></div>
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
                    }
                );
            </script>
            
        <?endif;?>

    </section>
<?endif;?>