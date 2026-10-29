<?
global $APPLICATION;

use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$lang = Util::getLang();
// $site = ucfirst($_SESSION["SOTBIT_REGIONS"]["UF_PREFIX"]);
$textTableString = "LangText{$lang}";
$textTable = Util::getTableString($textTableString);
$textIblock = Util::getIblock(['ID'], ['API_CODE' => $textTableString]);

$title = Util::getSection($textTableString, ['NAME', 'DESCRIPTION'], ['ID' => 7885]);

$edgetList = Util::getElements($textTableString, [
    'select' => ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 's' => 'svg.FILE.ID'],
    'filter' => ['IBLOCK_SECTION_ID' => 7885],
    'limit' => 7
]);

$viewList = [
    0 => [
        'w' => "xl:w-50",
        "block" => "w-100 xl:ar-100x38 p-32 d-f fd-c jc-c rg-16 r-20 bc-w bgs-cr bp-c bgr-nr bi-load lazyload",
        's' => 'd-f',
        "t" => "xl:f-24 ftw-5 lh-1 c-w",
        "title" => "y",
        "text" => "f-14 c-w",
    ],
    1 => [
        'w' => "md:w-4/12 xl:w-25",
        "block" => "w-100 ar-100x80 md:ar-10x8 xl:ar-100x80 p-32 d-f fd-c jc-c rg-16 r-20 bc-w bgs-cr bp-c bgr-nr bi-load lazyload",
        's' => 'd-f',
        "t" => "xl:f-24 ftw-5 lh-1",
        "title" => "n",
        "text" => "f-14",
    ],
    2 => [
        'w' => "md:w-4/12 xl:w-25",
        "block" => "w-100 ar-100x80 md:ar-10x8 xl:ar-100x80 p-32 d-f fd-c jc-c rg-16 r-20 bc-w bgs-cr bp-c bgr-nr bi-load lazyload",
        's' => 'd-f',
        "t" => "xl:f-24 ftw-5 lh-1",
        "title" => "n",
        "text" => "f-14",
    ],
    3 => [
        'w' => "md:w-4/12 xl:w-50",
        "block" => "w-100 ar-100x68 md:ar-10x8 xl:ar-100x68 p-32 d-f fd-c jc-c rg-16 r-20 bc-w bgs-cr bp-c bgr-nr bi-load lazyload",
        's' => 'd-f',
        "t" => "xl:f-24 ftw-5 lh-1",
        "title" => "n",
        "text" => "f-14",
    ],

    4 => [
        'w' => "md:w-50 xl:w-100",
        "block" => "w-100 p-32 d-f fd-c jc-s rg-16 r-20 bc-w bw-1 bs-s bcr-E6E4E4",
        's' => 'd-f',
        "t" => "xl:f-24 ftw-5 lh-1",
        "title" => "y",
        "text" => "f-14",
    ],
    5 => [
        'w' => "md:w-50 xl:w-50",
        "block" => "w-100 h-100 p-32 d-f fd-c jc-s rg-16 r-20 bc-w bw-1 bs-s bcr-E6E4E4",
        's' => 'd-f',
        "t" => "xl:f-24 ftw-5 lh-1",
        "title" => "y",
        "text" => "f-14",
    ],
    6 => [
        'w' => "md:w-100 xl:w-50",
        "block" => "w-100 h-100 p-32 d-f fd-c jc-s rg-16 r-20 bc-w bw-1 bs-s bcr-E6E4E4",
        's' => 'd-f',
        "t" => "xl:f-24 ftw-5 lh-1",
        "title" => "y",
        "text" => "f-14",
    ],
];
?>

<section class="w-100 d-f fd-c rg-32">

    <div class="<?=comp::get('container')?> d-f fd-c ai-c ta-c rg-16" <?=Util::iblockSectionEditAddId($title)?>>
        <h2 class="xl:w-60 mb-0 f-24 lg:f-32 xl:f-55 ta-c ftw-5 lh-1"><?=$title['NAME']?></h2>
        <?if(!empty($title["DESCRIPTION"])):?>
            <div class="xl:w-50"><?=$title["DESCRIPTION"]?></div>
        <?endif;?>
    </div>

    <div class="<?=comp::get('container')?>">
        <div class="<?=comp::get("row")?> rg-32">
            <?foreach($edgetList as $k => $item):
                if(!empty($item["PREVIEW_PICTURE"]))
                {
                    $item["PREVIEW_PICTURE"] = CFile::GetPath($item["PREVIEW_PICTURE"]);
                }
                ?>
                <div class="w-100 <?=$viewList[$k]['w']?> pr-16 pl-16 fg-0 fs-0">
                    <div class="<?=$viewList[$k]['block']?>" <?=!empty($item["PREVIEW_PICTURE"]) ? "data-bg=\"{$item["PREVIEW_PICTURE"]}\"" : ""?> <?=Util::iblockElementEditAddId($item)?>>
                        <?if(!empty($item['s'])):
                            $item['s'] = \CFile::GetPath($item['s']);
                            ?>
                            <div class="<?=$viewList[$k]['s']?>"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . $item['s'])?></div>
                        <?endif;?>
                        <?if(!empty($item['NAME']) && $viewList[$k]['title'] == 'y'):?>
                            <div class="<?=$viewList[$k]['t']?>"><?=$item['NAME']?></div>
                        <?endif;?>
                        <?if(!empty($item['PREVIEW_TEXT'])):?>
                            <div class="<?=$viewList[$k]['text']?>"><?=$item['PREVIEW_TEXT']?></div>
                        <?endif;?>
                    </div>
                </div>
                <?
                unset($edgetList[$k]);
                if($k == "3")
                {
                    break;
                }
                ?>
            <?endforeach;?>

            <?if(!empty($edgetList)):?>
                <div class="w-100 xl:w-50 pr-16 pl-16 fg-0 fs-0">
                    <div class="<?=comp::get("row")?> rg-32">

                        <?foreach($edgetList as $k => $item):
                            if(!empty($item["PREVIEW_PICTURE"]))
                            {
                                $item["PREVIEW_PICTURE"] = CFile::GetPath($item["PREVIEW_PICTURE"]);
                            }
                            ?>
                            <div class="w-100 <?=$viewList[$k]['w']?> pr-16 pl-16 fg-0 fs-0">
                                <div class="<?=$viewList[$k]['block']?>" <?=!empty($item["PREVIEW_PICTURE"]) ? "data-bg=\"{$item["PREVIEW_PICTURE"]}\"" : ""?> <?=Util::iblockElementEditAddId($item)?>>
                                    <?if(!empty($item['s'])):
                                        $item['s'] = \CFile::GetPath($item['s']);
                                        ?>
                                        <div class="<?=$viewList[$k]['s']?>"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . $item['s'])?></div>
                                    <?endif;?>
                                    <?if(!empty($item['NAME']) && $viewList[$k]['title'] == 'y'):?>
                                        <div class="<?=$viewList[$k]['t']?>"><?=$item['NAME']?></div>
                                    <?endif;?>
                                    <?if(!empty($item['PREVIEW_TEXT'])):?>
                                        <div class="<?=$viewList[$k]['text']?>"><?=$item['PREVIEW_TEXT']?></div>
                                    <?endif;?>
                                </div>
                            </div>

                        <?endforeach;?>
                    </div>
                </div>
            <?endif;?>
        </div>
    </div>

</section>