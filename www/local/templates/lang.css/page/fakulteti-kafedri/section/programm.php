<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\Engine\Response\BFile;


if(!empty($kafList)):

    $naprListEl = [];

    foreach($kafList as $kValue)
    {
        if(!empty($kValue->getNap()->getAll()))
        {
            foreach($kValue->getNap()->getAll() as $item)
            {
                $naprListEl[$item->getElement()->getIblockSectionId()][$item->getElement()->getId()] = $item->getElement()->getId();
            }
        }
    }

    $naprList = Util::getSectionsObject($naprTableString, [
        'select' => ['NAME', 'UF_DPO'],
        'filter' => ['ID' => array_keys($naprListEl)],
    ]);

    $title = Util::getElement($textTableString, ['NAME'], ['ID' => 204207]);
    $newCard = Util::getElement($textTableString, ["NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE", 'ld' => 'link.DESCRIPTION'], ['ID' => 173877]);

    if(!empty($newCard))
    {
        $newCard['PREVIEW_PICTURE'] = \CFile::GetPath($newCard['PREVIEW_PICTURE']);
        $newCard['DETAIL_PICTURE'] = \CFile::GetPath($newCard['DETAIL_PICTURE']);
    }

    $bgCard = Util::getElementObject($textTableString, ['PREVIEW_PICTURE'], ["ID" => 173878]);

    if(!empty($bgCard))
    {
        $naprPicture = BFile::createByFileId($bgCard->getPreviewPicture())->getFile();
    }

    $linkGos = Util::getElementObject($textTableString, ['link'], ['ID' => 205452]);

    ?>

    <section class="<?=comp::get("programmBlock")?>">
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

        <?if(!empty($title)):?>
            <div class="<?=comp::get('container')?>" <?=Util::iblockSectionEditAddId($title)?>>
                <h2 class="<?=comp::get("titleh2")?>"><?=$title['NAME']?></h2>
            </div>
        <?endif;?>

        <div class="<?=comp::get("container")?> d-f fd-c rg-32">

            <div class="lang-tabs--programm w-100 d-f lg:fw-w jc-s lg:jc-c g-16 ofx-a" data-tab-programm="">
                <?
                $k = 0;
                foreach($naprList as $item):?>
                    <?$modBtn = ($k != 0) ? ['a' => "bc-w c-py h:bc-py h:c-w", 'r' => "bc-py c-w h:bc-w h:c-dt"] : ['a' => "", 'r' => "h:bc-w h:c-dt"];?>
                    <button class="lang-tab--programm m-0 <?=comp::get("btnPy", $modBtn['a'], $modBtn['r'])?> ws-nw"><?=$item->getName()?></button>
                <?
                $k++;
                endforeach;?>
            </div>

            <div class="w-100" data-pan-programm="">
                <?foreach($naprList as $item):?>
                    <div class="<?=comp::get('row')?> rg-32">
                        <?
                        $k = 1;
                        $filter = $item->getId();
                        $linkDpo = $item->getUfDpo();
                        $progList = Util::getElementsObject($naprTableString, [
                            'select' => ['NAME', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'CODE', 'svg.FILE.ID', 'forma.ITEM', 'spec.ELEMENT', 'link'],
                            'filter' => ['IBLOCK_SECTION_ID' => $item->getId(), 'ID' => array_keys($naprListEl[$item->getId()])]
                        ]);
                        foreach($progList as $prog):
                            $detailLink = !empty($prog->getLink()) ? $prog->getLink()->getValue() : Util::getDetailPageUrl($naprIblock->getDetailPageUrl(), $prog);
                            ?>
                            <div class="<?=comp::get('col')?> <?=comp::get("programmCol")?> <?=($k > 8) ? "lang-item--{$filter} d-n md:d-b" : ''?>">
                                <a href="<?=$detailLink?>" class="<?=comp::get("programmItem")?>">
                                    
                                    <div class="<?=comp::get("programmSectionBlock")?>">
                                        <div class="<?=comp::get("programmSectionContent")?>">

                                            <div class="<?=comp::get("programmSectionNav")?>">
                                                <div class="ws-nw"><?=$item->getName()?></div>
                                                <?$spec = $prog->getSpec();
                                                if(!empty($spec)):?>
                                                    <div class="<?=comp::get("programmSectionSeparator")?>">
                                                        <svg width="11" height="7" viewBox="0 0 11 7" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M3.90708e-05 3.92002V2.74402H8.15504L6.13904 0.728023L6.85304 2.34842e-05L10.192 3.33902L6.85304 6.67802L6.13904 5.95002L8.16904 3.92002H3.90708e-05Z"/></svg>
                                                    </div>
                                                    <div class="<?=comp::get("programmSectionSpec")?>"><?=$spec->getElement()->getName()?></div>
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
                                        <?
                                        $svg = $prog->getSvg();
                                        if(!empty($svg)):
                                            $svg = \CFile::GetPath($svg->getFile()->getId());
                                            ?>
                                            <div class="<?=comp::get("programmSectionIcon")?>"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . $svg)?></div>
                                        <?endif;?>
                                    </div>

                                    <div class="<?=comp::get("programmItemTitle")?>"><?=$prog->getName()?></div>

                                    <div class="<?=comp::get("programmItemButton")?>">
                                        <?if(empty($prog->getLink())):?>
                                            <?if(!empty($linkDpo)):?>
                                                <object class="<?=comp::get("programmItemDpoButton")?>">
                                                    <button data-src="<?=SITE_TEMPLATE_PATH?>/ajax/dpo/?idir=<?=$prog->getId()?>"
                                                        data-fancybox="" data-type="ajax" data-close-button="false"
                                                        class="<?=comp::get("btnDt", "w-100", "")?> a">Записаться</button>
                                                </object>
                                            <?elseif(!empty($linkGos)):?>
                                                <object class="<?=comp::get("programmItemAllButton")?>" <?=Util::iblockElementEditAddId($linkGos)?>>
                                                    <a href="<?=$linkGos->getLink()->getValue()?>" target="_blank" rel="nofollow" class="<?=comp::get("btnDt", "w-100", "")?> a"><?=$linkGos->getLink()->getDescription()?></a>
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
                        <?endforeach;?>

                        <?if(!empty($newCard)):?>
                            <div class="w-100 md:w-6/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                <div class="w-100 ar-10x8 md:ar-1x1 lg:ar-10x8 p-32 d-f fd-c jc-c rg-16 pn-r r-20 bw-1 bs-s bcr-C6C6C6 bgs-cr bp-c bgr-nr of-h bi-load lazyload" data-bg="<?=$newCard['DETAIL_PICTURE']?>" <?=Util::iblockElementEditAddId($newCard)?>>
                                    <div class="h-100 pn-a t-0 rt-0 b-0 w-40 md:w-50 xl:w-40 bgs-cr md:bgs-cn lg:bgs-cr bp-c md:bp-b lg:bp-c bgr-nr lazyload" data-bg="<?=$newCard['PREVIEW_PICTURE']?>"></div>
                                    <div class="w-100 d-f fd-c jc-c rg-16 fg-1 pn-r z-1">
                                        <div class="w-60 sm:f-24 ftw-5 c-py lh-1"><?=$newCard['NAME']?></div>
                                        <div class="f-12 sm:f-14 lg:f-16 w-60"><?=$newCard['PREVIEW_TEXT']?></div>
                                    </div>
                                    <div class="w-100 pn-r z-1">
                                        <button class="<?=comp::get("btnPy")?>"
                                            data-fancybox="" data-src="<?=SITE_TEMPLATE_PATH?>/ajax/level/" data-type="ajax"><?=$newCard['ld']?></button>
                                    </div>
                                </div>
                            </div>
                        <?endif;?>

                        <?if($k > 8):?>
                            <div class="<?=comp::get("programmButtonAll")?>">
                                <button class="lang-btn--<?=(empty($filter)) ? 'all' : $filter?> <?=comp::get("btnDt")?>">Показать еще</button>
                            </div>
                            <script>programmMobile('<?=(empty($filter)) ? 'all' : $filter?>')</script>
                        <?endif;?>
                    </div>
                <?endforeach;?>
            </div>
        </div>

        <script>
            LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/tabbis.es5.min.js', () => {
                tabbis({
                    tabGroup: '[data-tab-programm]',
                    paneGroup: '[data-pan-programm]',
                    prefix: 'program',
                });
            });

            document.addEventListener("tabbis", e => {

                if(e.detail.tab.classList.contains('lang-tab--programm'))
                {
                    document.querySelectorAll('.lang-tabs--programm > button').forEach(el => {
                        el.classList.add("bc-w", 'c-py', 'h:bc-py', 'h:c-w');
                        el.classList.remove("bc-py", 'c-w', 'h:bc-w', 'h:c-dt');
                    });

                    e.detail.tab.classList.remove("bc-w", 'c-py', 'h:bc-py', 'h:c-w', 'h:c-dt');
                    e.detail.tab.classList.add("bc-py", 'c-w');
                }

            }, false );
        </script>
    </section>

<?endif;?>