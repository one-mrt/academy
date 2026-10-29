<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<section class="w-100">
    <div class="<?=comp::get("container")?> d-f fd-c ai-c rg-32 xl:rg-48">

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
            <h1 class="<?=comp::get('titleh1')?>"><?=$APPLICATION->ShowTitle()?></h1>
        </div>

        <?if(!empty($section->getUfText())):
            $textStart = Util::getElementObject($textSiteTableString, ['PREVIEW_TEXT'], ['ID' => $section->getUfText()]);
            ?>
            <div class="w-100 lg:w-10/12 xl:w-8/12 f-14 xl:f-16" <?=Util::iblockElementEditAddId($textStart)?>><?=$textStart->getPreviewText()?></div>
        <?endif;?>
    </div>
</section>