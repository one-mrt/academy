<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";
?>

<section class="w-100">
    <div class="<?=comp::get("container")?>">
        <div class="<?=comp::get("row")?> rg-32">
            
            <?if($arParams['param']['breadcrumb'] == 'y'):?>
                <div class="w-100 <?=comp::get('setcol')?>">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:breadcrumb",
                        "breadcrumb.dark",
                        [
                            "PATH" => "",
                            "SITE_ID" => SITE_ID,
                            "START_FROM" => "0"
                        ],
                        false,
                        ['HIDE_ICONS' => 'Y']
                    );?>
                </div>
            <?endif;?>

            <?$APPLICATION->IncludeComponent(
                "lang:block","",
                ["bannerId" => 239,"path" => "order/.sidebar.php"],
                false,['HIDE_ICONS' => 'Y']
            );?>

            <div class="w-100 xl:w-8/12 <?=comp::get('setcol')?>">
                <div class="w-100 h-100">
                    <?
                    /*$image = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => 247]);
                    if(!empty($image) && !empty($image->getPreviewPicture())) {
                        echo Data::getImageId($image->getPreviewPicture(), [
                            "alt" => strip_tags($image->getName()),
                            "fetchpriority" => "high",
                            "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
                        ]);
                    }*/
                    ?>
                    <div class="w-100 d-f fd-c rg-32">
                        <?@include_once($arParams['content'])?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<?if($arParams['param']['bannerBottom'] == 'y'):?>
    <?$APPLICATION->IncludeComponent(
        "lang:block","",
        ["elementId" => 238,"path" => "order/.banner.bottom.php"],
        false,['HIDE_ICONS' => 'Y']
    );?>
<?endif;?>