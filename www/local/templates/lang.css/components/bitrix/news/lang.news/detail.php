<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

use Bitrix\Main\Loader;
use Bitrix\Iblock\Component\Tools;
use Lang\Main\Css\Modifier;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util;

$lang = (SITE_ID == 'en') ? ucfirst(SITE_ID) : '';

$langText = "LangText{$lang}";
$langBlog = "LangBlog{$lang}";
$langBlogTable = Util::getTableString($langBlog);
$langTextTable = Util::getTableString($langText);

$element = $langBlogTable::getList([
	'select' => ['ID', 'IBLOCK_ID', 'PREVIEW_PICTURE', "DETAIL_PICTURE", 'ACTIVE_FROM', 'SHOW_COUNTER', 'DATE_CREATE', 'TIMESTAMP_X', 's' => 'start.VALUE', 't' => 'time.VALUE', 'a' => 'autor.VALUE'],
	'filter' => ['CODE' => $arResult["VARIABLES"]['ELEMENT_CODE']],
	'cache' => ['ttl' => $arParams['CACHE_TIME']]
])->fetch();

if(!empty($element['s']))
{
	$element['s'] = unserialize($element['s']);
}

$ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arParams['IBLOCK_ID'], $element['ID']);
$seoProperty = $ipropElementValues->getValues();

if(!empty($element['PREVIEW_PICTURE']))
{
	$element['PREVIEW_PICTURE'] = \CFile::GetFileArray($element['PREVIEW_PICTURE']);
}

if(!empty($element['DETAIL_PICTURE']))
{
	$element['DETAIL_PICTURE'] = \CFile::GetFileArray($element['DETAIL_PICTURE']);
}

$imgSrc = !empty($element['DETAIL_PICTURE']) ? $element['DETAIL_PICTURE'] : $element['PREVIEW_PICTURE'];
$element["ACTIVE_FROM"] = FormatDate("j F Y", MakeTimeStamp($item["ACTIVE_FROM"]));

