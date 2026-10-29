<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<?if(!empty($element->getContact())):
    $secObTitle = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $element->getContact()->getValue()]]);

    if(!empty($secObTitle)):
        $startList = Util::getElementsObject($textSiteTableString, [
            'select' => ['NAME', 'PREVIEW_TEXT', 'text', 'el'],
            'filter' => ['IBLOCK_SECTION_ID' => $secObTitle->getId()],
        ]);
        ?>

        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="<?=comp::get("row")?> rg-32 jc-c">

                    <div class="<?=comp::get("col")?> d-f jc-c">
                        <h2 class="xl:w-8/12 mb-0 f-24 lg:f-32 xl:f-44 ftw-5 ta-c" <?=Util::iblockSectionEditAddId($secObTitle)?>><?=$secObTitle->getName()?></h2>
                    </div>

                    <?foreach($startList as $item):?>
                        <div class="<?=comp::get("col")?> md:w-6/12 xl:w-6/12">
                            <div class="w-100 h-100 d-f fd-c rg-32 bc-w p-24 xl:p-32 bw-1 bs-s bcr-C6C6C6 r-24" <?=Util::iblockElementEditAddId($item)?>>
                                
                                <div class="f-18 sm:f-24 lg:f-24 ftw-5 lh-1 ff-ot"><?=$item->getName()?></div>

                                <div class="w-100 d-f fd-c rg-16 f-14 xl:f-16">

                                    <?if(!empty($item->getPreviewText())):?>
                                        <div class=""><?=$item->getPreviewText()?></div>
                                    <?endif;?>

                                    <?
                                    $text = $item->getText()->getAll();
                                    if(!empty($text)):?>
                                        <div class="w-100">
                                            <?foreach($text as $tValue):?>
                                                <a href="<?=$tValue->getValue()?>" class="ftw-6 h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3 ws-nw"><?=$tValue->getDescription()?></a>
                                            <?endforeach;?>
                                        </div>
                                    <?endif;?>

                                    <?
                                    $elDop = $item->getEl()->getAll();
                                    if(!empty($elDop)):
                                        $elList = [];

                                        foreach($item->getEl()->getAll() as $elDopItem)
                                        {
                                            $elList[] = $elDopItem->getValue();
                                        }

                                        $elConList = Util::getElementsObject($textSiteTableString, [
                                            'select' => ['NAME', 'PREVIEW_TEXT', 'link'],
                                            'filter' => ['ID' => $elList],
                                        ]);
                                        if(!empty($elConList)):?>
                                            <div class="w-100 d-f fd-c rg-16">
                                                <?foreach($elConList as $eCL):?>
                                                    <div class="">
                                                        <span class="ftw-6"><?=$eCL->getName()?></span> - <span><?=$eCL->getPreviewText()?></span><?if(!empty($eCL->getLink())):?>, <a href="<?=$eCL->getLink()->getValue()?>" class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3"><?=$eCL->getLink()->getDescription()?></a><?endif;?>
                                                    </div>
                                                <?endforeach?>
                                            </div>
                                        <?endif;?>
                                    <?endif;?>

                                </div>
                            </div>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
        </section>

    <?endif?>
<?endif?>