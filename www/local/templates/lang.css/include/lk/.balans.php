<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\UserTable;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Loader;

Loader::includeModule('sale');

$image = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => 248]);
$historyTitle = Data::getElementObject($langText, ['NAME'], ['ID' => 313]);
$blockTitle = Data::getElementObject($langText, ['NAME'], ['ID' => 314]);

$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;

$name = "Баланс";
$title = "Баланс";
?>

<div class="w-100 xl:w-8/12 <?=comp::get("setcol")?>">
    <div class="w-100 h-100 pn-r of-h p-0 md:p-32 md:bc-w md:r-24">
        <?if(!empty($image) && !empty($image->getPreviewPicture())) {
            echo Data::getImageId($image->getPreviewPicture(), [
                "alt" => strip_tags($image->getName()),
                "fetchpriority" => "high",
                "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c d-n md:d-f",
            ]);
        }?>

        <div class="w-100 d-f fd-c rg-32 pn-r">
            <h1 class="<?=comp::get('h2')?>"><?=$APPLICATION->ShowTitle(false)?></h1>

            <?$APPLICATION->IncludeComponent("lang:account","",[],false,['HIDE_ICONS' => 'Y']);?>

            <div class="w-100 d-f fd-c rg-32" <?=Data::iblockElementEditAddId($historyTitle)?>>
                <h2 class="<?=comp::get("h2page")?>"><?=$historyTitle->getName()?></h2>

                <div class="w-100 d-f fd-c rg-32">

                    <div class="w-100 d-f fd-c rg-0 f-12 md:f-14 r-8 of-h bc-w o-2">

                        <div class="w-100 p-16 pt-12 pb-12 c-636363">
                            <div class="<?=comp::get("row")?>">
                                <div class="w-6/12 <?=comp::get("setcol")?>">Операция</div>
                                <div class="w-3/12 <?=comp::get("setcol")?>">Сумма</div>
                                <div class="w-3/12 <?=comp::get("setcol")?>">Дата</div>
                            </div>
                        </div>

                        <div class="lang-transact--items w-100 d-f fd-c">
                            <?$transactParams = $APPLICATION->includeComponent("lang:transact","",[],false,['HIDE_ICONS'=>'Y'])?>
                        </div>
                    </div>

                    <?if($transactParams['count'] > 3):
                        $filter = $transactParams['filter'];
                        ?>

                        <div class="lang-transact--filter w-100 o-1">
                            <div class="<?=comp::get("row")?> rg-12 f-14">

                                <div class="w-100 sm:w-6/12 lg:w-5/12 xl:w-4/12 <?=comp::get("setcol")?>">
                                    <div class="w-100 pn-r h:c-py h:bcr-py d-f fd-c rg-8">
                                        <label for="transactDebit" class="mb-0 c-8c94a8 f-12">Тип операции</label>
                                        <select id="transactDebit" name="debit" class="<?=comp::get("inputTextLg")?>">
                                            <option value="" <?=(!isset($filter['debit'])) ? 'selected' : ''?>>Не выбрано</option>
                                            <option value="y" <?=($filter['debit'] == 'y') ? 'selected' : ''?>>Пополнение</option>
                                            <option value="n" <?=($filter['debit'] == 'n') ? 'selected' : ''?>>Списание</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="w-100 sm:w-6/12 lg:w-5/12 xl:w-4/12 <?=comp::get("setcol")?>">
                                    <div class="w-100 d-f fd-c rg-8">
                                        <label for="transactDate" class="mb-0 c-8c94a8 f-12">Дата операции</label>
                                        <input id="transactDate" value="<?=(isset($filter['date'])) ? date('Y-m-d', strtotime($filter['date'])) : ''?>" max="<?=date('Y-m-d')?>"
                                            class="<?=comp::get("inputTextLg")?>" type="date" name="date" placeholder="Дата">
                                    </div>
                                </div>

                                <?/*<div class="w-100 lg:w-2/12 xl:w-4/12 <?=comp::get("setcol")?> d-f jc-e">
                                    <button class="<?=comp::get("btnGreenSm")?>">Найти</button>
                                </div>*/?>

                            </div>
                        </div>

                        
                    <?endif;?>

                    <script><?=file_get_contents("{$templatePath}/js/main/util.js")?></script>
                    <script><?=file_get_contents("{$templatePath}/js/lk/filterTransact.js")?></script>
                    <script><?=file_get_contents("{$templatePath}/js/lk/transact.js")?></script>
                    <script>new LG.Transact()</script>

                </div>
            </div>

            <div class="w-100 d-f fd-c rg-32" <?=Data::iblockElementEditAddId($blockTitle)?>>
                <h2 class="<?=comp::get("h2page")?>"><?=$blockTitle->getName()?></h2>

                <div class="w-100 d-f fd-c rg-32">
                    <div class="w-100 d-f fd-c rg-0 f-12 md:f-14 r-8 of-h bc-w">

                        <div class="w-100 p-16 pt-12 pb-12 c-636363">
                            <div class="<?=comp::get("row")?>">
                                <div class="w-6/12 <?=comp::get("setcol")?>">Заказ</div>
                                <div class="w-3/12 <?=comp::get("setcol")?>">Сумма</div>
                                <div class="w-3/12 <?=comp::get("setcol")?>">Дата</div>
                            </div>
                        </div>

                        <?
                        $user = CurrentUser::get();
                        $langTransact = "LangTransact";
                        $transactList = Data::getElementsObject($langTransact, [
                            'order' => ['DATE_CREATE' => 'DESC', 'ID' => 'DESC'],
                            'select' => ['NAME', 'DATE_CREATE', 'uTo', 'order', 'to.ITEM.XML_ID', 'sum'],
                            'filter' => ['uTo.VALUE' => $user->getId()],
                            'cache' => Data::getCache(0)
                        ]);
                        if(!empty($transactList) && !empty($transactList->getIdList())):?>
                            <?foreach($transactList as $transact):
                                $modSum = "";
                                $sum = \SaleFormatCurrency($transact->getSum()->getValue(), "RUB");
                                $date = date("d.m.Y", strtotime($transact->getDateCreate()));

                                if($transact->getTo()->getItem()->getXmlId() == 'credit') {
                                    $sum = "+{$sum}";
                                    $modSum = "c-21A78E ftw-5";
                                }
                                ?>
                                <div class="w-100 btw-1 bts-s btc-EFEFEF p-16 pt-12 pb-12 h:bc-EFEFEF">
                                    <div class="<?=comp::get("row")?>">
                                        <div class="w-6/12 <?=comp::get("setcol")?>"><?=$transact->getName()?></div>
                                        <div class="w-3/12 <?=comp::get("setcol")?> <?=$modSum?>"><?=$sum?></div>
                                        <div class="w-3/12 <?=comp::get("setcol")?>"><?=$date?></div>
                                    </div>
                                </div>
                            <?endforeach?>
                        <?else:?>
                            <div class="w-100 btw-1 bts-s btc-EFEFEF p-16 pt-12 pb-12 ta-c ftw-5">Нет операций</div>
                        <?endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?
$APPLICATION->SetTitle(strip_tags($title));
$APPLICATION->SetPageProperty("title", strip_tags($name));
// $APPLICATION->SetPageProperty("description", strip_tags($seo['ELEMENT_META_DESCRIPTION']));
// $APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
?>