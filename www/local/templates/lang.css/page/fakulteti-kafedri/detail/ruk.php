<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($element->getRuk()->getAll())):
    $rukList = [];

    foreach($element->getRuk()->getAll() as $kItem)
    {
        $rukList[] = $kItem->getValue();
    }

    $fakRukList = Util::getElementsObject($rukFakTableString, [
        'select' => ['NAME', 'PREVIEW_PICTURE', 'phone', 'email', 'map', 'pos'],
        'filter' => ['ID' => $rukList]
    ]);

    if(!empty($fakRukList->getIdList())):
        $title = Util::getElementObject($textTableString, ['NAME'], ['ID' => 204211]);
        $countRuk = count($fakRukList->getIdList());
        $rukMainId = 0;
        $rukIndex = 0;
        ?>

        <section class="w-100 d-f fd-c rg-32">

            <?if(!empty($title)):?>
                <div class="<?=comp::get('container')?>" <?=Util::iblockSectionEditAddId($title)?>>
                    <h2 class="<?=comp::get("titleh2")?>"><?=$title->getName()?></h2>
                </div>
            <?endif;?>

            <div class="<?=comp::get("container")?> d-f fd-c rg-32">
                <?foreach($fakRukList as $fakRukItem):
                    $rukMainId = $fakRukItem->getId();
                    ?>
                    <div class="w-100 p-32 xl:p-48 bc-w r-20" <?=Util::iblockElementEditAddId($fakRukItem)?>>
                        <div class="<?=comp::get("row")?> rg-16">

                            <div class="<?=comp::get("col")?> sm:w-4/12 lg:w-20">
                                <?
                                $rukMod = "";
                                $rukImage = "";
                                if(!empty($fakRukItem->getPreviewPicture())):
                                    $rukImage = 'data-bg="' . \CFile::GetPath($fakRukItem->getPreviewPicture()) . '"';
                                    $rukMod = "bgs-cr bp-c bgr-nr bi-load lazyload";
                                    ?>
                                <?endif;?>
                                <div class="w-100 ar-10x11 bc-e5ebfa99 r-12 <?=$rukMod?>" <?=$rukImage?>></div>
                            </div>

                            <div class="<?=comp::get("col")?> sm:w-8/12 lg:w-80 d-f fd-c jc-c">
                                <div class="<?=comp::get("row")?> rg-16">
                                    <div class="<?=comp::get("col")?> lg:w-60 d-f fd-c jc-c rg-16 lg:rg-24">

                                        <?if(!empty($fakRukItem->getPos())):?>
                                            <div class="f-18 c-8C8181"><?=$fakRukItem->getPos()->getValue()?></div>
                                        <?endif;?>

                                        <div class="f-24 xl:f-32 ftw-5 ff-ot"><?=$fakRukItem->getName()?></div>
                                    </div>

                                    <div class="<?=comp::get("col")?> lg:w-40 d-f fd-c jc-c rg-16 lg:f-14 xl:f-16">

                                        <?if(!empty($fakRukItem->getEmail()->getAll())):?>
                                            <div class="d-f ai-c cg-8">
                                                <div class="d-f fg-0">
                                                    <svg width="15" height="12" viewBox="0 0 14 11" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6.61931 6.66346C6.79238 6.83654 7.052 6.83654 7.22508 6.66346L13.7443 0.605769C13.8597 0.375 13.8308 0 13.3693 0L0.475077 0.0288461C0.128923 0.0288461 -0.159538 0.346154 0.100077 0.634615L6.61931 6.66346Z"/>
                                                        <path d="M13.8598 2.97115C13.8598 2.68269 13.5137 2.50961 13.2829 2.71154L8.17713 7.41346C7.83098 7.73077 7.39829 7.90384 6.93675 7.90384C6.47521 7.90384 6.04252 7.73077 5.69636 7.4423L0.619441 2.71154C0.388672 2.50961 0.042518 2.65384 0.042518 2.97115C0.0136718 2.88461 0.0136719 9.51923 0.0136719 9.51923C0.0136719 10.1538 0.532903 10.6731 1.16752 10.6731H12.706C13.3406 10.6731 13.8598 10.1538 13.8598 9.51923C13.8598 7.78846 13.8598 4.26923 13.8598 2.97115Z"/>
                                                    </svg>
                                                </div>

                                                <div class="d-f fw-w g-8 fg-1">
                                                    <div class="ftw-6">Email:</div>
                                                    <?foreach($fakRukItem->getEmail()->getAll() as $email):?>
                                                        <a href="mailto:<?=$email->getValue()?>" class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3 ws-nw"><?=$email->getDescription()?></a>
                                                    <?endforeach;?>
                                                </div>

                                            </div>
                                        <?endif;?>

                                        <?if(!empty($fakRukItem->getPhone()->getAll())):?>
                                            <div class="d-f ai-c cg-8">
                                                <div class="d-f fg-0">
                                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.94192 8.72014L8.44025 10.2218L4.43108 6.21264L5.93275 4.71098C6.06447 4.57941 6.16896 4.42317 6.24025 4.25119C6.31154 4.07921 6.34824 3.89486 6.34824 3.70869C6.34824 3.52251 6.31154 3.33817 6.24025 3.16619C6.16896 2.99421 6.06447 2.83797 5.93275 2.70639L3.43942 0.205978C3.3067 0.0740504 3.12717 0 2.94004 0C2.75291 0 2.57338 0.0740504 2.44067 0.205978L0.414834 2.21764C0.251361 2.38115 0.130391 2.58218 0.062483 2.80319C-0.0054253 3.0242 -0.0182079 3.25847 0.0252505 3.48556C0.558279 6.2394 1.92296 8.76356 3.93525 10.7176C5.89123 12.7312 8.41804 14.096 11.1744 14.6276C11.4015 14.6711 11.6358 14.6583 11.8568 14.5904C12.0778 14.5225 12.2788 14.4015 12.4423 14.2381L14.454 12.2264C14.5859 12.0937 14.66 11.9142 14.66 11.727C14.66 11.5399 14.5859 11.3604 14.454 11.2276L11.9394 8.72014C11.674 8.45629 11.3149 8.30819 10.9407 8.30819C10.5664 8.30819 10.2073 8.45629 9.94192 8.72014Z"/>
                                                    </svg>
                                                </div>

                                                <div class="d-f fw-w g-8 fg-1">
                                                    <div class="ftw-6">Телефон:</div>
                                                    <?foreach($fakRukItem->getPhone()->getAll() as $phone):?>
                                                        <a href="mailto:<?=$phone->getValue()?>" class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3 ws-nw"><?=$phone->getDescription()?></a>
                                                    <?endforeach;?>
                                                </div>

                                            </div>
                                        <?endif;?>

                                        <?if(!empty($fakRukItem->getMap())):?>
                                            <div class="d-f ai-c cg-8">
                                                <div class="d-f fg-0">
                                                    <svg width="14" height="17" viewBox="0 0 14 17" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6.8 17C8.30102 17 13.6 10.2623 13.6 6.61111C13.6 2.9599 10.5555 0 6.8 0C3.04446 0 0 2.9599 0 6.61111C0 10.2623 5.29898 17 6.8 17ZM6.8 9.44445C8.40952 9.44445 9.71427 8.17592 9.71427 6.61111C9.71427 5.04631 8.40952 3.77778 6.8 3.77778C5.19048 3.77778 3.88573 5.04631 3.88573 6.61111C3.88573 8.17592 5.19048 9.44445 6.8 9.44445Z"/>
                                                    </svg>
                                                </div>

                                                <div class="d-f fw-w g-8 fg-1">
                                                    <div class="ftw-6"><?=$fakRukItem->getMap()->getValue()?></div>
                                                </div>

                                            </div>
                                        <?endif;?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?break;?>
                <?endforeach;?>

                <?if($countRuk > 1):?>
                    <div class="w-100 p-32 xl:p-48 bc-EDF0F9 r-20 d-f fd-c rg-32">
                        <?foreach($fakRukList as $fakRukItem):?>
                            <?if($rukMainId != $fakRukItem->getId()):
                                $rukIndex++;
                                ?>
                                <?if($rukIndex > 1):?>
                                    <div class="w-100 h-1px bc-DADADA"></div>
                                <?endif;?>

                                <div class="<?=comp::get("row")?> rg-16" <?=Util::iblockElementEditAddId($fakRukItem)?>>
                                    <div class="w-100 md:w-5/12 lg:w-40 xl:w-45 pr-16 pl-16 d-f fd-c jc-c rg-24 fg-0 fs-0">
                                        <div class="f-24 xl:f-28 ftw-5 ff-ot lh-1"><?=$fakRukItem->getName()?></div>
                                    </div>

                                    <div class="<?=comp::get("col")?> md:w-7/12 lg:w-60 xl:w-55">
                                        <div class="<?=comp::get("row")?> rg-16">
                                            <div class="w-100 lg:w-40 pr-16 pl-16 d-f fd-c jc-c rg-24 fg-0 fs-0">
                                                <?if(!empty($fakRukItem->getPos())):?>
                                                    <div class="f-14 xl:f-18 c-8C8181"><?=$fakRukItem->getPos()->getValue()?></div>
                                                <?endif;?>
                                            </div>

                                            <div class="w-100 lg:w-60 pr-16 pl-16 d-f fd-c jc-c rg-16 fg-0 fs-0 f-14 xl:f-16">

                                                <?if(!empty($fakRukItem->getEmail()->getAll())):?>
                                                    <div class="d-f ai-c cg-8">
                                                        <div class="d-f fg-0">
                                                            <svg width="15" height="12" viewBox="0 0 14 11" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M6.61931 6.66346C6.79238 6.83654 7.052 6.83654 7.22508 6.66346L13.7443 0.605769C13.8597 0.375 13.8308 0 13.3693 0L0.475077 0.0288461C0.128923 0.0288461 -0.159538 0.346154 0.100077 0.634615L6.61931 6.66346Z"/>
                                                                <path d="M13.8598 2.97115C13.8598 2.68269 13.5137 2.50961 13.2829 2.71154L8.17713 7.41346C7.83098 7.73077 7.39829 7.90384 6.93675 7.90384C6.47521 7.90384 6.04252 7.73077 5.69636 7.4423L0.619441 2.71154C0.388672 2.50961 0.042518 2.65384 0.042518 2.97115C0.0136718 2.88461 0.0136719 9.51923 0.0136719 9.51923C0.0136719 10.1538 0.532903 10.6731 1.16752 10.6731H12.706C13.3406 10.6731 13.8598 10.1538 13.8598 9.51923C13.8598 7.78846 13.8598 4.26923 13.8598 2.97115Z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="d-f fw-w g-8 fg-1">
                                                            <div class="ftw-6">Email:</div>
                                                            <?foreach($fakRukItem->getEmail()->getAll() as $email):?>
                                                                <a href="mailto:<?=$email->getValue()?>" class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3 ws-nw"><?=$email->getDescription()?></a>
                                                            <?endforeach;?>
                                                        </div>

                                                    </div>
                                                <?endif;?>

                                                <?if(!empty($fakRukItem->getPhone()->getAll())):?>
                                                    <div class="d-f ai-c cg-8">
                                                        <div class="d-f fg-0">
                                                            <svg width="15" height="15" viewBox="0 0 15 15" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.94192 8.72014L8.44025 10.2218L4.43108 6.21264L5.93275 4.71098C6.06447 4.57941 6.16896 4.42317 6.24025 4.25119C6.31154 4.07921 6.34824 3.89486 6.34824 3.70869C6.34824 3.52251 6.31154 3.33817 6.24025 3.16619C6.16896 2.99421 6.06447 2.83797 5.93275 2.70639L3.43942 0.205978C3.3067 0.0740504 3.12717 0 2.94004 0C2.75291 0 2.57338 0.0740504 2.44067 0.205978L0.414834 2.21764C0.251361 2.38115 0.130391 2.58218 0.062483 2.80319C-0.0054253 3.0242 -0.0182079 3.25847 0.0252505 3.48556C0.558279 6.2394 1.92296 8.76356 3.93525 10.7176C5.89123 12.7312 8.41804 14.096 11.1744 14.6276C11.4015 14.6711 11.6358 14.6583 11.8568 14.5904C12.0778 14.5225 12.2788 14.4015 12.4423 14.2381L14.454 12.2264C14.5859 12.0937 14.66 11.9142 14.66 11.727C14.66 11.5399 14.5859 11.3604 14.454 11.2276L11.9394 8.72014C11.674 8.45629 11.3149 8.30819 10.9407 8.30819C10.5664 8.30819 10.2073 8.45629 9.94192 8.72014Z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="d-f fw-w g-8 fg-1">
                                                            <div class="ftw-6">Телефон:</div>
                                                            <?foreach($fakRukItem->getPhone()->getAll() as $phone):?>
                                                                <a href="mailto:<?=$phone->getValue()?>" class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3 ws-nw"><?=$phone->getDescription()?></a>
                                                            <?endforeach;?>
                                                        </div>

                                                    </div>
                                                <?endif;?>

                                                <?/*if(!empty($fakRukItem->getMap())):?>
                                                    <div class="d-f ai-c cg-8">
                                                        <div class="d-f fg-0">
                                                            <svg width="14" height="17" viewBox="0 0 14 17" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.8 17C8.30102 17 13.6 10.2623 13.6 6.61111C13.6 2.9599 10.5555 0 6.8 0C3.04446 0 0 2.9599 0 6.61111C0 10.2623 5.29898 17 6.8 17ZM6.8 9.44445C8.40952 9.44445 9.71427 8.17592 9.71427 6.61111C9.71427 5.04631 8.40952 3.77778 6.8 3.77778C5.19048 3.77778 3.88573 5.04631 3.88573 6.61111C3.88573 8.17592 5.19048 9.44445 6.8 9.44445Z"/>
                                                            </svg>
                                                        </div>

                                                        <div class="d-f fw-w g-8 fg-1">
                                                            <div class="ftw-6"><?=$fakRukItem->getMap()->getValue()?></div>
                                                        </div>

                                                    </div>
                                                <?endif;*/?>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?endif;?>
                        <?endforeach;?>
                    </div>
                <?endif;?>
            </div>

        </section>
    <?endif;?>
<?endif;?>