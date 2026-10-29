<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["elementId" => 110,"path" => ".about/.banner.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["sectionId" => 28,"path" => "service/detail/.edge.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["sectionId" => 31,"titleElementId" => 132, "path" => ".about/.help.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block", "",
    ["elementId" => 133, "path" => ".about/.example.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId" => 464,"btnAction"=>"reg","path"=>"service/detail/.edge.2.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId"=>30,"path"=>"main/.work.close.php"],
    false,['HIDE_ICONS'=>'Y']
);?>


<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId"=>32,"path"=>".about/.edge.3.php"],
    false,['HIDE_ICONS'=>'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId"=>138,"path"=>".about/.form.php"],
    false,['HIDE_ICONS'=>'Y']
);?>