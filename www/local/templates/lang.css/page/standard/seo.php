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
    $seoSection = Util::getSeoPropertySection($structureTableString, $section->getId());

    $APPLICATION->SetTitle(strip_tags($seoSection['SECTION_PAGE_TITLE']));
    $APPLICATION->SetPageProperty("title", strip_tags($seoSection['SECTION_META_TITLE']));
    $APPLICATION->SetPageProperty("description", strip_tags($seoSection['SECTION_META_DESCRIPTION']));
    $APPLICATION->SetPageProperty("keywords", strip_tags($seoSection['SECTION_META_KEYWORDS']));

    $APPLICATION->AddChainItem($section->getName(), $section->getUfLink());
}

if(!empty($element))
{
    $seoElement = Util::getSeoPropertyElement($structureTableString, $element->getId());

    $APPLICATION->SetTitle(strip_tags($seoElement['ELEMENT_PAGE_TITLE']));
    $APPLICATION->SetPageProperty("title", strip_tags($seoElement['ELEMENT_META_TITLE']));
    $APPLICATION->SetPageProperty("description", strip_tags($seoElement['ELEMENT_META_DESCRIPTION']));
    $APPLICATION->SetPageProperty("keywords", strip_tags($seoElement['ELEMENT_META_KEYWORDS']));

    $APPLICATION->AddChainItem($element->getName(), $element->getLink()->getValue());
}