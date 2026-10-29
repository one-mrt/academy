<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($section->getUfNum())):

    $content = Util::getSectionObject($textSiteTableString, ['select' => ['NAME', 'PICTURE', 'DESCRIPTION'], 'filter' => ['ID' => $section->getUfNum()]]);

    if(!empty($content)):
        $stepList = Util::getElementsObject($textSiteTableString, ['select' => ['NAME', 'PREVIEW_TEXT'], 'filter' => ['IBLOCK_SECTION_ID' => $section->getUfNum()]]);
        ?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="<?=comp::get("row")?> rg-32">

                    <div class="w-100 lg:w-6/12 pr-16 pl-16 fg-0 fs-0">
                        <div class="w-100 h-100 p-32 xl:p-48 d-f fd-c rg-32 r-24 bc-EDF0F9">
                            <?if(!empty($content->getPicture())):?>
                                <div class="w-100 ar-16x9 r-20 bc-w bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=\CFile::GetPath($content->getPicture())?>"></div>
                            <?endif;?>

                            <div class="w-100 d-f fd-c rg-16">

                                <h2 class="mb-0 f-24 lg:f-32 ftw-5 lh-1"><?=$content->getName()?></h2>

                                <?if(!empty($content->getDescription())):?>
                                    <div class="f-14 xl:f-16"><?=$content->getDescription()?></div>
                                <?endif;?>
                            </div>

                            <?if(!empty($linkCredit)):?>
                                <div class="w-100 d-f fd-c rg-16">
                                    <?foreach($linkCredit as $link):
                                        $linkPrev = "";
                                        if(!empty($link->getPreviewPicture()))
                                        {
                                            $linkPrev = \CFile::GetPath($link->getPreviewPicture());
                                        }
                                        ?>
                                        <div class="w-100 fg-0 fs-0">
                                            <a href="<?=$link->getLink()->getValue()?>" class="w-100 h-100 d-f ai-c cg-32 p-16 r-12 bc-w h:bc-py h:c-w tn-all-3" <?=Util::iblockElementEditAddId($link)?> rel="nofollow" target="_blank">
                                                <div class="fg-0 w-25px h-25px sm:w-50px sm:h-50px ar-1x1 bgs-cn bgr-nr bp-c bi-load lazyload" data-bg="<?=$linkPrev?>"></div>
                                                <div class="fg-1 f-14 sm:f-16 lg:f-14 xl:f-18 ftw-5 wb-ba"><?=$link->getName()?></div>
                                                <div class="d-f fg-0 w-24px|svg h-24px|svg md:w-32px|svg md:h-32px|svg">
                                                    <svg width="32" height="32" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect class="fil-py" width="23" height="23" rx="5" fill="white"></rect>
                                                        <path class="fil-w" d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z"></path>
                                                    </svg>
                                                </div>
                                            </a>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            <?endif;?>
                        </div>
                    </div>

                    <?if(!empty($stepList)):
                        $stepIndex = 0;
                        ?>
                        <div class="w-100 lg:w-6/12 pr-16 pl-16 d-f fd-c jc-c rg-32 fg-0 fs-0">
                            <?foreach($stepList as $step):
                                $stepIndex++;
                                ?>
                                <div class="w-100 d-f fd-c rg-16 pn-r pl-48 lg:pl-0" <?=Util::iblockElementEditAddId($step)?>>
                                    <div class="f-24 ftw-5 ff-ot lh-1"><?=$step->getName()?></div>
                                    <?if(!empty($step->getPreviewText())):?>
                                        <div class="f-14 xl:f-16"><?=$step->getPreviewText()?></div>
                                    <?endif;?>

                                    <div class="pn-a l-0 lg:l--44 h-100%+2rem d-f fd-c ai-c <?=($stepIndex % 2 == 0) ? "c-py" : ""?>">
                                        <svg width="24" height="24" viewBox="0 0 25 25" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><circle cx="12.5" cy="12.5" r="12.5"/></svg>
                                        <?if($stepIndex != count($stepList)):?>
                                            <div class="w-1px fg-1 bw-1 bcr-202657b3 bs-ds"></div>
                                        <?endif;?>
                                    </div>
                                </div>
                            <?endforeach;?>
                        </div>
                    <?endif;?>

                </div>
            </div>
        </section>
    <?endif;?>

<?endif;?>