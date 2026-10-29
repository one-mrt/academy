<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$newsList = Util::getElementsObject($newsTableString, [
    'order' => ['ACTIVE_FROM' => 'DESC'],
    'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'ACTIVE_FROM', 'CODE', 'IBLOCK_SECTION_ID'],
    'limit' => 1,
]);
?>

<section class="w-100 pt-16">
    <div class="<?=comp::get("container")?>">

        <div class="w-100 of-h r-24 xl:r-48 p-32 xl:p-64 bi-b">

            <div class="<?=comp::get("row")?> rg-32">

                <div class="w-100 pr-16 pl-16 fg-0 fs-0">
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

                <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                    <h1 class="<?=comp::get("titleh1", "", 'ta-c')?>"><?=$APPLICATION->ShowTitle(false)?></h1>
                </div>

                <?if(!empty($newsList->getIdList())):
                    foreach($newsList as $news):
                        $filterBlog["!ID"] = [$news->getId()];
                        ?>
                        <div class="w-100 pr-16 pl-16 fg-0 fs-0 rg-32" <?=Util::iblockElementEditAddId($news)?>>
                            <a href="<?=Util::getDetailPageUrl($newsIblock->getDetailPageUrl(), $news)?>" class="<?=comp::get("row")?> rg-32">

                                <div class="w-100 md:w-5/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                    <?
                                    $previewPicture = "";
                                    $modPreview = "";
                                    if(!empty($news->getPreviewPicture()))
                                    {
                                        $previewPicture = 'data-bg="' . \CFile::GetPath($news->getPreviewPicture()) . '"';
                                        $modPreview = "bp-c bgr-nr bgs-cr bi-load lazyload";
                                    }
                                    ?>
                                    <div class="w-100 ar-16x9 xl:ar-16x9 r-20 bc-w <?=$modPreview?>" <?=$previewPicture?>></div>
                                </div>

                                <div class="w-100 md:w-7/12 xl:w-8/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-16">
                                    <div class="w-100 fg-1 d-f fd-c rg-16">
                                        <?if(!empty($news->getActiveFrom())):?>
                                            <div class="ftw-4 c-8C8C8C f-14 xl:f-16"><?=FormatDate('j F Y', $news->getActiveFrom());?></div>
                                        <?endif;?>

                                        <div class="lg:f-24 ftw-5 lh-1"><?=$news->getName()?></div>

                                        <?if(!empty($news->getPreviewText())):?>
                                            <div class="xl:f-24 ftw-5 lh-1"><?=$news->getPreviewText()?></div>
                                        <?endif;?>
                                    </div>

                                    <div class="w-100 fg-0 d-f jc-s">
                                        <div class="<?=comp::get("btnDt")?>">Читать статью</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?endforeach;?>
                <?endif;?>

            </div>
        </div>

    </div>
</section>