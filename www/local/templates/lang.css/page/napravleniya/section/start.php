<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($section) && !empty($section->getUfStart())):
    $startList = Util::getElementsObject($naprTableString, [
        'order' => ["SORT" => 'ASC', 'ID' => 'DESC'],
        'select' => ['NAME', 'PREVIEW_PICTURE', 'IBLOCK_SECTION_ID', 'CODE', 'svg.FILE', 'forma.ITEM', 'spec.ELEMENT', 'time', 'status', 'sum', 'link'],
        'filter' => ["ID" => $section->getUfStart()]
    ]);
    if(!empty($startList) && !empty($startList->getIdList())):
        $title = Util::getElementObject($textTableString, ['NAME', 'PREVIEW_TEXT'], ['ID' => 204027]);
        $bgCard = Util::getElementObject($textTableString, ['PREVIEW_PICTURE'], ["ID" => 173878]);
        ?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="w-100 d-f fd-c rg-32 xl:rg-48 bc-dt c-w r-24 xl:r-48 p-32 xl:p-64">

                    <?if(!empty($title)):?>
                        <div class="w-100 d-f fd-c rg-16 ai-c ta-c" <?=Util::iblockElementEditAddId($title)?>>
                            <h2 class="lg:w-100 mb-0 f-24 md:f-32 xl:f-55 ta-c ftw-5 lh-1"><?=$title->getName()?></h2>
                            <?if(!empty($title->getPreviewText())):?>
                                <div class="lg:w-5/12 f-14 xl:f-16"><?=$title->getPreviewText()?></div>
                            <?endif;?>
                        </div>
                    <?endif;?>

                    <div class="<?=comp::get("row")?> rg-32">
                        <?foreach($startList as $sItem):
                            $naprPrev = \CFile::GetPath($bgCard->getPreviewPicture());
                            $detailLink = !empty($sItem->getLink()) ? $sItem->getLink()->getValue() : Util::getDetailPageUrl($naprIblock->getDetailPageUrl(), $sItem);
                            ?>
                            <div class="w-100 md:w-6/12 xl:w-3/12 pr-16 pl-16 fg-0 fs-0">
                                <a href="<?=$detailLink?>" class="w-100 h-100 p-32 d-f fd-c rg-24 r-20 bw-1 bs-s bcr-C6C6C6 c-dt h:bxs-3 h:tm--ty5 tn-all-3 bgs-cr bp-c bgr-nr bc-w bi-load lazyload"
                                    data-bg="<?=$naprPrev?>" <?=Util::iblockElementEditAddId($sItem)?>>
                                    
                                    <?if(!empty($sItem->getSpec())):?>
                                        <div class="d-f cg-8 fg-0 f-14 md:f-12 lg:f-14 lh-1 ftw-5"><?=$sItem->getSpec()->getElement()->getName()?></div>
                                    <?endif;?>

                                    <div class="w-100 d-f fd-c rg-16">
                                        <div class="f-18 xl:f-24 ftw-5 lh-1 fg-0"><?=$sItem->getName()?></div>
                                    </div>

                                    <div class="d-f fd-c rg-24 fg-1">
                                        <?
                                        $time = $sItem->getTime();
                                        $status = $sItem->getStatus();
                                        $sum = $sItem->getSum();

                                        if(!empty($time) || !empty($status) || !empty($sum)):?>
                                            <div class="d-f fd-c rg-4 jc-e f-14 lh-1 ws-nw fg-1">
                                                <?if(!empty($time)):?>
                                                    <div class="d-f cg-8">
                                                        <div class="c-8C8C8C">Длительность:</div>
                                                        <div class="ftw-5"><?=$time->getValue()?></div>
                                                    </div>
                                                <?endif;?>

                                                <?if(!empty($sum)):?>
                                                    <div class="d-f cg-8">
                                                        <div class="c-8C8C8C">Стоимость:</div>
                                                        <div class="ftw-5"><?=number_format($sum->getValue(), 0, '.', ' ')?> ₽</div>
                                                    </div>
                                                <?endif;?>

                                                <?if(!empty($status)):?>
                                                    <div class="d-f cg-8">
                                                        <div class="c-8C8C8C">Старт:</div>
                                                        <div class="ftw-5"><?=FormatDate('j F Y', strtotime($status->getValue()));?></div>
                                                    </div>
                                                <?endif;?>
                                            </div>
                                        <?endif;?>

                                        <div class="<?=comp::get("btnPy")?>">Подробнее</div>

                                    </div>
                                </a>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>