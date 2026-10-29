<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$bitrixComp->setFrameMode(true);

if(!empty(file_exists("{$templatePath}/page/{$folder}/{$sectionCode}/list.php")))
{
    @include_once "{$sectionCode}/list.php";
}
else
{
    @include_once "section/banner.php";
    @include_once "section/info.php";
    @include_once "section/hod.php";
    @include_once "section/form.php";
    @include_once "section/contact.php";
    @include_once "section/doc.php";
    @include_once "section/question.php";
}

@include_once "section/seo.php";