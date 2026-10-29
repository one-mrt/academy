<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$button = Util::getElementObject($textTableString, ['NAME', 'link'], ['ID' => 181033]);
?>

<?if(!empty($button)):?>
    <section class="w-100">
        <div class="<?=comp::get('container')?>" <?=Util::iblockElementEditAdd($button)?>>
            <div class="w-100 d-f fw-w xl:fw-n jc-c ai-c g-24 xl:g-32 r-20 bc-dt p-32">
                <div class="f-24 md:f-18 lg:f-24 ftw-5 lh-1 c-w ta-c xl:ta-l"><?=$button->getName()?></div>
                <div class="">
                    <button class="<?=comp::get("btnPy")?>" data-fancybox="" data-type="ajax"
                        data-src="<?=SITE_TEMPLATE_PATH?>/ajax/order/"><?=$button->getLink()->getDescription()?></button>
                </div>
            </div>
        </div>
    </section>
<?endif;?>