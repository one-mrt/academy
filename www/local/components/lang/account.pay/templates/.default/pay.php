<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;

// echo '<pre>', print_r($arResult['paySystemData'], 1), '</pre>';
?>

<div class="w-100 h-100 d-f fd-c">

    <?if(!empty($arResult['error'])):?>
        <div class="w-100 p-32 d-f fd-c rg-8 fg-0 f-12 xl:f-14 bbw-1 bbs-s bcr-CECECE">
            <?foreach($arResult['error'] as $mError):?>
                <div class="c-c90536"><?=$mError?></div>
            <?endforeach;?>
        </div>
    <?endif;?>

    <?if(!empty($arResult['paySystemData'])):?>
        <div class="w-100 pn-sy t-0 p-32 d-f ai-c cg-24 fg-0 bbw-1 bbs-s bcr-CECECE bc-w">
            <div class="d-f">
                <?=Data::getImage([
                    "src" => $arResult['paySystemData']['LOGOTIP'],
                    "alt" => strip_tags($arResult['paySystemData']['NAME']),
                    "fetchpriority" => "high",
                    "class" => "lang-paysystem--img w-51px h-51px oft-cv op-c",
                    "width" => 64,
                    "height" => 64,
                ]);?>
            </div>
            <div class="lang-paysystem--name ff-ud md:f-24 ls--1 lh-1"><?=$arResult['paySystemData']['NAME']?></div>
        </div>

        <form class="w-100 d-f fd-c">
            <?=bitrix_sessid_post();?>
            <input type="hidden" name="paySystem" value="<?=$arResult['paySystem']?>">
            <input type="hidden" name="pay" value="y">

            <div class="w-100 p-32 bbw-1 bbs-s bcr-CECECE">
                <div class="w-100 d-f cg-16">
                    <div class="d-f fd-c rg-12 fg-1">
                        <label for="sumPay" class="mb-0 c-8c94a8 f-12">Введите сумму пополнения</label>
                        <div class="w-100 d-f ai-c cg-16">
                            <input id="sumPay" class="payin--sum <?=comp::get("textDefault")?>"
                                min="" max="" type="text" name="buyMoney" placeholder="1000" required="">
                        </div>
                    </div>
                    <div class="d-f fd-c rg-12">
                        <label class="mb-0 c-8c94a8 f-12">С комиссией</label>
                        <div class="lang-sum--end <?=comp::get("textDefault")?> ws-nw c-c90536">≈<?=$arResult['comInPrint']?></div>
                    </div>
                </div>
            </div>

            <div class="w-100 p-32 d-f ai-c jc-e">
                <?/*<div class="lang-paysystem--description f-14 ftw-6 ls--1 ws-nw"><?=$arResult['paySystemData']['DESCRIPTION']?></div>*/?>
                <button class="lang-btn--pay <?=comp::get("btnBlueFillSm")?>">Пополнить</button>
            </div>
        </form>
    <?endif;?>

</div>