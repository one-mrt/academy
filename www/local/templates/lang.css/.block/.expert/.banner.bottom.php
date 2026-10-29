<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$user = CurrentUser::get();
$langText = "LangText{$lang}";

if(!empty($arParams['elementId'])):
    $exp = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'link'], ['ID' => $arParams['elementId']]);

    if(!empty($exp) && !empty($exp->getId())):?>
        <section class="w-100">
            <div class="<?=comp::get('container-full')?> d-f fd-c rg-0">
                <div class="w-100 of-h pn-r pix-32 pby-48 xl:pby-64 r-24 bc-w" <?=Data::iblockElementEditAddId($exp)?>>

                    <?if(!empty($exp->getPreviewPicture())):?>
                        <?=Data::getImageId($exp->getPreviewPicture(), [
                            "alt" => strip_tags($exp->getName()),
                            "loading" => "lazy",
                            "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
                        ])?>
                    <?endif?>

                    <div class="<?=comp::get('container-p')?> d-f fd-c rg-32 xl:rg-48 pn-r">
                        <div class="<?=comp::get("row")?> rg-32">
                            <div class="w-100 lg:w-8/12 <?=comp::get("setcol")?> d-f fd-c rg-16">
                                <h2 class="<?=comp::get('h2')?>"><?=$exp->getName()?></h2>
                                <?if(!empty($exp->getPreviewText())):?>
                                    <div class="f-14"><?=$exp->getPreviewText()?></div>
                                <?endif?>
                            </div>
                            <?if(!empty($exp->getLink()) && !empty($exp->getLink()->getValue())):?>
                                <div class="w-100 lg:w-4/12 <?=comp::get("setcol")?> d-f ai-c jc-e">
                                    <?if(!empty($user->getId())):?>
                                        <a href="<?=$exp->getLink()->getValue()?>" class="<?=comp::get("btnGreenLg")?>"><?=$exp->getLink()->getDescription()?></a>
                                    <?else:?>
                                        <button class="lang-form--reg <?=comp::get("btnGreenLg")?>"><?=$exp->getLink()->getDescription()?></button>
                                    <?endif?>
                                </div>
                            <?endif?>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    <?endif?>
<?endif?>