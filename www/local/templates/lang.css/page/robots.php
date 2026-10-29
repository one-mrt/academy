<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header("Content-Type: text/plain");

use Lang\Region;
use Bitrix\Main\Loader;

Loader::includeModule('lang.region');


$region = Region::getCurrent(['select' => ['rob', 'domain']]);

if(!empty($region))
{
    $text = unserialize($region->getRob()->getValue());
    echo $text['TEXT'];
    echo PHP_EOL;

    $region->getDomain()->getValue();

    echo "Host: {$region->getDomain()->getValue()}" . PHP_EOL;
    echo "Sitemap: {$region->getDomain()->getValue()}/sitemap.xml";
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>