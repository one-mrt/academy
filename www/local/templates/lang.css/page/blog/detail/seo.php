<?
use Bitrix\Main\Context;
use Lang\Main\Iblock\Util;
use Bitrix\Main\Type\DateTime;

if(!empty($folderSection))
{
    if(!empty($folderSection->getUfLink()))
    {
        $APPLICATION->AddChainItem($folderSection->getName(), $folderSection->getUfLink());
    }
}

if(!empty($section))
{
    $APPLICATION->AddChainItem(mb_ucfirst(mb_strtolower(strip_tags($section->getName()))), Util::getDetailPageUrl($newsIblock->getSectionPageUrl(), $section, true, "S"));
}

$seo = Util::getSeoPropertyElement($newsTableString, $element->getId());
$detailPageUrl = Util::getDetailPageUrl($newsIblock->getDetailPageUrl(), $element);
Context::getCurrent()->getResponse()->setLastModified(DateTime::createFromUserTime($element->getTimestampX()));

$APPLICATION->AddChainItem(mb_ucfirst(mb_strtolower(strip_tags($element->getName()))), $detailPageUrl);
$APPLICATION->SetTitle($seo['ELEMENT_PAGE_TITLE']);
$APPLICATION->SetPageProperty("title", $seo['ELEMENT_META_TITLE']);
$APPLICATION->SetPageProperty("description", mb_ucfirst(mb_strtolower(strip_tags($seo['ELEMENT_META_DESCRIPTION']))));
$APPLICATION->SetPageProperty("keywords", mb_ucfirst(mb_strtolower(strip_tags($seo['ELEMENT_META_KEYWORDS']))));