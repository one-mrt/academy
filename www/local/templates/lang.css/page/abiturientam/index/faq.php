<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($section->getUfFaq())):

    $title = Util::getSectionObject($textSiteTableString, [
        'select' => ['NAME'],
        'filter' => ['ID' => $section->getUfFaq()]
    ]);

    if(!empty($title)):
        $faqList = Util::getElementsObject($textSiteTableString, ['select' => ['NAME', 'PREVIEW_TEXT'], 'filter' => ['IBLOCK_SECTION_ID' => $section->getUfFaq()]]);

        if(!empty($faqList)):?>
            <section class="w-100">
                <div class="<?=comp::get("container")?>">
                    <div class="<?=comp::get("row")?> rg-32 jc-c">

                        <?if(!empty($title)):?>
                            <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                                <h2 class="mb-0 f-24 lg:f-32 xl:f-44 ftw-5 ff-ot ta-c" <?=Util::iblockSectionEditAddId($title)?>><?=$title->getName()?></h2>
                            </div>
                        <?endif;?>

                        <?foreach($faqList as $item):?>
                            <div class="w-100 xl:w-8/12 pr-16 pl-16 fg-0 fs-0">
                                <div class="lang-faq--item w-100 d-f fd-c r-20 bc-EDF0F9 of-h" <?=Util::iblockElementEditAddId($item)?>>
                                    <div class="lang-faq--title d-f ai-c cg-16 pt-16 pr-24 pb-16 pl-24 r-20 cr-p h:bc-dt h:c-w tn-all-3">
                                        <div class="w-100 fg-1 ff-ot f-14 md:f-16 xl:f-18 ta-l"><?=$item->getName()?></div>
                                        <div class="fg-0">
                                            <div class="lang-faq--icon d-f r-100% bc-dt h:bc-py c-w p-8 tn-all-3">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><line y1="8" x2="16" y2="8"/><line x1="8" y1="16" x2="8"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lang-faq--description d-n pt-16 pr-24 pb-16 pl-24"><?=$item->getPreviewText()?></div>
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
                <script type="application/ld+json">
                    {
                        "@context": "https://schema.org",
                        "@type": "FAQPage",
                        "mainEntity": [<?foreach($faqList as $k => $arItem):?>
                            {
                                "@type": "Question",
                                "name": "<?=strip_tags($arItem['NAME'])?>",
                                "acceptedAnswer": {
                                    "@type": "Answer",
                                    "text": "<?=strip_tags($arItem['PREVIEW_TEXT'])?>"
                                }
                            }<?=(count($faqList) > intval($k)+1) ? ',' : ''?><?endforeach;?>
                        ]
                    }
                </script>
            </section>
        <?endif;?>

    <?endif;?>
<?endif;?>