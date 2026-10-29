<?
global $APPLICATION;

use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$ID = rand();
$lang = Util::getLang();
$textTableString = "LangText{$lang}";
$brandTableString = "LangBrand{$lang}";
$textTable = Util::getTableString($textTableString);
$brandTable = Util::getTableString($brandTableString);

$title = Util::getElementObject($textTableString, ['NAME'], ['ID' => 173881]);

$brandList = Util::getElementsObject($brandTableString, ['select' => ['NAME', 'PREVIEW_PICTURE'], 'filter' => ['!=PREVIEW_PICTURE' => '']]);

$columnList = [
    0 => ['direction' => "adn-n", "item" => []],
    1 => ['direction' => "adn-r", "item" => []],
];
$k = 0;
foreach($brandList as $item)
{
    if((count($brandList->getIdList()) / 2) <= ($k + 1))
    {
        $columnList[0]['item'][] = $item;
    }
    else
    {
        $columnList[1]['item'][] = $item;
    }
    $k++;
}

?>

<style>
    @-webkit-keyframes move {
        0% {
            -webkit-transform: translateX(0);
            transform: translateX(0)
        }

        to {
            -webkit-transform: translateX(-50%);
            transform: translateX(-50%)
        }
    }
    @keyframes move {
        0% {
            -webkit-transform: translateX(0);
            transform: translateX(0)
        }

        to {
            -webkit-transform: translateX(-50%);
            transform: translateX(-50%)
        }
    }
</style>

<section class="w-100 d-f fd-c rg-32">

    <?if(!empty($title)):?>
        <div class="<?=comp::get('container')?>" <?=Util::iblockElementEditAddId($title)?>>
            <h2 class="mb-0 f-24 lg:f-32 xl:f-55 ta-c ftw-5 lh-1"><?=$title->getName()?></h2>
        </div>
    <?endif;?>

    <div class="w-100 d-f fd-c rg-16 lg:rg-32">
        <?foreach($columnList as $col):?>
            <div class="lang-work--animation w-100 ofx-h d-f fd-r pn-r tm-n">
                <div class="d-f cg-16 lg:cg-32 an-move atf-l aic-f adu-40 aps-r ws-nw <?=$col['direction']?>">
                    <?foreach($col['item'] as $item):?>
                        <div class="lang-work--item w-100 xl:w-4/12 ar-3x1 sm:ar-100x43 bc-w d-f ai-c jc-c p-24 md:p-32 r-12 bw-1 bs-s bcr-E6E4E4"
                            title="<?=$item->getName()?>" <?=Util::iblockElementEditAddId($item)?>>
                            <div class="w-10/12 ar-100x48 bgs-cn bp-c bgr-nr bi-load lazyload" data-bg="<?=CFile::GetPath($item->getPreviewPicture())?>"></div>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
        <?endforeach;?>
    </div>

    <script>
        function resizeCardWork()
        {
            let width = 0;
            
            if(window.innerWidth >= 1200)
            {
                width = ((document.querySelector('.lang-work--animation').clientWidth * (1/7)) / 16).toFixed(2) + 'rem';
            }
            else if(window.innerWidth >= 992)
            {
                width = ((document.querySelector('.lang-work--animation').clientWidth * (1/6)) / 16).toFixed(2) + 'rem';
            }
            else if(window.innerWidth >= 768)
            {
                width = ((document.querySelector('.lang-work--animation').clientWidth * (1/5)) / 16).toFixed(2) + 'rem';
            }
            else if(window.innerWidth >= 576)
            {
                width = ((document.querySelector('.lang-work--animation').clientWidth * (1/3)) / 16).toFixed(2) + 'rem';
            }
            else if(window.innerWidth < 576)
            {
                width = ((document.querySelector('.lang-work--animation').clientWidth * (1/3)) / 16).toFixed(2) + 'rem';
            }

            document.querySelectorAll('.lang-work--item').forEach(el => {
                el.style.width = width;
            });
        }
        document.addEventListener('DOMContentLoaded', resizeCardWork.bind(false));
        window.addEventListener('resize', resizeCardWork.bind(false));
    </script>

</section>