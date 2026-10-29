<?if (!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;

use Bitrix\Main\Loader;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

Loader::includeModule('iblock');
Loader::includeModule('lang.css');
Loader::includeModule('lang.iblock');
Loader::includeModule('lang.academy');

$app = $APPLICATION;
$pathCss = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;
$templateFoderImage = SITE_TEMPLATE_PATH . "/img";
$page = $APPLICATION->GetCurPage(false);
$lang = Data::getLang();
$langText = "LangText{$lang}";
$user = CurrentUser::get();

// Loader::includeModule('im');
// Loader::includeModule('sale');
// Loader::includeModule('catalog');
?>
<!DOCTYPE html>
    <html lang="<?=LANGUAGE_ID?>" class="w-100 mnh-100 d-f fd-c sb-s ff-mn <?=comp::get("reboot")?>" xml:lang="<?=LANGUAGE_ID?>" prefix="og: http://ogp.me/ns#">

    <head>
        <?$APPLICATION->ShowHead();?>
        <title><?$APPLICATION->ShowTitle()?></title>

        <meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <meta name="format-detection" content="telephone=no">
        <meta name="robots" content="index,follow,archive"/>
        <link rel="canonical" href="https://<?=SITE_SERVER_NAME?><?=$page?>">
        <link rel="shortcut icon" href="<?=$templateFoderImage?>/favicon.svg">

        <?
        @include_once 'head/open.graph.php';
        @include "{$pathCss}/php.css/script.ext.php";
        @include "{$pathCss}/php.css/style.ext.php";
        @include_once "php.css/init.php";
        ?>
        
        <?if($user->getId() > 0):?>
            <script class=""><?=file_get_contents("{$pathCss}/js/main/modal.new.js")?></script>
            <script><?=file_get_contents("{$pathCss}/js/main/modal.js")?></script>
        <?endif;?>

    </head>

    <body class="w-100 mnh-100 d-f fd-c jc-b fg-1 sb-s ai-c m-0 p-0 c-dt lh-n bc-F4F4FD" id="lang-body--scroll-top">
        <?//echo '<pre>', print_r($user->getUserGroups(), 1), '</pre>'?>
        
        <?if(in_array(13, $user->getUserGroups()) || in_array(0, $user->getUserGroups())):?><section class="w-100"><?$app->ShowPanel()?></section><?endif;?>

        <?if($page == '/'):?><h1 class="d-n"><?=$app->ShowTitle(false)?></h1><?endif;?>

        <header class="w-100 fg-0 tn-all-3 pn-sy t-16 mt-16 z-2">
            <div class="<?=comp::get('container-full')?>">
                <div class="w-100 pt-24 pr-24 pb-24 pl-24 xxl:pr-0 xxl:pl-0 r-16 bw-1 bs-s bcr-c5c5c566 pn-r">

                    <?
                    $headerBg = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => 1]);
                    if(!empty($headerBg) && !empty($headerBg->getPreviewPicture())):?>
                        <?=Data::getImageId($headerBg->getPreviewPicture(), [
                            "alt" => strip_tags($headerBg->getName()),
                            "fetchpriority" => "high",
                            "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 w-100 h-100 r-16 oft-cv op-c bf-b16",
                        ]);?>
                    <?endif?>

                    <div class="<?=comp::get('container-p')?> pn-r">
                        <div class="lang-header--main w-100">
                            <div class="<?=comp::get("row")?>">

                                <div class="w-4/12 md:w-2/12 <?=comp::get("setcol")?> d-f ai-c">
                                    <?$APPLICATION->IncludeComponent(
                                        "lang:block","",
                                        ["path" => ".header/.logo.php"],
                                        false,['HIDE_ICONS' => 'Y']
                                    );?>
                                </div>

                                <div class="w-6/12 md:w-7/12 <?=comp::get("setcol")?> d-n md:d-f ai-c">
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:menu", 
                                        "lang.top",
                                        array(
                                            "ROOT_MENU_TYPE" => "top",
                                            "MAX_LEVEL" => "1",
                                            "USE_EXT" => "N",
                                            "MENU_CACHE_TYPE" => "A",
                                            "MENU_CACHE_TIME" => "3600",
                                            "MENU_CACHE_USE_GROUPS" => "Y",
                                            "MENU_CACHE_GET_VARS" => [],
                                            "CHILD_MENU_TYPE" => "",
                                            "DELAY" => "N",
                                            "ALLOW_MULTI_SELECT" => "N"
                                        ),
                                        false
                                    );?>
                                </div>

                                <div class="w-8/12 md:w-3/12 <?=comp::get("setcol")?> d-f jc-e cg-32">
                                    <?$APPLICATION->IncludeComponent(
                                        "lang:block","",
                                        ["path" => ".header/.user.php"],
                                        false,['HIDE_ICONS' => 'Y']
                                    );?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="w-100 d-f fd-c fg-1 mt-16">
            <section class="w-100 d-f fd-c fg-1 g-32 lg:rg-64 pb-32 xl:pb-64">