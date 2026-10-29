<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

// echo '<pre>', print_r($arResult, 1), '</pre>';

?>

<div class="w-100 pn-r of-h p-16 md:p-32 r-12" <?=$arResult['edit']?>>
    <?=$arResult['bgImage'];?>

    <div class="w-100 d-f fd-c rg-32 pn-r">
        <div class="w-100 d-f fd-c rg-16">
            <div class="w-100 d-f ai-c cg-24">
                <div class="<?=comp::get("h2page")?> fg-1">
                    <?if($arResult['isCustomer'] == 'n'):?>
                        Мои предложения
                    <?else:?>
                        Предложения
                    <?endif?>
                </div>

                <?if(empty($arResult['offer']) && $arResult['isCustomer'] == 'n'):?>
                    <div class="fg-0">
                        <button class="lang-offer--add <?=comp::get("btnGreenSm")?>">Добавить</button>
                    </div>
                <?endif;?>
                
            </div>

            <?if(!empty($arResult['description']) && empty($arResult['offer']) && $arResult['isCustomer'] == 'n'):?>
                <div class="f-12 md:f-14"><?=$arResult['description']?></div>
            <?elseif(empty($arResult['offer'])):?>
                <div class="f-12 md:f-14">Пока нет предложений</div>
            <?endif?>
        </div>

        <?if(!empty($arResult['offer'])):?>
            <div class="offer--list w-100 d-f fd-c rg-16">
                <div class="w-100 d-f fd-c rg-16"><?include_once(".list.php")?></div>
            </div>
        <?endif;?>

    </div>
</div>