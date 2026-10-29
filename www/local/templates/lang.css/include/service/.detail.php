<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langBlog = "LangBlog{$lang}";
$langText = "LangText{$lang}";
$langService = "LangService{$lang}";
$langExample = "LangExample{$lang}";
$langReview = "LangReview{$lang}";

$service = Data::getElementObject($langService,
    ['NAME', 'ban', 'sum', 'time', 'orig', 'gost', 'dor', 'srav', 'edge', 'price', 'rev', 'exm', 'step', 'edg', 'unq', 'seo', 'faq', 'art'],
    ['CODE' => $arParams['code']]
);
?>

<?if(!empty($service->getBan()) && !empty($service->getBan()->getValue())):
    $infoListId = [];
    if(!empty($service->getSum())) {
        $infoListId[$service->getSum()->getValue()] = $service->getSum()->getIblockPropertyId();
    }
    if(!empty($service->getTime())) {
        $infoListId[$service->getTime()->getValue()] = $service->getTime()->getIblockPropertyId();
    }
    if(!empty($service->getOrig())) {
        $infoListId[$service->getOrig()->getValue()] = $service->getOrig()->getIblockPropertyId();
    }
    if(!empty($service->getGost())) {
        $infoListId[$service->getGost()->getValue()] = $service->getGost()->getIblockPropertyId();
    }
    if(!empty($service->getDor())) {
        $infoListId[$service->getDor()->getValue()] = $service->getDor()->getIblockPropertyId();
    }
    ?>
    <?$APPLICATION->IncludeComponent(
        "lang:block", "",
        ["elementId" => $service->getBan()->getValue(),"infolistid"=>$infoListId,"path" => "service/detail/.banner.php"],
        false,['HIDE_ICONS' => 'Y']
    );?>
<?endif?>

<?if(!empty($service->getSrav()) && !empty($service->getSrav()->getValue())):?>
    <?$APPLICATION->IncludeComponent(
        "lang:block", "",
        ["sectionId" => $service->getSrav()?->getValue(),"path" => "service/detail/.risk.php"],
        false,['HIDE_ICONS' => 'Y']
    );?>
<?endif?>

<?if(!empty($service->getEdge()) && !empty($service->getEdge()->getValue())):?>
    <?$APPLICATION->IncludeComponent(
        "lang:block", "",
        ["sectionId" => $service->getEdge()?->getValue(),"path" => "service/detail/.edge.php"],
        false,['HIDE_ICONS' => 'Y']
    );?>
<?endif?>

<?if(!empty($service->getPrice()) && !empty($service->getPrice()->getValue())):?>
    <?$APPLICATION->IncludeComponent(
        "lang:block", "",
        ["sectionId" => $service?->getPrice()?->getValue(),"path" => "service/detail/.price.php"],
        false,['HIDE_ICONS' => 'Y']
    );?>
<?endif?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["elementsId" => $service?->getRev()?->getValueList(),"path" => "service/detail/.review.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["elementsId" => $service?->getExm()?->getValueList(),"path" => "service/detail/.example.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => $service?->getStep()?->getValue(),"path" => "service/.step.work.bg.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId" => $service?->getEdg()?->getValue(),"path" => "service/detail/.edge.2.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId" => $service?->getUnq()?->getValue(),"path" => "service/detail/.unique.banner.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId" => $service?->getSeo()?->getValue(),"path" => "main/.text.detail.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => $service?->getFaq()?->getValue(),"path" => "main/.faq.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementsId" => $service?->getArt()?->getValueList(),"path" => "service/detail/.blog.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?
$seo = Data::getSeoPropertyElement($langService, $service->getId());

$APPLICATION->SetTitle(strip_tags($seo['ELEMENT_PAGE_TITLE']));
$APPLICATION->SetPageProperty("title", strip_tags($seo['ELEMENT_META_TITLE']));
$APPLICATION->SetPageProperty("description", strip_tags($seo['ELEMENT_META_DESCRIPTION']));
$APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
$APPLICATION->AddChainItem(strip_tags($service->getName()), "");
?>