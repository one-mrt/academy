<?
use Lang\Main\Iblock\Util;

$bitrixComp->setFrameMode(true);

if(!empty(file_exists("{$templatePath}/page/{$folder}/{$sectionCode}/list.php")))
{
    @include_once "{$sectionCode}/list.php";
}
else
{
    Util::get404();
}