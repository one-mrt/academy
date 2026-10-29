<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;


if(!empty($element) && !empty($element->getContact())):
    $conReq = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $element->getContact()->getValue()]]);

    $conEl = Util::getElementsObject($textSiteTableString, [
        'select' => ['NAME', 'PREVIEW_TEXT', 'text'],
        'filter' => ['IBLOCK_SECTION_ID' => $element->getContact()->getValue()],
    ]);

    if(!empty($conEl->getIdList())):?>
        <section class="w-100 pt-16">
            <div class="<?=comp::get("container")?> d-f fd-c ai-c rg-32">

                <?if(!empty($conReq)):?>
                    <h2 class="<?=comp::get("titleh2")?>" <?=Util::iblockSectionEditAddId($conReq)?>><?=$conReq->getName()?></h2>
                <?endif;?>

                <div class="w-100 h-100 p-32 xl:p-48 d-f fd-c rg-24 r-24 bc-w bw-1 bs-s bcr-E5E5E5">
                    <div class="w-100">
                        <div class="<?=comp::get("row")?> rg-32">
                            <?
                            $conIndex = 0;
                            foreach($conEl as $con):
                                $conIndex++;
                                ?>
                                <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                    <div class="w-100 h-100 d-f fd-c rg-16 <?=($conIndex <= 2) ? "lg:pb-32 lg:bbw-1 lg:bbs-s" : ""?> bbc-E5E5E5" <?=Util::iblockElementEditAddId($con)?>>
                                        <div class="w-100 d-f fd-c ai-s rg-4">
                                            <div class="ftw-6"><?=$con->getName()?></div>

                                            <?if(!empty($con->getText()->getAll())):?>
                                                <div class="d-f fd-c">
                                                    <?foreach($con->getText()->getAll() as $phone):?>
                                                        <a class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3" href="<?=$phone->getValue()?>"><?=$phone->getDescription()?></a>
                                                    <?endforeach;?>
                                                </div>
                                            <?endif;?>
                                        </div>

                                        <?if(!empty($con->getPreviewText())):?>
                                            <div class="d-f fd-c ai-s"><?=$con->getPreviewText()?></div>
                                        <?endif;?>
                                    </div>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>