<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\Type\DateTime;

if(!empty($section) && !empty($section->getUfStep())):
    $title = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $section->getUfStep()]]);

    if(!empty($title)):
        $stepList = Util::getElementsObject($textSiteTableString, [
            'select' => ['NAME', 'SORT', 'PREVIEW_TEXT', 'file', 'text', 'link'],
            'filter' => ["IBLOCK_SECTION_ID" => $section->getUfStep()]
        ]);
        $idStep = rand();
        ?>

        <section class="w-100">
            <div class="<?=comp::get("container")?>">
                <div class="<?=comp::get("row")?> rg-32 xl:rg-48">

                    <?if(!empty($title)):?>
                        <div class="w-100 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-16 ai-c ta-c">
                            <h2 class="lg:w-100 mb-0 f-24 md:f-32 xl:f-55 ta-c ftw-5 lh-1" <?=Util::iblockSectionEditAddId($title)?>><?=$title->getName()?></h2>
                        </div>
                    <?endif;?>

                    <div class="lang-step--container w-100 md:mxh-1000px d-f fd-c md:fw-w">
                        <?
                        $iCount = count($stepList->getIdList());
                        $index = 0;

                        foreach($stepList as $sValue):
                            $index++;
                            ?>
                            <div class="lang-step--item w-100 md:w-6/12 xl:w-6/12 pr-16 pl-16 <?=($iCount != $index) ? "pb-32" : ""?> fg-0 fs-0 md:o-<?=($index % 2 == 0) ? 2 : 1?>" <?=Util::iblockElementEditAddId($sValue)?>>
                                <div class="w-100 bc-w p-32 r-24 d-f fd-c ai-s rg-32 <?=($iCount != $index) ? "" : "bc-e5ebfa99"?>">

                                    <div class="w-40px h-40px d-f ai-c jc-c f-18 xl:f-24 ftw-5 ff-ot lh-1 bc-dt c-w r-8"><?=$sValue->getSort()?></div>

                                    <div class="w-100 d-f fd-c rg-16">
                                        <div class="f-18 xl:f-24 ftw-5 ff-ot"><?=$sValue->getName()?></div>

                                        <?if(!empty($sValue->getPreviewText())):?>
                                            <div class="f-14 sm:f-12 lg:f-16"><?=$sValue->getPreviewText()?></div>
                                        <?endif;?>

                                        <?if(!empty($sValue->getText()->getAll()) || !empty($sValue->getFile()->getAll())):?>
                                            <ul class="w-100 pl-32 d-f fd-c rg-8 ai-s mb-0 f-14 sm:f-12 lg:f-16">

                                                <?foreach($sValue->getText()->getAll() as $slValue):?>
                                                    <li>
                                                        <a href="<?=$slValue->getValue()?>" class="tdl-u tdt-1 tuo-2 h:c-py tn-all-3" target="_blank"><?=$slValue->getDescription()?></a>
                                                    </li>
                                                <?endforeach;?>

                                                <?foreach($sValue->getFile()->getAll() as $sdValue):
                                                    $sFile = CFile::GetFileArray($sdValue->getValue());
                                                    ?>
                                                    <li>
                                                        <a href="<?=$sFile['SRC']?>" class="tdl-u tdt-1 tuo-2 h:c-py tn-all-3" target="_blank"><?=$sFile['DESCRIPTION']?></a>
                                                    </li>
                                                <?endforeach;?>
                                            </ul>
                                        <?endif;?>
                                    </div>

                                    <?if(!empty($sValue->getLink())):?>
                                        <div class="w-100 d-f jc-s">
                                            <a href="<?=$sValue->getLink()->getValue()?>" class="<?=($iCount != $index) ? comp::get("btnPy") : comp::get("btnDt")?>"><?=$sValue->getLink()->getDescription()?></a>
                                        </div>
                                    <?endif;?>
                                </div>
                            </div>
                        <?endforeach;?>

                        <div class="w-0 fb-100% o-1 d-n md:d-f"></div>
                    </div>
                    <script>
                        step<?=$idStep?> = () => {
                            if(window.innerWidth >= 768)
                            {
                                let step = document.querySelectorAll(".lang-step--item");
                                let stepCon = document.querySelector('.lang-step--container');
                                let index = 0;
                                let hei = 0;
                                step.forEach((e, c) => {
                                    if(c+1 <= Math.round(step.length/2))
                                    {
                                        hei += e.offsetHeight+32;
                                    }
                                })
                                stepCon.style.maxHeight = hei + 'px';
                            }
                        }
                        step<?=$idStep?>();
                    </script>
                </div>
            </div>
        </section>

    <?endif;?>

<?endif;?>