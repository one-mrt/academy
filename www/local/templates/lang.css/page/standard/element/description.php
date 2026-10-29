<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($element) && (!empty($element->getText()) || !empty($element->getHtml()))):

    if(!empty($element->getText())):
        $textEl = Util::getElementObject($textSiteTableString, ['PREVIEW_TEXT', 'DETAIL_TEXT'], ['ID' => $element->getText()->getValue()]);
    
        if(!empty($textEl) && (!empty($textEl->getPreviewText()) || !empty($textEl->getDetailText()))):?>
            <section class="w-100">
                <div class="<?=comp::get("container")?> d-f jc-c">
                    <div class="w-100 f-14 lg:f-16 of-a" <?=Util::iblockElementEditAddId($textEl)?>><?=$textEl->getPreviewText()?><?=$textEl->getDetailText()?></div>
                </div>
            </section>
        <?endif;?>
    <?elseif(!empty($element->getHtml())):
        $file = \CFile::GetPath($section->getHtml());
        ?>
        <section class="w-100">
            <div class="<?=comp::get("container")?> d-f jc-c">
                <div class="w-100 f-14 lg:f-16 of-a"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$file}")?></div>
            </div>
        </section>
    <?endif;?>
<?endif;?>