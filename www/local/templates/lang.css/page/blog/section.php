<?
global $APPLICATION;
global $filterBlog;

use Lang\Main\Iblock\Util;

$page = $APPLICATION->GetCurPage(false);
$newsTableString = "LangBlog{$site}";
$newsIblock = Util::getIblockObject($newsTableString, ['select' => ['ID', "LIST_PAGE_URL", 'DETAIL_PAGE_URL', 'SECTION_PAGE_URL']]);
$section = Util::getSectionObject($newsTableString, ['select' => ['NAME', 'CODE', 'IBLOCK_SECTION_ID'], 'filter' => ['CODE' => $sectionCode]]);

$bitrixComp->setFrameMode(true);

if(empty($section))
{
    $element = Util::getElementObject($newsTableString,
        ['NAME', 'CODE', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DETAIL_TEXT', 'SHOW_COUNTER', 'IBLOCK_SECTION_ID', 'TIMESTAMP_X', "ACTIVE_FROM", "DATE_CREATE", 'GALLERY_LINK', 'MORE_PHOTO'],
        ['CODE' => $sectionCode]
    );
    if(!empty($element))
    {
        $section = Util::getSectionObject($newsTableString, ['select' => ['NAME', 'CODE', "IBLOCK_SECTION_ID"], 'filter' => ['ID' => $element->getIblockSectionId()]]);
        @include_once "detail.php";
    }
    else
    {
        Util::get404();
    }
}
else
{
    @include_once "index/banner.php";
    @include_once "index/news.php";
    @include_once "index/question.php";
    @include_once "section/seo.php";
}