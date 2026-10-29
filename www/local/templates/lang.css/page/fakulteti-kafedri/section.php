<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$bitrixComp->setFrameMode(true);

$fakTableString = "LangFak{$site}";
$rukFakTableString = "LangRukFak{$site}";
$naprTableString = "LangNapr{$site}";

$fakIblock = Util::getIblockObject($fakTableString, ['select' => ['NAME', 'LIST_PAGE_URL', 'SECTION_PAGE_URL', 'DETAIL_PAGE_URL']]);
$naprIblock = Util::getIblockObject($naprTableString, ['select' => ['DETAIL_PAGE_URL', 'SECTION_PAGE_URL']]);
$section = Util::getSectionObject($fakTableString, ['select' => ['NAME', "DESCRIPTION", 'UF_RUK'], 'filter' => ['CODE' => $sectionCode]]);

if(!empty($section))
{
    $kafList = Util::getElementsObject($fakTableString, [
        'select' => ['NAME', 'CODE', 'IBLOCK_SECTION_ID', 'nap.ELEMENT', 'zav', 'map', 'email'],
        'filter' => ['IBLOCK_SECTION_ID' => $section->getId()]
    ]);

    @include_once "section/description.php";
    @include_once "section/ruk.php";
    @include_once "section/kaf.php";
    @include_once "section/programm.php";
    @include_once "section/form.php";
    @include_once "section/seo.php";
}
else
{
    Util::get404();
}
