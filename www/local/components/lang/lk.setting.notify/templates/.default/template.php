<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
?>

<style>
    .lang--checkbox {
        [type="checkbox"] {
            + .flag--view {
                .checked {
                    opacity: 0;
                }
            }

            &:checked {
                + .flag--view {
                    color: #527CC8;

                    .checked {
                        opacity: 1;
                    }
                }
            }

            &:hover {
                + .flag--view {
                    color: #527CC8;
                }
            }
        }
    }
</style>

<form class="w-100 h-100 d-f fd-c rg-32 fg-1">
    <?=bitrix_sessid_post();?>
    <input type="hidden" name="edit" value="y">

    <div class="w-100">
        <h2 class="mb-0 f-18 sm:f-24 <?=comp::get("fontUd")?>">Уведомления на почту</h2>
    </div>

    <div class="w-100 h-100 d-f fd-c rg-24 fg-1">
        <?//echo '<pre>', print_r($arResult, 1), '</pre>';?>

        <div class="w-100 d-f fd-c rg-16 fg-1">

            <?if(!empty($arResult['error'])):?>
                <div class="w-100 d-f fd-c rg-12">
                    <?foreach($arResult['error'] as $error):?>
                        <div class="c-c90536 f-12 md:f-14"><?=$error?></div>
                    <?endforeach;?>
                </div>
            <?endif;?>

            <div class="w-100 d-f fd-c rg-16 fg-1">
                <?foreach($arResult['notify'] as $nameSetting => $param):?>
                    <div class="w-100 d-f fd-c rg-12">
                        <label for="LangSetting<?=$nameSetting?>" class="lang--checkbox mb-0 ws-nw d-f cg-8 cr-p pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3">
                            <input class="d-n" type="checkbox" name="<?=$nameSetting?>" id="LangSetting<?=$nameSetting?>" <?=(!empty($param['value'])) ? 'checked' : ''?>>
                            <div class="flag--view d-f">
                                <svg width="18" height="18" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path class="checked tn-all-3" d="M22.8256 7.9603C22.4459 8.03068 22.1097 8.24901 21.891 8.56727L13.4982 20.7913L9.30042 15.7324L9.09097 15.5258C8.82956 15.3155 8.50348 15.202 8.16797 15.2046C7.83247 15.2072 7.50817 15.3257 7.25003 15.54C6.99188 15.7543 6.81577 16.0513 6.75153 16.3806C6.68728 16.7099 6.73886 17.0513 6.89752 17.3469L7.06333 17.5884L12.4888 24.1338C12.6324 24.307 12.8141 24.4444 13.0198 24.5355C13.2255 24.6265 13.4494 24.6686 13.6741 24.6585C13.8988 24.6483 14.1181 24.5862 14.3147 24.477C14.5113 24.3678 14.68 24.2145 14.8073 24.0291L24.291 10.2167C24.5097 9.89847 24.593 9.50635 24.5227 9.12664C24.4523 8.74693 24.234 8.41073 23.9157 8.192C23.5974 7.97327 23.2053 7.88993 22.8256 7.9603Z"/>
                                    <path class="no tn-all-3" d="M29.3905 0.0145458C30.1065 0.0886874 30.7696 0.425647 31.2516 0.960286C31.7336 1.49492 32.0002 2.18927 32 2.90909V29.0909L31.9855 29.3876C31.9173 30.0534 31.6217 30.6753 31.1485 31.1485C30.6753 31.6217 30.0534 31.9173 29.3876 31.9855L29.0909 32H2.90909L2.60946 31.9855C1.94422 31.9167 1.32299 31.6208 0.850363 31.1477C0.377731 30.6745 0.0825609 30.0529 0.0145459 29.3876L1.47845e-07 29.0909V2.90909C-0.000229215 2.18927 0.266421 1.49492 0.748401 0.960286C1.23038 0.425647 1.89346 0.0886874 2.60946 0.0145458L2.90909 0H29.0909L29.3905 0.0145458ZM2.90909 29.0909H29.0909V2.90909H2.90909V29.0909Z"/>
                                </svg>
                            </div>
                            <div class="mb-0 c-8c94a8 f-16"><?=$param['title']?></div>
                        </label>
                    </div>
                <?endforeach;?>
            </div>
        </div>

        <div class="w-100 pn-sy b-0 d-f jc-e">
            <button class="<?=comp::get("btnBlueFillSm")?>" type="submit">Сохранить</button>
        </div>
    </div>

</form>