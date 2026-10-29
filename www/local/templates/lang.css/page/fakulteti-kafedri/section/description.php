<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<section class="w-100">
    <div class="<?=comp::get("container")?> d-f fd-c ai-c rg-32">

        <div class="w-100 d-f jc-c">
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

        <div class="w-100 d-f jc-c">
            <h1 class="<?=comp::get("titleh1")?>"><?=$APPLICATION->ShowTitle()?></h1>
        </div>

        <?if(!empty($section->getDescription())):?>
            <div class="w-100 xl:w-8/12 f-14 xl:f-16"><?=$section->getDescription()?></div>
        <?endif;?>
    </div>
</section>