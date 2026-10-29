<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<h1 class="d-n"><?=$APPLICATION->ShowTitle(false)?></h1>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId" => 140,"path" => ".expert/.banner.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => 35,"path" => ".expert/.edge.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => 36,"path" => ".expert/.step.work.bg.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => 37,"path" => ".expert/.sum.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["sectionId" => 38,"path" => ".expert/.edge.3.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["titleElementId" => 197, "sectionId" => 39,"path" => ".expert/.task.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["titleElementId" => 196, 'elementsId' => [181,182,185,183],"path" => ".expert/.review.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ['sectionId' => 40,"path" => "main/.faq.php"],
    false,['HIDE_ICONS' => 'Y']
);?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId" => 190,"path" => ".expert/.banner.bottom.php"],
    false,['HIDE_ICONS' => 'Y']
);?>