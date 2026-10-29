<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$naprTableString = "LangNapr{$site}";
$naprIblock = Util::getIblockObject($naprTableString, ['select' => ['SECTION_PAGE_URL']]);

$naprList = Util::getSectionsObject($naprTableString, [
    'select' => ['NAME', 'DESCRIPTION', 'PICTURE', 'CODE', 'IBLOCK_SECTION_ID'],
    'filter' => ['ACTIVE' => 'Y'],
    'limit' => '6',
]);
?>

<section class="w-100 d-f fd-c rg-32">

    <?
    $cardMaketWidth = [
        0 => "w-100 md:w-6/12 xl:w-31.875 fd-c rg-32",
        1 => "w-100 md:w-6/12 xl:w-42.6 fd-c rg-32",
        2 => "w-100 md:w-100 xl:w-25.525 fd-c md:fd-r xl:fd-c rg-32 md:cg-32",
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