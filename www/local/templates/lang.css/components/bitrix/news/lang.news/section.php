<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
use Lang\Main\Css\Modifier;

$lang = (SITE_ID == 'en') ? ucfirst(SITE_ID) : '';
$langTitle = (SITE_ID == 'ru') ? 1337 : 2214;

?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "",
		"PATH" => SITE_TEMPLATE_PATH . '/include/lang.title.php',
		'description' => $langTitle,
	),
	false,
	['HIDE_ICONS' => 'Y']
);?>

<?if(!empty($arParams['titleId'])):
            
    $page = $APPLICATION->GetCurPage(false);
    $dSection = \Bitrix\Iblock\Model\Section::compileEntityByIblock($arParams['IBLOCK_ID']);

    $filT = $dSection::getList([
        'order' => ['SORT' => 'ASC', 'ID' => 'DESC'],
        'select' => ['ID','IBLOCK_ID','NAME','CODE'],
        'filter' => [],
        'cache' => ['ttl' => $arParams['CACHE_TIME']],
    ])->fetchAll();

    if(!empty($filT))
    {
        $nav = [
            'all' => [
                'url' => $arParams['SEF_FOLDER'],
                'name' => 'Все',
                'selected' => ($page == $arParams['SEF_FOLDER']) ? true : false
            ]
        ];

        foreach($filT as $k => &$i)
        {
            $editElement = CIBlock::GetPanelButtons($i["IBLOCK_ID"], 0, $i['ID'], ["SESSID" => false]);
            $i['EDIT_LINK'] = $editElement['edit']['edit_section']['ACTION_URL'];
            $i['DELETE_LINK'] = $editElement['edit']['delete_section']['ACTION_URL'];

            $this->AddEditAction($i['ID'], $i['EDIT_LINK'], CIBlock::GetArrayByID($i["IBLOCK_ID"], "SECTION_EDIT"));
            $this->AddDeleteAction($i['ID'], $i['DELETE_LINK'], CIBlock::GetArrayByID($i["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

            $nav[$i['CODE']]['name'] = $i['NAME'];
            $nav[$i['CODE']]['url'] = "{$arParams['SEF_FOLDER']}{$i['CODE']}/";
            $nav[$i['CODE']]['selected'] = ($page == "{$arParams['SEF_FOLDER']}{$i['CODE']}/") ? true : false;
        }
    }

    $section = ("Bitrix\Iblock\Elements\ElementLangText{$lang}Table")::getList([
        'select' => ['ID','IBLOCK_ID', 'NAME', 'l' => 'link.VALUE', 'ld' => 'link.DESCRIPTION'],
        'filter' => ['ID' => $arParams['titleId']],
        'cache' => ['ttl' => $arParams['CACHE_TIME']]])->fetch();

    $editElement = CIBlock::GetPanelButtons($section["IBLOCK_ID"],$section['ID'], 0, array("SESSID"=>false));
    $elementEdit = $editElement['edit']['edit_element']['ACTION_URL'];
    $elementDelete = $editElement['edit']['delete_element']['ACTION_URL'];

    $this->AddEditAction($section['ID'], $elementEdit, CIBlock::GetArrayByID($section["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($section['ID'], $elementDelete, CIBlock::GetArrayByID($section["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>

    <section class="<?=Modifier::get("w-100 pt-11 lg:pt-13 c-2B2B2B")?>">
        <div class="<?=Modifier::get("w-100 sm:mw-540 md:mw-720 lg:mw-1140 xl:mw-1277 pr-8 pl-8 mr-ao ml-ao flex f-col")?>">
            <div class="<?=Modifier::get('w-100 mb-8 lg:mb-11 flex ai-c')?>" id="<?=$this->GetEditAreaId($section['ID'])?>">
                <div class="<?=Modifier::get("grow-1 flex ai-c")?>">
                    <i class="lang-title--icon <?=Modifier::get("c-py pr-5 flex lh-sd")?>"><svg width="13" height="23" viewBox="0 0 13 23" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M13 0H11.0248L0 23H1.97515L13 0Z"/></svg></i>
                    <h2 class="<?=Modifier::get('c-dt ftw-6 f-14 md:f-32 mb-0 upp')?>"><?=$section['NAME']?></h2>
                </div>
                <div class="<?=Modifier::get("grow-0 flex gap-8 c-dt f-6 md:f-14")?>">
                    <?foreach($nav as $kSort => $iSort):
                        $mod = $iSort['selected'] ? 'c-py' : '';
                        ?>
                        <a href="<?=$iSort['url']?>" class="lang-project--filter <?=Modifier::get("bg-tt m-0 p-0 b-0 {$mod} hover:c-py upp tn-all-3")?>"
                            id="<?=$this->GetEditAreaId($iSort['ID'])?>"><?=$iSort['name']?></a>
                    <?endforeach;?>
                </div>
            </div>
        </div>
    </section>
<?endif;?>

<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "lang.news",
    Array(
        'titleId' => $arParams['titleId'],
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "NEWS_COUNT" => $arParams["NEWS_COUNT"],
        "SORT_BY1" => $arParams["SORT_BY1"],
        "SORT_ORDER1" => $arParams["SORT_ORDER1"],
        "SORT_BY2" => $arParams["SORT_BY2"],
        "SORT_ORDER2" => $arParams["SORT_ORDER2"],
        "FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
        "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
        "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
        "IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
        "PARENT_SECTION" => $arResult['VARIABLES']['SECTION_ID'],
		"PARENT_SECTION_CODE" => $arResult['VARIABLES']['SECTION_CODE'],
        "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
        "SET_TITLE" => $arParams["SET_TITLE"],
        "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
        "MESSAGE_404" => $arParams["MESSAGE_404"],
        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
        "SHOW_404" => $arParams["SHOW_404"],
        "FILE_404" => $arParams["FILE_404"],
        "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_FILTER" => $arParams["CACHE_FILTER"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
        "PAGER_TITLE" => $arParams["PAGER_TITLE"],
        "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
        "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
        "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
        "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
        "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
        "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
        "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
        "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
        "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
        "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
        "PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
        "ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
        "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
        "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
        "FILTER_NAME" => $arParams["FILTER_NAME"] ?: 'arrFilter',
        "HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
        "USE_RATING" => $arParams["USE_RATING"],
        "MAX_VOTE" => $arParams["MAX_VOTE"],
        "VOTE_NAMES" => $arParams["VOTE_NAMES"],
        "CHECK_DATES" => $arParams["CHECK_DATES"],
        "STRICT_SECTION_CHECK" => "Y"
    ),
    $component
);?>