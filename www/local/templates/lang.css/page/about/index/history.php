<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($section->getUfForm())):

    $title = Util::getSectionObject($textSiteTableString, [
        'select' => ['NAME', 'PICTURE', 'DESCRIPTION', 'UF_TEXT'],
        'filter' => ['ID' => $section->getUfForm()]
    ]);

    $numberList = Util::getElementsObject($textSiteTableString, [
        'select' => ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'],
        'filter' => ['IBLOCK_SECTION_ID' => $section->getUfForm()]
    ]);
    ?>

    <?if(!empty($title) && $numberList->getIdList()):?>
        <section class="w-100 xl:spt-100_hm" id="LangHistory">
            <div class="<?=comp::get("container")?>">
                <div class="w-100 d-f fd-c rg-32 xl:rg-48 p-32 xl:p-64 r-24 xl:r-48 bc-dt c-w">

                    <div class="w-100">
                        <h2 class="mb-0 f-24 xl:f-44 ftw-5 ff-ot ta-c" <?=Util::iblockSectionEditAddId($title)?>><?=$title['NAME']?></h2>
                    </div>

                    <div class="w-100 d-f fd-c rg-32">
                        <div class="<?=comp::get("row")?> rg-32">
                            <?if(!empty($title->getPicture()))
                            {
                                $prev = \CFile::GetPath($title->getPicture());
                                $historyModPrev = "bgs-cr bgr-nr bp-bc bi-load lazyload";
                            }
                            ?>
                            <div class="w-100 xl:w-5/12 pr-16 pl-16 fg-0 fs-0 d-f ai-c o-2 lg:o-1">
                                <?if(!empty($title->getDescription())):?>
                                    <div class="f-14 md:f-16"><?=$title->getDescription()?></div>
                                <?endif;?>
                            </div>

                            <div class="w-100 xl:w-7/12 pr-16 pl-16 fg-0 fs-0 o-1 lg:o-2">
                                <?if(!empty($prev)):?>
                                    <div class="w-100 ar-16x9 of-h r-20 bc-w <?=$historyModPrev?>" data-bg="<?=$prev?>"></div>
                                <?endif;?>
                            </div>
                        </div>

                        <div class="<?=comp::get("row")?> rg-32">
                            <?foreach($numberList as $number):?>
                                <div class="w-100 xl:w-4/12 pr-16 pl-16 fs-0 fg-0">
                                    <div class="w-100 h-100 of-h r-20 d-f fd-c lh-1 bc-EDF0F9 c-dt" <?=Util::iblockElementEditAddId($number)?>>
                                        <?
                                        $prevEl = "";
                                        $hisoryElModPrev = "";

                                        if(!empty($number->getPreviewPicture())):
                                            $prevEl = \CFile::GetPath($number->getPreviewPicture());
                                            $hisoryElModPrev = "bgs-cr bgr-nr bp-bc bi-load lazyload";
                                            ?>
                                            <div class="w-100 ar-2x1 bc-w <?=$hisoryElModPrev?>" data-bg="<?=$prevEl?>"></div>
                                        <?endif;?>

                                        <div class="w-100 d-f fd-c rg-16 p-24 xl:p-32 fg-1">
                                            <div class="f-18 xl:f-24 ftw-5 ff-ot"><?=$number->getName()?></div>
                                            <?if(!empty($number->getPreviewText())):?>
                                                <div class="f-14 md:f-16"><?=$number->getPreviewText()?></div>
                                            <?endif;?>
                                        </div>
                                    </div>
                                </div>
                            <?endforeach;?>

                            <?if(!empty($title) && !empty($title->getUfText())):?>
                                <div class="w-100 pr-16 pl-16 fg-0 fs-0 xl:f-24 md:ftw-5 ta-c"><?=$title->getUfText()?></div>
                            <?endif;?>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    <?endif;?>

<?endif;?>