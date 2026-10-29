<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Type\DateTime;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langBlog = "LangBlog{$lang}";

$pageBlog = Data::getElementObject($langBlog,
    ['NAME', 'DETAIL_TEXT', 'SHOW_COUNTER', 'ban', 'autor.ELEMENT'],
    ['CODE' => $arParams['code']]
);
?>

<h1 class="d-n"><?=$APPLICATION->ShowTitle(false)?></h1>

<?
// 01 - Баннер
if(!empty($pageBlog->getBan()) && !empty($pageBlog->getBan()->getValue())):
    $ban = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'], ['ID' => $pageBlog->getBan()->getValue()]);

    if(!empty($ban) && !empty($ban->getId())):?>
        <section class="w-100">
            <div class="<?=comp::get('container-full')?> d-f fd-c rg-0">
                <div class="w-100 xl:ar-100x43 of-h pn-r">

                    <div class="w-100 h-100 pn-a t-0 rt-0 b-0 l-0 d-n xl:d-b">
                        <div class="<?=comp::get("row")?>">
                            <div class="w-100 xl:w-6/12 <?=comp::get("setcol")?>">
                                <div class="w-100 h-100 bc-FFFFFF66 r-12 xl:r-24 of-h"></div>
                            </div>
                            <div class="w-100 xl:w-6/12 <?=comp::get("setcol")?>">
                                <div class="w-100 h-100 of-h bc-w r-12 xl:r-24">
                                    <?if(!empty($ban->getPreviewPicture())):?>
                                        <?=Data::getImageId($ban->getPreviewPicture(), [
                                            'alt' => strip_tags($ban->getName()),
                                            'fetchpriority' => 'high',
                                            'class' => 'w-100 h-100 oft-cv op-c r-12 xl:r-24'
                                        ])?>
                                    <?endif?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 h-100 pn-r">
                        <div class="<?=comp::get("container-p")?> h-100">
                            <div class="<?=comp::get("row")?> h-100">
                                <div class="w-100 xl:w-6/12 <?=comp::get("setcol")?>">
                                    <div class="w-100 h-100 xl:pt-32 d-f fd-c rg-32 xl:pr-32 xl:pl-32 xxl:pl-0 xxl:pr-64">

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

                                        <div class="w-100 d-f fd-c jc-c rg-32 p-24 md:p-32 xl:pix-0 xl:pb-32 fg-1 bc-w xl:bc-t r-12 xl:r-0">

                                            <div class="w-100 d-f fd-c rg-24 fg-1">
                                                <h2 class="<?=comp::get("h2")?>"><?=$ban->getName()?></h2>
                                                <?if(!empty($ban->getPreviewText())):?>
                                                    <div class="f-12 md:f-14 xxl:f-18"><?=$ban->getPreviewText()?></div>
                                                <?endif?>
                                            </div>

                                            <div class="d-f cg-8 c-5C5858 lh-1 f-12 md:f-14">
                                                <div class="d-f">
                                                    <svg width="19" height="14" viewBox="0 0 19 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M19 6.53125C19 6.53125 15.4375 0 9.5 0C3.5625 0 0 6.53125 0 6.53125C0 6.53125 3.5625 13.0625 9.5 13.0625C15.4375 13.0625 19 6.53125 19 6.53125ZM1.39294 6.53125C1.96711 5.65858 2.62751 4.84581 3.36419 4.10519C4.8925 2.5745 6.9825 1.1875 9.5 1.1875C12.0175 1.1875 14.1063 2.5745 15.637 4.10519C16.3737 4.84581 17.0341 5.65858 17.6082 6.53125C17.5394 6.63456 17.4634 6.74856 17.3767 6.87325C16.9789 7.44325 16.3911 8.20325 15.637 8.95731C14.1063 10.488 12.0163 11.875 9.5 11.875C6.9825 11.875 4.89369 10.488 3.363 8.95731C2.62633 8.21668 1.96712 7.40391 1.39294 6.53125Z" fill="#5C5858"/><path d="M9.5 3.5625C8.71264 3.5625 7.95753 3.87528 7.40078 4.43203C6.84403 4.98878 6.53125 5.74389 6.53125 6.53125C6.53125 7.31861 6.84403 8.07372 7.40078 8.63047C7.95753 9.18722 8.71264 9.5 9.5 9.5C10.2874 9.5 11.0425 9.18722 11.5992 8.63047C12.156 8.07372 12.4688 7.31861 12.4688 6.53125C12.4688 5.74389 12.156 4.98878 11.5992 4.43203C11.0425 3.87528 10.2874 3.5625 9.5 3.5625ZM5.34375 6.53125C5.34375 5.42894 5.78164 4.37179 6.56109 3.59234C7.34054 2.81289 8.39769 2.375 9.5 2.375C10.6023 2.375 11.6595 2.81289 12.4389 3.59234C13.2184 4.37179 13.6562 5.42894 13.6562 6.53125C13.6562 7.63356 13.2184 8.69071 12.4389 9.47016C11.6595 10.2496 10.6023 10.6875 9.5 10.6875C8.39769 10.6875 7.34054 10.2496 6.56109 9.47016C5.78164 8.69071 5.34375 7.63356 5.34375 6.53125Z"/></svg>
                                                </div>
                                                <div class=""><?=$pageBlog->getShowCounter()?></div>
                                                <div class="">просмотров</div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    <?endif?>
