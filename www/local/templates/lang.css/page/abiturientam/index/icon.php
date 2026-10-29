<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($section->getUfIfs())):
    $iconList = Util::getElementsObject($textSiteTableString, [
        'select' => ['NAME', 'svg'],
        'filter' => ['IBLOCK_SECTION_ID' => $section->getUfIfs()]
    ]);
    ?>

    <?if(!empty($iconList->getIdList())):?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="<?=comp::get("row")?> rg-32">
                    <?foreach($iconList as $item):?>
                        <div class="w-100 sm:w-6/12 md:w-4/12 xl:w-20 pr-16 pl-16 d-f fd-c ai-c rg-16 fg-0 fs-0" <?=Util::iblockElementEditAddId($item)?>>
                            <?if(!empty($item->getSvg())):
                                $svgLink = \CFile::GetPath($item->getSvg()->getValue());
                                ?>
                                <div class="w-100 d-f jc-c"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$svgLink}")?></div>
                            <?endif;?>
                            <div class="w-100 ta-c f-14 lg:f-16"><?=$item->getName()?></div>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>