<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($element->getDetailPicture()))
{
    $det = 'data-bg="' . \CFile::GetPath($element->getDetailPicture()) . '"';
    $modPicture = "bgs-cr bgr-nr bp-c bi-load lazyload";
}

$detailPageUrl = Util::getDetailPageUrl($newsIblock->getDetailPageUrl(), $element);
?>

<section class="w-100 xl:spt-100_hm" <?=Util::iblockElementEditAddId($element)?>>
    <div class="<?=comp::get("container")?>">

        <div class="w-100 d-f fd-c rg-32 pn-r ar-3x4 sm:ar-9x10 md:ar-10x7 lg:ar-100x45 xl:ar-10x4 r-24 xl:r-48 p-32 xl:p-64 bc-w bi-b <?=$modPicture?>" <?=$det?>>

            <div class="w-100 d-f fd-c rg-32 fg-1">

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

                <?if(!empty($section)):?>
                    <div class="w-100 d-f jc-s">
                        <a class="m-0 pb-12 pl-24 pr-24 pt-12 ftw-7 ta-c lh-1 bc-dt f-14 c-w bw-1 bs-s bcr-dt r-999 h:bc-w h:c-dt a:tm-s0.9 tn-all-3" rel="nofollow"
                            href="<?=Util::getDetailPageUrl($newsIblock->getSectionPageUrl(), $section, true, "S")?>"><?=$section->getName()?></a>
                    </div>
                <?endif;?>

                <div class="w-100 xl:w-8/12">
                    <h1 class="<?=comp::get("titleh1", "", 'ta-c')?>"><?=$APPLICATION->ShowTitle(false)?></h1>
                </div>

                <?if(!empty($element->getActiveFrom())):?>
                    <div class="w-100 ftw-4 c-8C8C8C f-14 lg:f-16"><?=FormatDate('j F Y', $element->getActiveFrom());?></div>
                <?endif;?>
            </div>

            <div class="w-100 d-f cg-16 sm:cg-24 f-14 lh-1">

                <?
                $shareNameButton = Util::getSectionObject($textTableString, ['select' => ['NAME', 'UF_SVG'], 'filter' => ['ID' => 8131]]);
                @include "share.php";
                ?>

                <div class="d-f ai-c cg-8 lh-1 f-12 xl:f-16">
                    <div class="d-f">
                        <svg width="19" height="14" viewBox="0 0 19 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M19 6.53125C19 6.53125 15.4375 0 9.5 0C3.5625 0 0 6.53125 0 6.53125C0 6.53125 3.5625 13.0625 9.5 13.0625C15.4375 13.0625 19 6.53125 19 6.53125ZM1.39294 6.53125C1.96711 5.65858 2.62751 4.84581 3.36419 4.10519C4.8925 2.5745 6.9825 1.1875 9.5 1.1875C12.0175 1.1875 14.1063 2.5745 15.637 4.10519C16.3737 4.84581 17.0341 5.65858 17.6082 6.53125C17.5394 6.63456 17.4634 6.74856 17.3767 6.87325C16.9789 7.44325 16.3911 8.20325 15.637 8.95731C14.1063 10.488 12.0163 11.875 9.5 11.875C6.9825 11.875 4.89369 10.488 3.363 8.95731C2.62633 8.21668 1.96712 7.40391 1.39294 6.53125Z"/><path d="M9.5 3.5625C8.71264 3.5625 7.95753 3.87528 7.40078 4.43203C6.84403 4.98878 6.53125 5.74389 6.53125 6.53125C6.53125 7.31861 6.84403 8.07372 7.40078 8.63047C7.95753 9.18722 8.71264 9.5 9.5 9.5C10.2874 9.5 11.0425 9.18722 11.5992 8.63047C12.156 8.07372 12.4688 7.31861 12.4688 6.53125C12.4688 5.74389 12.156 4.98878 11.5992 4.43203C11.0425 3.87528 10.2874 3.5625 9.5 3.5625ZM5.34375 6.53125C5.34375 5.42894 5.78164 4.37179 6.56109 3.59234C7.34054 2.81289 8.39769 2.375 9.5 2.375C10.6023 2.375 11.6595 2.81289 12.4389 3.59234C13.2184 4.37179 13.6562 5.42894 13.6562 6.53125C13.6562 7.63356 13.2184 8.69071 12.4389 9.47016C11.6595 10.2496 10.6023 10.6875 9.5 10.6875C8.39769 10.6875 7.34054 10.2496 6.56109 9.47016C5.78164 8.69071 5.34375 7.63356 5.34375 6.53125Z"/></svg>
                    </div>
                    <div class="ws-nw"><?=$element->getShowCounter() ?: 0?> просмотров</div>
                </div>
            </div>

        </div>

    </div>
</section>