<?
use Lang\Main\Iblock\Util;

if(!empty($folderSection))
{
    if(!empty($folderSection->getUfLink()))
    {
        $APPLICATION->AddChainItem($folderSection->getName(), $folderSection->getUfLink());
    }
}

if(!empty($element))
{
    $seoElement = Util::getSeoPropertyElement($naprTableString, $element->getId());

    $APPLICATION->SetTitle(strip_tags($seoElement['ELEMENT_PAGE_TITLE']));
    $APPLICATION->SetPageProperty("title", strip_tags($seoElement['ELEMENT_META_TITLE']));
    $APPLICATION->SetPageProperty("description", strip_tags($seoElement['ELEMENT_META_DESCRIPTION']));
    $APPLICATION->SetPageProperty("keywords", strip_tags($seoElement['ELEMENT_META_KEYWORDS']));

    if(!empty($element->getIblockSectionId()))
    {
        $seoSection = Util::getSectionObject($naprTableString, [
            'select' => ['NAME', 'CODE', 'IBLOCK_SECTION_ID'],
            'filter' =>['ID' => $element->getIblockSectionId()]
        ]);
        if(!empty($seoSection))
        {
            $APPLICATION->AddChainItem($seoSection->getName(), Util::getDetailPageUrl($naprIblock->getSectionPageUrl(), $seoSection, true, "S"));
        }
    }

    $APPLICATION->AddChainItem($element->getName(), Util::getDetailPageUrl($naprIblock->getDetailPageUrl(), $element, true, "S"));
}