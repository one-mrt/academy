<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($section) && (!empty($section->getUfText()) || !empty($section->getUfHtml()))):?>
    <section class="w-100">
        <div class="<?=comp::get("container")?> d-f jc-c">
            <?if(!empty($section->getUfText())):
                $textEl = Util::getElementObject($textSiteTableString, ['PREVIEW_TEXT'], ['ID' => $section->getUfText()]);
                
                if(!empty($textEl->getPreviewText())):?>
                    <div class="w-100 pr-16 pl-16 fg-0 fs-0 f-14 lg:f-16 of-a" <?=Util::iblockElementEditAddId($textEl)?>><?=$textEl->getPreviewText()?></div>
                <?endif;?>
            <?elseif(!empty($section->getUfHtml())):
                $file = \CFile::GetPath($section->getUfHtml());
                ?>
                <div class="w-100 pr-16 pl-16 fg-0 fs-0 f-14 lg:f-16 of-a"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$file}")?></div>
            <?endif;?>
        </div>
    </section>
<?endif;?>