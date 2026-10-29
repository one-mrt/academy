<?
use Lang\Main\Iblock\Util;

$seoSection = Util::getSeoPropertySection($structureTableString, $section->getId());

$APPLICATION->SetTitle(strip_tags($seoSection['SECTION_PAGE_TITLE']));
$APPLICATION->SetPageProperty("title", strip_tags($seoSection['SECTION_META_TITLE']));
$APPLICATION->SetPageProperty("description", strip_tags($seoSection['SECTION_META_DESCRIPTION']));
$APPLICATION->SetPageProperty("keywords", strip_tags($seoSection['SECTION_META_KEYWORDS']));


// $href = Util::getDetailPageUrl($naprIblock['SECTION_PAGE_URL'], $section, true, "S");

$APPLICATION->AddChainItem($section->getName(), $section->getUfLink());