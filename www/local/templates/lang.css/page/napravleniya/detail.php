<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$bitrixComp->setFrameMode(true);

$naprTableString = "LangNapr{$site}";
$naprIblock = Util::getIblockObject($naprTableString, ['select' => ['ID', "LIST_PAGE_URL", 'DETAIL_PAGE_URL', 'SECTION_PAGE_URL']]);

$element = Util::getElementObject(
    $naprTableString,
    [
        'ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'DETAIL_TEXT', 'DETAIL_PICTURE',
        'time', 'status', 'sum', 'mode', 'lBan', 'tBan.ITEM', 'lv', 'forma.ITEM',
        'res', 'ob', 'doc', 'prog', 'work', 'rez', 'form', 'faq', 'oprog',
        'contact', 'about', 'vi', 'ud', 'link'
    ],
    ['CODE' => $elementCode]
);

if(!empty($element) && empty($element->getLink()))
{
    if(!empty($element->getTban()))
    {
        if($element->getTban()->getItem()->getXmlId() == "old")
        {
            @include_once("detail/bannerOld.php");
        }
        else
        {
            @include_once("detail/banner.php");
        }
    }
    else
    {
        @include_once("detail/banner.php");
    }

    @include_once("detail/about.php");
    @include_once("detail/ud.php");
    @include_once("detail/ob.php");
    @include_once("detail/prog.php");
    @include_once("detail/work.php");
    @include_once("detail/rez.php");
    // @include_once("detail/form.php");
    @include_once("detail/faq.php");
    @include_once("detail/oprog.php");
    @include_once("detail/contact.php");
    @include_once "detail/question.php";
    @include_once("detail/seo.php");
}
else
{
    Util::get404();
}