<?
use Lang\Region;
use Lang\Main\Iblock\Util;

$seoSection = Util::getSeoPropertyElement($structureTableString, $element->getId());

$APPLICATION->SetTitle(strip_tags($seoSection['SECTION_PAGE_TITLE']));
$APPLICATION->SetPageProperty("title", strip_tags($seoSection['SECTION_META_TITLE']));
$APPLICATION->SetPageProperty("description", strip_tags($seoSection['SECTION_META_DESCRIPTION']));
$APPLICATION->SetPageProperty("keywords", strip_tags($seoSection['SECTION_META_KEYWORDS']));


// $indexSection = Util::getDetailPageUrl($fakIblock['LIST_PAGE_URL'], [], true, "S");

$codeList = Region::getUrlCode();
$sectionIndex = Util::getSectionObject($structureTableString, ['select' => ['NAME', 'UF_LINK'], 'filter' => ['CODE' => $codeList[0]]]);
$APPLICATION->AddChainItem($sectionIndex->getName(), $sectionIndex->getUfLink());

$APPLICATION->AddChainItem($section->getName(), Util::getDetailPageUrl($fakIblock['SECTION_PAGE_URL'], $section, true, "S"));
$APPLICATION->AddChainItem($element->getName(), Util::getDetailPageUrl($fakIblock['DETAIL_PAGE_URL'], $element));