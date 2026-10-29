<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($section->getUfQuo())):
    $payElement = Util::getElementObject($textSiteTableString,
        ['NAME', "PREVIEW_TEXT", 'PREVIEW_PICTURE', 'text'],
        ['ID' => $section->getUfQuo()]
    );
    if(!empty($payElement)):
        if(!empty($payElement->getPreviewPicture()))
        {
            $payPrevMod = "bgs-cr bgr-nr bp-c bi-load lazyload";
            $payPrev = 'data-bg="'. \CFile::GetPath($payElement->getPreviewPicture()) . '"';
        }
        ?>
        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="w-100 p-32 xl:p-48 pn-r of-h d-f fd-c rg-32 r-16 lg:r-32 bc-w <?=$payPrevMod?>" <?=$payPrev?> <?=Util::iblockElementEditAddId($payElement)?>>
                    <div class="w-100 md:w-7/12 lg:w-6/12 xl:w-5/12 pn-r z-1 d-f fd-c rg-32 c-w">

                        <div class="w-100 d-f fd-c rg-8">
                            <h2 class="<?=comp::get("titleh2", "ta-l", "ta-c")?>"><?=$payElement->getName()?></h2>
                            <?if(!empty($payElement->getPreviewText())):?>
                                <div class="f-14 xl:f-16"><?=$payElement->getPreviewText()?></div>
                            <?endif;?>
                        </div>

                        <?if(!empty($payElement->getText()->getAll())):?>
                            <div class="w-100 d-f fd-c rg-24">
                                <?foreach($payElement->getText()->getAll() as $text):?>
                                    <div class="p-16 r-12 ff-ot bc-w c-dt f-14 md:f-16 xl:f-18"><?=$text->getValue()?></div>
                                <?endforeach;?>
                            </div>
                        <?endif;?>
                    </div>

                    <div class="w-100 pn-a i-0 bi-pb"></div>
                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>
