<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$page = $APPLICATION->GetCurPage(false);
$logo = Data::getElementObject($langText, ['NAME', 'svg', 'link'], ['ID' => 5]);

if(!empty($logo) && !empty($logo->getSvg())):
    $docRoot = $_SERVER['DOCUMENT_ROOT'];
    $svgPath = \CFile::GetPath($logo->getSvg()->getValue());
    $svg = file_get_contents("{$docRoot}{$svgPath}");

    if($page != '/'):?>
        <a href="<?=$logo->getLink()->getValue()?>" title="<?=strip_tags($logo->getName())?>" class="d-f ai-c" <?=Data::iblockElementEditAddId($logo)?>><?=$svg?></a>
    <?else:?>
        <div title="<?=strip_tags($logo->getName())?>" class="d-f ai-c" <?=Data::iblockElementEditAddId($logo)?>><?=$svg?></div>
    <?endif;?>
<?else:?>
    <a href="/" class="pl-16 xxl:pl-0 h:c-py tn-all-3 ff-ud ftw-6 tt-l f-24">Лого</a>
<?endif;?>