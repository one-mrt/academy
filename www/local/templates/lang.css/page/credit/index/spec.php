<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($section->getUfIfs())):
    $title = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $section->getUfIfs()]]);
    if(!empty($title)):
        $specList = Util::getElementsObject($textSiteTableString, [
            'select' => ['NAME', "PREVIEW_TEXT", 'svg'],
            'filter' => ['IBLOCK_SECTION_ID' => $section->getUfIfs()]
        ]);
        ?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="w-100 p-32 xl:p-48 d-f fd-c rg-32 r-16 xl:r-32 bc-py">

                    <h2 class="<?=comp::get("titleh2")?> c-w" <?=Util::iblockSectionEditAddId($title)?>><?=$title->getName()?></h2>

                    <?if(!empty($specList)):?>
                        <div class="<?=comp::get("row")?> rg-32">
                            <?foreach($specList as $spec):?>
                                <div class="w-100 md:w-6/12 xl:w-3/12 pr-16 pl-16 fg-0 fs-0">
                                    <div class="w-100 h-100 p-32 d-f fd-c rg-24 bc-w r-20" <?=Util::iblockElementEditAddId($spec)?>>
                                        <?if(!empty($spec->getSvg())):
                                            $specSvg = \CFile::GetPath($spec->getSvg()->getValue());
                                            ?>
                                            <div class="w-100 d-f jc-s"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$specSvg}")?></div>
                                        <?endif;?>

                                        <div class="d-f fd-c rg-16">
                                            <div class="f-18 xl:f-24 ftw-5 lh-1 ff-ot"><?=$spec->getName()?></div>

                                            <?if(!empty($spec->getPreviewText())):?>
                                                <div class="f-14 xl:f-16"><?=$spec->getPreviewText()?></div>
                                            <?endif;?>
                                        </div>
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