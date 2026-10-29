<?
use Lang\Main\Iblock\Util;

$bitrixComp->setFrameMode(true);

if(empty($sectionCode))
{
    $naprTableString = "LangNapr{$site}";
    $section = Util::getSectionObject($naprTableString, ['select' => ['CODE', 'IBLOCK_SECTION_ID']]);
    $naprIblock = Util::getIblockObject($naprTableString, ['select' => ['DETAIL_PAGE_URL']]);

    if(!empty($section))
    {
        LocalRedirect(Util::getDetailPageUrl($naprIblock->getDetailPageUrl(), $section, true, 'S'));
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