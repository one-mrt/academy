<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($section) && !empty($section->getUfFaq())):
    $parentSection = Util::getSectionObject($textSiteTableString, [
        'select' => ['NAME', 'DEPTH_LEVEL', 'LEFT_MARGIN', 'RIGHT_MARGIN'],
        'filter' => ['ID' => $section->getUfFaq()]
    ]);

    if(!empty($parentSection)):
        $sectList = Util::getSectionsObject($textSiteTableString, [
            'select' => ['NAME'],
            'filter' => [
                '>LEFT_MARGIN' => $parentSection->getLeftMargin(),
                '<RIGHT_MARGIN' => $parentSection->getRightMargin(),
                '>DEPTH_LEVEL' => $parentSection->getDepthLevel(),
            ]
        ]);

        $elementList = Util::getElementsObject($textSiteTableString, [
            'select' => ['NAME', 'IBLOCK_SECTION_ID', 'file', 'link'],
            'filter' => ["IBLOCK_SECTION_ID" => $sectList->getIdList()]
        ]);
        ?>
        <section class="w-100">
            <div class="<?=comp::get("container")?> d-f fd-c rg-32 xl:rg-48">
                
                <h2 class="mb-0 ftw-6 f-24 lg:f-32 xl:f-40 ff-ot ta-c lh-1" <?=Util::iblockSectionEditAddId($parentSection)?>><?=$parentSection->getName()?></h2>

                <div class="<?=comp::get("row")?> rg-16 jc-c">

                    <?foreach($sectList as $item):?>
                        <div class="w-100 xl:w-8/12 pr-16 pl-16 fg-0 fs-0">
                            <div class="lang-faq--item w-100 d-f fd-c r-20 bc-EDF0F9 of-h" <?=Util::iblockSectionEditAddId($item)?>>
                                <div class="lang-faq--title d-f ai-c cg-16 pt-16 pr-24 pb-16 pl-24 r-20 cr-p h:bc-dt h:c-w tn-all-3">
                                    <div class="w-100 fg-1 ff-ot f-14 lg:f-16 xl:f-18 ta-l"><?=$item->getName()?></div>
                                    <div class="fg-0">
                                        <div class="lang-faq--icon d-f r-100% bc-dt h:bc-py c-w p-8 tn-all-3">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><line y1="8" x2="16" y2="8"/><line x1="8" y1="16" x2="8"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="lang-faq--description d-n d-f fd-c ai-s rg-16 pt-16 pr-24 pb-24 pl-24 f-14 lg:f-16">
                                    <?foreach($elementList as $faq):
                                        if($faq->getIblockSectionId() == $item->getId()):
                                            $link = "";

                                            if(!empty($faq->getLink()))
                                            {
                                                $link = $faq->getLink()->getValue();
                                            }

                                            if(!empty($faq->getFile()->getAll()))
                                            {
                                                foreach($faq->getFile()->getAll() as $file)
                                                {
                                                    $link = \CFile::GetPath($file->getValue());
                                                    break;
                                                }
                                            }
                                            ?>
                                            <a href="<?=$link?>" class="tdl-u tdt-1 tuo-2 h:c-py tn-all-3" <?=Util::iblockElementEditAddId($faq)?> target="_blank" rel="nofollow"><?=$faq->getName()?></a>
                                        <?endif;?>
                                    <?endforeach;?>
                                </div>
                            </div>
                        </div>
                    <?endforeach;?>

                </div>
            </div>
        </section>
        <script>
            document.querySelectorAll('.lang-faq--item').forEach((item) => {
                item.querySelector('.lang-faq--title').addEventListener('click', (e) => {
                    item.querySelector('.lang-faq--icon').classList.toggle('tm-r135');
                    item.querySelector('.lang-faq--description').classList.toggle('d-n');
                })
            });
        </script>
    <?endif;?>

<?endif;?>