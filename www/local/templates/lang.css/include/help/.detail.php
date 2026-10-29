<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Main\Iblock\Util as Data;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util;
use Bitrix\Main\Type\DateTime;
use Bitrix\Iblock\PropertyTable;

$lang = Util::getLang();
$langBlog = "LangBlog{$lang}";
$langText = "LangText{$lang}";
$langHelp = "LangHelp{$lang}";

$help = Data::getElementObject($langHelp,['NAME', 'ban', 'edge', 'art'],['CODE' => $arParams['code']]);

if(!empty($help->getBan())):
    $banner = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'text'], ['ID' => $help->getBan()->getValue()]);

    if(!empty($banner) && !empty($banner->getId())):?>
        <section class="w-100">
            <div class="<?=comp::get('container-full')?> d-f fd-c rg-32 lg:rg-0">

                <div class="w-100 xl:h-550px xxl:h-600px r-24 bc-w of-h pn-r d-f fd-c">

                    <?if(!empty($banner->getDetailPicture())):?>
                        <?=Data::getImageId($banner->getDetailPicture(), [
                            'alt' => strip_tags($banner->getName()),
                            'class' => 'w-100 h-100 pn-a t-0 rt-0 b-0 l-0 w-100 h-100 oft-cv op-c',
                            'fetchpriority' => 'high',
                        ])?>
                    <?endif?>

                    <div class="<?=comp::get('container-p')?> h-100 pn-r z-1 fg-1" <?=Data::iblockElementEditAddId($banner)?>>
                        <div class="<?=comp::get("row")?> h-100">
                            <div class="w-100 sm:w-10/12 md:w-7/12 lg:w-7/12 xxl:w-6/12 <?=comp::get('setcol')?> d-f fd-c rg-32">

                                <div class="w-100 pix-32 md:pix-0 pl-32 md:pl-16 lg:pl-32 xxl:pl-0 pt-32">
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

                                <div class="w-100 pn-r z-1 d-f fd-c jc-c rg-24 pix-32 md:pix-0 pl-32 md:pl-16 lg:pl-32 xxl:pl-0 pby-48 xl:pby-86 fg-1">
                                    <h2 class="<?=comp::get("h2")?>"><?=$banner->getName()?></h2>
                                    <?if(!empty($banner->getPreviewText())):?>
                                        <div class="xl:w-10/12 xxl:w-100 f-14 xxl:f-16"><?=$banner->getPreviewText()?></div>
                                    <?endif?>
                                    <?if(!empty($banner->getText()) && !empty($banner->getText()->getAll())):
                                        $indBtn = 0;
                                        ?>
                                        <div class="w-100 d-f jc-s fw-w md:fw-n rg-16 sm:cg-24">
                                            <?foreach ($banner->getText()->getAll() as $btn):
                                                $modBtn = comp::get("btnPurpleLg");

                                                if($indBtn > 0) {
                                                    $modBtn = comp::get("btnPurpleBorderLg");
                                                }
                                                ?>

                                                <a href="<?=$btn->getValue()?>" class="<?=$modBtn?>"><?=$btn->getDescription()?></a>

                                                <?
                                                $indBtn++;
                                            endforeach?>
                                        </div>
                                    <?endif?>
                                </div>
                            </div>
                            <div class="w-100 lg:w-5/12 xl:w-5/12 xxl:w-6/12 <?=comp::get('setcol')?> d-f ai-e jc-e">
                                <?if(!empty($banner->getPreviewPicture())):?>
                                    <?=Data::getImageId($banner->getPreviewPicture(), [
                                        'alt' => strip_tags($banner->getName()),
                                        'class' => 'w-a xxl:w-100 h-300px sm:h-350px md:h-400px lg:h-450px xl:h-550px xxl:h-600px pn-a lg:pn-s fr-b4 md:fr-b2 lg:fr-b0 b-0 oft-cv op-r',
                                        'fetchpriority' => 'high',
                                    ])?>
                                <?endif?>
                            </div>
                        </div>
                    </div>
                </div>

                <?if(!empty($help->getEdge()) && !empty($help->getEdge()->getValueList())):
                    $infoList = Data::getElementsObject($langText, ['select'=>['NAME', 'PREVIEW_TEXT', 'svg'],'filter'=>['ID' => $help->getEdge()->getValueList()]]);

                    if(!empty($infoList) && !empty($infoList->getIdList())):?>
                        <div class="<?=comp::get('container-p')?> h-100">
                            <div class="w-100 lg:mt--32 xl:mt--51 z-1 pn-r xl:pix-32 xxl:pix-0">
                                <div class="<?=comp::get("row")?> rg-32">
                                    <?foreach($infoList as $info):?>
                                        <div class="w-100 md:w-6/12 lg:w-4/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                            <div class="w-100 h-100 p-32 lg:p-24 xl:p-32 d-f fd-c rg-24 bc-w bw-1 bs-s bcr-527CC8 r-12" <?=Data::iblockElementEditAddId($info)?>>
                                                <div class="w-100 d-f fd-c rg-24">
                                                    <div class="w-100 d-f cg-24">
                                                        <?if(!empty($info->getSvg())):?>
                                                            <div class="d-f"><?=file_get_contents($_SERVER["DOCUMENT_ROOT"] . \CFile::GetPath($info->getSvg()->getValue()))?></div>
                                                        <?endif?>
                                                        <div class="fg-1 xxl:f-18 tt-l ftw-6"><?=$info->getName()?></div>
                                                    </div>
                                                    <?if(!empty($info->getPreviewText())):?>
                                                        <div class="f-12 xxl:f-14"><?=$info->getPreviewText()?></div>
                                                    <?endif;?>
                                                </div>
                                            </div>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            </div>
                        </div>
                    <?endif?>
                <?endif?>

            </div>
        </section>
    <?endif?>
