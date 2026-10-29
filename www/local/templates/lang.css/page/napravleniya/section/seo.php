<?
use Lang\Main\Iblock\Util;

if(!empty($folderSection))
{
    if(!empty($folderSection->getUfLink()))
    {
        $APPLICATION->AddChainItem($folderSection->getName(), $folderSection->getUfLink());
    }
}

if(!empty($section))
{
    $seoSection = Util::getSeoPropertySection($naprTableString, $section->getId());

    $APPLICATION->SetTitle(strip_tags($seoSection['SECTION_PAGE_TITLE']));
    $APPLICATION->SetPageProperty("title", strip_tags($seoSection['SECTION_META_TITLE']));
    $APPLICATION->SetPageProperty("description", strip_tags($seoSection['SECTION_META_DESCRIPTION']));
    $APPLICATION->SetPageProperty("keywords", strip_tags($seoSection['SECTION_META_KEYWORDS']));

    $APPLICATION->AddChainItem($section->getName(), Util::getDetailPageUrl($naprIblock->getSectionPageUrl(), $section, true, "S"));
}