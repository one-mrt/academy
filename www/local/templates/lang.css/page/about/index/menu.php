<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($section->getUfMenu())):

    $parentSection = Util::getSectionObject($textSiteTableString, [
        'select' => ['NAME', 'DEPTH_LEVEL', 'LEFT_MARGIN', 'RIGHT_MARGIN'],
        'filter' => ['ID' => $section->getUfMenu()]
    ]);

    if(!empty($parentSection)):

        $sectList = Util::getSectionsObject($textSiteTableString, [
            'select' => ['NAME', 'UF_FILE', 'UF_LINK', 'DEPTH_LEVEL', 'LEFT_MARGIN', 'RIGHT_MARGIN'],
            'filter' => [
                '>LEFT_MARGIN' => $parentSection->getLeftMargin(),
                '<RIGHT_MARGIN' => $parentSection->getRightMargin(),
                '>DEPTH_LEVEL' => $parentSection->getDepthLevel(),
            ]
        ]);

        $elementList = Util::getElementsObject($textSiteTableString, [
            'select' => ['NAME', 'IBLOCK_SECTION_ID', 'file.FILE', 'link'],
            'filter' => ["IBLOCK_SECTION_ID" => $sectList->getIdList()]
        ]);

        ?>

        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="w-100 p-32 xl:p-64 r-24 xl:r-48 bc-w bw-1 bcr-dt bs-s">
                    <div class="<?=comp::get("row")?> rg-16">
                        <?foreach($sectList as $item):
                            $file = "";
                            $elements = [];

                            if(!empty($item->getUfFile()))
                            {
                                $file = \CFile::GetPath($item->getUfFile());
                            }
                            
                            foreach($elementList as $el)
                            {
                                if($el->getIblockSectionId() == $item->getId())
                                {
                                    $elements[$el->getId()]['name'] = $el->getName();

                                    if(!empty($el->getLink()))
                                    {
                                        $elements[$el->getId()]['l'] = $el->getLink()->getValue();
                                    }

                                    if(!empty($el->getFile()->getAll()))
                                    {
                                        foreach($el->getFile()->getAll() as $elFile)
                                        {
                                            $elements[$el->getId()]['f'] = \CFile::GetPath($elFile->getFile()->getId());
                                            break;
                                        }
                                    }

                                    if(!empty($elements[$el->getId()]['l']))
                                    {
                                        $elements[$el->getId()]['link'] = 'href="' . $elements[$el->getId()]['l'] .'"';

                                        if(strpos($elements[$el->getId()]['l'], 'http') !== false)
                                        {
                                            $elements[$el->getId()]['link'] .= ' target="_blank" rel="nofollow"';
                                        }
                                    }
                                    elseif(!empty($elements[$el->getId()]['f']))
                                    {
                                        $elements[$el->getId()]['link'] = 'href="' . $elements[$el->getId()]['f'] .'" target="_blank"';
                                    }
                                }
                            }
                            ?>
                            <div class="w-100 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                <div class="lang-about--menu w-100 pn-r" <?=Util::iblockSectionEditAddId($item)?>>
                                    <?if(!empty($elements)):?>

                                        <button class="lang-about--item w-100 md:w-a m-0 p-0 bc-t bw-0 z-3 cr-p ftw-6 d-f ai-c cg-8 lh-1 h:c-py ta-l tn-all-3" title="<?=$item->getName()?>">
                                            <div class="fg-1"><?=$item->getName()?></div>
                                            <div class="d-f">
                                                <svg width="10" height="6" viewBox="0 0 10 6" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M4.76315 6L1.33514e-05 0L9.52629 0L4.76315 6Z"/></svg>
                                            </div>
                                        </button>

                                        <div class="lang-about--wrap w-100 h-100 pn-f i-0 z-2 bc-ffffff08 bf-b5 d-n">
                                            <div class="lang-about--items pn-a p-24 xl:p-32 d-f fd-c rg-16 r-20 bc-E5EBFA lh-1 bxs-py">
                                                <?foreach($elements as $elm):?>
                                                    <a class="f-14 h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3" <?=$elm['link']?>><?=$elm['name']?></a>
                                                <?endforeach;?>
                                            </div>
                                        </div>

                                    <?else:
                                        $link = 'href="' . ($item->getUfLink() ?: $file) . '"' . (empty($item->getUfLink()) ? 'target="_blank"' : '');
                                        if(strpos($link, 'http') !== false)
                                        {
                                            $link .= ' target="_blank" rel="nofollow"';
                                        }
                                        ?>
                                        <a class="ftw-6 h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3" <?=$link?>><?=$item->getName()?></a>
                                    <?endif;?>
                                </div>
                            </div>
                        <?endforeach;?>

                        <script>
                            document.querySelectorAll('.lang-about--menu').forEach((el) => {
                                let btn = el.querySelector('.lang-about--item');

                                if(btn)
                                {
                                    btn.wrap = el.querySelector('.lang-about--wrap');
                                    btn.items = btn.wrap.querySelector('.lang-about--items');

                                    btn.toggle = () => {
                                        btn.classList.toggle('pn-r');
                                        btn.wrap.classList.toggle('d-n');
                                        document.body.classList.toggle('of-h');
                                        document.body.classList.toggle('md:pr-15');
                                    };

                                    btn.wrap.addEventListener('click', btn.toggle.bind(false));

                                    btn.addEventListener('click', () => {
                                        let top = btn.getBoundingClientRect().bottom + 16;
                                        let left = btn.getBoundingClientRect().left;
                                        
                                        btn.items.style.top = `${top/16}rem`;
                                        btn.items.style.left = `${left/16}rem`;
                                        btn.toggle();
                                    });
                                }
                            });
                        </script>

                    </div>
                </div>
            </div>
        </section>
        
    <?endif;?>
<?endif;?>