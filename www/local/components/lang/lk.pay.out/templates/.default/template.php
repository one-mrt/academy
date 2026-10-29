<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

?>

<div class="w-100 pn-sy t-0 pix-32 pby-16 bc-w d-f ai-c cg-24 fg-0 bbw-1 bbs-s bcr-CECECE bc-w">
    <div class="d-f ai-c cg-24 fg-1">
        <div class="d-f">
            <?if(!empty($arResult['payData']['picture'])):?>
                <?=Data::getImageId($arResult['payData']['picture'], [
                    'alt' => strip_tags($arResult['payData']['name']),
                    'class' => 'lang-paysystem--img w-51px h-51px oft-cv op-c',
                    'fetchpriority' => 'high',
                ])?>
            <?endif;?>
        </div>
        <div class="lang-paysystem--name md:f-24 ftw-5 <?//=comp::get("fontUd")?>"><?=$arResult['payData']['name']?></div>
    </div>
</div>

<form class="w-100 h-100 d-f fd-c fg-1">
    <?=bitrix_sessid_post();?>
    <input type="hidden" name="paySystem" value="<?=$arResult['paySystem']?>">

    <?if(!empty($arResult['error'])):?>
        <div class="w-100 pix-32 pby-16 d-f fd-c rg-8 fg-0 f-12 xl:f-14 bc-c90536 bbw-1 bbs-s bcr-c90536 c-w">
            <?foreach($arResult['error'] as $mError):?>
                <div class=""><?=$mError?></div>
            <?endforeach;?>
        </div>
    <?endif;?>

    <?if(!empty($arResult['message']['notaccount'])):?>
        <div class="w-100 pn-sy t-0 ftw-5 c-8c94a8 p-32 fg-0 bc-w bbw-1 bbs-s bcr-CECECE"><?=$arResult['message']['notaccount']?></div>
    <?else:?>
        <div class="w-100 d-f fd-c fg-0">
            <?foreach($arResult['accountList'] as $index => $account):?>
                <label class="mb-0 pix-32 pby-16 d-f ai-c cg-16 cr-p h:bc-CECECE tn-all-3 bbw-1 bbs-s bcr-CECECE" for="payCard<?=$account['id']?>">
                    <?/*<div class="d-f">
                        <?if(!empty($account['pay']['picture'])):?>
                            <?=Data::getImageId($account['pay']['picture'], [
                                'alt' => strip_tags($account['pay']['name']),
                                'class' => 'lang-paysystem--img w-30px h-30px oft-cv op-c',
                                'fetchpriority' => 'high',
                            ])?>
                        <?endif;?>
                    </div>*/?>
                    <div class="lang-paysystem--name ftw-5 fg-1 c-dt"><?=$account['name']?></div>
                    <input id="payCard<?=$account['id']?>" class="w-20px h-20px atc-py" type="radio" name="payCard" <?=($index == 0) ? "checked" : ""?> value="<?=$account['id']?>">
                </label>
            <?endforeach;?>
        </div>
    <?endif;?>

    <?if(!empty($arResult['message']['notaccount'])):?>

        <div class="w-100 h-100 d-f fd-c rg-16 p-32 bbw-1 bbs-s bcr-CECECE fg-1">
            <?foreach($arResult['payData']['sort'] as $nameF => $sort):
                $field = $arResult['payData']['field'][$nameF];
                ?>
                <div class="w-100 d-f fd-c rg-12">
                    <label for="<?=$nameF?><?=$arResult['paySystem']?>" class="mb-0 c-8c94a8 f-12"><?=$field['title']?></label>
                    <?if($field['type'] == 'card'):?>
                        <input id="<?=$nameF?><?=$arResult['paySystem']?>" class="paysystem--card <?=comp::get("textDefault")?>"
                            type="tel" name="<?=$nameF?>" placeholder="<?=$field['placeholder']?>"
                            pattern="[0-9\s]{13,19}" autocomplete="cc-number" minlength="19" maxlength="19" inputmode="numeric" required="">
                    <?elseif($field['type'] == 'phone'):?>
                        <input id="<?=$nameF?><?=$arResult['paySystem']?>" class="paysystem--phone <?=comp::get("textDefault")?>"
                            type="tel" name="<?=$nameF?>" placeholder="<?=$field['placeholder']?>"
                            autocomplete="tel" inputmode="numeric" required="">
                    <?elseif($field['type'] == 'inn'):?>
                        <input id="<?=$nameF?><?=$arResult['paySystem']?>" class="paysystem--inn <?=comp::get("textDefault")?>"
                            type="tel" name="<?=$nameF?>" minlength="<?=$field['min']?>" maxlength="<?=$field['max']?>"
                            autocomplete="inn" placeholder="<?=$field['placeholder']?>" required="">
                    <?elseif($field['type'] == 'wallet'):?>
                        <input id="<?=$nameF?><?=$arResult['paySystem']?>" class="paysystem--inn <?=comp::get("textDefault")?>"
                            type="text" name="<?=$nameF?>" placeholder="<?=$field['placeholder']?>" required="">
                    <?elseif($field['type'] == "nameCard"):?>
                        <input id="<?=$nameF?><?=$arResult['paySystem']?>" class="<?=comp::get("textDefault")?>"
                            type="text" name="<?=$nameF?>" placeholder="<?=$field['placeholder']?>" required="" autocomplete="cc-name">
                    <?else:?>
                        <input id="<?=$nameF?><?=$arResult['paySystem']?>" class="<?=comp::get("textDefault")?>"
                            type="text" name="<?=$nameF?>" placeholder="<?=$field['placeholder']?>" required="">
                    <?endif;?>
                </div>
            <?endforeach;?>
        </div>
        

        <div class="w-100 d-f jc-e p-32 fg-0 pn-sy b-0 bc-w">
            <input type="hidden" name="addPaySystem" value="y">
            <button class="<?=comp::get("btnBlueFillSm")?> d-f ai-c cg-16">
                <div class="d-f">
                    <svg width="20" height="20" viewBox="0 0 416 416" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M232 24C232 10.7 221.3 0 208 0C194.7 0 184 10.7 184 24V184H24C10.7 184 0 194.7 0 208C0 221.3 10.7 232 24 232H184V392C184 405.3 194.7 416 208 416C221.3 416 232 405.3 232 392V232H392C405.3 232 416 221.3 416 208C416 194.7 405.3 184 392 184H232V24Z"/>
                    </svg>
                </div>
                <div class="">Добавить счёт</div>
            </button>
        </div>
    <?else:?>
        <?if(empty($arResult['error']['payout'])):?>
            <div class="w-100 p-32 bbw-1 bbs-s bcr-CECECE">
                <div class="w-100 d-f cg-16">
                    <div class="d-f fd-c rg-12 fg-1">
                        <label for="sumPay" class="mb-0 c-8c94a8 f-12">Введите сумму вывода</label>
                        <div class="w-100 d-f ai-c cg-16">
                            <input id="sumPay" class="payout--sum <?=comp::get("textDefault")?>"
                                min="<?=$arResult['payData']['mnOut']?>" max="<?=$arResult['payData']['mxOut']?>"
                                value="<?=$arResult['payData']['mxOut']?>"
                                type="text" name="sumPay" placeholder="1000" required="">
                        </div>
                    </div>
                    <div class="d-f fd-c rg-12">
                        <label class="mb-0 c-8c94a8 f-12">С комиссией</label>
                        <div class="lang-sum--end <?=comp::get("textDefault")?> ws-nw c-c90536"><?=$arResult['comOutPrint']?></div>
                        <?/*≈<?=$arResult['scomPrint']?>*/?>
                    </div>
                </div>
            </div>

            <div class="w-100 d-f ai-c jc-e p-32 fg-0 pn-sy b-0 bc-w">
                <input type="hidden" name="addPaySum" value="y">
                <div class="fg-1 c-8c94a8 f-14">
                    от <?=$arResult['payData']['mnPrint']?> до <?=$arResult['payData']['mxPrint']?>
                </div>
                <button class="<?=comp::get("btnBlueFillSm")?> d-f cg-16">
                    <div class="">Вывести</div>
                </button>
            </div>
        <?/*else:?>
            <div class="w-100 p-32 bc-c90536"><?=$arResult['error']['payout']?></div>*/?>
        <?endif;?>
    <?endif;?>

</form>