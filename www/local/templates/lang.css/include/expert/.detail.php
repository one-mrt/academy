<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langExpert = "LangExpert{$lang}";

$pageExpert = Data::getElementObject($langExpert,
    [
        'NAME', 'ban.VALUE', 'num.VALUE', 'step.VALUE',
        'sum.VALUE', 'edge.VALUE', 'sea.VALUE', 'rev.VALUE',
        'faq.VALUE', 'exp.VALUE'
    ],
    ['CODE' => $arParams['code']]
);
?>

<h1 class="d-n"><?=$APPLICATION->ShowTitle(false)?></h1>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId" => $pageExpert?->getBan()?->getValue(),"path" => ".expert/.banner.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => $pageExpert?->getNum()?->getValue(),"path" => ".expert/.edge.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => $pageExpert?->getStep()?->getValue(),"path" => ".expert/.step.work.bg.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => $pageExpert?->getSum()?->getValue(),"path" => ".expert/.sum.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => $pageExpert?->getEdge()?->getValue(),"path" => ".expert/.edge.3.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["titleElementId" => 197, "sectionId" => $pageExpert?->getSea()?->getValue(),"path" => ".expert/.task.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["titleElementId" => 196, 'elementsId' => $pageExpert?->getRev()?->getValueList(),"path" => ".expert/.review.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId" => $pageExpert?->getExp()?->getValue(),"path" => ".expert/.banner.bottom.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?
$seo = Data::getSeoPropertyElement($langExpert, $pageExpert->getId());

$APPLICATION->SetTitle(strip_tags($seo['ELEMENT_PAGE_TITLE']));
$APPLICATION->SetPageProperty("title", strip_tags($seo['ELEMENT_META_TITLE']));
$APPLICATION->SetPageProperty("description", strip_tags($seo['ELEMENT_META_DESCRIPTION']));
$APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
$APPLICATION->AddChainItem(strip_tags($pageExpert->getName()), "");
?>