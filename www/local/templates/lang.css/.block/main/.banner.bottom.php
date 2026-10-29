<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$user = CurrentUser::get();
$lang = Util::getLang();
$langText = "LangText{$lang}";

$help = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'link'], ['ID' => $arParams['elementId']]);

if(!empty($help) && !empty($help->getId())):?>
    <section class="w-100">
        <div class="<?=comp::get('container-full')?>">

            <div class="w-100 pn-r of-h r-24 bc-w">
                <?if(!empty($help->getDetailPicture())):?>
                    <?=Data::getImageId($help->getDetailPicture(), [
                        "title" => strip_tags($help->getName()),
                        "alt" => strip_tags($help->getName()),
                        "loading" => "lazy",
                        "class" => "pn-a t-0 rt-0 b-0 l-0 w-100 h-100 oft-cv op-c r-12",
                    ])?>
                <?endif?>

                <div class="<?=comp::get('container-p')?> pn-r" <?=Data::iblockElementEditAddId($help)?>>
                    <div class="<?=comp::get('row')?>">
                        <div class="w-100 sm:w-8/12 md:w-7/12 lg:w-7/12 xl:w-6/12 <?=comp::get('setcol')?> pn-r z-1">
                            <div class="w-100 h-100 d-f fd-c rg-24 jc-c pl-32 pby-48 lg:pby-48 xl:pby-86 xxl:pl-0">
                                <h2 class="<?=comp::get("h2")?>"><?=$help->getName()?></h2>
                                <?if(!empty($help->getPreviewText())):?>
                                    <div class="f-14 md:f-12 lg:f-14 xl:f-16"><?=$help->getPreviewText()?></div>
                                <?endif?>
                                <?if(!empty($help->getLink())):?>
                                    <div class="w-100 d-f jc-s">
                                        <?if(!empty($user->getId())):?>
                                            <a href="<?=$help->getLink()->getValue()?>" class="<?=comp::get("btnBlueLg")?>"><?=$help->getLink()->getDescription()?></a>
                                        <?else:?>
                                            <button class="lang-form--order-add <?=comp::get("btnBlueLg")?>"><?=$help->getLink()->getDescription()?></button>
                                        <?endif?>
                                    </div>
                                <?endif?>
                            </div>
                        </div>
                        <?if(!empty($help->getPreviewPicture())):?>
                            <div class="w-100 sm:w-4/12 md:w-5/12 lg:w-5/12 xl:w-6/12 <?=comp::get('setcol')?> d-f ai-e jc-e">
                                <?=Data::getImageId($help->getPreviewPicture(), [
                                    "title" => strip_tags($help->getName()),
                                    "alt" => strip_tags($help->getName()),
                                    "loading" => "lazy",
                                    "class" => "w-a h-100 pn-a sm:pn-s t-0 pt-64 sm:pt-0 sm:h-300px md:w-a md:h-300px xl:w-a xl:h-100 fr-b4 md:fr-n",
                                ])?>
                            </div>
                        <?endif?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?endif?>