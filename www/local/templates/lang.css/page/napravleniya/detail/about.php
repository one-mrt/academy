<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

// $element['u'];
// $element['ab'];
// $element['time'];

if(!empty($element->getAbout()) || !empty($element->getVi())):?>
    <section class="w-100">
        <div class="<?=comp::get("container")?> d-f fd-c rg-32">

            <?if(!empty($element->getAbout())):

                $about = Util::getElementObject($textSiteTableString,
                        ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_TEXT'],
                        ['ID' => $element->getAbout()->getValue()]);

                if(!empty($about)):?>

                    <div class="<?=comp::get("row")?> rg-32" <?=Util::iblockElementEditAddId($about)?>>

                        <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                            <h2 class="mb-0 f-24 md:f-32 xl:f-42 ftw-5 lh-1 ta-c"><?=$about->getName()?></h2>
                        </div>

                        <div class="w-100 lg:w-7/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-32">

                            <div class="w-100 d-f fd-c rg-16">
                                <?if(!empty($about->getPreviewText())):?>
                                    <div class="xl:f-24 ftw-5"><?=$about->getPreviewText()?></div>
                                <?endif;?>

                                <?if(!empty($about->getDetailText())):?>
                                    <div class="f-14 xl:f-16"><?=$about->getDetailText()?></div>
                                <?endif;?>
                            </div>

                            <?
                            $forma = $element->getForma()->getAll();

                            if(empty($element->getVi()) && (!empty($element->getTime()) || !empty($forma))):?>
                                <div class="w-100">
                                    <div class="<?=comp::get("row")?> rg-32">

                                        <?if(!empty($element->getTime())):?>
                                            <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                                <div class="w-100 h-100 p-32 r-12 bc-e5ebfa99">
                                                    <div class="ftw-5 xl:f-18 ff-ot">Срок обучения</div>
                                                    <div class=""><?=$element->getTime()->getValue()?></div>
                                                </div>
                                            </div>
                                        <?endif;?>

                                        <?if(!empty($element->getForma()->getAll())):?>
                                            <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                                <div class="w-100 h-100 p-32 r-12 bc-e5ebfa99">
                                                    <div class="ftw-5 xl:f-18 ff-ot">Форма обучения</div>
                                                    <div class="">
                                                        <?
                                                        $ind = 0;
                                                        foreach($element->getForma()->getAll() as $value):?>
                                                            <?
                                                            $ind++;
                                                            if($ind > 1):?>, <?endif;?>
                                                            <?=$value->getItem()->getValue();?>
                                                        <?endforeach;?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?endif;?>
                                    </div>
                                </div>
                            <?endif;?>

                        </div>

                        <?if(!empty($about->getPreviewPicture())):?>
                            <div class="w-100 lg:w-5/12 pr-16 pl-16 fg-0 fs-0">
                                <div class="w-100 ar-10x6 xl:h-100 r-24 bc-w bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=\CFile::GetPath($about->getPreviewPicture())?>"></div>
                            </div>
                        <?endif;?>
                    </div>
                <?endif;?>

            <?endif;?>

            <?if(!empty($element->getVi())):
                $forma = $element->getForma()->getAll();
                $viSec = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $element->getVi()->getValue()]]);
                $viElm = Util::getElementsObject($textSiteTableString, ['select' => ['NAME', 'PREVIEW_TEXT', 'el'], 'filter' => ['IBLOCK_SECTION_ID' => $element->getVi()->getValue()]]);
                ?>
                
                <div class="<?=comp::get("row")?> rg-32">

                    <div class="w-100 lg:w-7/12 pr-16 pl-16 fg-0 fs-0">
                        <div class="w-100 h-100 p-32 d-f fd-c rg-16 r-12 bc-e5ebfa99">

                            <div class="ftw-5 f-18 ff-ot" <?=Util::iblockSectionEditAddId($viSec)?>><?=$viSec->getName()?></div>
                            
                            <div class="<?=comp::get("row")?> rg-16">
                                <?foreach($viElm as $viElmItem):?>
                                    <?if(empty($viElmItem->getEl()->getAll())):?>
                                        <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0" <?=Util::iblockElementEditAddId($viElmItem)?>>
                                            <div class="p-16 d-f fd-c rg-8 bc-w r-12 f-14 xl:f-16">
                                                <div class="w-100 ftw-5"><?=$viElmItem->getName()?></div>
                                                <?if(!empty($viElmItem->getPreviewText())):?>
                                                    <div class=""><?=$viElmItem->getPreviewText()?></div>
                                                <?endif;?>
                                            </div>
                                        </div>
                                    <?else:?>
                                        <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-16">

                                            <div class="ftw-6"><?=$viElmItem->getName()?></div>

                                            <div class="p-16 d-f fd-c rg-8 bc-w r-12 f-14 xl:f-16">

                                                <?if(!empty($viElmItem->getPreviewText())):?>
                                                    <div class=""><?=$viElmItem->getPreviewText()?></div>
                                                <?endif;?>

                                                <?foreach($viElmItem->getEl()->getAll() as $viValueEl):
                                                    $els = Util::getElementObject($textSiteTableString, ['NAME', 'PREVIEW_TEXT', 'text'], ['ID' => $viValueEl->getValue()]);
                                                    if(!empty($els)):?>

                                                        <div class="ftw-6" <?=Util::iblockElementEditAddId($els)?>><?=$els->getName()?></div>

                                                        <?if(!empty($els->getPreviewText())):?>
                                                            <div class=""><?=$els->getPreviewText()?></div>
                                                        <?endif;?>

                                                        <?if(!empty($els->getText()->getAll())):?>
                                                            <div class="w-100">
                                                                <ul class="mb-0">
                                                                    <?foreach($els->getText()->getAll() as $text):?>
                                                                        <li><?=$text->getValue()?></li>
                                                                    <?endforeach;?>
                                                                </ul>
                                                            </div>
                                                        <?endif;?>
                                                    <?endif;?>
                                                <?endforeach;?>
                                            </div>
                                        </div>
                                    <?endif;?>
                                <?endforeach;?>
                            </div>
                        </div>
                    </div>

                    <?if(!empty($element->getTime()) || !empty($forma)):?>
                        <div class="w-100 lg:w-5/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-32">

                            <?if(!empty($element->getTime())):?>
                                <div class="w-100">
                                    <div class="w-100 h-100 p-32 r-12 bc-e5ebfa99">
                                        <div class="ftw-5 xl:f-18 ff-ot">Срок обучения</div>
                                        <div class=""><?=$element->getTime()->getValue()?></div>
                                    </div>
                                </div>
                            <?endif;?>

                            <?if(!empty($element->getForma()->getAll())):?>
                                <div class="w-100">
                                    <div class="w-100 h-100 p-32 r-12 bc-e5ebfa99">
                                        <div class="ftw-5 xl:f-18 ff-ot">Форма обучения</div>
                                        <div class="">
                                            <?
                                            $ind = 0;
                                            foreach($element->getForma()->getAll() as $value):?>
                                                <?
                                                $ind++;
                                                if($ind > 1):?>, <?endif;?>
                                                <?=$value->getItem()->getValue();?>
                                            <?endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            <?endif;?>

                        </div>
                    <?endif;?>

                </div>
            <?endif;?>

            <?if(!empty($element->getLv())):?>
                <div class="w-100 d-f jc-c">
                    <a class="<?=comp::get('btnPy')?>" href="<?=$element->getLv()->getValue()?>" target="_blank" rel="nofollow"><?=$element->getLv()->getDescription()?></a>
                </div>
            <?endif;?>
        </div>
    </section>

<?endif;?>