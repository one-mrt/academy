<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

?>

<?if(!empty($arResult['blog']) && !$arResult['blog']->isEmpty()):?>
    <?foreach($arResult["blog"] as $blog):
        $previewPicture = \CFile::GetFileArray($blog->getPreviewPicture());
        $url = Data::getDetailPageUrl($arResult['detailPage'], $blog);
        $schemaData = [
            '@context' => 'http://schema.org',
            '@type' => 'Article',
            'headline' => $blog->getName(),
            'image' => $previewPicture['SRC'],
            'description' => $blog->getPreviewText(),
            'datePublished' => date(DateTime::ATOM, strtotime($blog->getDateCreate())),
            'dateModified' => date(DateTime::ATOM, strtotime($blog->getTimestampX())),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $url,
            ]
        ];
        ?>
        <div class="lang--item w-100 md:w-6/12 xl:w-4/12 <?=comp::get('setcol')?>">
            <a href="<?=$url?>" class="w-100 h-100 of-h d-f fd-c rg-0 r-12 bc-w bw-0 bs-s bcr-c5c5c566 h:bcr-py h:c-py tn-all-3" <?=Data::iblockElementEditAddId($blog)?>>
                <?=Data::getImage([
                    'alt' => strip_tags($blog->getName()),
                    'src' => $previewPicture['SRC'],
                    'width' => $previewPicture['WIDTH'],
                    'height' => $previewPicture['HEIGHT'],
                    'class' => 'w-100 h-a ar-16x9 oft-cv op-c',
                    'fetchpriority' => 'high',
                ])?>
                
                <div class="w-100 p-32 d-f fd-c rg-24 fg-1">
                    <div class="w-100 d-f fd-c rg-24 fg-1">
                        <time class="f-12 lg:f-14 c-6B7B99"><?=FormatDate('j F Y', $blog->getActiveFrom());?></time>

                        <div class="lg:f-18 xl:f-20 ff-ud tt-l ftw-5 lh-1"><?=$blog->getName()?></div>

                        <?if(!empty($blog->getPreviewText())):?>
                            <div class="f-12 lg:f-14"><?=$blog->getPreviewText()?></div>
                        <?endif?>
                    </div>

                    <?if(!empty($blog->getAutor()?->getElement())):
                        $autor = $blog->getAutor()?->getElement();

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
                                <?=Data::getImageId($autor->getPreviewPicture(), [
                                    "alt" => strip_tags($autor->getName()),
                                    "fetchpriority" => "high",
                                    "class" => "w-40px h-40px r-100 oft-cv op-c"
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
                </div>
            </a>
            <?echo '<script type="application/ld+json">'.json_encode($schemaData).'</script>';?>
        </div>
    <?endforeach;?>

    <?if($arResult['nav']->getPageCount() > $arResult['nav']->getCurrentPage()):?>
        <div class="lang--nav w-100 d-f jc-c <?=comp::get('setcol')?>">
            <button class="<?=comp::get("btnGreenSm")?>">Показать еще</button>
        </div>
    <?endif;?>
<?else:?>
	<div class="w-100 <?=comp::get('setcol')?>"><?=$arResult['message']?></div>
<?endif?>