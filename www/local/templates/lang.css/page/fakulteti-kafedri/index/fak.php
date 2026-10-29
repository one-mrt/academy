<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\Engine\Response\BFile;

$fakSecList = Util::getSectionsObject($fakTableString, ['select' => ['NAME', 'CODE', 'PICTURE']]);

if(!empty($fakSecList->getidList())):
    $kafList = Util::getElementsObject($fakTableString, [
        'select' => ['NAME', 'IBLOCK_SECTION_ID', 'CODE', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'],
        'filter' => ['IBLOCK_SECTION_ID' => $fakSecList->getIdList()]
    ]);
    $naprList = Util::getElementsObject($naprTableString,[
        'select' => ['NAME', 'IBLOCK_SECTION_ID', 'CODE', 'forma.ITEM', 'cnap', 'link'],
        'limit' => 4,
    ]);

    $bgCard = Util::getElementObject($textTableString, ['PREVIEW_PICTURE'], ["ID" => 173878]);

    if(!empty($bgCard))
    {
        $naprPicture = BFile::createByFileId($bgCard->getPreviewPicture())->getFile();
    }
    ?>
    <section class="w-100 d-f fd-c rg-32">

        <div class="<?=comp::get('container')?> d-f fd-c ai-c">
            <h2 class="<?=comp::get("titleh2")?> lg:w-8/12"><?=$fakIblock->getName()?></h2>
        </div>

        <div class="<?=comp::get('container')?> d-f fd-c rg-32">
            <div class="lang-tabs--programm w-100 d-f lg:fw-w jc-s xl:jc-c g-16 ofx-a" data-tab-programm="">
                <button class="lang-tab--programm m-0 <?=comp::get("btnDt", "", "h:bc-w h:c-dt")?>">Факультеты</button>
                <?if(!empty($kafList->getIdList())):?>
                    <button class="lang-tab--programm m-0 <?=comp::get("btnDt", "bc-w c-dt h:bc-dt h:c-w", "bc-dt c-w h:bc-w h:c-dt")?>">Кафедры</button>
                <?endif?>
                <?if(!empty($naprList->getIdList())):?>
                    <button class="lang-tab--programm m-0 <?=comp::get("btnDt", "bc-w c-dt h:bc-dt h:c-w", "bc-dt c-w h:bc-w h:c-dt")?>">Направления</button>
                <?endif?>
            </div>

            <div class="w-100" data-pan-programm="">
                <div class="<?=comp::get('row')?> rg-32">
                    <?foreach($fakSecList as $item):
                        $picture = "";
                        $modPicture = "";

                        if(!empty($item->getPicture()))
                        {
                            $modPicture = "bgs-cr bp-c bgr-nr bi-load lazyload";
                            $picture = "data-bg=\"" . \CFile::GetPath($item->getPicture()) . "\"";
                        }
                        ?>
                    
                        <div class="w-100 md:w-6/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                            <a href="<?=Util::getDetailPageUrl($fakIblock->getSectionPageUrl(), $item, true, 'S')?>" <?=Util::iblockSectionEditAddId($item)?>
                                class="w-100 pn-r of-h ar-16x9 sm:ar-3x1 md:ar-16x9 lg:ar-16x9 p-32 d-f fd-c jc-e rg-24 r-20 bw-1 bs-s bcr-C6C6C6 h:bxs-3 h:tm--ty5 tn-all-3 <?=$modPicture?>" <?=$picture?>>
                                <div class="pn-r z-1 d-f">
                                    <div class="fg-1 f-18 sm:f-24 md:f-18 lg:f-24 lh-1 ftw-5 ff-ot c-w"><?=$item->getName()?></div>
                                    <div class="d-f fg-0">
                                        <svg width="32" height="32" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect class="fil-py" width="23" height="23" rx="5" fill="white"></rect>
                                            <path class="fil-w" d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="pn-a i-0 bi-fw bf-b2"></div>
                            </a>
                        </div>
                        
                    <?endforeach;?>
                </div>

                <?if(!empty($kafList->getIdList())):?>
                    <div class="<?=comp::get('row')?> rg-32">
                        <?foreach($kafList as $iKaf):
                            $picture = "";
                            $modPicture = "";

                            if(!empty($iKaf->getPreviewPicture()))
                            {
                                $modPicture = "bgs-cr bp-c bgr-nr bi-load lazyload";
                                $picture = "data-bg=\"" . \CFile::GetPath($iKaf->getPreviewPicture()) . "\"";
                            }
                            ?>
                        
                            <div class="w-100 md:w-6/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                <a href="<?=Util::getDetailPageUrl($fakIblock->getDetailPageUrl(), $iKaf)?>" <?=Util::iblockElementEditAddId($iKaf)?>
                                    class="w-100 pn-r of-h ar-16x9 sm:ar-3x1 md:ar-16x9 lg:ar-16x9 p-32 d-f fd-c jc-e rg-24 r-20 bw-1 bs-s bcr-C6C6C6 h:bxs-3 h:tm--ty5 tn-all-3 <?=$modPicture?>" <?=$picture?>>
                                    <div class="pn-r z-1 d-f">
                                        <div class="fg-1 f-18 sm:f-24 md:f-18 lg:f-24 lh-1 ftw-5 ff-ot c-w"><?=$iKaf->getName()?></div>
                                        <div class="d-f fg-0">
                                            <svg width="32" height="32" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect class="fil-py" width="23" height="23" rx="5" fill="white"></rect>
                                                <path class="fil-w" d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="pn-a i-0 bi-fw bf-b2"></div>
                                </a>
                            </div>
                            
                        <?endforeach;?>
                    </div>
                <?endif;?>

                <?if(!empty($naprList->getIdList())):?>
                    <div class="<?=comp::get('row')?> rg-32">
                        <?foreach($naprList as $prog):
                            $detailLink = !empty($prog->getLink()) ? $prog->getLink()->getValue() : Util::getDetailPageUrl($naprIblock->getDetailPageUrl(), $prog);
                            ?>
                            <div class="w-100 md:w-6/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                <a href="<?=$detailLink?>"
                                    class="w-100 h-100 p-32 d-f fd-c rg-24 r-20 bc-t h:bc-dt h:c-w h:bc-DD445A|sp h:bcr-DD445A|sp h:c-w|sp bw-1 bs-s bcr-C6C6C6 h:bcr-dt h:bxs-3 h:tm--ty5 tn-all-3 pn-r z-1 of-h">
                                    <div class="d-f cg-8 fg-1 pn-r z-1">
                                        <div class="d-f fd-c rg-16 fg-1">
                                            <?if(!empty($prog->getForma())):?>
                                                <div class="d-f jc-s fw-w g-8 lh-1 fg-1">
                                                    <?foreach($prog->getForma()->getAll() as $form):?>
                                                        <div class="pt-8 pr-16 pb-8 pl-16 f-14 ws-nw ftw-4 r-9999 bc-E5EBFA c-dt"><?=$form->getItem()->getValue()?></div>
                                                    <?endforeach;?>
                                                </div>
                                            <?endif;?>

                                            <?if(!empty($prog->getCnap())):?>
                                                <div class="f-12 ftw-6"><?=$prog->getCnap()->getValue()?></div>
                                            <?endif;?>
                                        </div>
                                    </div>
                                    <div class="f-18 lg::f-24 ftw-5 lh-1 fg-0 pn-r z-1"><?=$prog->getName()?></div>
                                    <div class="d-f jc-e cg-24 xl:cg-32 fg-0 pn-r z-1">
                                        <span class="<?=comp::get("btnDt")?>">Подробнее</span>
                                    </div>

                                    <?if(!empty($naprPicture)):?>
                                        <div class="w-100 h-100 pn-a i-0 bgs-cr bp-c bgr-nr bi-load lazyload" data-bg="<?=$naprPicture['SRC']?>"></div>
                                    <?endif;?>

                                    <div class="w-100 h-100 pn-a i-0 bc-i"></div>
                                </a>
                            </div>
                        <?endforeach;?>
                    </div>
                <?endif;?>
            </div>
        </div>

    </section>
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
                    el.classList.add("bc-w", 'c-dt', 'h:bc-dt', 'h:c-w');
                    el.classList.remove("bc-dt", 'c-w', 'h:bc-w', 'h:c-dt');
                });

                e.detail.tab.classList.remove("bc-w", 'c-dt', 'h:bc-dt', 'h:c-w', 'h:c-dt');
                e.detail.tab.classList.add("bc-dt", 'c-w');
            }

        }, false );
    </script>
<?endif;?>