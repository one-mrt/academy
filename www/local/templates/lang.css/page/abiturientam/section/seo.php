<?
use Lang\Main\Iblock\Util;

$seoSection = Util::getSeoPropertySection($structureTableString, $section->getId());

$APPLICATION->SetTitle(strip_tags($seoSection['SECTION_PAGE_TITLE']));
$APPLICATION->SetPageProperty("title", strip_tags($seoSection['SECTION_META_TITLE']));
$APPLICATION->SetPageProperty("description", strip_tags($seoSection['SECTION_META_DESCRIPTION']));
$APPLICATION->SetPageProperty("keywords", strip_tags($seoSection['SECTION_META_KEYWORDS']));

if(!empty($folderSection))
{
    if(!empty($folderSection->getUfLink()))
    {
        $APPLICATION->AddChainItem($folderSection->getName(), $folderSection->getUfLink());
    }
}

$APPLICATION->AddChainItem($section->getName(), $section->getUfLink());