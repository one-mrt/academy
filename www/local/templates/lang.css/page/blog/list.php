<?
global $APPLICATION;
global $filterBlog;

use Lang\Main\Iblock\Util;

$page = $APPLICATION->GetCurPage(false);
$newsTableString = "LangBlog{$site}";
$newsIblock = Util::getIblockObject($newsTableString, ['select' => ['ID', "LIST_PAGE_URL", 'DETAIL_PAGE_URL', 'SECTION_PAGE_URL']]);

$bitrixComp->setFrameMode(true);

@include_once "index/banner.php";
@include_once "index/news.php";
@include_once "index/question.php";
@include_once "index/seo.php";