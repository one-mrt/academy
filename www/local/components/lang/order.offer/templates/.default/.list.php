<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
?>

<?foreach($arResult['offer'] as $offer):?>
    <div class="w-100 d-f fd-c rg-0 <?if($offer['selected'] == 'y'):?>bc-008000 c-w<?else:?>bc-w<?endif;?> r-8" id="comment<?=$offer['id']?>">

        <div class="w-100 pby-16 pix-16">
            <div class="<?=comp::get("row8")?> rg-16">

                <a href="<?=$offer['user']['url']?>" target="_blank" class="w-a <?=comp::get("col8")?> d-f">
                    <?if(!empty($offer['user'])):?>
                        <?=Data::getImageId($offer['user']['photo'], [
                            "title" => $offer['user']['name'],
                            "alt" => $offer['user']['name'],
                            "fetchpriority" => "high",
                            "class" => "w-40px h-40px r-100 oft-cv op-c",
                        ])?>
                    <?else:?>
                        <div class="w-40px h-40px bc-w r-100"></div>
                    <?endif?>
                </a>

                <div class="w-a md:w-8/12 <?=comp::get("col8", 'fg-1 md:fg-0', 'fg-0')?> d-f fd-c rg-8">
                    <div class="w-100 d-f ai-c cg-8">
                        <div class="ws-nw f-12"><?=$offer['user']['name']?></div>
                        <div class="w-100 ta-l f-12 c-8c94a8"><?=$offer['date']?></div>
                    </div>

                    <?if(!empty($offer['comment'])):?>
                        <div class="w-100 f-12 md:f-14"><?=$offer['comment']?></div>
                    <?endif;?>
                </div>

                <div class="w-a md:w-2/12 <?=comp::get("col8")?> d-f fd-c jc-c">
                    <div class="w-100 ta-r ftw-5 f-14 md:f-16 xl:f-18"><?=$offer['sum']?></div>
                </div>

            </div>
        </div>

        
        <?if($offer['preorder'] == 'y'):?>
            <?if($arResult['isCustomer'] == 'n'):?>
                <div class="w-100 pb-16 pix-16">
                    <div class="w-100 d-f ai-c jc-e cg-12 fg-1">
                        <div class="f-10 c-8c94a8 fg-0">Предложение действительно еще <?=$offer['timeEnd']->h?>ч <?=$offer['timeEnd']->i?>м <?=$offer['timeEnd']->s?>с</div>
                        <button class="answer--cancel <?=comp::get("btnOfferCancel")?> ws-nw">Отказываюсь</button>
                        <button class="answer--accept <?=comp::get("btnOfferAccept")?> ws-nw">Подтверждаю</button>
                    </div>
                </div>
            <?else:?>
                <div class="w-100 pb-16 pix-16">
                    <div class="w-100 d-f ai-c cg-12 fg-1 jc-e">
                        <div class="f-10 c-8c94a8">Если исполнитель не подтвердит выполнение заказа через <?=$offer['timeEnd']->h?>ч <?=$offer['timeEnd']->i?>м <?=$offer['timeEnd']->s?>с можно выбрать другого</div>
                        <div class="pby-4 pix-8 r-4 c-w bc-008000 bw-1 bs-s bcr-w f-12 tt-l ws-nw">На подтверждении</div>
                    </div>
                </div>
            <?endif;?>
        <?endif;?>

        <?if($offer['selected'] != 'y' && $offer['preorder'] != 'y' && empty($arResult['selected'])):?>
            <?if($arResult['isCustomer'] == 'y'):?>
                <div class="w-100 pb-16 pix-16">
                    <div class="w-100 d-f cg-12 fg-1 jc-e">
                        <button class="offer--cancel <?=comp::get("btnOfferCancel")?>">Отказаться</button>
                        <button class="offer--accept <?=comp::get("btnOfferAccept")?>">Принять</button>
                    </div>
                </div>
            <?elseif($arResult['isCustomer'] == 'n'):?>
                <div class="w-100 pb-16 pix-16">
                    <div class="w-100 d-f cg-12 fg-1 jc-e">
                        <button class="offer--withdraw <?=comp::get("btnOfferWithdraw")?>">Отозвать</button>
                    </div>
                </div>
            <?endif?>
        <?endif?>

        <?if($offer['selected'] == 'y'):?>
            <div class="w-100 pb-16 pix-16">
                <div class="w-100 fg-1 d-f ai-s jc-e">
                    <div class="pby-4 pix-8 r-4 c-w bc-008000 bw-1 bs-s bcr-w f-12 tt-l">Выбрано</div>
                </div>
            </div>
        <?endif?>
    </div>
<?endforeach;?>

<?if($arResult['nav']->getPageCount() > $arResult['nav']->getCurrentPage()):?>
    <div class="lang-offer--nav w-100 d-f jc-c">
        <button class="<?=comp::get("btnGreenSm")?>">Показать еще</button>
    </div>
<?endif;?>