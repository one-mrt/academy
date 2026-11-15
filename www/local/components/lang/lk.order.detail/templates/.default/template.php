<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;

$order = &$arResult['order'];
?>


<div class="w-100 h-100 ofx-h ofy-a p-24 xl:p-32 d-f fd-c rg-24 bc-fafafa r-12">
    <div class="w-100 d-f fd-c rg-12">
        <h2 class="mb-0 fg-1 f-16 md:f-18 <?=comp::get("fontUd")?>"><?=$order['name']?></h2>
    </div>

    <div class="w-100 d-f ai-c jc-e cg-16 bc-w p-16 r-12 pn-sy t-0 bw-1 bs-s bcr-fafafa">
        <?if(!empty($order['reviewScore'])):?>
            <div class="lang--review d-f ai-c jc-s" title="<?=$order['reviewName']?>">
                <?
                $check = false;
                for($k = 1; $k < 6; $k++):?>
                    <div class="score--wrap pn-r d-f <?=(!$check) ? 'c-e64231' : 'c-t'?> tn-all-3">
                        <svg class="lang-review--score-radio-svg" width="24" height="24" viewBox="0 0 46 44" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M43.7378 17.2735L29.9638 15.2929L23.7816 2.77855L23.3333 1.8711L22.885 2.77855L16.7029 15.2929L2.92884 17.2735L1.92495 17.4179L2.65077 18.1262L12.6445 27.8799L10.2737 41.6248L10.1005 42.629L11.0007 42.1515L23.3333 35.6101L35.666 42.1515L36.5662 42.629L36.393 41.6248L34.0221 27.8799L44.0159 18.1262L44.7417 17.4179L43.7378 17.2735Z" stroke="#E64231"/>
                        </svg>
                    </div>
                    <?
                    if(empty($order['reviewScore']) && $k == 1) {
                        $check = true;
                    } elseif($order['reviewScore'] == $k) {
                        $check = true;
                    }
                    ?>
                <?endfor;?>
            </div>
        <?endif;?>
        <div class="">
            <div class="order--status pn-r">
                <div class="lang-status--close w-100 h-100 pn-f t-0 rt-0 b-0 l-0 z-1 d-f d-n bf-b6"></div>
                <button title="Статус заказа" class="status--selected pix-12 pby-4 bc-py c-w m-0 bw-1 bs-s bcr-py r-999 lh-1 f-12 tt-l h:bc-w h:c-dt tn-all-3 pn-r z-2"><?=$order['status']?></button>
                <?if(!empty($arResult['statusEdit'])):?>
                    <div class="status--list mnw-100 d-f d-n fd-c bc-w bw-1 bs-s bcr-py r-4 pn-a t-100% z-2">
                        <?foreach($arResult['statusEdit'] as $statusId => $statusName):?>
                            <div class="w-100 d-f">
                                <input id="langStatus<?=$statusId?>" type="radio" name="status" class="d-n" value="<?=$statusId?>">
                                <label for="langStatus<?=$statusId?>" class="w-100 m-0 f-12 tt-l pby-4 pix-12 cr-p ws-nw h:bc-py h:c-w h:bcr-py tn-all-3"><?=$statusName?></label>
                            </div>
                        <?endforeach;?>
                    </div>
                <?endif;?>
            </div>
        </div>
        <div title="№ заказ" class="xxl:f-20 <?=comp::get("fontUd")?>">#<?=$order['id']?></div>
    </div>

    <div class="w-100 bc-w p-16 r-12 f-14">
        <div class="<?=comp::get('row')?> rg-16">
            <div class="w-100 <?=comp::get("setcol")?> ftw-6 d-f cg-16">
                <div class="fg-1">Описание заказа</div>
                <?if($arResult['editDraft'] == 'y'):?>
                    <button class="description--edit <?=comp::get("btnEditOrder")?>">Редактировать</button>
                <?endif;?>
            </div>
            <div class="w-100 <?=comp::get("setcol")?> mxh-200px of-h">
                <div class="w-100 lang-order--description mb-0|lc:p"><?=$order['description']?></div>
            </div>
        </div>
    </div>
    
    <?if(!empty($order['file'])):?>
        <div class="w-100 bc-w p-16 r-12 f-14">
            <div class="<?=comp::get('row')?> rg-16">
                <div class="w-100 <?=comp::get("setcol")?> ftw-6 d-f cg-16">
                    <div class="fg-1">Файлы</div>
                    <?if($arResult['editDraft'] == 'y'):?>
                        <button class="file--edit <?=comp::get("btnEditOrder")?>">Редактировать</button>
                    <?endif;?>
                </div>
                <div class="w-100 <?=comp::get("setcol")?>">
                    <div class="d-f fw-w mi--4 rg-16">
                        <?foreach($order['file'] as $file):?>
                            <div class="w-4/12 sm:w-3/12 md:w-2/12 lg:w-3/12 xxl:w-2/12 pix-8 fg-0 fs-0">
                                <a href="<?=$file['src']?>" class="w-100 h-100 pix-12 pby-16 d-f fd-c ai-c rg-8 bw-1 bs-s bcr-f5f6f6 r-8 bc-w c-dt h:bc-py h:c-w tn-all-3" title="<?=$file['name']?>">
                                    <div class="d-f w-a>svg h-48px>svg se-cc>svg se-cc|pa">
                                        <?if(!empty($file['svg'])):?>
                                            <?=$file['svg']?>
                                        <?else:?>
                                            <svg width="82" height="102" viewBox="0 0 82 102" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M20.75 75.75H60.75M20.75 55.75H40.75M45.75 3.25V5.75C45.75 19.89 45.75 26.965 50.145 31.355C54.535 35.75 61.61 35.75 75.75 35.75H78.25M80.75 44.035V60.75C80.75 79.605 80.75 89.035 74.89 94.89C69.03 100.745 59.605 100.75 40.75 100.75C21.895 100.75 12.465 100.75 6.61 94.89C0.755 89.03 0.75 79.605 0.75 60.75V38.03C0.75 21.805 0.750001 13.69 5.18 8.195C6.07469 7.08544 7.08544 6.07469 8.195 5.18C13.7 0.75 21.805 0.75 38.03 0.75C41.555 0.75 43.32 0.75 44.935 1.32C45.2683 1.44 45.5967 1.57667 45.92 1.73C47.47 2.47 48.715 3.715 51.21 6.21L74.89 29.89C77.785 32.78 79.225 34.23 79.99 36.065C80.75 37.905 80.75 39.945 80.75 44.035Z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        <?endif;?>
                                    </div>
                                    <div class="w-100 f-10 of-h ws-nw of-h to-e"><?=$file['name']?></div>
                                </a>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
                <div class="w-100 <?=comp::get("setcol")?> d-f jc-e">
                    <button class="file--download m-0 p-0 bc-t bw-0 c-8c94a8 h:c-py tn-all-3 f-10 ftw-5 cr-p">Скачать архивом</button>
                </div>
            </div>
        </div>
    <?endif;?>
    
    <?if($arResult['showResult'] == 'y'):?>
        <div class="w-100 bc-w p-16 r-12 f-14">
            <div class="<?=comp::get('row')?> rg-16">
                <div class="w-100 <?=comp::get("setcol")?> ftw-6 d-f cg-16">
                    <div class="fg-1">Результаты</div>
                    <?if($arResult['editFileRes'] == 'y'):?>
                        <button class="result--edit <?=comp::get("btnEditOrder")?>">Загрузить</button>
                    <?endif;?>
                </div>
                <div class="w-100 <?=comp::get("setcol")?> f-10 c-8c94a8">
                    Много файлов? Проще загрузить архивом. Допустимые форматы: pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, png, zip, rar. Максимальный размер: 5МБ.
                    Результаты может загрузить только <span class="ftw-5">«Исполнитель»</span>. Загрузка дополнительных файлов не поддерживается. При повторной загрузке старые файлы будут удалены.
                </div>
                <?if(!empty($order['fileRes'])):?>
                    <div class="w-100 <?=comp::get("setcol")?>">
                        <div class="d-f fw-w mi--4 rg-16">
                            <?foreach($order['fileRes'] as $file):?>
                                <div class="w-100 w-4/12 sm:w-3/12 md:w-2/12 lg:w-3/12 xxl:w-2/12 pix-8 fg-0 fs-0">
                                    <a href="<?=$file['src']?>" class="w-100 h-100 pix-12 pby-16 d-f fd-c ai-c rg-8 bw-1 bs-s bcr-f5f6f6 r-8 bc-w c-dt h:bc-py h:c-w tn-all-3" title="<?=$file['name']?>">
                                        <div class="d-f w-a>svg h-48px>svg se-cc>svg se-cc|pa"><?=$file['svg']?></div>
                                        <div class="w-100 f-10 of-h ws-nw of-h to-e"><?=$file['name']?></div>
                                    </a>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                    <div class="w-100 <?=comp::get("setcol")?> d-f jc-e">
                        <button class="result--download m-0 p-0 bc-t bw-0 c-8c94a8 h:c-py tn-all-3 f-10 ftw-5 cr-p">Скачать архивом</button>
                    </div>
                <?endif;?>
            </div>
        </div>
    <?endif;?>

    <?/*
    <div class="w-100 bc-w p-16 r-12 f-14">
        <div class="<?=comp::get('row')?> rg-12">
            <div class="w-100 sm:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> ftw-6">Исполнитель</div>
            <div class="w-100 sm:w-7/12 xl:w-7/12 <?=comp::get("setcol")?>"><?=$order['answer']?></div>

            <div class="w-100 sm:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> ftw-6">Статус заказа</div>
            <div class="w-100 sm:w-7/12 xl:w-7/12 <?=comp::get("setcol")?> d-f jc-s">
                <div class="order--status pn-r">
                    <button class="status--selected pix-12 pby-4 bc-py c-w m-0 bw-1 bs-s bcr-py r-999 lh-1 f-12 tt-l h:bc-w h:c-dt tn-all-3"><?=$order['status']?></button>
                    <?if(!empty($arResult['statusEdit'])):?>
                        <div class="status--list mnw-100 d-f d-n fd-c bc-w bw-1 bs-s bcr-py r-4 pn-a t-100% z-1">
                            <?foreach($arResult['statusEdit'] as $statusId => $statusName):?>
                                <div class="w-100 d-f">
                                    <input id="langStatus<?=$statusId?>" type="radio" name="status" class="d-n" value="<?=$statusId?>">
                                    <label for="langStatus<?=$statusId?>" class="w-100 m-0 f-12 tt-l pby-4 pix-12 cr-p ws-nw h:bc-py h:c-w h:bcr-py tn-all-3"><?=$statusName?></label>
                                </div>
                            <?endforeach;?>
                        </div>
                    <?endif;?>
                </div>
            </div>
        </div>
    </div>
    */?>

    <div class="w-100 bc-w p-16 r-12 f-14">
        <div class="<?=comp::get('row')?> rg-12">
            <?if($arResult['editDraft'] == 'y'):?>
                <div class="w-100 <?=comp::get("setcol")?> d-f jc-e">
                    <button class="all--edit <?=comp::get("btnEditOrder")?>">Редактировать</button>
                </div>
            <?endif;?>
            
            <?/*<div class="w-100 sm:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> ftw-6">№ заказ</div>
            <div class="w-100 sm:w-7/12 xl:w-7/12 <?=comp::get("setcol")?>"><?=$order['id']?></div>*/?>

            <div class="w-100 sm:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> ftw-6">Исполнитель</div>
            <div class="w-100 sm:w-7/12 xl:w-7/12 <?=comp::get("setcol")?>"><?=$order['answer']?></div>

            <div class="w-100 sm:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> ftw-6">Раздел</div>
            <div class="w-100 sm:w-7/12 xl:w-7/12 <?=comp::get("setcol")?>"><?=$order['spec']?></div>

            <div class="w-100 sm:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> ftw-6">Тип работы</div>
            <div class="w-100 sm:w-7/12 xl:w-7/12 <?=comp::get("setcol")?>"><?=$order['work']?></div>

            <div class="w-100 sm:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> ftw-6">Антиплагиат</div>
            <div class="w-100 sm:w-7/12 xl:w-7/12 <?=comp::get("setcol")?>"><?=(intval($order['anti']) > 0) ? "{$order['anti']}%" : $order['anti']?></div>

            <div class="w-100 sm:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> ftw-6">Срок сдачи</div>
            <div class="w-100 sm:w-7/12 xl:w-7/12 <?=comp::get("setcol")?>"><?=$order['date']?></div>

            <div class="w-100 sm:w-5/12 xl:w-5/12 <?=comp::get("setcol")?> ftw-6">Цена</div>
            <div class="w-100 sm:w-7/12 xl:w-7/12 <?=comp::get("setcol")?>"><?=$order['price']?></div>
        </div>
    </div>


    <?if($arResult['pay'] == 'y'):?>
        <div class="w-100 d-f jc-e pn-sy b-0">
            <button class="order--pay <?=comp::get("btnBlueFillSm")?>">Оплатить</button>
        </div>
    <?endif;?>

    <?if($arResult['reviewAdd'] == 'y'):?>
        <div class="w-100 d-f jc-e pn-sy b-0">
            <button class="review--add <?=comp::get("btnBlueFillSm")?>">Оставить отзыв</button>
        </div>
    <?endif;?>
    
</div>