<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["sectionId" => 24,"path" => ".faq/.list.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["elementId" => 107,"path" => ".faq/.form.php"],
    false,['HIDE_ICONS' => 'Y']
);?>