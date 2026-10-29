<?
use Lang\Css\Component as comp;
use Bitrix\Iblock\PropertyTable;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$langText = "LangText{$lang}";

$banner = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'], ['ID' => $arParams['elementId']]);

if(!empty($banner) && !empty($banner->getId())):?>
    <section class="w-100">
        <div class="<?=comp::get('container-full')?> d-f fd-c rg-32 xl:rg-0">

            <div class="w-100 xl:ar-100x43 of-h pn-r r-24 bc-w">
                <?if(!empty($banner->getDetailPicture())):?>
                    <?=Data::getImageId($banner->getDetailPicture(), [
                        "title" => strip_tags($banner->getName()),
                        "alt" => strip_tags($banner->getName()),
                        "fetchpriority" => "high",
                        "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 w-100 h-100 oft-cv op-c",
                    ])?>
                <?endif?>

                <div class="<?=comp::get("container-p")?> h-100 pn-r z-1" <?=Data::iblockElementEditAddId($banner)?>>
                    <div class="<?=comp::get("row")?> h-100">
                        <div class="w-100 lg:w-7/12 xl:w-6/12 pr-16 xl:pr-0 pl-16 fg-0 fs-0">
                            <div class="w-100 h-100 pix-32 md:pl-16 lg:pl-32 xxl:pl-0 pt-32 d-f fd-c rg-0">

                                <div class="w-100">
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:breadcrumb",
                                        "breadcrumb.dark",
                                        [
                                            "PATH" => "",
                                            "SITE_ID" => SITE_ID,
                                            "START_FROM" => "0"
                                        ],
                                        false,
                                        ['HIDE_ICONS' => 'Y']
                                    );?>
                                </div>

                                <div class="w-100 h-100 d-f fd-c jc-c rg-24 pby-48 xl:pby-86 fg-1">
                                    <h2 class="mb-0 ftw-6 xl:f-36 ff-ud tt-l lh-1.125 ls--1"><?=$banner->getName()?></h2>
                                    <?if(!empty($banner->getPreviewText())):?>
                                        <div class="f-12 lg:f-14 xxl:f-18"><?=$banner->getPreviewText()?></div>
                                    <?endif?>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0 d-f ai-e">
                            <?if(!empty($banner->getPreviewPicture())):?>
                                <?=Data::getImageId($banner->getPreviewPicture(), [
                                    "title" => strip_tags($banner->getName()),
                                    "alt" => strip_tags($banner->getName()),
                                    "fetchpriority" => "high",
                                    "class" => "w-100 xl:ar-100x94 oft-cv op-c",
                                ])?>
                            <?endif?>
                        </div>
                    </div>
                </div>
            </div>

            <?if(!empty($arParams['infolistid'])):
                $infoList = Data::getElementsObject($langText, ['select'=>['NAME', 'PREVIEW_TEXT', 'svg'],'filter'=>['ID' => array_keys($arParams['infolistid'])]]);

                if(!empty($infoList) && !empty($infoList->getIdList())):?>
                    <div class="<?=comp::get("container-p", 'md:pix-16 lg:pix-32 xxl:pix-0')?> h-100">
                        <div class="w-100 xl:mt--51 z-1 pn-r">
                            <div class="<?=comp::get("row")?> rg-32">
                                <?foreach($infoList as $info):
                                    $title = PropertyTable::getList([
                                        'select' => ['NAME'],
                                        'filter' => ['ID' => $arParams['infolistid'][$info->getId()]],
                                        'cache' => Data::getCache(),
                                    ])->fetchObject();
                                    ?>
                                    <div class="w-6/12 md:w-4/12 xl:w-20 <?=comp::get("setcol")?>">
                                        <div class="w-100 h-100 p-16 sm:p-32 md:p-24 xxl:p-32 d-f fd-c rg-24 bc-w bw-1 bs-s bcr-527CC8 r-12" <?=Data::iblockElementEditAddId($info)?>>

                                            <?if(!empty($info->getSvg())):?>
                                                <div class="w-100 d-f"><?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . \CFile::GetPath($info->getSvg()->getValue()))?></div>
                                            <?endif?>

                                            <div class="w-100 d-f fd-c rg-8">
                                                <div class="w-100 d-f fd-c rg-4">
                                                    <?if(!empty($title)):?>
                                                        <div class="f-12 xl:f-12 xxl:f-14 c-7A7A7A ftw-5"><?=$title->getName()?>:</div>
                                                    <?endif?>
                                                    <div class="bi-ggn bgc-t tf-t xl:f-16 xxl:f-18 ff-ud ftw-6 ls--1px"><?=$info->getName()?></div>
                                                </div>
                                                <?if(!empty($info->getPreviewText())):?>
                                                    <div class="f-12 xl:f-12 xxl:f-14"><?=$info->getPreviewText()?></div>
                                                <?endif?>
                                            </div>

                                        </div>
                                    </div>
                                <?endforeach?>
                            </div>
                        </div>
                    </div>
                <?endif?>
            <?endif?>

        </div>
    </section>
<?endif?>