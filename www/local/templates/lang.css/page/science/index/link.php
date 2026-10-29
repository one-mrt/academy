<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($section->getUfIfs())):

    $parentSection = Util::getSectionObject($textSiteTableString, [
        'select' => ['NAME', 'DEPTH_LEVEL', 'LEFT_MARGIN', 'RIGHT_MARGIN'],
        'filter' => ['ID' => $section->getUfIfs()]
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

        if(!empty($sectList->getIdList())):

            $elementList = Util::getElementsObject($textSiteTableString, [
                'select' => ['NAME', 'IBLOCK_SECTION_ID', 'file', 'link'],
                'filter' => ["IBLOCK_SECTION_ID" => $sectList->getIdList()]
            ]);
            ?>
            <section class="w-100">
                <div class="<?=comp::get("container")?>">
                    <div class="<?=comp::get("row")?> rg-32">
                        <?foreach($sectList as $item):?>
                            <div class="w-100 md:w-6/12 xl:w-3/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-32">

                                <h2 class="mb-0 ftw-5 f-18 sm:f-22 xl:f-22 ff-ot lh-1 ta-c xl:ta-l"><?=$item->getName()?></h2>

                                <div class="w-100 d-f fd-c rg-16 xl:rg-32">
                                    <?foreach($elementList as $iLink):
                                        if($iLink->getIblockSectionId() == $item->getId()):
                                            $link = "";

                                            if(!empty($iLink->getLink()))
                                            {
                                                $link = 'href="' . $iLink->getLink()->getValue() . '"';
                                                if(strpos($iLink->getLink()->getValue(), 'http') !== false)
                                                {
                                                    $link .= ' target="_blank" rel="nofollow"';
                                                }
                                            }

                                            if(!empty($iLink->getFile()->getAll()))
                                            {
                                                foreach($iLink->getFile()->getAll() as $iFile)
                                                {
                                                    $link = 'href="' . \CFile::GetPath($iFile->getValue()) . '" target="_blank"';
                                                    break;
                                                }
                                            }
                                            ?>
                                            <a class="w-100 d-f ai-c cg-16 r-12 pt-16 pr-24 pb-16 pl-24 bc-EDF0F9 lh-1 h:bc-dt h:c-w tn-all-3" <?=$link?> <?=Util::iblockElementEditAddId($iLink)?>>
                                                <div class="fg-1 ftw-5 f-14 xl:f-16"><?=$iLink->getName()?></div>
                                                <div class="d-f">
                                                    <svg width="24" height="24" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect class="fil-py" width="23" height="23" rx="5" fill="white"></rect>
                                                        <path class="fil-w" d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z"></path>
                                                    </svg>
                                                </div>
                                            </a>
                                        <?endif;?>
                                    <?endforeach;?>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
            </section>

        <?endif;?>
    <?endif;?>
<?endif;?>