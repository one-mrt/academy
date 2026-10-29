<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($section->getUfMenu())):
    $parentSection = Util::getSectionObject($textSiteTableString,[
        'select' => ['DEPTH_LEVEL', 'LEFT_MARGIN', 'RIGHT_MARGIN'],
        'filter' => ['ID' => $section->getUfMenu()]
    ]);

    if(!empty($parentSection)):
        $sectList = Util::getSectionsObject($textSiteTableString, [
            'select' => ['NAME', 'UF_SVG'],
            'filter' => [
                '>LEFT_MARGIN' => $parentSection->getLeftMargin(),
                '<RIGHT_MARGIN' => $parentSection->getRightMargin(),
                '>DEPTH_LEVEL' => $parentSection->getDepthLevel(),
            ]
        ]);

        if(!empty($sectList)):

            $elementList = Util::getElementsObject($textSiteTableString, [
                'select' => ['NAME', 'IBLOCK_SECTION_ID', 'file', 'link'],
                'filter' => ["IBLOCK_SECTION_ID" => $sectList->getIdList()]
            ]);

            $sectionList = [];

            foreach($sectList as $secItem)
            {
                $sectionList[$secItem->getId()]['name'] = $secItem->getName();
                $sectionList[$secItem->getId()]['item'] = [];

                if(!empty($secItem->getUfSvg()))
                {
                    $sectionList[$secItem->getId()]['svg'] = \CFile::GetPath($secItem->getUfSvg());
                }

                foreach($elementList as $element)
                {
                    if($element->getIblockSectionId() == $secItem->getId())
                    {
                        $pred =& $sectionList[$secItem->getId()]['item'];
                        $pred[$element->getId()]['name'] = $element->getName();

                        if($element->getFile()->getAll())
                        {
                            foreach($element->getFile()->getAll() as $file)
                            {
                                $pred[$element->getId()]['doc'] = \CFile::GetPath($file->getValue());
                                break;
                            }
                        }

                        if($element->getLink())
                        {
                            $pred[$element->getId()]['link'] = $element->getLink()->getValue();
                        }
                    }
                }
            }

            ?>

            <section class="w-100">
                <div class="<?=comp::get("container")?>">
                    <div class="w-100 p-32 lg:p-48 bc-EDF0F9 r-24">
                        <div class="<?=comp::get("row")?> rg-32">
                            <?foreach($sectionList as $item):?>
                                <div class="w-100 lg:w-6/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                    <div class="d-f ai-s cg-16 pn-r">
                                            
                                        <div class="w-50px h-50px lg:w-75px lg:h-75px pn-r z-1 d-f ai-c jc-c p-16 r-12 bc-dt"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$item['svg']}")?></div>

                                        <div class="d-f fd-c rg-16 fg-1 pn-r z-1 pb-32">

                                            <div class="d-f ai-c ftw-6 f-16 lg:f-24 h-50px lg:h-75px lh-1" <?=Util::iblockSectionEditAddId($item)?>>
                                                <div class="lh-1"><?=$item['name']?></div>
                                            </div>

                                            <div class="w-100 d-f fd-c ai-s rg-16 f-14 lg:f-16">
                                                <?foreach($item['item'] as $el):
                                                    $link = $el['doc'] ? $el['doc'] : $el['link'];
                                                    ?>
                                                    <a href="<?=$link?>" <?=(!empty($el['link']) ? 'target="_blank" rel="nofollow"' : '')?> <?=Util::iblockElementEditAddId($el)?> class="tdl-u tdt-1 tuo-2 bc-t h:c-py tn-all-3"><?=$el['name']?></a>
                                                <?endforeach;?>
                                            </div>
                                        </div>

                                        <div class="w-50px lg:w-75px pn-a t-50 lg:t-75 b-0 l-0 ml-24 lg:ml-37 bbw-2 bbs-s bbc-dt blw-2 bls-s blc-dt"></div>
                                    </div>
                                </div>
                            <?endforeach;?>
                            <?unset($sectionList);?>
                        </div>
                    </div>
                </div>
            </section>

        <?endif;?>
    <?endif;?>
<?endif;?>