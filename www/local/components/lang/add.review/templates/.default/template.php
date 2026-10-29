<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
// echo '<pre>', print_r($arResult, 1), '</pre>';
?>

<div class="w-100 h-100 d-f fd-c ai-c jc-c fg-1">
    <?if(!empty($arResult['error']) && !array_key_exists("textlength", $arResult['error'])):?>
        <div class="w-100 h-100 d-f fd-c rg-8 fg-0 f-12 xl:f-14">
            <?foreach($arResult['error'] as $mError):?>
                <div class="c-c90536"><?=$mError?></div>
            <?endforeach;?>
        </div>
    <?elseif($arResult['addSuccess'] == 'y'):?>
        <div class="w-100 sm:w-4/12 h-100 d-f fd-c ai-c jc-c rg-16">
            <div class="d-f c-008000" title="Средств достаточно">
                <svg width="72" height="72" viewBox="0 0 76 76" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M53.1834 24.7714C53.1834 24.7714 36.4206 53.9837 36.1309 54.2449C35.1737 55.1474 33.6704 55.1001 32.775 54.1501L21.9165 44.1274C21.0187 43.1774 21.0686 41.6576 22.0258 40.7789C22.9829 39.8764 24.4863 39.9237 25.384 40.8737L33.8817 48.7113L49.0699 22.3964C49.7254 21.2801 51.1765 20.8761 52.3141 21.5411C53.4494 22.1824 53.8389 23.6551 53.1834 24.7714ZM38 0C17.0145 0 0 17.005 0 38C0 58.995 17.0145 76 38 76C58.9855 76 76 58.995 76 38C76 17.005 58.9855 0 38 0Z"/>
                </svg>
            </div>
            <div class="ta-c">Спасибо за ваш отзыв!</div>
        </div>
    <?else:?>
        <form class="w-100 h-100 d-f fd-c rg-32 fg-1">
            <?=bitrix_sessid_post();?>
            <input type="hidden" name="add" value="y">
            <input type="hidden" name="serviceId" value="<?=$arResult['id']?>">

            <div class="w-100 fg-0 pn-sy t-0 bc-w fg-0">
                <h2 class="mb-0 f-18 sm:f-24 ff-ud ftw-5 tt-l ls--1 lh-1.125"><?=$arResult['name']?></h2>
            </div>

            <div class="w-100 d-f fd-c rg-32 ai-s fg-1">

                <?if(!empty($arResult['error']) && array_key_exists("textlength", $arResult['error'])):?>
                    <div class="w-100 d-f fd-c rg-8 fg-0 f-12 xl:f-14">
                        <div class="c-c90536"><?=$arResult['error']['textlength']?></div>
                    </div>
                <?endif;?>

                <div class="w-100 d-f fd-c ai-s rg-16">
                    <div class="c-8c94a8 f-12">Поставьте оценку от 1 до 5</div>
                    <div class="lang--review d-f jc-s cg-8">
                        <?for($k = 1; $k < 6; $k++):
                            $check = false;
                            if(empty($arResult['score']) && $k == 1) {
                                $check = true;
                            } elseif($arResult['score'] == $k) {
                                $check = true;
                            }
                            ?>
                            <div class="score--wrap pn-r d-f h:c-e64231 <?=($arResult['score'] >= $k) ? 'c-e64231' : ''?> c-t tn-all-3">
                                <input class="review--score d-n c:c-e64231~lb" type="radio" name="score" value="<?=$k?>"
                                    id="scoreValue_<?=$k?>" required="" <?=($check) ? 'checked' : ''?>>
                                <label class="score--label mb-0 d-f cr-p" for="scoreValue_<?=$k?>" title="Оценка <?=$k?>">
                                    <svg class="lang-review--score-radio-svg" width="42" height="42" viewBox="0 0 46 44" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M43.7378 17.2735L29.9638 15.2929L23.7816 2.77855L23.3333 1.8711L22.885 2.77855L16.7029 15.2929L2.92884 17.2735L1.92495 17.4179L2.65077 18.1262L12.6445 27.8799L10.2737 41.6248L10.1005 42.629L11.0007 42.1515L23.3333 35.6101L35.666 42.1515L36.5662 42.629L36.393 41.6248L34.0221 27.8799L44.0159 18.1262L44.7417 17.4179L43.7378 17.2735Z" stroke="#E64231"/>
                                    </svg>
                                </label>
                            </div>
                        <?endfor;?>
                    </div>
                </div>

                <div class="review--description w-100 mxh-500px d-f fd-c rg-8 fg-1 ofy-a">
                    <div class="w-100 d-f jc-e">
                        <div class="c-8c94a8 f-12">Осталось <span class="description--length">400</span> символов</div>
                    </div>
                    <input type="hidden" name="description" class="description--input <?=comp::get("textDefault")?>" value="<?=$arResult['description']?>" required>
                </div>
            </div>

            <div class="w-100 d-f jc-e fg-0 pn-sy b-0">
                <button class="<?=comp::get("btnBlueFillSm")?>" type="submit">Оставить</button>
            </div>
        </form>
    <?endif;?>
</div>