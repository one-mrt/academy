<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($section->getUfQuo())):

    $hod = Util::getElementObject($textSiteTableString, ['NAME', "PREVIEW_TEXT", 'PREVIEW_PICTURE', 'link'], ['ID' => $section->getUfQuo()]);

    if(!empty($hod)):?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="w-100 p-32 xl:p-64 bc-w r-48" <?=Util::iblockElementEditAddId($hod)?>>
                    <div class="<?=comp::get("row")?>">
                        <div class="w-100 xl:w-7/12 pr-16 pl-16 d-f fd-c rg-24 fg-0 fs-0">

                            <h2 class="mb-0 xl:f-44 ftw-5 ff-ot"><?=$hod->getName()?></h2>

                            <?if(!empty($hod->getPreviewText())):?>
                                <div class="w-100"><?=$hod->getPreviewText()?></div>
                            <?endif;?>

                            <?if(!empty($hod->getLink())):?>
                                <div class="w-100 d-f jc-s">
                                    <a href="<?=$hod->getLink()->getValue()?>" class="<?=comp::get("btnDt")?>" rel="nofollow" target="_blank"><?=$hod->getLink()->getDescription()?></a>
                                </div>
                            <?endif;?>

                        </div>

                        <?if(!empty($hod->getPreviewPicture())):?>
                            <div class="w-100 xl:w-5/12 pr-16 pl-16 fg-0 fs-0">
                                <div class="w-100 ar-16x9 r-20 bgs-cr bgr-nr bp-bc bi-load lazyload" data-bg="<?=\CFile::GetPath($hod->getPreviewPicture())?>"></div>
                            </div>
                        <?endif;?>

                    </div>
                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>