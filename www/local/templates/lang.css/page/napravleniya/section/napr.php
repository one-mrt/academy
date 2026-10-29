<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\Engine\Response\BFile;


$allElement = \Bitrix\Iblock\SectionElementTable::getList([
    'select' => ['IBLOCK_ELEMENT_ID'],
    'filter' => ['IBLOCK_SECTION_ID' => $section->getId()],
    'cache' => Util::getCache()
])->fetchCollection();

if(!empty($allElement->getIblockElementIdList())):
    $listNapr = Util::getElementsObject($naprTableString, [
        'order' => ["SORT" => 'ASC', 'ID' => 'DESC'],
        'select' => ['NAME', 'PREVIEW_PICTURE', 'IBLOCK_SECTION_ID', 'CODE', 'svg', 'forma.ITEM', 'spec.ELEMENT', 'time', 'status', 'filt.ITEM', 'link'],
        'filter' => ['ID' => $allElement->getIblockElementIdList()],
    ]);

    if(!empty($listNapr->getIdList())):
        $bgCard = Util::getElementObject($textTableString, ['PREVIEW_PICTURE'], ["ID" => 173878]);
        $linkGos = Util::getElementObject($textTableString, ['link'], ['ID' => 205452]);
        $linkDpo = $section->getUfDpo();

        if(!empty($bgCard))
        {
            $naprPicture = BFile::createByFileId($bgCard->getPreviewPicture())->getFile();
        }

        $tabListFilter = ['all' => 'Все'];

        foreach($listNapr->getFiltList() as $key => $filt)
        {
            if(!empty($filt))
            {
                $tabListFilter[$filt->getItem()->getXmlId()] = $filt->getItem()->getValue();
            }
        }
        ?>

        <section class="w-100">
            <div class="<?=comp::get("container")?> d-f fd-c rg-32 xl:rg-48">

                <script>
                    programmMobile = (filter) => {
                        if(window.innerWidth < 768)
                        {
                            let btn = document.querySelector(`.lang-btn--${filter}`);

                            btn.addEventListener('click', () => {
                                document.querySelectorAll(`.lang-item--${filter}`).forEach(item => {
                                    item.classList.remove('d-n');
                                });
                                btn.remove();
                            });
                        }
                    }
                </script>

                <?if(!empty($section->getUfTitle())):?>
                    <h2 class="lg:w-100 mb-0 f-24 md:f-32 xl:f-55 ta-c ftw-5 lh-1"><?=$section->getUfTitle()?></h2>
                <?endif;?>

                <div class="w-100 d-f fd-c rg-32">
                    <?if(count($tabListFilter) > 1):?>
                        <div class="lang-tabs--napr d-f lg:jc-c g-16 f-14 lg:f-16 ofx-a ws-nw" data-tab-napr="">
                            <?foreach($tabListFilter as $filter => $nameFilter):?>
                                <?if($filter == 'all'):?>
                                    <button class="lang-tab--napr <?=comp::get("btnDt")?>">Все</button>
                                <?else:?>
                                    <button class="lang-tab--napr <?=comp::get("btnDt", "bc-w h:bc-dt c-dt h:c-w", "bc-dt c-w h:bc-w h:c-dt")?>"><?=$nameFilter?></button>
                                <?endif;?>
                            <?endforeach;?>
                        </div>
                    <?endif;?>

                    <div class="w-100" data-pan-napr="">
                        <?foreach($tabListFilter as $filter => $nameFilter):
                            $k = 1;
                            ?>
                            <div class="<?=comp::get('row')?> rg-32">
                                <?foreach($listNapr as $prog):
                                    if((!empty($prog->getFilt()) && $prog->getFilt()->getItem()->getXmlId() == $filter) || $filter == 'all'):
                                        $detailLink = !empty($prog->getLink()) ? $prog->getLink()->getValue() : Util::getDetailPageUrl($naprIblock->getDetailPageUrl(), $prog);
                                        ?>
                                        <div class="<?=comp::get("col")?> <?=comp::get("programmCol")?> <?=($k > 9) ? "lang-item--{$filter} d-n md:d-b" : ''?>">
                                            <a href="<?=$detailLink?>" <?=Util::iblockElementEditAddId($prog)?> class="<?=comp::get("programmItem")?>">

                                                <div class="<?=comp::get("programmSectionBlock")?>">
                                                    <div class="<?=comp::get("programmSectionContent")?>">

                                                        <div class="<?=comp::get("programmSectionNav")?>">
                                                            <div class="<?=comp::get("programmSectionName")?>"><?=$section->getName()?></div>
                                                            <?if(!empty($prog->getSpec()) && !empty($prog->getSpec()->getElement())):?>
                                                                <div class="<?=comp::get("programmSectionSeparator")?>">
                                                                    <svg width="11" height="7" viewBox="0 0 11 7" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M3.90708e-05 3.92002V2.74402H8.15504L6.13904 0.728023L6.85304 2.34842e-05L10.192 3.33902L6.85304 6.67802L6.13904 5.95002L8.16904 3.92002H3.90708e-05Z"/></svg>
                                                                </div>
                                                                <div class="<?=comp::get("programmSectionSpec")?>"><?=$prog->getSpec()->getElement()->getName()?></div>
                                                            <?endif;?>
                                                        </div>

                                                        <?if(!empty($prog->getForma())):?>
                                                            <div class="<?=comp::get("programmForma")?>">
                                                                <?foreach($prog->getForma()->getAll() as $form):?>
                                                                    <div class="<?=comp::get("programmFormaTitle")?>"><?=$form->getItem()->getValue()?></div>
                                                                <?endforeach;?>
                                                            </div>
                                                        <?endif;?>
                                                    </div>
                                                    <?if(!empty($prog->getSvg())):?>
                                                        <div class="<?=comp::get("programmSectionIcon")?>"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($prog->getSvg()->getValue()))?></div>
                                                    <?endif;?>
                                                </div>

                                                <div class="w-100 d-f fd-c rg-16 pn-r z-1">

                                                    <?if(!empty($prog->getTime()) || !empty($prog->getStatus())):?>
                                                        <div class="d-f cg-8 f-14 c-8C8C8C lh-1 ws-nw">
                                                            <?if(!empty($prog->getTime())):?>
                                                                <div title="Продолжительность" class=""><?=$prog->getTime()->getValue()?></div>
                                                            <?endif;?>

                                                            <?if(!empty($prog->getTime()) && !empty($prog->getStatus())):?>
                                                                <div class="">•</div>
                                                            <?endif;?>

                                                            <?if(!empty($prog->getStatus())):?>
                                                                <div title="Старт" class=""><?=FormatDate('j F Y', strtotime($prog->getStatus()->getValue()));?></div>
                                                            <?endif;?>
                                                        </div>
                                                    <?endif;?>
                                                </div>

                                                <div class="<?=comp::get("programmItemTitle")?>"><?=$prog->getName()?></div>

                                                <div class="<?=comp::get("programmItemButton")?>">
                                                    <?if(empty($prog->getLink())):?>
                                                        <?if(!empty($linkDpo)):?>
                                                            <object class="d-f fg-1">
                                                                <button data-src="<?=SITE_TEMPLATE_PATH?>/ajax/dpo/?idir=<?=$prog->getId()?>"
                                                                    data-fancybox="" data-type="ajax" data-close-button="false"
                                                                    class="<?=comp::get("btnDt", "w-100")?> a">Записаться</button>
                                                            </object>
                                                        <?elseif(!empty($linkGos)):?>
                                                            <object class="d-f fg-1" <?=Util::iblockElementEditAddId($linkGos)?>>
                                                                <a href="<?=$linkGos->getLink()->getValue()?>" target="_blank" rel="nofollow" class="<?=comp::get("btnDt", "w-100")?> a"><?=$linkGos->getLink()->getDescription()?></a>
                                                            </object>
                                                        <?endif;?>
                                                    <?else:?>
                                                        <div class="<?=comp::get("btnDt", "w-100")?> a"><?=!empty($linkDpo) ? 'Записаться' : 'Поступить'?></div>
                                                    <?endif;?>
                                                    <div class="<?=comp::get("btnDt", "bc-t h:bc-dt c-dt h:c-w fg-1", "bc-dt c-w h:bc-w h:c-dt")?> s">Подробнее</div>
                                                </div>

                                                <?if(!empty($naprPicture)):?>
                                                    <div class="<?=comp::get("programmItemPicture")?> lazyload" data-bg="<?=$naprPicture['SRC']?>"></div>
                                                <?endif;?>

                                                <div class="<?=comp::get("programmItemBackground")?>"></div>

                                                <?if(!empty($prog->getPreviewPicture())):
                                                    $naprPrev = 'data-bg="' . BFile::createByFileId($prog->getPreviewPicture())->getFile()['SRC'] . '"';
                                                    $modPrev = "bgs-cr bp-c bgr-nr bi-load lazyload";
                                                    ?>
                                                    <div class="w-100 h-100 pn-a i-0 <?=$modPrev?>" <?=$naprPrev?>></div>
                                                <?endif;?>
                                            </a>
                                        </div>
                                        <?$k++;?>
                                    <?endif;?>
                                <?endforeach;?>

                                <?if($k > 9):?>
                                    <div class="w-100 pr-16 pl-16 fg-0 fs-0 d-f md:d-n jc-c">
                                        <button class="lang-btn--<?=$filter?> <?=comp::get("btnDt")?>">Показать еще</button>
                                    </div>
                                    <script>programmMobile('<?=$filter?>')</script>
                                <?endif;?>

                            </div>
                        <?endforeach;?>
                    </div>
                </div>

                <?if(count($tabListFilter) > 1):?>
                    <script>
                        LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/tabbis.es5.min.js', () => {
                            tabbis({
                                tabGroup: '[data-tab-napr]',
                                paneGroup: '[data-pan-napr]',
                                prefix: 'napr',
                            });
                        });

                        document.addEventListener("tabbis", e => {

                            if(e.detail.tab.classList.contains('lang-tab--napr'))
                            {
                                document.querySelectorAll('.lang-tabs--napr > button').forEach(el => {
                                    el.classList.add("bc-w", 'c-dt', 'h:bc-dt', 'h:c-w');
                                    el.classList.remove("bc-dt", 'c-w', 'h:bc-w', 'h:c-dt');
                                });

                                e.detail.tab.classList.remove("bc-w", 'c-dt', 'h:bc-dt', 'h:c-w');
                                e.detail.tab.classList.add("bc-dt", 'c-w');
                            }

                        }, false );
                    </script>
                <?endif;?>
            </div>
        </section>

    <?endif;?>

<?endif;?>