<?endif?>


<section class="w-100 xl:spt-120_hm">
    <div class="<?=comp::get('container')?> d-f fd-c rg-0">
        <div class="<?=comp::get("row")?> rg-32">

            <div class="w-100 xl:w-8/12 <?=comp::get("setcol")?> d-f fd-c rg-32">
                <div class="lang-blog--menu-page w-100 of-h p-24 md:p-32 d-f fd-c rg-24 bc-FAFAFA r-12 xl:r-24">
                    <div class="w-100 <?=comp::get("h2page")?>">Содержание статьи</div>
                </div>
                <?if(!empty($pageBlog->getDetailText())):?>
                    <div class="lang-blog--text-content w-100 of-h p-32 bc-FAFAFA r-12 xl:r-24 f-14 md:f-16 w-100|img h-a|img"><?=$pageBlog->getDetailText()?></div>
                <?endif;?>
            </div>

            <div class="w-100 xl:w-4/12 <?=comp::get("setcol")?> d-f fd-c rg-32">

                <?if(!empty($pageBlog->getAutor()) && !empty($pageBlog->getAutor()->getElement())):
                    $autor = $pageBlog->getAutor()->getElement();
                    ?>
                    <div class="w-100 p-32 d-f fd-c rg-16 bc-FAFAFA r-12 xl:r-24">
                        <div class="w-100 ff-ud tt-l xl:f-24 ftw-5">Автор статьи</div>
                        <div class="w-100 d-f ai-c cg-16">
                            <?if(!empty($autor->getPreviewPicture())):?>
                                <?=Data::getImageId($autor->getPreviewPicture(), [
                                    "alt" => strip_tags($autor->getName()),
                                    "fetchpriority" => "high",
                                    "class" => "w-60px h-60px r-100 oft-cv op-c"
                                ]);?>
                            <?endif;?>
                            <div class="fg-1">
                                <div class="f-14 ftw-6 lh-nl"><?=$autor->getName()?></div>
                                <?if(!empty($autor->getPreviewText())):?>
                                    <div class="f-10 c-6B7B99"><?=$autor->getPreviewText()?></div>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                <?endif;?>

                <?
                $workBanner = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE', 'link'], ['ID' => 224]);
                $orderBanner = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'link'], ['ID' => 225]);

                if(!empty($workBanner) && !empty($orderBanner)):?>
                    <div class="w-100">
                        <div class="<?=comp::get("row")?> rg-32">

                            <?if(!empty($workBanner) && !empty($workBanner->getId())):?>
                                <div class="w-100 md:w-6/12 xl:w-100 <?=comp::get("setcol")?>">
                                    <div class="w-100 h-100 pn-r of-h r-12 xl:r-24 bc-FAFAFA" <?=Data::iblockElementEditAddId($workBanner)?>>
                                        <?=Data::getImageId($workBanner->getPreviewPicture(), [
                                            "alt" => strip_tags($workBanner->getName()),
                                            "loading" => "lazy",
                                            "class" => "w-100 h-100 r-12 xl:r-24 oft-cv op-c",
                                        ]);?>
                                        <div class="w-100 h-100 pn-a t-0 rt-0 b-0 l-0 p-32 d-f fd-c rg-32">
                                            <div class="w-100 fg-1 ff-ud tt-l f-18 lg:f-20 ftw-5"><?=$workBanner->getName()?></div>
                                            <?if(!empty($workBanner->getLink()) && !empty($workBanner->getLink()->getValue())):?>
                                                <div class="w-100 d-f jc-s">
                                                    <a href="<?=$workBanner->getLink()->getValue()?>" class="pby-16 pix-32 xl:pby-24 xl:pix-48 bc-527CC8 h:bc-w bw-2 bs-s bcr-527CC8 xl:f-18 r-12 h:r-32 xl:h-r-48 c-w h:c-dt ff-ud ftw-6 ta-c tt-l lh-1 tn-all-3"><?=$workBanner->getLink()->getDescription()?></a>
                                                </div>
                                            <?endif?>
                                        </div>
                                    </div>
                                </div>
                            <?endif;?>
                        
                            <?if(!empty($orderBanner) && !empty($orderBanner->getId())):?>
                                <div class="w-100 md:w-6/12 xl:w-100 <?=comp::get("setcol")?>">
                                    <div class="w-100 h-100 pn-r of-h d-f fd-c r-12 xl:r-24 bi-bgb" <?=Data::iblockElementEditAddId($orderBanner)?>>
                                        <?=Data::getImageId($orderBanner->getPreviewPicture(),[
                                            "alt" => strip_tags($orderBanner->getName()),
                                            "loading" => "lazy",
                                            "class" => "w-100 h-a xl:ar-16x9 oft-cv op-c",
                                        ]);?>
                                        <div class="w-100 p-32 d-f fd-c rg-32 fg-1">
                                            <div class="w-100 d-f fd-c rg-16 fg-1 c-w">
                                                <div class="ff-ud tt-l f-18 lg:f-20 ftw-5 lh-nl"><?=$orderBanner->getName()?></div>
                                                <?if(!empty($orderBanner->getPreviewText())):?>
                                                    <div class="f-14"><?=$orderBanner->getPreviewText()?></div>
                                                <?endif;?>
                                            </div>
                                            <?if(!empty($orderBanner->getLink()) && !empty($orderBanner->getLink()->getValue())):?>
                                                <div class="w-100 d-f">
                                                    <a href="<?=$orderBanner->getLink()->getValue()?>"
                                                        class="pby-16 pix-32 xl:pby-24 xl:pix-48 bc-w h:bc-w bw-2 bs-s bcr-w xl:f-18 r-12 h:r-32 xl:h-r-48 c-dt ff-ud ftw-6 ta-c tt-l lh-1 tn-all-3"><?=$orderBanner->getLink()->getDescription()?></a>
                                                </div>
                                            <?endif;?>
                                        </div>
                                    </div>
                                </div>
                            <?endif;?>
                        </div>
                    </div>
                <?endif;?>
            </div>
        </div>
    </div>
