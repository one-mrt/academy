<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<section class="w-100">
    <div class="<?=comp::get("container")?> d-f fd-c ai-c rg-32">

        <div class="w-100 bc-w p-32 xl:p-64 of-h d-f fd-c ai-c rg-32 r-24 xl:r-48">

            <div class="w-100 d-f jc-c">
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
            </div>

            <div class="w-100 d-f jc-c">
                <h1 class="<?=comp::get("titleh1")?>"><?=$APPLICATION->ShowTitle()?></h1>
            </div>

            <?if(!empty($section->getUfText())):
                $description = Util::getElementObject($textSiteTableString, ['NAME', 'PREVIEW_TEXT'], ['ID' => $section->getUfText()]);

                if(!empty($description)):?>
                    <?if(!empty($description->getPreviewText())):?>
                        <div class="w-100 xl:w-8/12 f-14 xl:f-16" <?=Util::iblockElementEditAddId($description)?>><?=$description->getPreviewText()?></div>
                    <?endif;?>
                <?endif;?>
            <?endif;?>

            <?if(!empty($section->getUfBan())):
                $linkCredit = Util::getElementsObject($textSiteTableString,[
                    'select' => ['NAME', 'PREVIEW_PICTURE', 'link'],
                    'filter' => ['IBLOCK_SECTION_ID' => $section->getUfBan()]
                ]);

                if(!empty($linkCredit)):?>
                    <div class="w-100 xl:w-10/12">
                        <div class="<?=comp::get("row")?> rg-32">
                            <?foreach($linkCredit as $link):
                                $linkPrev = "";
                                if(!empty($link->getPreviewPicture()))
                                {
                                    $linkPrev = \CFile::GetPath($link->getPreviewPicture());
                                }
                                ?>
                                <div class="w-100 md:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                    <a href="<?=$link->getLink()->getValue()?>" class="w-100 h-100 d-f ai-c cg-32 p-16 r-12 bc-EDF0F9 h:bc-py h:c-w tn-all-3" <?=Util::iblockElementEditAddId($link)?> rel="nofollow" target="_blank">
                                        <div class="fg-0 w-25px h-25px sm:w-50px sm:h-50px md:w-25px md:h-25px lg:w-50px lg:h-50px ar-1x1 bgs-cn bgr-nr bp-c bi-load lazyload" data-bg="<?=$linkPrev?>"></div>
                                        <div class="fg-1 f-14 sm:f-16 md:f-14 lg:f-16 xl:f-18 ftw-5 wb-ba"><?=$link->getName()?></div>
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
                <?endif;?>
            <?endif;?>
        </div>

    </div>
</section>