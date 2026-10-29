<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<section class="w-100 pt-0">
    <div class="<?=comp::get("container")?>">

        <div class="w-100 p-32 xl:p-64 bc-w r-48 d-f fd-c ai-c rg-32">

            <?$APPLICATION->IncludeComponent(
                "bitrix:breadcrumb",
                "breadcrumb.dark",
                [
                    "PATH" => "",
                    "SITE_ID" => SITE_ID,
                    "START_FROM" => "0"
                ],
                false,
                ['HIDE_ICONS' => 'Y']
            );?>

            <div class="w-100 d-f fd-c rg-32 ai-c">

                <div class="w-100">
                    <h1 class="<?=comp::get('titleh1')?>"><?=$APPLICATION->ShowTitle(false)?></h1>
                </div>

                <?if(!empty($section->getUfFaq())):
                    $faqList = Util::getElementsObject($textSiteTableString, ['select' => ['NAME', 'PREVIEW_TEXT'], 'filter' => ['IBLOCK_SECTION_ID' => $section->getUfFaq()]]);
                    
                    if(!empty($faqList->getIdList())):?>
                        <?foreach($faqList as $item):?>
                            <div class="<?=comp::get("faqCol", "", "pr-16 pl-16 fg-0 fs-0")?>">
                                <div class="lang-faq--item <?=comp::get("faqItem")?>" <?=Util::iblockElementEditAddId($item)?>>
                                    <div class="lang-faq--title <?=comp::get("faqItemTitle")?>">
                                        <div class="<?=comp::get("faqItemName")?>"><?=$item->getName()?></div>
                                        <div class="lang-faq--icon <?=comp::get("faqItemIcon")?>">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><line y1="8" x2="16" y2="8"/><line x1="8" y1="16" x2="8"/></svg>
                                        </div>
                                    </div>
                                    <div class="lang-faq--description <?=comp::get("faqItemText", "f-12", "f-14")?> f-10|ta ta-l|td"><?=$item->getPreviewText()?></div>
                                </div>
                            </div>
                        <?endforeach;?>
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
                <?endif;?>
            </div>
        </div>
    </div>
</section>