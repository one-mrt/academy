<?
global $APPLICATION;

use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Lang\Region;
use Lang\Css\Component as comp;

$lang = Util::getLang();
$region = Region::getCurrent(['select' => ['prefix']]);
$site = ucfirst($region->getPrefix()->getValue());
$textTableString = "LangText{$lang}";
$naprTableString = "LangNapr{$site}";
$naprIblock = Util::getIblockObject($naprTableString, ['select' => ['SECTION_PAGE_URL']]);

if(!empty($naprIblock)):
    $title = Util::getElementObject($textTableString, ['NAME', 'PREVIEW_PICTURE', 'text'], ['ID' => 173834]);

    $naprList = Util::getSectionsObject($naprTableString, [
        'select' => ['NAME', 'DESCRIPTION', 'PICTURE', 'CODE', 'IBLOCK_SECTION_ID'],
        'limit' => 6,
    ]);
    if(!empty($naprList)):?>
        <section class="w-100 d-f fd-c rg-32 xl:rg-48">

            <div class="<?=comp::get('container')?> d-f fd-c ai-c rg-16 md:rg-0" <?=Util::iblockSectionEditAddId($title)?>>
                <div class="w-100 d-f fd-c lg:fd-r jc-c ai-c ta-c xl:ta-l">
                    <?if(!empty($title->getPreviewPicture())):?>
                        <div class="atr-1x1 w-54px h-54px bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=\CFile::GetPath($title->getPreviewPicture())?>"></div>
                    <?endif;?>
                    <?if(!empty($title->getText())):
                        foreach($title->getText()->getAll() as $text):?>
                            <div class="ftw-6"><?=$text->getValue()?></div>
                            <?break;?>
                        <?endforeach;?>
                    <?endif;?>
                </div>
                <h2 class="<?=comp::get("titleh2", "xl:f-55", "xl:f-42")?> lg:w-8/12 xl:w-8/12"><?=$title->getName()?></h2>
            </div>

            <?
            $cardMaketWidth = [
                0 => "md:w-6/12 xl:w-31.875 fd-c rg-32",
                1 => "md:w-6/12 xl:w-42.6 fd-c rg-32",
                2 => "xl:w-25.525 fd-c md:fd-r xl:fd-c rg-32 md:cg-32",
            ];
            $cardMaketAspect = [
                0 => [
                    "w" => "",
                    "a" => "xl:ar-100x84"
                ],
                1 => [
                    "w" => "",
                    "a" => "xl:ar-100x95",
                ],
                2 => [
                    "w" => "",
                    "a" => "xl:ar-100x51",
                ],
                3 => [
                    "w" => "",
                    "a" => "xl:ar-100x80",
                ],
                4 => [
                    "w" => "md:w-6/12 lg:w-100",
                    "a" => "xl:ar-100x128",
                ],
                5 => [
                    "w" => "md:w-6/12 lg:w-100",
                    "a" => "xl:ar-1x1",
                ]
            ];
            $index = 0;
            $k = 0;
            ?>

            <div class="<?=comp::get('container')?>">
                <div class="<?=comp::get('row')?> rg-32">

                    <?foreach($naprList as $item):
                        $iPicture = "";
                        $modPicture = "";

                        if(!empty($item->getPicture()))
                        {
                            $iPicture = 'data-bg="' . \CFile::GetPath($item->getPicture()) . '"';
                            $modPicture = "bgs-cr bgr-nr bp-c bi-load lazyload";
                        }
                        ?>

                        <?if((intval($k)+1) % 2 == 1):?>
                            <div class="<?=comp::get("col")?> <?=$cardMaketWidth[$index]?> d-f">
                            <?$index++?>
                        <?endif;?>
                        
                            <a class="of-h d-f bc-dt r-20 h:bxs-3 h:tm--ty5 tn-all-3 h:bc-py|.i h:p-16|.i h:h-100|.t h:oy-1|.t h:pt-8|.t <?=$cardMaketAspect[$k]["w"]?>"
                                href="<?=Util::getDetailPageUrl($naprIblock->getSectionPageUrl(), $item, true, 'S')?>" <?=Util::iblockSectionEditAddId($item)?>>
                                <div class="w-100 r-20 ar-16x9 <?=$cardMaketAspect[$k]["a"]?> p-32 d-f fd-c jc-e <?=$modPicture?>" <?=$iPicture?>>
                                    <div class="d-f ai-e jc-b c-w cg-16">
                                        <div class="d-f fd-c fg-1 lh-1">
                                            <div class="ftw-5 f-18 lg:f-24"><?=$item->getName()?></div>

                                            <?if(!empty($item->getDescription())):?>
                                                <div class="mb-0 f-14 lg:f-16 h-0px oy-0 tn-all-3 tp-oy t"><?=$item->getDescription()?></div>
                                            <?endif;?>
                                        </div>
                                        <i class="d-f jc-e ai-e fg-0 r-100% tn-all-3 mnw-46px w-46px h-46px i">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M13 1L1 13M13 1H3M13 1V11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </i>
                                    </div>
                                </div>
                            </a>

                        <?=((intval($k)+1) % 2 == 0) ? '</div>' : ''?>
                        <?$k++;?>
                    <?endforeach;?>

                </div>
            </div>

        </section>
    <?endif;?>
<?endif;?>