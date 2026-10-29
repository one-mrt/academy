<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$bitrixComp->setFrameMode(true);

$naprTableString = "LangNapr{$site}";
$naprIblock = Util::getIblockObject($naprTableString, ['select' => ['ID', "LIST_PAGE_URL", 'DETAIL_PAGE_URL', 'SECTION_PAGE_URL']]);

$section = Util::getSectionObject($naprTableString, [
    'select' => [
        'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'DEPTH_LEVEL',
        'UF_BAN', 'UF_BB', 'UF_COL', 'UF_TITLE', 'UF_DOC', 'UF_START', 'UF_ABOUT',
        'UF_STEP', 'UF_CRE', 'UF_NEWS', 'UF_DPO'
    ],
    'filter' => ['CODE' => $sectionCode]
]);

if(!empty($section))
{
    @include_once('section/banner.php');
    @include_once('section/about.php');
    @include_once('section/napr.php');
    @include_once('section/step.php');
    @include_once('section/doc.php');
    @include_once('section/start.php');
    @include_once('section/credit.php');
    
    if(!empty($section->getUfNews()))
    {
        @include_once('section/news.php');
    }

    @include_once "section/question.php";
    @include_once "section/seo.php";
}
else
{
    Util::get404();
}