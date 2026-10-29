<?
use Lang\Main\Iblock\Util;
use Bitrix\Main\Type\DateTime;
use Lang\Css\Component as comp;

$newsTableString = "LangBlog{$site}";

$newsList = Util::getElementsObject($newsTableString, [
    'order' => ['ACTIVE_FROM' => 'DESC'],
    'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'ACTIVE_FROM', 'CODE', 'IBLOCK_SECTION_ID'],
    'limit' => 3,
	'filter' => [
        'LOGIC' => 'AND',
        ['LOGIC' => 'OR', '>=ACTIVE_TO' => new DateTime(), 'ACTIVE_TO' => null],
        ['LOGIC' => 'OR', '<=ACTIVE_FROM' => new DateTime(), 'ACTIVE_FROM' => null],
    ]
]);

if(!empty($newsList)):
    $newsIblock = Util::getIblockObject($newsTableString, ['select' => ['DETAIL_PAGE_URL']]);
    $title = Util::getElementObject($textTableString, ['NAME', 'l' => 'link'], ['ID' => 173830]);
    $sectionNews = Util::getSectionsObject($newsTableString, ['select' => ['NAME']]);
    ?>

    <section class="w-100 d-f fd-c rg-32 xl:rg-48">

        <div class="<?=comp::get('container')?>" <?=Util::iblockElementEditAddId($title)?>>
            <h2 class="mb-0 f-24 md:f-32 xl:f-55 ta-c ftw-5 lh-1"><?=$title->getName()?></h2>
        </div>

        <div class="<?=comp::get('container')?> d-f fd-c rg-32">

            <div class="lang-tabs--news w-100 d-f fw-w jc-c cg-16" data-tab-news="">
                <button class="lang-tab--news <?=comp::get("btnDt")?>">Все</button>
                <?foreach($sectionNews as $item):?>
                    <button class="lang-tab--news <?=comp::get("btnDt", "bc-w h:bc-dt c-dt h:c-w", "bc-dt c-w h:bc-w h:c-dt")?>"><?=$item->getName()?></button>
                <?endforeach;?>
            </div>

            <div class="w-100" data-pan-news="">

                <div class="w-100">
                    <div class="<?=comp::get('row')?> rg-32">
                        <?foreach($newsList as $iNews):
                            $prevNews = "";
                            $modPrevNews = "";

                            if(!empty($iNews->getPreviewPicture()))
                            {
                                $prevNews = 'data-bg="' . \CFile::GetPath($iNews->getPreviewPicture()) . '"';
                                $modPrevNews = "bp-c bgr-nr bgs-cr bi-load lazyload";
                            }
                            ?>
                            <div class="w-100 md:w-6/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                <a href="<?=Util::getDetailPageUrl($newsIblock->getDetailPageUrl(), $iNews)?>" class="w-100 d-f fd-c rg-16 h:c-py tn-all-3" <?=Util::iblockElementEditAddId($iNews)?>>
                                    <div class="w-100 ar-16x9 r-20 bc-w bp-c <?=$modPrevNews?>" <?=$prevNews?>></div>
                                    <div class="ftw-4 c-8C8C8C f-14 xl:f-16"><?=FormatDate('j F Y', $iNews->getActiveFrom());?></div>
                                    <div class="f-18 xl:f-24 ftw-5 lh-1"><?=$iNews->getName()?></div>
                                </a>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>

                <?foreach($sectionNews as $item):?>
                    <div class="w-100">
                        <div class="<?=comp::get('row')?> rg-32">
                            <?
                            $newsList = Util::getElementsObject($newsTableString, [
                                'order' => ['ACTIVE_FROM' => 'DESC'],
                                'select' => ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'ACTIVE_FROM', 'CODE', 'IBLOCK_SECTION_ID'],
                                'filter' => ['IBLOCK_SECTION_ID' => $item->getId()],
                                'limit' => 3,
                            ]);

                            foreach($newsList as $iNews):
                                $prevNews = "";
                                $modPrevNews = "";

                                if(!empty($iNews->getPreviewPicture()))
                                {
                                    $prevNews = 'data-bg="' . \CFile::GetPath($iNews->getPreviewPicture()) . '"';
                                    $modPrevNews = "bp-c bgr-nr bgs-cr bi-load lazyload";
                                }
                                ?>
                                <div class="w-100 md:w-6/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                    <a href="<?=Util::getDetailPageUrl($newsIblock->getDetailPageUrl(), $iNews)?>" class="w-100 d-f fd-c rg-16 h:c-py tn-all-3" <?=Util::iblockElementEditAddId($iNews)?>>
                                        <div class="w-100 ar-16x9 r-20 bc-w <?=$modPrevNews?>" <?=$prevNews?>></div>
                                        <div class="ftw-4 c-8C8C8C f-14 xl:f-16"><?=FormatDate('j F Y', $iNews->getActiveFrom());?></div>
                                        <div class="f-18 xl:f-24 ftw-5 lh-1"><?=$iNews->getName()?></div>
                                    </a>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                <?endforeach;?>

            </div>

        </div>

        <?if(!empty($title->getLink())):?>
            <div class="<?=comp::get('container')?> d-f jc-c">
                <a href="<?=$title->getLink()->getValue()?>" class="<?=comp::get("btnDt")?>"><?=$title->getLink()->getDescription()?></a>
            </div>
        <?endif;?>

        <script>
            LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/tabbis.es5.min.js', () => {
                tabbis({
                    tabGroup: '[data-tab-news]',
                    paneGroup: '[data-pan-news]',
                    prefix: 'news',
                });
            });
            
            document.addEventListener("tabbis", e => {
                if(e.detail.tab.classList.contains('lang-tab--news'))
                {
                    document.querySelectorAll('.lang-tabs--news > button').forEach(el => {
                        el.classList.add("bc-w", 'c-dt', 'h:bc-dt', 'h:c-w');
                        el.classList.remove("bc-dt", 'c-w', 'h:bc-w', 'h:c-dt');
                    });

                    e.detail.tab.classList.remove("bc-w", 'c-dt', 'h:bc-dt', 'h:c-w');
                    e.detail.tab.classList.add("bc-dt", 'c-w');
                }

            }, false );
        </script>

    </section>
    
<?endif;?>