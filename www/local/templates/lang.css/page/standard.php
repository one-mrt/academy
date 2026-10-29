<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$bitrixComp->setFrameMode(true);

if(!empty($section) && empty($element)):?>

    <section class="w-100 d-f fd-c rg-32 xl:rg-48 fg-1">
        <div class="<?=comp::get("container")?> d-f fd-c ai-c rg-32">
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
            <div class="w-100">
                <h1 class="<?=comp::get('titleh1')?>"><?=$APPLICATION->ShowTitle(false)?></h1>
            </div>
        </div>
        <div class="w-100 d-f fd-c rg-32 xl:rg-64 fg-1">

            <?
            // if(!empty($section->getUfBan()))
            // {
            //     @include_once "standard/section/banner.php";
            // }
            // else
            // {
            @include_once "standard/section/description.php";
            // }

            @include_once "standard/section/image.php";
            @include_once "standard/section/link.php";
            // @include_once "standard/section/contact.php";
            @include_once "standard/section/faq.php";

            @include_once "standard/section/question.php";
            @include_once "standard/seo.php";
            ?>
        </div>
    </section>
<?elseif($element):?>
    <section class="w-100 d-f fd-c rg-32 xl:rg-48 fg-1">
        <div class="<?=comp::get("container")?> d-f fd-c ai-c rg-32">
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
            <div class="w-100">
                <h1 class="<?=comp::get('titleh1')?>"><?=$APPLICATION->ShowTitle(false)?></h1>
            </div>
        </div>
        <div class="w-100 d-f fd-c rg-32 xl:rg-64 fg-1">

            <?
            // if(!empty($element->getBan()))
            // {
            //     @include_once "standard/element/banner.php";
            // }
            // else
            // {
            @include_once "standard/element/description.php";
            // }

            @include_once "standard/element/image.php";
            @include_once "standard/element/link.php";
            @include_once "standard/element/contact.php";
            @include_once "standard/element/faq.php";
            @include_once "standard/element/question.php";
            @include_once "standard/seo.php";
            ?>
        </div>
    </section>
<?else:?>
    <?Util::get404();?>
<?endif;?>