<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<?if(!empty($element->getOprog())):
    $secObTitle = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $element->getOprog()->getValue()]]);

    if(!empty($secObTitle)):
        $startList = Util::getElementsObject($textSiteTableString, [
            'select' => ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'link', 'text'],
            'filter' => ['IBLOCK_SECTION_ID' => $secObTitle->getId()],
            'limit' => 2
        ]);
        ?>

        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="<?=comp::get("row")?> rg-32 jc-c">

                    <div class="w-100 pr-16 pl-16 d-f jc-c fg-0 fs-0">
                        <h2 class="xl:w-8/12 mb-0 f-24 md:f-32 xl:f-42 ftw-5 ff-ot ta-c" <?=Util::iblockSectionEditAddId($secObTitle)?>><?=$secObTitle->getName()?></h2>
                    </div>

                    <?foreach($startList as $item):
                        $iPrev = CFile::GetPath($item->getPreviewPicture());
                        ?>
                        <div class="w-100 md:w-6/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                            <div class="w-100 md:h-100 xl:h-a xl:ar-2x1 rele r-20 bc-e5ebfa99 bp-c bgr-nr bgs-cr bi-load lazyload" data-bg="<?=$iPrev?>">
                                <div class="w-100 xl:w-7/12 h-100 p-32 c-w d-f fd-c rg-24 jc-c" <?=Util::iblockElementEditAdd($item)?>>
                                    <div class="f-18 lg:f-24 ftw-5 lh-1"><?=$item->getName()?></div>

                                    <?
                                    $text = $item->getText();
                                    if(!empty($text)):?>
                                        <div class="w-100">
                                            <?foreach($text->getAll() as $tValue):?>
                                                <div class="f-14 d-f cg-8">
                                                    <div class=""><?=$tValue->getValue()?></div>
                                                    <div class="ftw-5"><?=$tValue->getDescription()?></div>
                                                </div>
                                            <?endforeach;?>
                                        </div>
                                    <?endif;?>

                                    <?if(!empty($item->getPreviewText())):?>
                                        <div class="f-14 md:f-12 lg:f-14"><?=$item->getPreviewText()?></div>
                                    <?endif;?>

                                    <?
                                    $link = $item->getLink();
                                    if(!empty($link)):?>
                                        <div class="w-100 d-f jc-s">
                                            <a href="<?=$link->getValue()?>" class="<?=comp::get("btnPy")?>"><?=$link->getDescription()?></a>
                                        </div>
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