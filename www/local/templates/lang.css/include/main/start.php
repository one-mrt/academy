<?
global $APPLICATION;

use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$lang = Util::getLang();
$textTableString = "LangText{$lang}";

$button = Util::getElementObject($textTableString, ['NAME', 'link'], ['ID' => 173833]);
$startList = Util::getElementsObject($textTableString, [
    'select' => ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'link'],
    'filter' => ['IBLOCK_SECTION_ID' => 7878],
    'limit' => 2
]);

if(!empty($startList->getIdList()) || !empty($button)):?>
    <section class="w-100 d-f fd-c rg-32">

        <?if(!empty($startList->getIdList())):
            $title = Util::getSectionObject($textTableString, ['select' => ['NAME'], 'filter' => ['ID' => 7878]]);
            $k = 0;
            ?>

            <?if(!empty($title)):?>
                <div class="<?=comp::get('container')?>" <?=Util::iblockSectionEditAddId($title)?>>
                    <h2 class="mb-0 f-24 lg:f-32 xl:f-55 ta-c ftw-5 lh-1"><?=$title->getName()?></h2>
                </div>
            <?endif;?>

            <div class="<?=comp::get('container')?>">
                <div class="<?=comp::get("row")?> rg-32">
                    <?foreach($startList as $item):
                        $preview = "";
                        $modPreview = "";
                        $k++;

                        if(!empty($item->getPreviewPicture()))
                        {
                            $preview = 'data-bg="' . CFile::GetPath($item->getPreviewPicture()) . '"';
                            $modPreview = "bgr-nr bgs-cr bi-load lazyload";
                        }
                        ?>
                        <div class="w-100 md:w-6/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                            <div class="w-100 md:h-100 xl:h-a xl:ar-16x9 rele r-20 bc-py bp-c <?=$modPreview?>" <?=$preview?> <?=Util::iblockElementEditAddId($item)?>>
                                <div class="w-10/12 xl:w-7/12 h-100 p-32 c-w d-f fd-c rg-24 jc-c">

                                    <div class="w-100 d-f fd-c rg-24 fg-1">
                                        <div class="f-18 lg:f-24 ftw-5 lh-1"><?=$item->getName()?></div>

                                        <?if(!empty($item->getPreviewText())):?>
                                            <div class="f-14 md:f-12 lg:f-14"><?=$item->getPreviewText()?></div>
                                        <?endif;?>
                                    </div>
                                    
                                    <?if($k == 1 && !empty($item->getLink())):?>
                                        <div class="w-100 d-f jc-s">
                                            <button class="<?=comp::get("btnDt", "w-100 lg:w-a")?>" data-fancybox="" data-type="ajax"
                                                data-src="<?=SITE_TEMPLATE_PATH?>/ajax/level/"><?=$item->getLink()->getDescription()?></button>
                                        </div>
                                    <?elseif(!empty($item->getLink())):?>
                                        <div class="w-100 d-f jc-s">
                                            <a href="<?=$item->getLink()->getValue()?>" class="<?=comp::get("btnDt", "w-100 lg:w-a")?>"><?=$item->getLink()->getDescription()?></a>
                                        </div>
                                    <?endif;?>
                                </div>
                            </div>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
        <?endif;?>

        <?if(!empty($button)):?>
            <div class="<?=comp::get('container')?>" <?=Util::iblockElementEditAddId($button)?>>
                <div class="w-100 d-f fw-w xl:fw-n jc-c ai-c g-24 xl:g-32 r-20 bc-dt p-32">
                    <div class="f-24 md:f-18 lg:f-24 ftw-5 lh-1 c-w ta-c xl:ta-l"><?=$button->getName()?></div>
                    <div class="">
                        <button class="<?=comp::get("btnPy")?>" data-fancybox="" data-type="ajax"
                            data-src="<?=SITE_TEMPLATE_PATH?>/ajax/order/"><?=$button->getLink()->getDescription()?></button>
                    </div>
                </div>
            </div>
        <?endif;?>

    </section>
<?endif;?>