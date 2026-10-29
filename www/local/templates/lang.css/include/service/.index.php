<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["sectionId" => 16,"path" => "main/.banner.php",'edge' => 'n'],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["path" => "service/.item.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => 15,"path" => "service/.step.work.bg.php"],
    false, ['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => 6,"path" => "main/.work.expert.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId"=>13,"path"=>"main/.review.php"],
    false,['HIDE_ICONS'=>'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId"=>11,"path"=>"main/.work.close.php"],
    false,['HIDE_ICONS'=>'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId"=>19,"path"=>"main/.banner.bottom.php"],
    false,['HIDE_ICONS'=>'Y']
);?>