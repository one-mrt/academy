<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($section->getUfMenu())):
    $title = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $section->getUfMenu()]]);
    if(!empty($title)):
        $docList = Util::getElementsObject($textSiteTableString, [
            'select' => ['NAME', "PREVIEW_TEXT", 'file', 'link'],
            'filter' => ['IBLOCK_SECTION_ID' => $section->getUfMenu()]
        ]);
        ?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="w-100 p-32 xl:p-48 d-f fd-c rg-32 r-16 lg:r-32 bc-w">

                    <h2 class="<?=comp::get("titleh2")?>" <?=Util::iblockSectionEditAddId($title)?>><?=$title->getName()?></h2>

                    <?if(!empty($docList)):?>
                        <div class="<?=comp::get("row")?> rg-16">
                            <?foreach($docList as $doc):?>
                                <div class="w-100 lg:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                    <?if(!empty($doc->getFile())):
                                        foreach ($doc->getFile()->getAll() as $key => $value) {
                                            $docFile = \CFile::GetPath($value->getValue());
                                            break;
                                        }
                                        ?>
                                    <?endif;?>

                                    <a class="ff-ot lg:f-14 xl:f-18 tdl-u tdt-1 tuo-2 h:c-py tn-all-3" target="_blank" href="<?=(!empty($doc->getLink())) ? $doc->getLink()->getValue() : $docFile?>"><?=$doc->getName()?></a>

                                </div>
                            <?endforeach;?>
                        </div>
                    <?endif;?>
                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>