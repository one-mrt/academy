<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

?>
<?if($arResult['addSuccess'] != 'y'):?>
    <form class="w-100 d-f fd-c rg-32">
        <?=bitrix_sessid_post()?>
        <input type="hidden" name="save" value="y">
        <input type="hidden" name="add" value="y">
        <input type="hidden" name="customer" value="<?=$arResult['customer']?>">
        <input type="hidden" name="orderId" value="<?=$arResult['orderId']?>">
        <input type="hidden" name="serviceId" value="<?=$arResult['serviceId']?>">

        <div class="<?=comp::get("h2page")?> fg-1">Ваше предложение</div>
        
        <div class="w-100 d-f fd-c rg-16">
            <?if(!empty($arResult["error"])):?>
                <div class="d-f fd-c rg-8">
                    <?foreach($arResult["error"] as $error):?>
                        <div class="c-c90536 f-12"><?=$error?></div>
                    <?endforeach?>
                </div>
            <?endif?>

            <div class="w-100 d-f fd-c">
                <input placeholder="Сумма" value="<?=$arResult['summ']?>" class="offer--price <?=comp::get("textDefault")?>"
                    type="text" name="summ" required="" min="1" step="1">
            </div>
            <div class="w-100 d-f fd-c rg-8">
                <div class="w-100 d-f jc-e">
                    <div class="c-8c94a8 f-12">Осталось <span class="comment--length">100</span> символов</div>
                </div>
                <textarea maxlength="100" class="offer--comment <?=comp::get("textDefault")?> f-14"
                    placeholder="Комментарий к заказу" name="message" rows="4" cols="40"><?=$arResult['message']?></textarea>
            </div>
        </div>

        <div class="w-100 d-f jc-e">
            <button type="submit" name="submit" class="<?=comp::get("btnBlueFillSm")?>"><?=$arResult['btnName']?></button>
        </div>
    </form>
<?else:?>
    <div class="w-100 sm:w-6/12 h-100 d-f fd-c ai-c jc-c rg-16">
        <div class="d-f c-008000" title="Предложение успешно отправлено!">
            <svg width="72" height="72" viewBox="0 0 76 76" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M53.1834 24.7714C53.1834 24.7714 36.4206 53.9837 36.1309 54.2449C35.1737 55.1474 33.6704 55.1001 32.775 54.1501L21.9165 44.1274C21.0187 43.1774 21.0686 41.6576 22.0258 40.7789C22.9829 39.8764 24.4863 39.9237 25.384 40.8737L33.8817 48.7113L49.0699 22.3964C49.7254 21.2801 51.1765 20.8761 52.3141 21.5411C53.4494 22.1824 53.8389 23.6551 53.1834 24.7714ZM38 0C17.0145 0 0 17.005 0 38C0 58.995 17.0145 76 38 76C58.9855 76 76 58.995 76 38C76 17.005 58.9855 0 38 0Z"/>
            </svg>
        </div>
        <div class="ta-c">Предложение успешно отправлено!</div>
    </div>
<?endif;?>