<?endif?>

<?

if(!empty($help->getArt()) && !empty($help->getArt()->getValueList())):
    $artList = Data::getElementsObject($langBlog, [
        'order' => ['NAME' => 'ASC', 'ID' => 'DESC'],
        'select' => ['NAME', 'CODE', 'IBLOCK_SECTION_ID'],
        'filter' => ['ID' => $help->getArt()->getValueList()],
    ]);

    if(!empty($artList) && !empty($artList->getIdList())):
        $letterBlogList = [];
        foreach ($artList as $iArt) {
            $firstLetter = mb_strtoupper(mb_substr($iArt->getName(), 0, 1, 'UTF-8'), 'UTF-8');
            $letterBlogList[$firstLetter][] = [
                'name' => $iArt->getName(),
                'url' => Data::getDetailPageUrlApiCode($langBlog, $iArt),
                'edit' => Data::iblockElementEditAddId($iArt)
            ];
        }

        if(!empty($letterBlogList)):
            $letterCount = count($letterBlogList);
            $columnIndex = round($letterCount / 2);
            $letterIndex = 1;
            ?>
            <section class="w-100">
                <div class="<?=comp::get('container')?> d-f fd-c rg-32">

                    <?
                    $artTitle = Data::getElementObject($langText, ['NAME'], ['ID' => 237]);
                    $widthCol = "w-100 lg:w-6/12 xl:w-6/12 d-f fd-c rg-32 " . comp::get('setcol');
                    if(!empty($artTitle) && !empty($artTitle->getId())):?>
                        <div class="w-100 xl:w-8/12" <?=Data::iblockElementEditAddId($artTitle)?>>
                            <h2 class="<?=comp::get('h2')?>"><?=$artTitle->getName()?></h2>
                        </div>
                    <?endif?>

                    <div class="w-100">
                        <div class="<?=comp::get('row')?>">
                            <div class="<?=$widthCol?>">
                                <?foreach ($letterBlogList as $letter => $links):?>

                                        <?if($letterIndex == $columnIndex):?>
                                            </div>
                                            <div class="<?=$widthCol?>">
                                        <?endif;?>

                                        <div class="w-100 d-f cg-24">
                                            <div class="f-24 ff-ud ftw-6 bi-st bgc-t tf-t lh-1"><?=$letter?></div>
                                            <div class="d-f fd-c rg-16 fg-1">
                                                <?foreach($links as $link):?>
                                                    <a class="f-14 h:c-py tdt-1 tuo-4 tdc-py h:tdl-u tn-all-3" href="<?=$link['url']?>" target="_blank" <?=$link['edit']?>><?=$link['name']?></a>
                                                <?endforeach?>
                                            </div>
                                        </div>
                                    <?
                                    unset($links[$letter]);
                                    $letterIndex++;
                                    ?>
                                <?endforeach;?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?endif?>
    <?endif;?>

<?endif;?>


<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId"=>232,"path"=>"main/.banner.bottom.php"],
    false,['HIDE_ICONS'=>'Y']
);?>

<?
$seo = Data::getSeoPropertyElement($langHelp, $help->getId());

$APPLICATION->SetTitle(strip_tags($seo['ELEMENT_PAGE_TITLE']));
$APPLICATION->SetPageProperty("title", strip_tags($seo['ELEMENT_META_TITLE']));
$APPLICATION->SetPageProperty("description", strip_tags($seo['ELEMENT_META_DESCRIPTION']));
$APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
$APPLICATION->AddChainItem(strip_tags($help->getName()), "");
?>