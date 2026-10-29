<?
use Lang\Region;
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\Engine\Response\BFile;

$lang = Util::getLang();
$region = Region::getCurrent(['select' => ['prefix']]);
$site = ucfirst($region->getPrefix()->getValue());
$textTableString = "LangText{$lang}";
$naprTableString = "LangNapr{$site}";
$naprIblock = Util::getIblockObject($naprTableString, ['select' => ['DETAIL_PAGE_URL', 'SECTION_PAGE_URL']]);

if(!empty($naprIblock)):
    $title = Util::getElementObject($textTableString, ['NAME'], ['ID' => 173876]);
    $naprList = Util::getSectionsObject($naprTableString, [
        'select' => ['NAME', 'UF_DPO'],
        'filter' => ['ACTIVE' => 'Y'],
    ]);

    if(!empty($naprList->getIdList())):

        $progList = Util::getElementsObject($naprTableString, [
            'select' => ['NAME', 'PREVIEW_PICTURE', 'IBLOCK_SECTION_ID', 'CODE', 'svg', 'forma.ITEM', 'spec.ELEMENT', 'SECTIONS', 'link'],
            'filter' => ['IBLOCK_SECTION_ID' => $naprList->getIdList()]
        ]);

        if(!empty($progList->getIdList())):
            $k = 0;
            $bgCard = Util::getElementObject($textTableString, ['PREVIEW_PICTURE'], ["ID" => 173878]);
            $linkGos = Util::getElementObject($textTableString, ['link'], ['ID' => 205452]);

            if(!empty($bgCard))
            {
                $naprPicture = BFile::createByFileId($bgCard->getPreviewPicture())->getFile();
            }
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

                <div class="<?=comp::get('container')?> <?=comp::get('programmContainerTitle')?>" <?=Util::iblockSectionEditAddId($title)?>>
                    <h2 class="<?=comp::get("titleh2", "xl:f-55", "xl:f-42")?> <?=comp::get('programmTitle')?>"><?=$title->getName()?></h2>
                </div>

                <div class="<?=comp::get("container")?> <?=comp::get('programmContainer')?>">

                    <div class="lang-tabs--programm <?=comp::get('programmTabBlock')?>" data-tab-programm="">
                        <?foreach($naprList as $item):?>
                            <?$modBtn = ($k != 0) ? ['a' => "bc-w c-py h:bc-py h:c-w", 'r' => "bc-py c-w h:bc-w h:c-dt"] : ['a' => "", 'r' => "h:bc-w h:c-dt"];?>
                            <button class="lang-tab--programm m-0 <?=comp::get("btnPy", $modBtn['a'], $modBtn['r'])?> ws-nw"><?=$item->getName()?></button>
                            <?$k++;?>
                        <?endforeach;?>
                    </div>

                    <div class="<?=comp::get("programmPanBlock")?>" data-pan-programm="">
                        <?foreach($naprList as $item):
                            $k = 1;
                            $filter = 0;
                            ?>
                            <div class="<?=comp::get('row')?> <?=comp::get("programmRow")?>">
                                <?foreach($progList as $prog):
                                    $secionId = 0;
                                    foreach($prog->getSections()->getAll() as $section)
                                    {
                                        if($item->getId() == $section->getId())
                                        {
                                            $secionId = $section->getId();
                                        }
                                    }
                                    if($prog->getIblockSectionId() == $item->getId() || $secionId == $item->getId()):
                                        $linkDpo = $item->getUfDpo();
                                        $filter = $secionId;
                                        $detailLink = !empty($prog->getLink()) ? $prog->getLink()->getValue() : Util::getDetailPageUrl($naprIblock->getDetailPageUrl(), $prog);
                                        ?>
                                        <div class="<?=comp::get('col')?> <?=comp::get("programmCol")?> <?=($k > 8) ? "lang-item--{$secionId} d-n md:d-b" : ''?>" <?=Util::iblockElementEditAddId($prog)?>>
                                            <a class="<?=comp::get("programmItem")?>" href="<?=$detailLink?>">

                                                <div class="<?=comp::get("programmSectionBlock")?>">
                                                    <div class="<?=comp::get("programmSectionContent")?>">

                                                        <div class="<?=comp::get("programmSectionNav")?>">
                                                            <div class="<?=comp::get("programmSectionName")?>"><?=$item->getName()?></div>
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

                                                    <?if(!empty($prog->getSvg())):
                                                        $svg = \CFile::GetPath($prog->getSvg()->getValue());
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
                                    <?endif;?>
                                <?endforeach;?>

                                <?
                                $newCard = Util::getElementObject($textTableString, ["NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_PICTURE", 'link'], ['ID' => 173877]);

                                if(!empty($newCard)):

                                    if(!empty($newCard->getPreviewPicture()))
                                    {
                                        $previewPicture = \CFile::GetPath($newCard->getPreviewPicture());
                                    }

                                    if(!empty($newCard->getDetailPicture()))
                                    {
                                        $detailPicture = \CFile::GetPath($newCard->getDetailPicture());
                                    }
                                    ?>
                                    <div class="<?=comp::get("programmQuizItem")?>">
                                        <div class="<?=comp::get("programmQuizContent")?> bi-load lazyload" data-bg="<?=$detailPicture?>" <?=Util::iblockElementEditAddId($newCard)?>>
                                            <div class="<?=comp::get("programmQuizPicture")?> lazyload" data-bg="<?=$previewPicture?>"></div>

                                            <div class="<?=comp::get("programmQuizTextContent")?>">
                                                <div class="<?=comp::get("programmQuizName")?>"><?=$newCard->getName()?></div>
                                                <?if(!empty($newCard->getPreviewText())):?>
                                                    <div class="<?=comp::get("programmQuizText")?>"><?=$newCard->getPreviewText()?></div>
                                                <?endif;?>
                                            </div>

                                            <?if(!empty($newCard->getLink())):?>
                                                <div class="<?=comp::get("programmQuizButton")?>">
                                                    <button class="<?=comp::get("btnPy")?>"
                                                        data-fancybox="" data-src="<?=SITE_TEMPLATE_PATH?>/ajax/level/" data-type="ajax"><?=$newCard->getLink()->getDescription()?></button>
                                                </div>
                                            <?endif;?>
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
    <?endif;?>
<?endif;?>