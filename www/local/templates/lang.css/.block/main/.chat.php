<?
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$user = CurrentUser::get();
$lang = Data::getLang();
$langText = "LangText{$lang}";

$chat = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'link'], ['ID' => $arParams['elementId']]);

if(!empty($chat) && !empty($chat->getId())):?>
    <section class="w-100">
        <div class="<?=comp::get('container-full')?>">

            <div class="w-100 pn-r of-h r-24 bc-w pix-32 pby-48 xxl:pix-0 xxl:pby-0">
                <?if(!empty($chat->getDetailPicture())):?>
                    <?=Data::getImageId($chat->getDetailPicture(), [
                        "title" => strip_tags($chat->getName()),
                        "alt" => strip_tags($chat->getName()),
                        "loading" => "lazy",
                        "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c md:op-c r-12",
                    ])?>
                <?endif?>

                <div class="<?=comp::get('container-p')?> pn-r" <?=Data::iblockElementEditAddId($chat)?>>
                    <div class="<?=comp::get('row')?>">
                        <div class="w-100 md:w-8/12 xl:w-6/12 <?=comp::get('setcol')?>">
                            <div class="w-100 h-100 d-f fd-c rg-24 jc-c xxl:pby-86">
                                <h2 class="<?=comp::get("h2")?>"><?=$chat->getName()?></h2>
                                <?if(!empty($chat->getPreviewText())):?>
                                    <div class="f-14 xl:f-16"><?=$chat->getPreviewText()?></div>
                                <?endif?>
                                <?if(!empty($chat->getLink())):?>
                                    <div class="w-100 d-f jc-s">
                                        <?if(!empty($user->getId())):?>
                                            <a href="<?=$chat->getLink()->getValue()?>" class="<?=comp::get("btnGreenLg")?>"><?=$chat->getLink()->getDescription()?></a>
                                        <?else:?>
                                            <button class="lang-form--order-add <?=comp::get("btnGreenLg")?>"><?=$chat->getLink()->getDescription()?></button>
                                        <?endif?>
                                    </div>
                                <?endif?>
                            </div>
                        </div>
                        
                        <?if(!empty($chat->getPreviewPicture())):?>
                            <div class="w-100 xl:w-6/12 <?=comp::get('setcol')?> d-f jc-e">
                                <?=Data::getImageId($chat->getPreviewPicture(), [
                                    "title" => strip_tags($chat->getName()),
                                    "alt" => strip_tags($chat->getName()),
                                    "loading" => "lazy",
                                    "class" => "w-a h-100 op-r md:op-c",
                                ])?>
                            </div>
                        <?endif?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?endif?>