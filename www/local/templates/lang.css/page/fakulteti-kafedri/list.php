<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$fakTableString = "LangFak{$site}";
$rukFakTableString = "LangRukFak{$site}";
$naprTableString = "LangNapr{$site}";

$fakIblock = Util::getIblockObject($fakTableString, ['select' => ['NAME', 'LIST_PAGE_URL', 'SECTION_PAGE_URL', 'DETAIL_PAGE_URL']]);
$naprIblock = Util::getIblockObject($naprTableString, ['select' => ['DETAIL_PAGE_URL', 'SECTION_PAGE_URL']]);

$bitrixComp->setFrameMode(true);

if(!empty($fakIblock))
{
    @include_once "index/banner.php";
    @include_once "index/fak.php";
    @include_once "index/doc.php";
    @include_once "index/prog.php";
    @include_once "index/faq.php";
    @include_once "index/question.php";
    @include_once "index/seo.php";
}
else
{
    Util::get404();
}