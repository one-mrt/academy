<?
use Lang\Main\Iblock\Util as Data;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util;
use Bitrix\Main\UserTable;
use Bitrix\Main\Engine\CurrentUser;

$image = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => 248]);
$name = "Новый заказ";
$title = "Разместить новый заказ";
?>

<div class="w-100 xl:w-8/12 pr-16 pl-16 fg-0 fs-0">
    <div class="w-100 h-100 pn-r of-h p-32 bc-w r-24">
        <?if(!empty($image) && !empty($image->getPreviewPicture())) {
            echo Data::getImageId($image->getPreviewPicture(), [
                "alt" => strip_tags($image->getName()),
                "fetchpriority" => "high",
                "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
            ]);
        }?>

        <div class="w-100 d-f fd-c rg-32 pn-r">
            <h1 class="<?=comp::get('h2')?>"><?=$APPLICATION->ShowTitle(false)?></h1>

            <?$APPLICATION->IncludeComponent(
                "lang:order",
                "",
                [],
                false,
                ['HIDE_ICONS' => 'Y']
            );?>
        </div>
    </div>
</div>

<?
$APPLICATION->SetTitle(strip_tags($title));
$APPLICATION->SetPageProperty("title", strip_tags($name));
// $APPLICATION->SetPageProperty("description", strip_tags($seo['ELEMENT_META_DESCRIPTION']));
// $APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
?>