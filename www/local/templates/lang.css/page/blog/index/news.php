<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<section class="w-100 pb-11 lg:pb-13">
    <div class="<?=comp::get("container")?> d-f fd-c rg-32">

        <?
        $sectionNews = Util::getSectionsObject($newsTableString, [
            'select' => ['NAME', 'CODE', 'IBLOCK_SECTION_ID'],
            'filter' => ['DEPTH_LEVEL' => 1]
        ]);
        if(!empty($sectionNews)):?>
            <div class="w-100 d-f jc-c cg-16">
                <?
                $modActive = comp::get("btnDt");
                $mod = comp::get("btnDt", "bc-w h:bc-dt c-dt h:c-w", "bc-dt c-w h:bc-w h:c-dt");
                $urlAll = str_replace("#SITE_DIR#", SITE_DIR, $newsIblock->getListPageUrl());
                ?>
                
                <a href="<?=$urlAll?>" class="<?=($page == $urlAll) ? $modActive : $mod?>">Все</a>

                <?foreach($sectionNews as $item):
                    $href = Util::getDetailPageUrl($newsIblock->getSectionPageUrl(), $item, true, "S");
                    ?>
                    <a href="<?=$href?>" class="<?=($page == $href) ? $modActive : $mod?>" <?=Util::iblockSectionEditAddId($item)?>><?=$item->getName()?></a>
                <?endforeach;?>
            </div>
        <?endif;?>

        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "lang.news",
            Array(
                "ACTIVE_DATE_FORMAT" => "j F Y",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "CHECK_DATES" => "Y",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "DETAIL_URL" => "",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "DISPLAY_TOP_PAGER" => "N",
                "FIELD_CODE" => array("", ""),
                "FILE_404" => "/404/index.php",
                "USE_FILTER" => "Y",
                "FILTER_NAME" => "filterBlog",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "IBLOCK_ID" => $newsIblock->getId(),
                "IBLOCK_TYPE" => "lang_ru_content",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "INCLUDE_SUBSECTIONS" => "Y",
                "MESSAGE_404" => "",
                "NEWS_COUNT" => "9",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => "lang.pager.ajax",
                "PAGER_TITLE" => "Новости",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => $sectionCode,
                "PREVIEW_TRUNCATE_LEN" => "",
                "PROPERTY_CODE" => array("", ""),
                "ADD_SECTIONS_CHAIN" => "N",
                "SET_BROWSER_TITLE" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "N",
                "SHOW_404" => "N",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_BY2" => "SORT",
                "SORT_ORDER1" => "DESC",
                "SORT_ORDER2" => "ASC",
                "STRICT_SECTION_CHECK" => "Y"
            )
        );?>

    </div>
</section>