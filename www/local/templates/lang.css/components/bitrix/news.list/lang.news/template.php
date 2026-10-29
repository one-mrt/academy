<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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

use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;

$langText = "LangText{$lang}";
$textTable = Util::getTableString($langText);

if(!empty($arResult["ITEMS"])):?>
    <div class="lang-events--list <?=comp::get("row")?> rg-32">
        <?foreach($arResult["ITEMS"] as $key => $arItem):
            $schemaData = [
                '@context' => 'http://schema.org',
                '@type' => 'Article',
                'headline' => $arItem['NAME'],
                'image' => $arItem['PREVIEW_PICTURE']['SRC'],
                'description' => $arItem['PREVIEW_TEXT'],
                'datePublished' => date(DateTime::ATOM, strtotime($arItem['DATE_CREATE'])),
                'dateModified' => date(DateTime::ATOM, strtotime($arItem['TIMESTAMP_X'])),
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $arItem['DETAIL_PAGE_URL'],
                ]
            ];
            ?>
            <div class="lang-events--item w-100 md:w-6/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="w-100 h-100 of-h d-f fd-c rg-0 r-12 bc-w bw-0 bs-s bcr-c5c5c566 h:c-py tn-all-3" <?=Util::iblockElementEditAddId($arItem)?>>
                    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" class="w-100 h-a ar-16x9 oft-cv op-c" fetchpriority="high"
                        alt="<?=strip_tags($arItem['NAME'])?>" width="<?=$arItem['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arItem['PREVIEW_PICTURE']['HEIGHT']?>">
                    
                    <div class="w-100 p-32 d-f fd-c rg-24 fg-1">
                        <div class="w-100 d-f fd-c rg-24 fg-1">
                            <time class="f-14"><?=$arItem["DISPLAY_ACTIVE_FROM"]?></time>

                            <div class="xl:f-20 ff-ud tt-l ftw-5 lh-1"><?=$arItem["NAME"]?></div>

                            <?if(!empty($arItem["PREVIEW_TEXT"])):?>
                                <div class="f-14"><?=$arItem["PREVIEW_TEXT"]?></div>
                            <?endif?>
                        </div>

                        <?if(!empty($arItem['PROPERTIES']['autor']['VALUE'])):
                            $autor = Util::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT'], ['ID' => $arItem['PROPERTIES']['autor']['VALUE']]);

                            if(!empty($autor) && !empty($autor->getId())):
                                $schemaData['author'] = [
                                    [
                                        "@type" => "Organization",
                                        "name" => $autor->getName(),
                                        "url" => 'https://' . SITE_SERVER_NAME . '/'
                                    ]
                                ];
                                $schemaData['publisher'] = [
                                    '@type' => 'Organization',
                                    'name' => $autor->getName(),
                                    'url' => 'https://' . SITE_SERVER_NAME . '/',
                                    'logo' => [
                                        '@type' => 'ImageObject'
                                    ]
                                ];
                                ?>
                                <div class="w-100 d-f ai-c cg-16">
                                    <?if(!empty($autor->getPreviewPicture())):
                                        $schemaData['publisher']['logo']['url'] = \CFile::GetPath($autor->getPreviewPicture());
                                        ?>
                                        <?=Util::getImageId($autor->getPreviewPicture(), [
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
                            <?endif?>
                        <?endif?>
                    </div>
                </a>
                <?echo '<script type="application/ld+json">'.json_encode($schemaData).'</script>';?>
            </div>
        <?endforeach;?>

        <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
            <div class="js-lk--score-pagination w-100 fg-0 fs-0 pr-16 pl-16 d-f jc-c f-8 md:f-20 ftw-4"><?=$arResult["NAV_STRING"]?></div>
            <script>
                loadPageEvents = (btn) => {
                    this.loadMore = false;

                    if(this.loadMore)
                    {
                        return false;
                    }

                    this.src = btn.getAttribute('data-src');
                    this.loadMore = true;

                    fetch(this.src)
                        .then(response => {
                            if(response.ok)
                            {
                                response.text().then(data => {
                                    let ndp = new DOMParser();
                                    let doc = ndp.parseFromString(data, 'text/html');

                                    document.querySelector('.js-lk--score-pagination').remove();
                                    console.log(doc.querySelectorAll('.lang-events--item'));
                                    doc.querySelectorAll('.lang-events--item').forEach(item => {console.log(item);document.querySelector('.lang-events--list').append(item)});

                                    if(doc.querySelector('.js-lk--score-pagination > button'))
                                    {
                                        document.querySelector('.lang-events--list').append(doc.querySelector('.js-lk--score-pagination'));
                                    }

                                    this.loadMore = false;
                                });
                            }
                        })
                        .catch(er => {
                            
                        });
                }
            </script>
        <?endif;?>

    </div>
<?endif;?>