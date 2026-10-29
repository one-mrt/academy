<?
use Lang\Region;
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$region = Region::getCurrent(['select' => ['NAME', 'prefix', 'domain']]);
$site = ucfirst($region->getPrefix()->getValue());

$manTableString = "LangMan{$site}";

$sectionsMan = Util::getSectionsObject($manTableString, [
    'select' => ['NAME', 'DEPTH_LEVEL', 'LEFT_MARGIN', 'RIGHT_MARGIN'],
    'filter' => ['<=DEPTH_LEVEL' => 1]
]);
?>

<section class="w-100">
    <div class="<?=comp::get("container")?> d-f jc-c">
        <?$APPLICATION->IncludeComponent(
            "bitrix:breadcrumb",
            "breadcrumb.dark",
            [
                "PATH" => "",
                "SITE_ID" => SITE_ID,
                "START_FROM" => "0"
            ],
            false,
            ['HIDE_ICONS' => 'Y']
        );?>
    </div>
</section>

<?if(!empty($sectionsMan->getIdList())):
    $mans = Util::getElementsObject($manTableString, [
        'select' => ['NAME', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'pos', 'phone', 'email'],
        'filter' => ['IBLOCK_SECTION_ID' => $sectionsMan->getIdList()]
    ]);

    $k = 0;
    ?>

    <section class="w-100">
        <div class="<?=comp::get("container")?> d-f fd-c rg-32">
            <?foreach($sectionsMan as $item):?>
                <?if($k == 0):?>
                    <div class="<?=comp::get('row')?> rg-32" itemprop="rucovodstvo">
                        <?foreach($mans as $iEl):?>
                            <?if($iEl->getIblockSectionId() == $item->getId()):
                                $dataPicture = ['m' => '', 'p' => ''];

                                if($iEl->getPreviewPicture())
                                {
                                    $dataPicture['m'] = "bgs-cr bgr-nr bp-c bi-load lazyload";
                                    $dataPicture['p'] = \CFile::GetPath($iEl->getPreviewPicture());
                                    $dataPicture['p'] = "data-bg=\"{$dataPicture['p']}\"";
                                }
                                ?>

                                <div class="w-100 sm:w-4/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0">
                                    <div class="w-100 h-100 ar-90x100 r-20 bc-w <?=$dataPicture['m']?>" <?=$dataPicture['p']?>></div>
                                </div>

                                <div class="w-100 sm:w-8/12 xl:w-8/12 pr-16 pl-16 fg-0 fs-0">
                                    <div class="w-100 h-100 p-32 d-f fd-c rg-24 r-20 bc-w lh-1" <?=Util::iblockElementEditAddId($iEl)?>>

                                        <h1 class="mb-0 ff-ot f-24 lg:f-32 xl:f-42 ftw-5" <?=Util::iblockSectionEditAddId($item)?>><?=$item->getName()?></h1>

                                        <div class="w-100 d-f fd-c rg-12">
                                            <div class="c-8C8181 ftw-5 f-14 md:f-16 xl:f-18" itemprop="post"><?=$iEl->getPos()->getValue()?></div>
                                            <div class="ftw-5 f-18 xl:f-32 ff-ot" itemprop="fio"><?=$iEl->getName()?></div>
                                        </div>

                                        <?
                                        $email = $iEl->getEmail();
                                        $phone = $iEl->getPhone();
                                        ?>
                                        <?if(!empty($email) || !empty($phone)):?>
                                            <div class="w-100 d-f fd-c rg-12 f-14 xl:f-16">

                                                <?if(!empty($email)):?>
                                                    <div class="w-100 d-f cg-8 xl:f-18" itemprop="e-mail">
                                                        <div class="d-f ai-c ftw-6 cg-4">
                                                            <div class="d-f">
                                                                <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.1194 7.99615C8.3317 8.20385 8.65016 8.20385 8.86246 7.99615L16.8591 0.726923C17.0006 0.45 16.9653 0 16.3991 0L0.582741 0.0346153C0.15814 0.0346153 -0.195694 0.415385 0.122757 0.761538L8.1194 7.99615Z" fill="#1E234C"/><path d="M17 3.56399C17 3.21784 16.5754 3.01014 16.2924 3.25245L10.0295 8.89476C9.60489 9.27553 9.07414 9.48322 8.50801 9.48322C7.94187 9.48322 7.41112 9.27553 6.98652 8.92937L0.759042 3.25245C0.475975 3.01014 0.0513746 3.18322 0.0513746 3.56399C0.0159912 3.46014 0.0159912 11.4217 0.0159912 11.4217C0.0159912 12.1832 0.652892 12.8063 1.43133 12.8063H15.5847C16.3631 12.8063 17 12.1832 17 11.4217C17 9.34476 17 5.12168 17 3.56399Z" fill="#1E234C"/></svg>
                                                            </div>
                                                            <div class="">Email:</div>
                                                        </div>
                                                        <div class="d-f fd-c rg-8 ai-s jc-s" itemprop="e-mail">
                                                            <?foreach($email->getAll() as $value):?>
                                                                <a href="mailto:<?=$value->getValue()?>" class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3"><?=$value->getDescription()?></a>
                                                            <?endforeach;?>
                                                        </div>
                                                    </div>
                                                <?endif;?>

                                                <?if(!empty($phone)):?>
                                                    <div class="w-100 d-f cg-8 xl:f-18">
                                                        <div class="d-f ai-c ftw-6 cg-4">
                                                            <div class="d-f">
                                                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5288 10.1169L9.78748 11.8591L5.13837 7.20779L6.87973 5.46558C7.03248 5.31294 7.15365 5.13167 7.23632 4.93214C7.31899 4.73261 7.36154 4.51874 7.36154 4.30274C7.36154 4.08675 7.31899 3.87288 7.23632 3.67335C7.15365 3.47382 7.03248 3.29255 6.87973 3.13991L3.98842 0.238972C3.83452 0.0859118 3.62633 0 3.40933 0C3.19233 0 2.98414 0.0859118 2.83025 0.238972L0.481049 2.57287C0.291483 2.76256 0.151204 2.9958 0.0724565 3.25221C-0.00629128 3.50862 -0.0211143 3.78042 0.029281 4.04388C0.647391 7.23883 2.2299 10.1673 4.56339 12.4344C6.83159 14.7705 9.76173 16.3539 12.9581 16.9707C13.2214 17.0211 13.4931 17.0063 13.7494 16.9275C14.0057 16.8487 14.2388 16.7084 14.4284 16.5187L16.7611 14.1848C16.9141 14.0308 17 13.8226 17 13.6055C17 13.3883 16.9141 13.1801 16.7611 13.0261L13.8452 10.1169C13.5374 9.81082 13.121 9.639 12.687 9.639C12.253 9.639 11.8366 9.81082 11.5288 10.1169Z" fill="#1E234C"/></svg>
                                                            </div>
                                                            <div class="">Телефон:</div>
                                                        </div>
                                                        <div class="d-f fd-c rg-8 ai-s jc-s">
                                                            <?foreach($phone->getAll() as $value):?>
                                                                <a href="tel:<?=$value->getValue()?>" class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3" itemprop="Telephone"><?=$value->getDescription()?></a>
                                                            <?endforeach;?>
                                                        </div>
                                                    </div>
                                                <?endif;?>

                                            </div>
                                        <?endif;?>
                                    </div>
                                </div>
                            <?endif;?>
                        <?endforeach;?>
                    </div>
                <?else:?>
                    <?if($k == 1):?>
                        <div class="<?=comp::get('row')?> rg-32">
                            
                            <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                                <h2 class="<?=comp::get('titleh2')?>" <?=Util::iblockSectionEditAddId($item)?>><?=$item->getName()?></h2>
                            </div>

                            <?foreach($mans as $iEl):?>
                                <?if($iEl->getIblockSectionId() == $item->getId()):
                                    $dataPicture = ['m' => '', 'p' => ''];

                                    if($iEl->getPreviewPicture())
                                    {
                                        $dataPicture['m'] = "bgs-cr bgr-nr bp-c bi-load lazyload";
                                        $dataPicture['p'] = \CFile::GetPath($iEl->getPreviewPicture());
                                        $dataPicture['p'] = "data-bg=\"{$dataPicture['p']}\"";
                                    }
                                    ?>
                                    <div class="w-100 sm:w-6/12 lg:w-4/12 xl:w-3/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-16" itemprop="rucovodstvoZam" <?=Util::iblockElementEditAddId($iEl)?>>
                                        <div class="w-100 ar-85x100 r-20 bc-w <?=$dataPicture['m']?>" <?=$dataPicture['p']?>></div>
                                        <div class="c-8C8181 ftw-5 f-14 xl:f-16 fg-1" itemprop="post"><?=$iEl->getPos()->getValue()?></div>

                                        <div class="w-100 d-f fd-c rg-16">
                                            <div class="ftw-5 f-18 xl:f-24 ff-ot fg-1" itemprop="fio"><?=$iEl->getName()?></div>
                                        
                                            <?
                                            $email = $iEl->getEmail();
                                            $phone = $iEl->getPhone();
                                            ?>
                                            <?if(!empty($email) || !empty($phone)):?>
                                                <div class="w-100 d-f fd-c rg-8 f-14 xl:f-16">

                                                    <?if(!empty($email)):?>
                                                        <div class="w-100 d-f cg-8 xl:f-16" itemprop="email">
                                                            <div class="d-f ai-c ftw-6 cg-4">
                                                                <div class="d-f">
                                                                    <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.1194 7.99615C8.3317 8.20385 8.65016 8.20385 8.86246 7.99615L16.8591 0.726923C17.0006 0.45 16.9653 0 16.3991 0L0.582741 0.0346153C0.15814 0.0346153 -0.195694 0.415385 0.122757 0.761538L8.1194 7.99615Z" fill="#1E234C"/><path d="M17 3.56399C17 3.21784 16.5754 3.01014 16.2924 3.25245L10.0295 8.89476C9.60489 9.27553 9.07414 9.48322 8.50801 9.48322C7.94187 9.48322 7.41112 9.27553 6.98652 8.92937L0.759042 3.25245C0.475975 3.01014 0.0513746 3.18322 0.0513746 3.56399C0.0159912 3.46014 0.0159912 11.4217 0.0159912 11.4217C0.0159912 12.1832 0.652892 12.8063 1.43133 12.8063H15.5847C16.3631 12.8063 17 12.1832 17 11.4217C17 9.34476 17 5.12168 17 3.56399Z" fill="#1E234C"/></svg>
                                                                </div>
                                                                <div class="">Email:</div>
                                                            </div>
                                                            <div class="d-f fd-c rg-8 ai-s jc-s" itemprop="e-mail">
                                                                <?foreach($email->getAll() as $value):?>
                                                                    <a href="mailto:<?=$value->getValue()?>" class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3"><?=$value->getDescription()?></a>
                                                                <?endforeach;?>
                                                            </div>
                                                        </div>
                                                    <?endif;?>

                                                    <?if(!empty($phone)):?>
                                                        <div class="w-100 d-f cg-8 xl:f-16">
                                                            <div class="d-f ai-c ftw-6 cg-4">
                                                                <div class="d-f">
                                                                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5288 10.1169L9.78748 11.8591L5.13837 7.20779L6.87973 5.46558C7.03248 5.31294 7.15365 5.13167 7.23632 4.93214C7.31899 4.73261 7.36154 4.51874 7.36154 4.30274C7.36154 4.08675 7.31899 3.87288 7.23632 3.67335C7.15365 3.47382 7.03248 3.29255 6.87973 3.13991L3.98842 0.238972C3.83452 0.0859118 3.62633 0 3.40933 0C3.19233 0 2.98414 0.0859118 2.83025 0.238972L0.481049 2.57287C0.291483 2.76256 0.151204 2.9958 0.0724565 3.25221C-0.00629128 3.50862 -0.0211143 3.78042 0.029281 4.04388C0.647391 7.23883 2.2299 10.1673 4.56339 12.4344C6.83159 14.7705 9.76173 16.3539 12.9581 16.9707C13.2214 17.0211 13.4931 17.0063 13.7494 16.9275C14.0057 16.8487 14.2388 16.7084 14.4284 16.5187L16.7611 14.1848C16.9141 14.0308 17 13.8226 17 13.6055C17 13.3883 16.9141 13.1801 16.7611 13.0261L13.8452 10.1169C13.5374 9.81082 13.121 9.639 12.687 9.639C12.253 9.639 11.8366 9.81082 11.5288 10.1169Z" fill="#1E234C"/></svg>
                                                                </div>
                                                                <div class="">Тел.:</div>
                                                            </div>
                                                            <div class="d-f fd-c rg-8 ai-s jc-s">
                                                                <?foreach($phone->getAll() as $value):?>
                                                                    <a href="tel:<?=$value->getValue()?>" class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3" itemprop="telephone"><?=$value->getDescription()?></a>
                                                                <?endforeach;?>
                                                            </div>
                                                        </div>
                                                    <?endif;?>

                                                </div>
                                            <?endif;?>
                                        </div>
                                    </div>
                                <?endif;?>
                            <?endforeach;?>
                        </div>
                    <?else:
                        $sectionFil = Util::getSections($manTableString, [
                            'select' => ['NAME'],
                            'filter' => [
                                '>LEFT_MARGIN' => $item->getLeftMargin(),
                                '<RIGHT_MARGIN' => $item->getRightMargin(),
                                '>DEPTH_LEVEL' => $item->getDepthLevel()
                            ]
                        ]);
                        $sectionListId = [];
                        foreach($sectionFil as $itemF)
                        {
                            $sectionListId[] = $itemF["ID"];
                        }

                        $elementsFil = Util::getElementsObject($manTableString, [
                            'select' => ['NAME', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'SECTIONS', 'PREVIEW_TEXT', 'pos', 'phone', 'email']
                        ]);
                        ?>

                        <div class="<?=comp::get('row')?> rg-32" itemprop="rucovodstvoFil">
                            <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                                <h2 class="<?=comp::get('titleh2')?>" <?=Util::iblockSectionEditAddId($item)?>><?=$item->getName()?></h2>
                            </div>

                            <?foreach($sectionFil as $iFil):?>
                                <?foreach($elementsFil as $eFil):?>
                                    <?

                                    $eSections = $eFil->getSections();
                                    $inArraySections = [];

                                    if(!empty($eSections))
                                    {
                                        foreach($eSections->getAll() as $eSection)
                                        {
                                            $inArraySections[$eSection->getId()] = $eSection->getId();
                                        }
                                    }
                                    else
                                    {
                                        $inArraySections[$eFil->getIblockSectionId()] = $eFil->getIblockSectionId();
                                    }

                                    if(in_array($iFil['ID'], $inArraySections)):
                                        $dataPicture = ['m' => '', 'p' => ''];

                                        if($eFil->getPreviewPicture())
                                        {
                                            $dataPicture['m'] = "bgs-cr bgr-nr bp-c bi-load lazyload";
                                            $dataPicture['p'] = \CFile::GetPath($eFil->getPreviewPicture());
                                            $dataPicture['p'] = "data-bg=\"{$dataPicture['p']}\"";
                                        }
                                        ?>

                                        <div class="w-100 lg:w-6/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                            <div class="w-100 h-100 p-32 d-f fd-c rg-24 r-20 bc-w lh-1" <?=Util::iblockElementEditAddId($eFil)?>>

                                                <div class="f-24 xl:f-32 ftw-5 ff-ot fg-1" itemprop="nameFil"><?=$iFil['NAME']?></div>

                                                <div class="w-100 d-f fd-c sm:fd-r g-24 xl:g-32">

                                                    <div class="w-100 mnw-100px w-100px h-100px xl:w-150px xl:h-150px xl:ar-1x1 r-100% bc-F8F8F8 <?=$dataPicture['m']?>" <?=$dataPicture['p']?>></div>

                                                    <div class="fg-1 d-f fd-c jc-c rg-16 xl:pt-16 xl:pb-16">
                                                        <div class="f-18 xl:f-24 ftw-5 ff-ot" itemprop="fio"><?=$eFil->getName()?></div>

                                                        <?if(!empty($eFil->getPos())):?>
                                                            <div class="c-8C8181 ftw-5 f-14 xl:f-16 fg-1" itemprop="post"><?=$eFil->getPos()->getValue()?></div>
                                                        <?endif;?>

                                                        <?
                                                        $email = $eFil->getEmail();
                                                        $phone = $eFil->getPhone();
                                                        ?>
                                                        <?if(!empty($email) || !empty($phone)):?>
                                                            <div class="w-100 d-f fd-c rg-8 f-14 xl:f-16">

                                                                <?if(!empty($email)):?>
                                                                    <div class="w-100 d-f cg-8 xl:f-16" itemprop="email">
                                                                        <div class="d-f ai-c ftw-6 cg-4">
                                                                            <div class="d-f">
                                                                                <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.1194 7.99615C8.3317 8.20385 8.65016 8.20385 8.86246 7.99615L16.8591 0.726923C17.0006 0.45 16.9653 0 16.3991 0L0.582741 0.0346153C0.15814 0.0346153 -0.195694 0.415385 0.122757 0.761538L8.1194 7.99615Z" fill="#1E234C"/><path d="M17 3.56399C17 3.21784 16.5754 3.01014 16.2924 3.25245L10.0295 8.89476C9.60489 9.27553 9.07414 9.48322 8.50801 9.48322C7.94187 9.48322 7.41112 9.27553 6.98652 8.92937L0.759042 3.25245C0.475975 3.01014 0.0513746 3.18322 0.0513746 3.56399C0.0159912 3.46014 0.0159912 11.4217 0.0159912 11.4217C0.0159912 12.1832 0.652892 12.8063 1.43133 12.8063H15.5847C16.3631 12.8063 17 12.1832 17 11.4217C17 9.34476 17 5.12168 17 3.56399Z" fill="#1E234C"/></svg>
                                                                            </div>
                                                                            <div class="">Email:</div>
                                                                        </div>
                                                                        <div class="d-f fd-c rg-8 ai-s jc-s" itemprop="e-mail">
                                                                            <?foreach($email->getAll() as $value):?>
                                                                                <a href="mailto:<?=$value->getValue()?>" class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3"><?=$value->getDescription()?></a>
                                                                            <?endforeach;?>
                                                                        </div>
                                                                    </div>
                                                                <?endif;?>

                                                                <?if(!empty($phone)):?>
                                                                    <div class="w-100 d-f ai-s cg-8 xl:f-16">
                                                                        <div class="d-f ai-c ftw-6 cg-4">
                                                                            <div class="d-f">
                                                                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5288 10.1169L9.78748 11.8591L5.13837 7.20779L6.87973 5.46558C7.03248 5.31294 7.15365 5.13167 7.23632 4.93214C7.31899 4.73261 7.36154 4.51874 7.36154 4.30274C7.36154 4.08675 7.31899 3.87288 7.23632 3.67335C7.15365 3.47382 7.03248 3.29255 6.87973 3.13991L3.98842 0.238972C3.83452 0.0859118 3.62633 0 3.40933 0C3.19233 0 2.98414 0.0859118 2.83025 0.238972L0.481049 2.57287C0.291483 2.76256 0.151204 2.9958 0.0724565 3.25221C-0.00629128 3.50862 -0.0211143 3.78042 0.029281 4.04388C0.647391 7.23883 2.2299 10.1673 4.56339 12.4344C6.83159 14.7705 9.76173 16.3539 12.9581 16.9707C13.2214 17.0211 13.4931 17.0063 13.7494 16.9275C14.0057 16.8487 14.2388 16.7084 14.4284 16.5187L16.7611 14.1848C16.9141 14.0308 17 13.8226 17 13.6055C17 13.3883 16.9141 13.1801 16.7611 13.0261L13.8452 10.1169C13.5374 9.81082 13.121 9.639 12.687 9.639C12.253 9.639 11.8366 9.81082 11.5288 10.1169Z" fill="#1E234C"/></svg>
                                                                            </div>
                                                                            <div class="">Тел.:</div>
                                                                        </div>
                                                                        <div class="d-f fd-c rg-4 ai-s jc-s" itemprop="telephone">
                                                                            <?foreach($phone->getAll() as $value):?>
                                                                                <a href="tel:<?=$value->getValue()?>" class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3"><?=$value->getDescription()?></a>
                                                                            <?endforeach;?>
                                                                        </div>
                                                                    </div>
                                                                <?endif;?>

                                                            </div>
                                                        <?endif;?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?endif;?>
                                <?endforeach;?>
                            <?endforeach;?>
                        </div>
                    <?endif;?>
                <?endif;?>
            <?
            $k++;
            endforeach?>
        </div>
    </section>

<?endif;?>