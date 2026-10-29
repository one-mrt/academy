<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langService = "LangService{$lang}";
$langExample = "LangExample{$lang}";

$user = CurrentUser::get();
$exampleList = Data::getElementsObject($langExample, [
    'select' => ['NAME', 'PREVIEW_TEXT', 'CODE', 'IBLOCK_SECTION_ID', 'time', 'price', 'spec.ELEMENT', 'file.VALUE', 'tp.ITEM']
]);

if(!empty($exampleList) && !empty($exampleList->getIdList())):?>
    <section class="w-100">
        <div class="<?=comp::get("container")?> d-f fd-c rg-32">
            <?foreach($exampleList as $example):?>
                <div class="w-100 p-24 md:p-32 r-24 bc-w" <?=Data::iblockElementEditAddId($example)?>>
                    <div class="<?=comp::get("row")?> rg-24">

                        <div class="w-100 lg:w-8/12 xl:w-6/12 <?=comp::get("setcol")?>">
                            <div class="w-100 h-100 d-f fd-c rg-24">

                                <div class="w-100 d-f fw-w md:fw-n ai-c rg-16 cg-24">
                                    <?if(!empty($example->getTp()->getItem()->getValue())):?>
                                        <div class="f-20 bi-pyg bgc-t tf-t ff-ud tt-l ftw-5 lh-1 o-2 md:o-1 w-100 md:w-a"><?=$example->getTp()->getItem()->getValue()?></div>
                                    <?endif;?>
                                    <?if(!empty($example->getSpec()) && !empty($example->getSpec()->getElement())):?>
                                        <div class="f-8 pt-4 pr-8 pb-4 pl-8 r-4 bi-sbb c-w lh-1 o-1 md:o-2 ws-nw"><?=$example->getSpec()->getElement()->getName()?></div>
                                    <?endif?>
                                </div>

                                <?if(!empty($example->getName())):?>
                                    <div class="f-14"><?=$example->getName()?></div>
                                <?endif?>

                                <?if(!empty($example->getPrice()) && !empty($example->getTime())):?>
                                    <div class="w-100 d-f jc-b fw-w xl:fw-n rg-8 cg-24">

                                        <?if(!empty($example->getPrice())):?>
                                            <div class="d-f cg-8 lh-1 ai-e fg-1 fs-0">
                                                <div class="tt-l f-12 xl:f-14 c-7A7A7A">Cтоимость:</div>
                                                <div class="xl:f-18 ftw-6"><?=$example->getPrice()->getValue()?></div>
                                            </div>
                                        <?endif?>

                                        <?if(!empty($example->getTime())):?>
                                            <div class="d-f cg-8 lh-1 ai-e fg-1 fs-0">
                                                <div class="tt-l f-12 xl:f-14 c-7A7A7A">Время выполнения:</div>
                                                <div class="xl:f-18 ftw-6"><?=$example->getTime()->getValue()?></div>
                                            </div>
                                        <?endif?>

                                    </div>
                                <?endif;?>
                            </div>
                        </div>

                        <div class="w-100 lg:w-4/12 xl:w-6/12 <?=comp::get("setcol")?> d-f fw-w ai-c jc-e rg-16 cg-24">
                            <?if(!empty($example->getFile()) && !empty($example->getFile()->getValue())):
                                $file = \CFile::GetPath($example->getFile()->getValue());
                                ?>
                                <a href="<?=$file?>" target="_blank" class="<?=comp::get("btnGreenBorderSm", "w-100 md:w-a lg:w-100 xl:w-a")?>">Cмотреть работу</a>
                            <?endif;?>

                            <?if(!empty($user->getId())):?>
                                <a href="/lk/order/add/" class="<?=comp::get("btnGreenSm", "w-100 md:w-a lg:w-100 xl:w-a")?>">Cоздать заказ</a>
                            <?else:?>
                                <button class="lang-form--order-add <?=comp::get("btnGreenSm", "w-100 md:w-a lg:w-100 xl:w-a")?>">Cоздать заказ</button>
                            <?endif?>
                        </div>
                    </div>
                </div>
            <?endforeach?>
        </div>
    </section>
<?endif?>