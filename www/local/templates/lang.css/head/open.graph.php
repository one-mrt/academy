<?
    $rsSites = CSite::GetByID(SITE_ID);
    $dataSite = $rsSites->Fetch();
?>

<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#665249">
<meta property="og:title" content="<?$APPLICATION->ShowTitle()?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?=$dataSite['SITE_NAME']?>">
<meta property="og:url" content="https://<?=$dataSite['SERVER_NAME']?>/">
<meta property="og:description" content="<?$APPLICATION->GetProperty("description");?>">
<meta property="og:locale" content="ru_RU">
<meta property="og:image" content="https://<?=$dataSite['SERVER_NAME']?><?=$templateFoderImage?>/bg.full.webp?<?=md5_file($_SERVER['DOCUMENT_ROOT'] . $templateFoderImage . '/bg.full.webp')?>"/>
<meta property="og:image:width" content="1920"/>
<meta property="og:image:height" content="1080"/>
<meta property="og:image:type" content="image/jpeg">
<meta name="twitter:card" content="summary_large_image">