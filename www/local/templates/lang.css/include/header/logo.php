<?
use Lang\Region;
use Lang\Main\Iblock\Util;

$lang = Util::getLang();
$langText = "LangText{$lang}";
$region = Region::getCurrent(['select' => ["NAME"]]);
$logo = Util::getElement($langText, ['PREVIEW_PICTURE', 'l' => 'link.VALUE', 's' => 'svg.FILE.ID'], ['ID' => 173829]);

if(!empty($logo)):
    $logo['PREVIEW_PICTURE'] = \CFile::GetPath($logo['PREVIEW_PICTURE']);
    $logo['s'] = \CFile::GetPath($logo['s']);
    ?>

    <a href="<?=$logo['l']?>" title="<?=$region->getName()?>" class="d-f ai-c" <?=Util::iblockElementEditAddId($logo)?>>
        <?if(!empty($logo['s'])):?>
            <?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$logo['s']}")?>
        <?elseif(!empty($logo['PREVIEW_PICTURE'])):?>
            <img src="<?=$logo['PREVIEW_PICTURE']?>" class="w-100 h-a">
        <?endif;?>
    </a>

<?endif;?>