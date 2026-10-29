<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
// echo '<pre>', print_r($arResult, 1), '</pre>';
?>

<?if(empty($arResult['paySuccess'])):?>
    <form class="w-100 h-100 d-f fd-c rg-24 lg:rg-32 jc-b fg-1">
        <?=bitrix_sessid_post();?>
        <input type="hidden" name="orderId" value="<?=$arResult['orderId']?>">
        <input type="hidden" name="chatId" value="<?=$arResult['chatId']?>">

        <div class="w-100">
            <h2 class="mb-0 f-18 sm:f-24 <?=comp::get("fontUd")?>">Оплата заказа №<?=$arResult['orderId']?></h2>
        </div>

        <?if(intval($arResult['budget']) >= intval($arResult['sum'])):?>
            <div class="w-100 d-f fd-c rg-4 fg-1">
                <div class="d-f ai-c cg-16 fg-0">
                    <input type="hidden" name="pay" value="y">
                    <div class="pix-24 pby-12 bw-1 bs-s bcr-008000 bc-w r-6 f-18 ftw-5"><?=\SaleFormatCurrency($arResult['sum'], $arResult['currency']);?></div>
                    <div class="d-f c-008000" title="Средств достаточно">
                        <svg width="24" height="24" viewBox="0 0 76 76" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M53.1834 24.7714C53.1834 24.7714 36.4206 53.9837 36.1309 54.2449C35.1737 55.1474 33.6704 55.1001 32.775 54.1501L21.9165 44.1274C21.0187 43.1774 21.0686 41.6576 22.0258 40.7789C22.9829 39.8764 24.4863 39.9237 25.384 40.8737L33.8817 48.7113L49.0699 22.3964C49.7254 21.2801 51.1765 20.8761 52.3141 21.5411C53.4494 22.1824 53.8389 23.6551 53.1834 24.7714ZM38 0C17.0145 0 0 17.005 0 38C0 58.995 17.0145 76 38 76C58.9855 76 76 58.995 76 38C76 17.005 58.9855 0 38 0Z"/>
                        </svg>
                    </div>
                </div>
                <div class="fg-1 f-10 c-008000">
                    На текущем счету <?=\SaleFormatCurrency($arResult['budget'], $arResult['currency']);?>
                </div>
            </div>

            <div class="w-100 d-f ai-c jc-b cg-16">
                <div class="fg-1 f-10 c-8c94a8"></div>
                <div class="fg-0">
                    <button type="submit" name="pay" class="<?=comp::get("btnBlueFillSm")?>">Оплатить</button>
                </div>
            </div>
        <?else:?>
            <?if(!empty($arResult['error']['budget'])):?>
                <div class="w-100 d-f fd-c rg-4 fg-1">
                    <div class="d-f ai-c cg-16 fg-0">
                        <div class="pix-24 pby-12 bw-1 bs-s bcr-c90536 bc-w r-6 f-18 ftw-5"><?=\SaleFormatCurrency($arResult['sum'], $arResult['currency']);?></div>
                        <div class="d-f c-c90536" title="<?=$arResult['error']['budget']?>">
                            <svg width="24" height="24" viewBox="0 0 100 100" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M50 0C22.4 0 0 22.4 0 50C0 77.6 22.4 100 50 100C77.6 100 100 77.6 100 50C100 22.4 77.6 0 50 0ZM55 75H45V65H55V75ZM55 55H45V25H55V55Z">
                            </svg>
                        </div>
                    </div>
                    <div class="fg-1 f-10 c-c90536"><?=$arResult['error']['budget']?></div>
                </div>

                <div class="w-100 d-f ai-c jc-b cg-16">
                    <div class="fg-1 f-10 sm:f-12 c-8c94a8 ta-r lh-1">Вы можете пополнить ваш счет любым удобным для вас способом и вернуться к оплате заказа</div>
                    <div class="fg-0">
                        <a href="/lk/balans/" target="_blank" class="pay--close <?=comp::get("btnBlueFillSm")?>">Пополнить</a>
                    </div>
                </div>
            <?else:?>
                <div class="w-100 d-f fd-c rg-4 fg-1">
                    <?foreach($arResult['error'] as $error):?>
                        <div class=""><?=$error?></div>
                    <?endforeach;?>
                </div>
            <?endif;?>
        <?endif;?>
    </form>
<?elseif($arResult['paySuccess'] == 'y'):?>
    <div class="w-100 sm:w-4/12 h-100 d-f fd-c ai-c jc-c rg-16">
        <div class="d-f c-008000" title="Средств достаточно">
            <svg width="72" height="72" viewBox="0 0 76 76" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M53.1834 24.7714C53.1834 24.7714 36.4206 53.9837 36.1309 54.2449C35.1737 55.1474 33.6704 55.1001 32.775 54.1501L21.9165 44.1274C21.0187 43.1774 21.0686 41.6576 22.0258 40.7789C22.9829 39.8764 24.4863 39.9237 25.384 40.8737L33.8817 48.7113L49.0699 22.3964C49.7254 21.2801 51.1765 20.8761 52.3141 21.5411C53.4494 22.1824 53.8389 23.6551 53.1834 24.7714ZM38 0C17.0145 0 0 17.005 0 38C0 58.995 17.0145 76 38 76C58.9855 76 76 58.995 76 38C76 17.005 58.9855 0 38 0Z"/>
            </svg>
        </div>
        <div class="ta-c">Заказ №<?=$arResult['orderId']?> успешно оплачен!</div>
    </div>
<?elseif($arResult['paySuccess'] == 'n'):?>
    <div class="w-100 sm:w-4/12 h-100 d-f fd-c ai-c jc-c rg-16">
       <div class="d-f c-c90536">
            <svg width="72" height="72" viewBox="0 0 100 100" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M50 0C22.4 0 0 22.4 0 50C0 77.6 22.4 100 50 100C77.6 100 100 77.6 100 50C100 22.4 77.6 0 50 0ZM55 75H45V65H55V75ZM55 55H45V25H55V55Z">
            </svg>
        </div>
        <div class="w-100 d-f fd-c rg-4 fg-1 ta-c">
            <?foreach($arResult['error'] as $error):?>
                <div class=""><?=$error?></div>
            <?endforeach;?>
        </div>
    </div>
<?endif?>