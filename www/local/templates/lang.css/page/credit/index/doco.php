<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($section->getUfForm())):
    $title = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $section->getUfForm()]]);
    if(!empty($title)):
        $specList = Util::getElementsObject($textSiteTableString, [
            'select' => ['NAME', "PREVIEW_TEXT", 'svg', 'text'],
            'filter' => ['IBLOCK_SECTION_ID' => $section->getUfForm()]
        ]);
        ?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="w-100 p-32 xl:p-48 d-f fd-c rg-32 r-16 lg:r-32 bc-dt">

                    <h2 class="<?=comp::get("titleh2")?> c-w" <?=Util::iblockSectionEditAddId($title)?>><?=$title->getName()?></h2>

                    <?if(!empty($specList)):?>
                        <div class="<?=comp::get("row")?> rg-32">
                            <?foreach($specList as $spec):?>
                                <div class="w-100 lg:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                    <div class="w-100 h-100 p-32 d-f fd-c rg-24 bc-w r-20" <?=Util::iblockElementEditAddId($spec)?>>
                                        <?if(!empty($spec->getSvg())):
                                            $specSvg = \CFile::GetPath($spec->getSvg()->getValue());
                                            ?>
                                            <div class="w-100 d-f jc-s"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$specSvg}")?></div>
                                        <?endif;?>

                                        <div class="d-f fd-c rg-16">
                                            <div class="f-18 xl:f-24 ftw-5 lh-1 ff-ot"><?=$spec->getName()?></div>

                                            <?if(!empty($spec->getPreviewText())):?>
                                                <div class="f-12 sm:f-14 xl:f-16"><?=$spec->getPreviewText()?></div>
                                            <?endif;?>

                                            <?if(!empty($spec->getText()->getAll())):?>
                                                <ul class="mb-0 d-f fd-c rg-8 pl-16 lg:pl-24 f-12 sm:f-14 xl:f-16">
                                                    <?foreach($spec->getText()->getAll() as $text):?>
                                                        <li class=""><?=$text->getValue()?></li>
                                                    <?endforeach;?>
                                                </ul>
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