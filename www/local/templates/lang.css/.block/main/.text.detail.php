<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Util::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['elementId'])):
    $helpText = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'link'], ['ID' => $arParams['elementId']]);

    if(!empty($helpText) && !empty($helpText->getId())):?>
        <section class="w-100">
            <div class="<?=comp::get('container')?> d-f fd-c rg-32" <?=Data::iblockElementEditAddId($helpText)?>>

                <div class="w-100 xl:w-8/12 d-f fd-c rg-16">
                    <h2 class="<?=comp::get("h2")?>"><?=$helpText->getName()?></h2>
                    <?if(!empty($helpText->getPreviewText())):?>
                        <div class="f-14 xl:f-16"><?=$helpText->getPreviewText()?></div>
                    <?endif;?>
                </div>

                <?if(!empty($helpText->getDetailText())):?>
                    <div class="w-100"><?=$helpText->getDetailText()?></div>
                <?endif?>

                <?if(!empty($helpText->getLink()) && !empty($helpText->getLink()->getDescription())):?>
                    <div class="d-f jc-s ai-c cg-12 cr-p c-724A98 h:c-dt tn-all-3">
                        <div class="ff-ud tt-l ls--1 ftw-6 lh-1 f-20"><?=$helpText->getLink()->getDescription()?></div>
                        <div class="d-f">
                            <svg width="7" height="14" viewBox="0 0 7 14" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M1.00002 1L6.00002 6.83333L1.00002 12.6667" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                    </div>
                <?endif?>
            </div>
        </section>
    <?endif?>
<?endif?>