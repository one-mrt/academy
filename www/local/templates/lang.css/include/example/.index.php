<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["elementId" => 109,"path" => ".example/.banner.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["path" => ".example/.list.php"],
    false,['HIDE_ICONS' => 'Y']
);?>