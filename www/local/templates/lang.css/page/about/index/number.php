<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($section) && !empty($section->getUfNum())):

    $title = Util::getSectionObject($textSiteTableString, ['select' => ['NAME', 'PICTURE'], 'filter' => ['ID' => $section->getUfNum()]]);

    if(!empty($title)):
        
        $numberList = Util::getElementsObject($textSiteTableString, ['select' => ['NAME', 'PREVIEW_TEXT'], 'filter' => ['IBLOCK_SECTION_ID' => $section->getUfNum()]]);
        
        if(!empty($numberList->getIdList())):?>
            <section class="w-100 spt-100_hm xl:spt-100_hm" id="LangNumber">
                <div class="<?=comp::get("container")?>">
                    <div class="<?=comp::get("row")?> rg-32">

                        <?if(!empty($title)):
                            if(!empty($title->getPicture()))
                            {
                                $prev = 'data-bg="' . \CFile::GetPath($title->getPicture()) . '"';
                                $aboutModPrev = "bgs-cr bgr-nr bp-bc bi-load lazyload";
                            }
                            ?>
                            <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                                <h2 class="mb-0 f-24 xl:f-44 ftw-5 ff-ot ta-c" <?=Util::iblockSectionEditAddId($title)?>><?=$title->getName()?></h2>
                            </div>
                        <?endif;?>

                        <div class="w-100 xl:w-7/12 pr-16 pl-16 fg-0 fs-0">
                            <?if(!empty($prev)):?>
                                <div class="w-100 ar-16x9 of-h r-20 bc-w <?=$aboutModPrev?>" <?=$prev?>></div>
                            <?endif;?>
                        </div>

                        <div class="w-100 xl:w-5/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-32">
                            <?foreach($numberList as $number):?>
                                <div class="w-100 d-f fd-c rg-8 lh-1 ta-c md:ta-l" <?=Util::iblockElementEditAddId($number)?>>
                                    <div class="c-py f-24 xl:f-32 ftw-5 ff-ot"><?=$number->getName()?></div>
                                    <?if(!empty($number->getPreviewText())):?>
                                        <div class=""><?=$number->getPreviewText()?></div>
                                    <?endif;?>
                                </div>
                            <?endforeach;?>
                        </div>

                    </div>
                </div>
            </section>
        <?endif;?>
    <?endif;?>
<?endif;?>