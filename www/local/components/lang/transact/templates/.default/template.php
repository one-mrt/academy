<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
?>

<?if(!empty($arResult['transact'])):?>
    <?foreach($arResult['transact'] as $transact):?>
        <div class="w-100 btw-1 bts-s btc-EFEFEF pby-16 pix-12 h:bc-EFEFEF">
            <div class="<?=comp::get("row")?>">
                <div class="w-6/12 <?=comp::get("setcol")?>"><?=$transact['desc']?></div>
                <div class="w-3/12 <?=comp::get("setcol")?> <?=($transact['debit'] == 'Y') ? "c-21A78E ftw-5" : ""?>"><?=$transact['sum']?></div>
                <div class="w-3/12 <?=comp::get("setcol")?>"><?=$transact['date']?></div>
            </div>
        </div>
    <?endforeach?>

    <?if(isset($arResult['NavBtnShow'])):?>
        <div class="lang-transact--nav">
            <button class="w-100 m-0 pby-16 pix-12 d-f jc-c ftw-5 bw-0 btw-1 bts-s btc-EFEFEF bc-w h:bc-EFEFEF tn-all-3">Показать еще</button>
        </div>
    <?endif;?>

<?else:?>
    <div class="w-100 btw-1 bts-s btc-EFEFEF p-16 pt-12 pb-12 ta-c ftw-5">Нет операций</div>
<?endif?>
