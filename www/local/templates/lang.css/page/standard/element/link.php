<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($element)):
    if(!empty($element->getMenu())):
        $linkTitle = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $element->getMenu()->getValue()]]);
        $catEl = Util::getElementsObject($textSiteTableString, ['select' => ['NAME', 'link', 'file'], 'filter' => ['IBLOCK_SECTION_ID' => $element->getMenu()->getValue()]]);

        if(!empty($catEl)):?>
            <section class="w-100">
                <div class="<?=comp::get("container")?> d-f fd-c rg-32">

                    <?if(!empty($linkTitle)):?>
                        <div class="w-100 d-f jc-c">
                            <h2 class="<?=comp::get("titleh2")?> xl:w-8/12" <?=Util::iblockSectionEditAddId($linkTitle)?>><?=$linkTitle->getName()?></h2>
                        </div>
                    <?endif;?>

                    <div class="<?=comp::get("row")?> rg-16 jc-c">
                        <?foreach($catEl as $cat):
                            $name = $cat->getName();
                            $link = "";

                            if(!empty($cat->getLink()))
                            {
                                $link = $cat->getLink()->getValue();

                                if(!empty($cat->getLink()->getDescription()))
                                {
                                    $name = $cat->getLink()->getDescription();
                                }
                            }
                            elseif(!empty($cat->getFile()->getAll()))
                            {
                                foreach($cat->getFile()->getAll() as $file)
                                {
                                    $link = \CFile::GetPath($file->getValue());
                                    break;
                                }
                            }
                            ?>
                            <div class="w-100 xl:w-4/12 pr-16 pl-16 fg-0 fs-0 d-f ai-c">
                                <a class="w-100 h-100 d-f ai-c cg-16 r-12 p-24 bc-EDF0F9 lh-1 h:bc-dt h:c-w tn-all-3" href="<?=$link;?>" <?=(!empty($cat->getFile()->getAll())) ? "target=\"_blank\"" : ""?> <?=Util::iblockElementEditAddId($cat)?>>
                                    <div class="fg-1 ftw-5"><?=$name;?></div>
                                    <div class="d-f">
                                        <svg width="24" height="24" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect class="fil-py" width="23" height="23" rx="5" fill="white"></rect>
                                            <path class="fil-w" d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z"></path>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
            </section>
        <?endif;?>
    <?endif;?>
<?endif;?>