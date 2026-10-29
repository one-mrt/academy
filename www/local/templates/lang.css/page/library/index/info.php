<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if($section->getUfBan()):
    $infoTitle = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $section->getUfBan()]]);
    if(!empty($infoTitle)):
        $infoList = Util::getElementsObject($textSiteTableString, ['select' => ['NAME', 'link', 'file'], 'filter' => ['IBLOCK_SECTION_ID' => $section->getUfBan()]]);
        if(!empty($infoList)):?>
            <section class="w-100">
                <div class="<?=comp::get("container")?> d-f fd-c rg-32 xl:rg-48">

                    <h2 class="<?=comp::get("titleh2")?>" <?=Util::iblockElementEditAddId($infoTitle)?>><?=$infoTitle->getName()?></h2>

                    <div class="<?=comp::get("row")?> rg-32">
                        <?foreach($infoList as $info):
                            $linkFile = "";
                            if(!empty($info->getFile()->getAll()))
                            {
                                foreach($info->getFile()->getAll() as $file)
                                {
                                    $linkFile = \CFile::GetPath($file->getFile()->getValue());
                                    break;
                                }
                            }
                            ?>
                            <div class="w-100 md:w-6/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                <a class="w-100 h-100 d-f ai-c cg-32 pt-16 pr-32 pb-16 pl-32 r-12 bc-EDF0F9 h:bc-py h:c-w tn-all-3" target="_blank"
                                    href="<?=$info->getLink() ? $info->getLink()->getValue() : $linkFile?>" <?=Util::iblockElementEditAddId($info)?>>
                                    <div class="fg-1 f-14 lg:f-16 xl:f-18"><?=$info->getName()?></div>
                                    <div class="d-f fg-0 w-24px|svg h-24px|svg lg:w-32px|svg lg:h-32px|svg">
                                        <svg width="32" height="32" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
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