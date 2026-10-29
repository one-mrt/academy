<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Util::getLang();
$langText = "LangText{$lang}";

$example = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'link'], ['ID' => $arParams["elementId"]]);

if(!empty($example) && !empty($example->getId())):?>
    <section class="w-100">
        <div class="<?=comp::get('container-full')?>">
            <div class="w-100 pn-r of-h r-24 bc-w">
                <?if(!empty($example->getDetailPicture())):?>
                    <?=Data::getImageId($example->getDetailPicture(), [
                        "title" => strip_tags($example->getName()),
                        "alt" => strip_tags($example->getName()),
                        "loading" => "lazy",
                        "class" => "pn-a t-0 rt-0 b-0 l-0 w-100 h-100 oft-cv op-c r-12",
                    ])?>
                <?endif?>

                <div class="<?=comp::get('container-p')?> pn-r" <?=Data::iblockElementEditAddId($example)?>>
                    <div class="<?=comp::get('row')?>">
                        <div class="w-100 md:w-7/12 lg:w-7/12 xl:w-6/12 <?=comp::get('setcol')?>">
                            <div class="w-100 h-100 d-f fd-c rg-24 jc-c pix-32 pby-48 xxl:pix-0 lg:pby-64 xxl:pby-86">
                                <h2 class="<?=comp::get("h2")?>"><?=$example->getName()?></h2>
                                <?if(!empty($example->getPreviewText())):?>
                                    <div class="f-14 xl:f-16"><?=$example->getPreviewText()?></div>
                                <?endif?>
                                <?if(!empty($example->getLink())):?>
                                    <div class="w-100 d-f jc-s">
                                        <a href="<?=$example->getLink()->getValue()?>" class="<?=comp::get("btnPurpleLg")?>"><?=$example->getLink()->getDescription()?></a>
                                    </div>
                                <?endif?>
                            </div>
                        </div>
                        <div class="w-100 md:w-5/12 lg:w-5/12 xl:w-6/12 <?=comp::get('setcol')?> d-f md:jc-e md:ai-e">
                            <?if(!empty($example->getPreviewPicture())):?>
                                <?=Data::getImageId($example->getPreviewPicture(), [
                                    "title" => strip_tags($example->getName()),
                                    "alt" => strip_tags($example->getName()),
                                    "loading" => "lazy",
                                    "class" => "w-a h-100 md:w-100 md:h-a xl:w-a xl:h-100",
                                ])?>
                            <?endif?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?endif?>