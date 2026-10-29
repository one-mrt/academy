<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if($section->getUfIfs()):
    $chartTitle = Util::getSectionObject($textSiteTableString, ['select' => ['NAME', 'DESCRIPTION'], 'filter' => ['ID' => $section->getUfIfs()]]);
    if(!empty($chartTitle)):
        $chartList = Util::getElementsObject($textSiteTableString, [
            'select' => ['NAME', 'PREVIEW_TEXT', 'text'],
            'filter' => ['IBLOCK_SECTION_ID' => $section->getUfIfs()]
        ]);
        if(!empty($chartList)):?>
            <section class="w-100">
                <div class="<?=comp::get("container")?> d-f fd-c rg-32 xl:rg-48">

                    <div class="w-100 d-f fd-c rg-16">
                        <h2 class="<?=comp::get('titleh2')?>" <?=Util::iblockElementEditAddId($chartTitle)?>><?=$chartTitle->getName()?></h2>
                        <?if(!empty($chartTitle->getDescription())):?>
                            <div class="f-14 xl:f-16 ta-c"><?=$chartTitle->getDescription()?></div>
                        <?endif;?>
                    </div>

                    <div class="<?=comp::get("row")?> rg-32">
                        <?foreach($chartList as $chart):?>
                            <div class="w-100 md:w-6/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                <div class="w-100 h-100 p-32 xl:p-48 d-f fd-c rg-24 r-20 bc-w bw-1 bs-s bcr-E5E5E5 lh-1" <?=Util::iblockElementEditAddId($chart)?>>
                                    <div class="f-18 xl:f-24 ftw-5 ff-ot"><?=$chart->getName()?></div>
                                    <?if(!empty($chart->getText()->getAll())):?>
                                        <div class="w-100 d-f fd-c jc-e rg-16 fg-1">
                                            <?foreach($chart->getText()->getAll() as $text):?>
                                                <div class="d-f fd-c rg-8 f-12 lg:f-14">
                                                    <div class="ftw-6"><?=$text->getValue()?></div>
                                                    <div class="ftw-4"><?=$text->getDescription()?></div>
                                                </div>
                                            <?endforeach;?>
                                        </div>
                                    <?endif;?>
                                </div>
                            </div>
                        <?endforeach;?>

                    </div>
                </div>
            </section>
        <?endif;?>
    <?endif;?>
<?endif;?>