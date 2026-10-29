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
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\UserTable;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

Loader::includeModule('iblock');
Loader::includeModule('lang.iblock');

$obParser = new CTextParser;
$ID = rand();
$lang = (SITE_ID == 'en' || SITE_ID == 'tt') ? ucfirst(SITE_ID) : '';
// $apiCode = "LangBlog";
// $reviewCode = "LangReview";
// $textCode = "LangText";
// $langText = "LangText{$lang}";
// $apiTable = Util::getTableString("{$apiCode}{$lang}");
// $reviewTable = Util::getTableString("{$reviewCode}{$lang}");
// $textTable = Util::getTableString("{$textCode}{$lang}");
// $apiIblock = Util::getIblock(['ID', 'IBLOCK_TYPE_ID', 'DETAIL_PAGE_URL'], ['API_CODE' => "{$apiCode}{$lang}"]);

if(!empty($arResult['PROPERTIES']['autor']['VALUE']))
{
	$autor = Util::getElement($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'], ['ID' => $arResult['PROPERTIES']['autor']['VALUE']]);
	
	if(!empty($autor['PREVIEW_PICTURE']))
	{
		$autor['PREVIEW_PICTURE'] = \CFile::GetPath($autor['PREVIEW_PICTURE']);
	}

	$autor['url'] = 'https://' . SITE_SERVER_NAME . '/';
}
?>

<style>
	.lang-blog--text-content table {
		width: 100%;
		overflow-x: auto;
	}
	/*.font-proxima h2,
	.font-proxima h3,
	.font-proxima h4,
	.font-proxima h5,
	.font-proxima h6 {
		font-family: var(--proxima)
	}
	.sert--detail .main-link {
		color:#bb9b8c!important;
		background-image: linear-gradient(to right, #bb9b8c 50%, transparent 50%);
	}
	.sert--detail .main-link:hover {
		color: #bb9b8c;
    	background-image: linear-gradient(to right, rgb(187 155 140 / 30%) 50%, transparent 50%);
	}*/

	.luciano--btn-up {
		background-color: var(--dark-biege);
		width: 60px;
		height: 60px;
		z-index: 42;
		bottom: 100px;
    	left: 50px;
	}
	/* ol li::before {padding-right: 1rem}

	.link-h2::before{color:#bb9b8c}
	.link-h3{counter-increment: new 1}
	.link-h3::before{content: counter(ordered-list) "." counter(new);color:#bb9b8c;}
	.link-h4{counter-increment: new 1}
	.link-h4::before{content: counter(ordered-list) "." counter(new);color:#bb9b8c;}
	.link-h5{counter-increment: new 1}
	.link-h5::before{content: counter(ordered-list) "." counter(new);color:#bb9b8c;}
	.link-h6{counter-increment: new 1}
	.link-h6::before{content: counter(ordered-list) "." counter(new);color:#bb9b8c;} */

	.lang-blog--text-content img {
		width: 100%;
		height: auto;
		margin-bottom: 0;
	}
	.lang-blog--text-content p {
		margin-bottom: 0;
	}
</style>

<section class="w-100 pb-11 xl:pb-13">

	<div class="<?=comp::get("container")?>">

		<div class="w-100 flex f-col gap-32">
			<div class="w-100 flex f-col gap-32">

				<div class="w-100 flex f-col">
					<div class="lang-blog--menu-page w-100 flex f-col rg-24">
						<div class="ftw-5 f-18 xl:f-20">Содержание</div>
					</div>
				</div>

				<div class="lang-blog--text-content w-100 ofw-ao flex f-col rg-24 f-14 md:f-16"><?=$arResult['~DETAIL_TEXT']?></div>


				<?if(!empty($arResult['PROPERTIES']['end']['VALUE']['TEXT'])):?>
					<div class="w-100 flex f-col gap-16 lh-nl">
						<div class="f-16 xl:f-24 ftw-5"><?=$arResult['PROPERTIES']['end']['DESCRIPTION']?></div>
						<div class="w-100 f-14 xl:f-18"><?=$arResult['PROPERTIES']['end']['~VALUE']['TEXT']?></div>
					</div>
				<?endif;?>

				<div class="w-100 flex ai-c jc-s">
					<footer class="flex ai-c cg-16">
						<div class="w-50px h-50px atr-1x1 r-100 bg-py bg-cr bg-cn bg-c bg-load lazyload" data-bg="<?=$autor['PREVIEW_PICTURE']?>"></div>
						<div class="flex f-col rg-8 lh-sd">
							<div class="ftw-6 f-16 sm:f-18">Автор: <cite><?=$autor['NAME']?></cite></div>
							<div class="c-sy2 f-14 sm:f-16"><?=$autor['PREVIEW_TEXT']?></div>
						</div>
					</footer>
				</div>

				<div class="flex c-sy flex f-col sm:f-row gap-16 sm:ai-c sm:jc-e f-14 xl:f-18 lh-sd">

					<div class="grow-1 flex jc-s order-2 sm:order-1">
						<a href="/blog/" class="pt-4 sm:pt-6 pr-8 sm:pr-10 pb-4 sm:pb-6 pl-8 sm:pl-10 flex ai-c jc-c gap-8 bg-tt c-dt b-1 b-sd b-070B43 r-8 f-14 md:f-16 ws-nw hover:bg-070B43 hover:c-we active:tm-c-95 tn-all-3 ftw-5">
							<div class="d-none sm:flex">
								<svg width="12" height="12" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M32.1103 14.3534C32.1095 14.3527 32.1088 14.3519 32.108 14.3511L18.6466 0.890259C18.0728 0.316223 17.31 0 16.4985 0C15.6871 0 14.9242 0.315971 14.3502 0.890007L0.895832 14.3441C0.8913 14.3486 0.886768 14.3534 0.882236 14.3579C-0.296048 15.543 -0.294034 17.4658 0.888027 18.6479C1.42807 19.1881 2.14134 19.5011 2.90395 19.5338C2.93492 19.5368 2.96614 19.5384 2.99761 19.5384H3.53413V29.4448C3.53413 31.405 5.12909 33 7.08988 33H12.3564C12.8902 33 13.3232 32.5672 13.3232 32.0332V24.2666C13.3232 23.3721 14.0508 22.6444 14.9454 22.6444H18.0517C18.9462 22.6444 19.6738 23.3721 19.6738 24.2666V32.0332C19.6738 32.5672 20.1066 33 20.6406 33H25.9072C27.868 33 29.4629 31.405 29.4629 29.4448V19.5384H29.9604C30.7716 19.5384 31.5345 19.2224 32.1088 18.6484C33.2921 17.4643 33.2926 15.5382 32.1103 14.3534Z" fill="currentColor"></path>
								</svg>
							</div>
							<div>Все статьи</div>
						</a>
					</div>

					<div class="flex ai-c cg-24 f-14 order-1 sm:order-2">
						
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

				<?/*<a href="#lang-blog--text-header" rel="nofollow" class="luciano--btn-up ai-c r-100 c-we flex jc-c left-2 bottom-2 p-11 fix" title="В начало статьи">
					<svg widht="24" height="24" style="fill:#FFF" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M318 177.5c3.8-8.8 2-19-4.6-26l-136-144C172.9 2.7 166.6 0 160 0s-12.9 2.7-17.4 7.5l-136 144c-6.6 7-8.4 17.2-4.6 26S14.4 192 24 192h88l0 288c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32l0-288h88c9.6 0 18.2-5.7 22-14.5z"/></svg>
				</a>*/?>
			</div>
		</div>
		
	</div>

</section>

<script>
	let container = document.querySelector('.lang-blog--menu-page');
	let wrapper = document.createElement('ul');
	// let title = document.createElement('div');
	// title.classList.add('f-29','md:f-42','mb-11','ta-l','w-100');
	// title.innerText = 'Содержание';
	// wrapper.style.listStyleType = 'none';
	wrapper.classList.add('m-0','p-0','flex','f-col', 'list-ne', 'gap-16', 'f-16', 'md:f-18');
	// wrapper.append(title);
	
	document.querySelector('.lang-blog--text-content').querySelectorAll('h2,h3,h4,h5,h6').forEach((item,k) => {

		switch(item.tagName)
		{
			case 'H2':
				break;
			case 'H3':
				break;
			case 'H4':
				break;
			case 'H5':
				break;
			case 'H6':
				break;
		}

		item.id = `luciano--text-title-${k}`;
		item.classList.add('mb-0', 'f-20', 'xl:f-24', 'ftw-5');

		let it = document.createElement('li');
		let link = document.createElement('a');
		link.classList.add('lh-sd', 'flex','tn-all-3', 'c-0187AC', 'ftw-4', 'hover:tdl-u', 'tuo-4', 'hover:tdc-py');

		switch(item.tagName)
		{
			case 'H2':
				it.classList.add('link-h2');
				break;
			case 'H3':
				it.classList.add('link-h3');
				// link.classList.add('hover:bg-F9F9F9', 'tn-all-3');
				break;
			case 'H4':
				it.classList.add('link-h4');
				// link.classList.add('hover:bg-F9F9F9', 'tn-all-3');
				break;
			case 'H5':
				it.classList.add('link-h5');
				// link.classList.add('hover:bg-F9F9F9', 'tn-all-3');
				break;
			case 'H6':
				it.classList.add('link-h6');
				// link.classList.add('hover:bg-F9F9F9', 'tn-all-3');
				break;
		}

		
		// link.classList.add('main-link','main-link--1');
		link.innerText = item.innerText;
		link.href = `#${item.id}`;
		it.append(link);
		wrapper.append(it);
		//link[item.tagName].push(item);
	});

	document.querySelectorAll('.lang-blog--text-content img').forEach(img => {
		img.setAttribute('data-fancybox', "galleryNewsDetail");
		img.classList.add('w-100', 'h-ao', 'r-12');
	});

	document.querySelectorAll('.lang-blog--text-content a').forEach(link => {
		link.classList.add('tdl-u', 'tuo-4', 'tdc-py', 'c-pr', 'tdt-1', 'hover:c-sy2', 'tn-all-3');
	})

	if(document.querySelector('.lang-blog--text-content').querySelectorAll('h2,h3,h4,h5,h6').length > 0)
	{
		// container.appendChild(title);
		container.appendChild(wrapper);
		// wrapper.style.height = wrapper.clientHeight + 'px';
		// wrapper.style.top = document.querySelector('header').clientHeight + 'px';

		if(window.location.hash == "")
        {
            if(wrapper !== null)
            {
                var navA = wrapper.querySelector('a');
                if(navA !== null)
                {
                    wrapper.querySelector('a').classList.add('border-gray-6');
                }
                
            }
            
        }

        document.addEventListener('scroll',function(e) {
            document.querySelectorAll('h2,h3,h4,h5,h6').forEach(
                (heading) =>
                {
                    if(heading.id)
                    {
                        if(window.pageYOffset+100 >= (heading.offsetTop - heading.clientHeight))
                        {
                            // wrapper.querySelectorAll('a').forEach((itemScroll)=> {
                            //     itemScroll.classList.remove('b-0187AC');
                            //     itemScroll.classList.add('b-F9F9F9');
                            // });

                            // wrapper.querySelector(`[href*="#${heading.id}"]`).classList.add('b-0187AC');
                            // wrapper.querySelector(`[href*="#${heading.id}"]`).classList.remove('b-F9F9F9');
                        }
                    }
                }
            );
        });
	}
</script>

<?
$schemaData = [
	'@context' => 'http://schema.org',
	'@type' => 'Article',
	'headline' => $arResult['NAME'],
	'image' => $arResult['PREVIEW_PICTURE']['SRC'],
	'description' => $arResult['PREVIEW_TEXT'],
	'datePublished' => date(DateTime::ATOM, strtotime($arResult['DATE_CREATE'])),
	'dateModified' => date(DateTime::ATOM, strtotime($arResult['TIMESTAMP_X'])),
	'mainEntityOfPage' => [
		'@type' => 'WebPage',
		'@id' => $arResult['DETAIL_PAGE_URL'],
	],
	'author' => [
		[
			"@type" => "Organization",
			"name" => $autor['NAME'],
			"url" => $autor['url'],
		]
	],
	'publisher' => [
		'@type' => 'Organization',
		'name' => $autor['NAME'],
		'url' => $autor['url'],
		'logo' => [
			'@type' => 'ImageObject',
			'url' => $autor['PREVIEW_PICTURE'],
		]
	],
];

echo '<script type="application/ld+json">'.json_encode($schemaData).'</script>';
?>