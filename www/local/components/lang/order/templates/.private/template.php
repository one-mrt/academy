<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\UI\Extension;
use Lang\Css\Component as comp;
use Lang\Academy\Util as Academy;
use Lang\Main\Iblock\Util as Data;


Extension::load(['ui.text-editor']);
?>

<form class="w-100 h-100 d-f fd-c rg-32" method="post" enctype="multipart/form-data">
    <?=bitrix_sessid_post()?>

    <input type="hidden" name="submit" value="y">
    
    <?if($arResult['private'] == 'y'):?>
        <input type="hidden" name="private" value="<?=$arResult['private']?>">
        <input type="hidden" name="toUser" value="<?=$arResult['toUser']?>">
    <?endif;?>

    <?if($arResult['public'] == 'y'):?>
        <input type="hidden" name="public" value="<?=$arResult['public']?>">
    <?endif;?>

    <div class="w-100 fg-0 pn-sy t-0 bc-w">
        <h2 class="mb-0 f-18 sm:f-24 <?=comp::get("fontUd")?>">Новый заказ <?if($arResult['private'] == 'y'):?>для - <span class=""><?=$arResult['toUserName']?></span><?endif;?></h2>
    </div>

    <div class="w-100 fg-1 ofy-a ofx-h">
        <div class="<?=comp::get("row")?> rg-16">

            <?if(!empty($arResult['error'])):?>
                <div class="w-100 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-8">
                    <?foreach($arResult['error'] as $error):?>
                        <div class="c-c90536"><?=$error?></div>
                    <?endforeach;?>
                </div>
            <?endif;?>

            <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                <div class="w-100 d-f fd-c rg-12">
                    <label for="orderName" class="mb-0 c-8c94a8 f-12">Введите наименование работы <span class="c-c90536">*</span></label>
                    <input id="orderName" class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
                        type="text" name="name" maxlength="255" value="<?=$arResult['name']?>" placeholder="Наименование работы" required>
                </div>
            </div>

            <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                <div class="w-100 pn-r d-f fd-c rg-12">
                    <label for="typeWork" class="mb-0 c-8c94a8 f-12">Выберите тип работы <span class="c-c90536">*</span></label>
                    <select id="typeWork" name="type" class="w-100 pt-16 pr-48 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 ap-n" placeholder="Тип работы" required>
                        <option value="">Выбрать</option>
                        <?foreach($arResult['typeWork'] as $idType => $type):?>
                            <option value="<?=$idType?>" <?=($arResult['type'] == $idType) ? 'selected' : ''?>><?=$type?></option>
                        <?endforeach?>
                    </select>
                </div>
            </div>

            <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                <div class="w-100 pn-r d-f fd-c rg-12">
                    <label for="orderSpec" class="mb-0 c-8c94a8 f-12">Выберите наименование предмета <span class="c-c90536">*</span></label>
                    <select id="orderSpec" name="spec" class="w-100 pt-16 pr-48 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 ap-n" placeholder="Наименование предмета" required>
                        <option value="">Выбрать</option>
                        <?foreach($arResult['specList'] as $idSpec => $spec):?>
                            <option value="<?=$idSpec?>" <?=($arResult['spec'] == $idSpec) ? 'selected' : ''?>><?=$spec?></option>
                        <?endforeach?>
                    </select>
                </div>
            </div>

            <div class="w-100 xl:w-7/12 pr-16 pl-16 fg-0 fs-0">
                <div class="w-100 d-f fd-c rg-12">
                    <label for="orderDate" class="mb-0 c-8c94a8 f-12">Выберите дату сдачи <span class="c-c90536">*</span></label>
                    <input id="orderDate" class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
                        type="datetime-local" name="date" min="<?=date('Y-m-d\TH:i')?>" value="<?=(!empty($arResult['date'])) ? date('Y-m-d\TH:i', strtotime($arResult['date'])) : ''?>" placeholder="Срок сдачи" required>
                </div>
            </div>

            <div class="w-100 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-12">
                <label for="orderDescription" class="mb-0 c-8c94a8 f-12">Опишите что нужно сделать</label>
                <div class="lang-order--description w-100">
                    <input id="orderDescription" name="description" class="description--input w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 d-n" value="<?=$arResult['description']?>">
                </div>
            </div>

            <div class="w-100 xl:w-7/12 pr-16 pl-16 fg-0 fs-0">
                <div class="w-100 h-100 d-f fd-c rg-12">
                    <div class="w-100 d-f fd-c rg-8 fg-1">
                        <div class="ftw-5 f-18">Вложения</div>
                        <div class="mb-0 c-8c94a8 f-12">Много файлов? Проще загрузить архивом. Допустимые форматы: pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, png, zip, rar. Максимальный размер: 5МБ</div>
                    </div>
                    <div class="w-100 d-f fd-c rg-12 h:c-py tn-all-3">
                        <div class="private--view-file w-100 d-n d-f fd-c rg-12"></div>
                        <div class="w-100 d-f ai-c cg-16">
                            <label for="langOrderFile" class="w-100 m-0 pt-16 pr-24 pb-16 pl-24 d-f jc-b cg-16 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt h:c-py tn-all-3 cr-p h:r-30 tn-all-3">
                                <input type="file" name="file[]" id="langOrderFile" class="private--file d-n" multiple
                                    accept="<?=implode(",", Academy::getAcceptFile())?>">
                                <div class="fg-1 ftw-5">Выберите файлы</div>
                                <div class="d-f fg-0">
                                    <svg width="18" height="18" viewBox="0 0 32 32" fill="currentColor"><path fill-rule="evenodd" d="M14.667 24.299V7.795l-5.664 5.664-1.886-1.886 7.94-7.94L16 2.69l.943.943 7.94 7.94-1.885 1.886-5.664-5.664v16.504zm-11.142.944V21.12H6.19v4.122c0 .736.597 1.333 1.334 1.333h16.95c.737 0 1.334-.597 1.334-1.333V21.12h2.667v4.122a4 4 0 0 1-4 4H7.525a4 4 0 0 1-4-4" clip-rule="evenodd"></path></svg>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-100 xl:w-7/12 pr-16 pl-16 fg-0 fs-0">
                <div class="w-100 h-100 d-f fd-c rg-12">
                    <label for="orderSumm" class="w-100 mb-0 d-f fd-c rg-8 fg-1">
                        <div class="ftw-5 f-18">Введите стоимость</div>
                        <div class="mb-0 c-8c94a8 f-12">Сумма, которую вы готовы заплатить эксперту. Оставте поле пустым если цена договорная</div>
                    </label>
                    <div class="w-100 d-f ai-c cg-16 fg-0 fs-0">
                        <input id="orderSumm" class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
                            type="number" step="1" name="summ" value="<?=$arResult['summ']?>" placeholder="Стоимость">
                    </div>
                </div>
            </div>

            <div class="w-100 xl:w-7/12 <?=comp::get("setcol")?> d-f ai-c cg-16">
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
                <div class="w-100 d-f fd-c rg-12">
                    <div class="mb-0 c-8c94a8 f-12">Нужна проверка на плагиат?</div>
                    <label for="langOrderPlag" class="lang--checkbox mb-0 ws-nw d-f cg-8 cr-p pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3">
                        <input class="d-n" type="checkbox" name="isPlag" id="langOrderPlag" <?=(!empty($arResult['isPlag'])) ? 'checked' : ''?> oninput="(this.checked == true) ? langPlag.classList.remove('d-n') : langPlag.classList.add('d-n')">
                        <div class="flag--view d-f">
                            <svg width="18" height="18" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path class="checked tn-all-3" d="M22.8256 7.9603C22.4459 8.03068 22.1097 8.24901 21.891 8.56727L13.4982 20.7913L9.30042 15.7324L9.09097 15.5258C8.82956 15.3155 8.50348 15.202 8.16797 15.2046C7.83247 15.2072 7.50817 15.3257 7.25003 15.54C6.99188 15.7543 6.81577 16.0513 6.75153 16.3806C6.68728 16.7099 6.73886 17.0513 6.89752 17.3469L7.06333 17.5884L12.4888 24.1338C12.6324 24.307 12.8141 24.4444 13.0198 24.5355C13.2255 24.6265 13.4494 24.6686 13.6741 24.6585C13.8988 24.6483 14.1181 24.5862 14.3147 24.477C14.5113 24.3678 14.68 24.2145 14.8073 24.0291L24.291 10.2167C24.5097 9.89847 24.593 9.50635 24.5227 9.12664C24.4523 8.74693 24.234 8.41073 23.9157 8.192C23.5974 7.97327 23.2053 7.88993 22.8256 7.9603Z"/>
                                <path class="no tn-all-3" d="M29.3905 0.0145458C30.1065 0.0886874 30.7696 0.425647 31.2516 0.960286C31.7336 1.49492 32.0002 2.18927 32 2.90909V29.0909L31.9855 29.3876C31.9173 30.0534 31.6217 30.6753 31.1485 31.1485C30.6753 31.6217 30.0534 31.9173 29.3876 31.9855L29.0909 32H2.90909L2.60946 31.9855C1.94422 31.9167 1.32299 31.6208 0.850363 31.1477C0.377731 30.6745 0.0825609 30.0529 0.0145459 29.3876L1.47845e-07 29.0909V2.90909C-0.000229215 2.18927 0.266421 1.49492 0.748401 0.960286C1.23038 0.425647 1.89346 0.0886874 2.60946 0.0145458L2.90909 0H29.0909L29.3905 0.0145458ZM2.90909 29.0909H29.0909V2.90909H2.90909V29.0909Z"/>
                            </svg>
                        </div>
                        проверка на плагиат
                    </label>
                </div>
            </div>

            <div id="langPlag" class="w-100 xl:w-7/12 <?=comp::get('setcol')?> <?=(!empty($arResult['isPlag'])) ? '' : 'd-n'?>">
                <div class="w-100 h-100 d-f fd-c rg-12">
                    <label for="orderPlag" class="mb-0 c-8c94a8 f-12">Введите уникальность текста</label>
                    <input id="orderPlag" class="pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
                        type="number" step="1" max="100" name="plag" value="<?=$arResult['plag']?>" placeholder="70%">
                </div>
            </div>
        </div>
    </div>

    <div class="w-100 d-f jc-e fg-0 bc-w">
        <button type="submit" name="submit" value="y" class="<?=comp::get("btnBlueFillSm")?> h:r-999">Разместить заказ</button>
    </div>
</form>