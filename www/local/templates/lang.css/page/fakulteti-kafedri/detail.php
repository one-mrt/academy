<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$bitrixComp->setFrameMode(true);

$fakTableString = "LangFak{$site}";
$rukFakTableString = "LangRukFak{$site}";
$naprTableString = "LangNapr{$site}";

$fakIblock = Util::getIblockObject($fakTableString, ['select' => ['NAME', 'LIST_PAGE_URL', 'SECTION_PAGE_URL', 'DETAIL_PAGE_URL']]);
$naprIblock = Util::getIblockObject($naprTableString, ['select' => ['DETAIL_PAGE_URL', 'SECTION_PAGE_URL']]);
$section = Util::getSectionObject($fakTableString, ['select' => ['NAME', 'CODE', 'IBLOCK_SECTION_ID'], 'filter' => ['CODE' => $sectionCode]]);

if(!empty($section))
{
    $element = Util::getElementObject($fakTableString,
        ['NAME', 'CODE', 'DETAIL_TEXT', 'IBLOCK_SECTION_ID', 'ruk', 'nap.ELEMENT', 'zav'],
        ['CODE' => $elementCode]
    );

    if(!empty($element))
    {
        @include_once "detail/description.php";
        @include_once "detail/ruk.php";
        @include_once "detail/programm.php";
        @include_once "detail/form.php";
        @include_once "detail/seo.php";
    }
    else
    {
        Util::get404();
    }
}
else
{
    Util::get404();
}