</section>



<?
$seo = Data::getSeoPropertyElement($langBlog, $pageBlog->getId());

$APPLICATION->SetTitle(strip_tags($seo['ELEMENT_PAGE_TITLE']));
$APPLICATION->SetPageProperty("title", strip_tags($seo['ELEMENT_META_TITLE']));
$APPLICATION->SetPageProperty("description", strip_tags($seo['ELEMENT_META_DESCRIPTION']));
$APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
$APPLICATION->AddChainItem(strip_tags($pageBlog->getName()), "");
\CIBlockElement::CounterInc($pageBlog->getId());
?>

<script>
	let container = document.querySelector('.lang-blog--menu-page');
	let wrapper = document.createElement('ol');
    let lI = {h2:0, h3:0, h4:0, h5:0, h6:0}

	wrapper.classList.add('m-0','p-0', 'pl-32', 'd-f', 'fd-c', 'rg-16', 'f-16', 'md:f-18', 'lst-n');
	
	document.querySelector('.lang-blog--text-content').querySelectorAll('h2,h3,h4,h5,h6').forEach((item,k) => {

        let itemText = item.innerText

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
		item.classList.add('ftw-5', 'ff-ud');

		let it = document.createElement('li');
		let link = document.createElement('a');
		link.classList.add('lh-1', 'd-f', 'ftw-4', 'tdt-1', 'tuo-8', 'tdc-py', 'h:tdl-u', 'tn-all-3');

		switch(item.tagName)
		{
			case 'H2':
                lI.h2++
                lI.h3 = 0
                lI.h4 = 0
                lI.h5 = 0
                lI.h6 = 0
				it.classList.add('link-h2');
                itemText = `${lI.h2}. ${itemText}`
				break;
			case 'H3':
                lI.h3++
                lI.h4 = 0
                lI.h5 = 0
                lI.h6 = 0
				it.classList.add('link-h3', 'pl-16');
                itemText = `${lI.h2}.${lI.h3}. ${itemText}`
				break;
			case 'H4':
                lI.h4++
                lI.h5 = 0
                lI.h6 = 0
				it.classList.add('link-h4', 'pl-24');
                itemText = `${lI.h2}.${lI.h3}.${lI.h4}. ${itemText}`
				break;
			case 'H5':
                lI.h5++
                lI.h6 = 0
				it.classList.add('link-h5', 'pl-32');
                itemText = `${lI.h2}.${lI.h3}.${lI.h4}.${lI.h5}. ${itemText}`
				break;
			case 'H6':
                lI.h6++
				it.classList.add('link-h6', 'pl-48');
                itemText = `${lI.h2}.${lI.h3}.${lI.h4}.${lI.h5}.${lI.h6}. ${itemText}`
				break;
		}

		link.innerText = itemText
		link.href = `#${item.id}`;
		it.append(link);
		wrapper.append(it);
	});

	document.querySelectorAll('.lang-blog--text-content img').forEach(img => {
		img.setAttribute('data-fancybox', "galleryNewsDetail");
		img.classList.add('w-100', 'h-ao', 'r-12');
	});

	document.querySelectorAll('.lang-blog--text-content a').forEach(link => {
		link.classList.add('tdt-1', 'tuo-4', 'tdc-py', 'h:tdl-u', 'tn-all-3');
	})

	if(document.querySelector('.lang-blog--text-content').querySelectorAll('h2,h3,h4,h5,h6').length > 0)
	{
		container.appendChild(wrapper);

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