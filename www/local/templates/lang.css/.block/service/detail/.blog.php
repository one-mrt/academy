<?
use Bitrix\Main\Type\DateTime;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langBlog = "LangBlog{$lang}";

if(!empty($arParams['elementsId'])):

    $blogList = Data::getElementsObject($langBlog, [
        'order' => ['ACTIVE_FROM' => 'DESC'],
        'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'ACTIVE_FROM', 'CODE', 'IBLOCK_SECTION_ID'],
        'limit' => 3,
        'filter' => [
            "ID" => $arParams['elementsId'],
            'LOGIC' => 'AND',
            ['LOGIC' => 'OR', '>=ACTIVE_TO' => new DateTime(), 'ACTIVE_TO' => null],
            ['LOGIC' => 'OR', '<=ACTIVE_FROM' => new DateTime(), 'ACTIVE_FROM' => null],
        ]
    ]);

    if(!empty($blogList) && !empty($blogList->getIdList())):
        $blogIblock = Data::getIblockObject($langBlog, ['select' => ['DETAIL_PAGE_URL']]);
        ?>
        <section class="w-100">
            <div class="<?=comp::get('container')?>">
                <div class="<?=comp::get('row')?> rg-32">
                    <?foreach ($blogList as $blog):?>
                        <div class="w-100 md:w-6/12 lg:w-4/12 xl:w-4/12 <?=comp::get('setcol')?>">
                            <a href="<?=Data::getDetailPageUrlApiCode($langBlog, $blog)?>" <?=Data::iblockElementEditAddId($blog)?>
                                class="w-100 h-100 of-h d-f fd-c rg-0 r-12 bc-w bw-1 bs-s bcr-c5c5c566 h:c-py tn-all-3">
                                <?if(!empty($blog->getPreviewPicture())):?>
                                    <?=Data::getImageId($blog->getPreviewPicture(), [
                                        "title" => strip_tags($blog->getName()),
                                        "alt" => strip_tags($blog->getName()),
                                        "loading" => "lazy",
                                        "class" => "w-100 h-a ar-16x9 oft-cv op-c",
                                    ])?>
                                <?endif?>

                                <div class="w-100 d-f fd-c rg-24 p-32">
                                    <?if(!empty($blog->getActiveFrom())):?>
                                        <time class="f-14"><?=FormatDate('j F Y', $blog->getActiveFrom());?></time>
                                    <?endif;?>

                                    <div class="lg:f-14 xl:f-20 ff-ud tt-l ftw-5 lh-1"><?=$blog->getName()?></div>

                                    <?if(!empty($blog->getPreviewText())):?>
                                        <div class="f-14 lg:f-12 xl:f-14"><?=$blog->getPreviewText()?></div>
                                    <?endif?>
                                </div>
                            </a>
                        </div>
                    <?endforeach?>
                </div>
            </div>
        </section>
    <?endif?>

<?endif;?>