if(!empty($element['a']))
{
	$autor = Util::getElement($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'], ['ID' => $element['a']]);
	
	if(!empty($autor['PREVIEW_PICTURE']))
	{
		$autor['PREVIEW_PICTURE'] = \CFile::GetPath($autor['PREVIEW_PICTURE']);
	}

	$autor['url'] = 'https://' . SITE_SERVER_NAME . '/';
}
?>

<meta property="og:type" content="article" prefix="article">
<meta property="article:published_time" content="<?=date(DateTime::ATOM, strtotime($element['DATE_CREATE']))?>" />
<meta property="article:modified_time" content="<?=date(DateTime::ATOM, strtotime($element['TIMESTAMP_X']))?>" />
<meta property="article:author" content="<?=$autor['NAME']?>" />
<meta property="article:section" content="Рестораны" />
<meta property="article:tag" content="Рестораны" />

<article id="lang-blog--text-header" class="w-100">

	<section class="lang-main--block w-100 atr-10x9 lg:atr-10x4 mh-500px bg-cr bg-nr bg-c bg-load bf-b6 flex f-col jc-s lazyload" data-bg="<?=$imgSrc['SRC']?>">
		<div class="<?=Modifier::getComponent('container')?> flex jc-s pt-8 lg:pt-13 mt-11 lg:mt-12">
			<div class="p-4 md:p-8 r-8 bg-ffffff21 bf-b16">
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
		</div>
	</section>

	<section class="w-100 pt-11 pb-11">
		<div class="w-100 sm:mw-540 md:mw-720 lg:mw-960 pr-8 pl-8 mr-ao ml-ao flex f-col rg-24">

			<div class="flex c-sy flex f-col sm:f-row gap-16 sm:ai-c f-14 lh-sd">

				<div class="flex gap-16">
					<time class="c-sy2"><?=$element["ACTIVE_FROM"]?></time>

					<div class="flex gap-8 ai-c ws-nw c-sy2">
						<div class="">Читать</div>
						<div class=""><?=$element['t']?></div>
					</div>

					<div class="flex gap-8 ai-c c-sy2 c-pr" title="Просмотров">
						<div class="">Просмотров</div>
						<div class=""><?=$element['SHOW_COUNTER'] ? $element['SHOW_COUNTER'] : 0?></div>
					</div>
				</div>

				<div class="grow-1 flex js-s sm:jc-e">
					<div class="flex ai-c cg-24">
							
						<div class="">Поделиться</div>

						<div class="flex cg-8">
							<div class="w-100 hover:tm-c-110 tn-all-3 c-pr" title="Скопировать"
								onclick="navigator.clipboard.writeText('https://<?=SITE_SERVER_NAME?><?=$arResult['DETAIL_PAGE_URL']?>')">
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M4 10c0-4.941 1.059-6 6-6s6 1.059 6 6-1.059 6-6 6-6-1.059-6-6"></path><path d="M10 17c-.5 0-1 .5-1 1 0 2 3.898 2 5 2 4.941 0 6-1.059 6-6 0-1.102 0-5-2-5-.53.042-1 .5-1 1 0 5.5-1.5 7-7 7"></path></svg>
							</div>

							<a class="w-100 hover:tm-c-110 tn-all-3" href="https://vk.com/share.php?url=https://<?=SITE_SERVER_NAME?><?=$arResult['DETAIL_PAGE_URL']?>" title="ВКонтакте" rel="nofollow" target="_blank">
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M3.406 3.406C2 4.812 2 7.075 2 11.6v.8c0 4.525 0 6.788 1.406 8.194S7.075 22 11.6 22h.8c4.525 0 6.788 0 8.194-1.406S22 16.925 22 12.4v-.8c0-4.525 0-6.788-1.406-8.194S16.925 2 12.4 2h-.8C7.075 2 4.812 2 3.406 3.406m1.969 4.677h2.283c.075 3.817 1.759 5.434 3.092 5.767V8.083h2.15v3.292c1.317-.142 2.7-1.642 3.167-3.292h2.15c-.359 2.034-1.859 3.534-2.925 4.15 1.066.5 2.775 1.809 3.425 4.175H16.35c-.508-1.583-1.775-2.808-3.45-2.975v2.975h-.258c-4.559 0-7.159-3.125-7.267-8.325"></path></svg>
							</a>

							<a class="w-100 hover:tm-c-110 tn-all-3" href="https://telegram.me/share/url?url=https://<?=SITE_SERVER_NAME?><?=$arResult['DETAIL_PAGE_URL']?>" title="Telegram" rel="nofollow" target="_blank">
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M3.375 10.888q8.054-3.387 10.74-4.466c5.114-2.053 6.177-2.41 6.87-2.422.152-.002.493.034.713.207a.74.74 0 0 1 .262.481c.025.139.055.454.031.7-.277 2.811-1.476 9.633-2.086 12.781-.259 1.333-.767 1.78-1.259 1.823-1.07.095-1.881-.682-2.917-1.338-1.621-1.025-2.537-1.664-4.11-2.665-1.818-1.156-.64-1.792.396-2.831.272-.272 4.984-4.41 5.075-4.785.011-.047.022-.221-.086-.314-.108-.092-.266-.06-.381-.036q-.244.054-7.768 4.957-1.102.73-1.997.712c-.658-.014-1.923-.359-2.863-.654-1.153-.362-2.07-.553-1.99-1.168q.062-.48 1.37-.982"></path></svg>
							</a>

							<a class="w-100 hover:tm-c-110 tn-all-3" href="https://api.whatsapp.com/send?text=<?=urlencode("https://".SITE_SERVER_NAME.$arResult['DETAIL_PAGE_URL'])?>" title="WhatsApp">
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21a9 9 0 1 0-9-9c0 1.92.6 3.698 1.624 5.159l-.806 3.023 3.076-.77A8.96 8.96 0 0 0 12 21M8.443 6.667c.356-.003.701-.006.932.458.2.401.445 1.09.735 1.912l.004.011c.068.192.173.492.034.69-.141.202-.457.61-.685.873-.059.068-.176.222-.016.474.04.063.074.127.112.198.152.285.36.675 1.21 1.499.858.831 1.934 1.31 2.33 1.47.109.06.368.142.542-.02.08-.074.23-.266.394-.476.193-.247.405-.517.543-.649l.013-.011c.093-.085.24-.22.509-.112.236.095 1.141.53 1.825.86l.371.178c.109.034.267.161.267.416 0 .574-.407 2.273-2.381 2.308-1.975.035-4.353-.998-6.164-2.994-1.875-2.068-2.432-3.451-2.432-4.715 0-1.54 1.078-2.37 1.77-2.37z"></path></svg>
							</a>
						</div>

					</div>
				</div>

			</div>

			<div class="w-100 flex f-col rg-24">
				<h1 class="f-24 xl:f-36 upp ff-ny ftw-4 mb-0"><?=$seoProperty['ELEMENT_PAGE_TITLE']?></h1>
				<?if(!empty($element['s']['TEXT'])):?>
					<div class="f-16"><?=$element['s']['TEXT']?></div>
				<?endif;?>
			</div>
		</div>
	</section>

	<script>document.querySelector('header').classList.remove('sty');document.querySelector('header').classList.add('fix')</script>

	<?$ElementID = $APPLICATION->IncludeComponent(
		"bitrix:news.detail",
		"lang.blog.detail.schema",
		Array(
			"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
			"DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
			"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
			"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
			"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
			"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
			"META_KEYWORDS" => $arParams["META_KEYWORDS"],
			"META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
			"BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
			"SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
			"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
			"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
			"SET_TITLE" => $arParams["SET_TITLE"],
			"MESSAGE_404" => $arParams["MESSAGE_404"],
			"SET_STATUS_404" => $arParams["SET_STATUS_404"],
			"SHOW_404" => $arParams["SHOW_404"],
			"FILE_404" => $arParams["FILE_404"],
			"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
			"ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
			"ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
			"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
			"DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
			"PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
			"CHECK_DATES" => $arParams["CHECK_DATES"],
			"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
			"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
			"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
			"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
			"USE_SHARE" => $arParams["USE_SHARE"],
			"SHARE_HIDE" => $arParams["SHARE_HIDE"],
			"SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
			"SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
			"SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
			"SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
			"ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
			'STRICT_SECTION_CHECK' => (isset($arParams['STRICT_SECTION_CHECK']) ? $arParams['STRICT_SECTION_CHECK'] : ''),
		),
		$component
	);?>

</article>

<?
$title = Util::getElement($langText, ['NAME'], ['ID' => 222]);
$listBlog = $langBlogTable::getList([
	'order' => ['ACTIVE_FROM' => 'DESC', 'SORT' => 'ASC'],
	'select' => ['ID', 'IBLOCK_ID', 'NAME', 'PREVIEW_PICTURE', "ACTIVE_FROM", 'CODE', 'IBLOCK_SECTION_ID', 'DATE_CREATE', 'TIMESTAMP_X', 'a' => 'autor.ELEMENT.ID'],
	'filter' => ['!CODE' => $arResult["VARIABLES"]['ELEMENT_CODE'], 'ACTIVE' => 'Y'],
	'limit' => 3,
	'cache' => ['ttl' => $arParams['CACHE_TIME']]
])->fetchAll();

$iblockBlog = Util::getIblock(['DETAIL_PAGE_URL'], ['API_CODE' => $langBlog]);

if(!empty($listBlog)):?>

	<article class="w-100 pb-11 xl:pb-13">
		<div class="<?=Modifier::getComponent('container')?>">
			<div class="<?=Modifier::getComponent('row')?> rg-32">

				<?if(!empty($title)):?>
					<div class="w-100 pr-8 pl-8 grow-0 shrink-0" id="<?=Util::iblockElementEditAdd($title)?>">
						<h2 class="mb-0 f-24 ftw-4 xl:f-28 ff-ny"><?=$title['NAME']?></h2>
					</div>
				<?endif;?>
				
				<?foreach($listBlog as $key => $item):
					$item['PREVIEW_PICTURE'] = \CFile::GetPath($item['PREVIEW_PICTURE']);
					$item["ACTIVE_FROM"] = FormatDate("j F Y", MakeTimeStamp($item["ACTIVE_FROM"]));
					$item['DETAIL_PAGE_URL'] = Util::getDetailPageUrl($iblockBlog['DETAIL_PAGE_URL'], $item);

					if(!empty($item['a']))
					{
						$item['autor'] = Util::getElement($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => $item['a']]);

						if(!empty($item['autor']['PREVIEW_PICTURE']))
						{
							$item['autor']['PREVIEW_PICTURE'] = \CFile::GetPath($item['autor']['PREVIEW_PICTURE']);
						}
					}
					?>
					<div class="w-100 lg:w-6/12 xl:w-4/12 pr-8 pl-8 grow-0 shrink-0" id="<?=Util::iblockElementEditAdd($item)?>">
                        <a href="<?=$item['DETAIL_PAGE_URL']?>" class="w-100 h-100 rele flex f-col jc-b gap-16 lh-nl tn-all-3 c-dt hover:c-sy2">

							<div class="w-100 ofw-hn r-8">
								<div class="w-100 atr-16x9 md:atr-3x2 bg-load bg-cr bg-c bg-nr tn-all-3 grow-0 r-8 hover:tm-c-110 lazyload" data-bg="<?=$item['PREVIEW_PICTURE']?>"></div>
							</div>

                            <div class="w-100 f-16 lg:f-18 grow-1 upp ftw-5 c-it"><?=$item["NAME"]?></div>

                            <time class="w-100 f-12 c-sy2 grow-0 upp"><?=$item["ACTIVE_FROM"]?></time>
                        </a>
                        <?
                            $schemaData = [
                                '@context' => 'http://schema.org',
                                '@type' => 'Article',
                                'headline' => $item['NAME'],
                                'image' => $item['PREVIEW_PICTURE'],
                                'description' => $item['PREVIEW_TEXT'],
                                'datePublished' => date(DateTime::ATOM, strtotime($item['DATE_CREATE'])),
                                'dateModified' => date(DateTime::ATOM, strtotime($item['TIMESTAMP_X'])),
                                'mainEntityOfPage' => [
                                    '@type' => 'WebPage',
                                    '@id' => $item['DETAIL_PAGE_URL'],
                                ],
                                'author' => [
                                    [
                                        "@type" => "Organization",
                                        "name" => $item['autor']['NAME'],
                                        "url" => 'https://poseidonrest.ru/',
                                    ]
                                ],
                                'publisher' => [
                                    '@type' => 'Organization',
                                    'name' => $item['autor']['NAME'],
                                    'url' => 'https://poseidonrest.ru/',
                                    'logo' => [
                                        '@type' => 'ImageObject',
                                        'url' => $item['autor']['PREVIEW_PICTURE'],
                                    ]
                                ],
                            ];
                        ?>
						<script type="application/ld+json"><?=json_encode($schemaData)?></script>
                    </div>
				<?endforeach;?>
			</div>
		</div>
	</article>
<?endif;?>