<?
use Lang\Region;
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<section class="w-100">
    <div class="<?=comp::get("container")?> d-f fd-c ai-c rg-32">

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

        <div class="w-100 d-f fd-c rg-32 xl:rg-48">

            <div class="w-100">
                <h1 class="<?=comp::get("titleh1")?>"><?=$APPLICATION->ShowTitle(false)?></h1>
            </div>

            <div class="<?=comp::get("row")?> rg-32">

                <?
                $region = Region::getCurrent(['select' => ['NAME', 'PREVIEW_TEXT', 'adr', 'work', 'phone', 'email', 'req', 'con']]);
                ?>

                <div class="<?=comp::get("col")?> lg:w-6/12">
                    <div class="w-100 h-100 p-32 xl:p-48 d-f fd-c rg-24 r-24 bc-w bw-1 bs-s bcr-E5E5E5">

                        <h2 class="mb-0 f-24 ftw-5 ff-ot lh-1">Адрес</h2>

                        <div class="w-100 d-f fd-c rg-16 f-14 xl:f-16">
                            <?if(!empty($region->getAdr())):?>
                                <div class=""><?=$region->getAdr()->getValue()?></div>
                            <?endif;?>


                            <div class="d-f fd-c">
                                <?if(!empty($region->getWork()->getAll())):?>
                                    <div class="d-f cg-4">
                                        <div class="ftw-6">Режим работы:</div>
                                        <div class="d-f fw-w g-8">
                                            <?foreach($region->getWork()->getAll() as $work):?>
                                                <div class=""><?=$work->getValue()?> <?=$work->getDescription()?></div>
                                            <?endforeach;?>
                                        </div>
                                    </div>
                                <?endif;?>

                                <?if(!empty($region->getPhone()->getAll())):?>
                                    <div class="d-f cg-4">
                                        <div class="ftw-6">Тел.:</div>
                                        <div class="">
                                            <?foreach($region->getPhone()->getAll() as $phone):?>
                                                <a class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3" href="tel:<?=$phone->getValue()?>"><?=$phone->getDescription()?></a>
                                                <?//break;?>
                                            <?endforeach;?>
                                        </div>
                                    </div>
                                <?endif;?>

                                <?if(!empty($region->getEmail()->getAll())):?>
                                    <div class="d-f cg-4">
                                        <div class="ftw-6">E-mail:</div>
                                        <div class="">
                                            <?foreach($region->getEmail()->getAll() as $email):?>
                                                <a class="ftw-5 f-14 h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3" href="mailto:<?=$email->getValue()?>"><?=$email->getDescription()?></a>
                                            <?endforeach;?>
                                        </div>
                                    </div>
                                <?endif;?>
                            </div>

                            <?if(!empty($region->getPreviewText())):?>
                                <div class="d-f fd-c rg-8">
                                    <div class="ftw-6">Проезд:</div>
                                    <div class=""><?=$region->getPreviewText()?></div>
                                </div>
                            <?endif;?>
                        </div>
                    </div>
                </div>

                <?if(!empty($region->getReq())):
                    $secReq = Util::getSectionObject($textTableString, [
                        'select' => ['NAME'],
                        'filter' => ['ID' => $region->getReq()->getValue()]
                    ]);

                    if(!empty($secReq)):
                        $reqEl = Util::getElementsObject($textTableString, [
                            'select' => ['NAME', 'PREVIEW_TEXT', 'text'],
                            'filter' => ['IBLOCK_SECTION_ID' => $secReq->getId()],
                        ]);

                        if(!empty($reqEl)):?>
                            <div class="<?=comp::get("col")?> lg:w-6/12">
                                <div class="w-100 h-100 p-32 xl:p-48 d-f fd-c rg-24 r-24 bc-w bw-1 bs-s bcr-E5E5E5">

                                    <h2 class="mb-0 f-24 ftw-5 ff-ot lh-1" <?=Util::iblockSectionEditAddId($secReq)?>><?=$secReq->getName()?></h2>

                                    <div class="w-100 d-f fd-c rg-16 f-14 xl:f-16">
                                        <?foreach($reqEl as $req):?>
                                            <div class="d-f fd-c rg-4" <?=Util::iblockElementEditAddId($req)?>>
                                                <div class="">
                                                    <span class="ftw-6 mr-8"><?=$req->getName()?></span>
                                                    <?if(!empty($req->getPreviewText())):?>
                                                        <span class=""><?=$req->getPreviewText()?></span>
                                                    <?endif;?>
                                                </div>

                                                <?if(!empty($req->getText()->getAll())):?>
                                                    <div class="d-f fd-c rg-4 lh-1">
                                                        <?foreach($req->getText()->getAll() as $text):?>
                                                            <div class="">
                                                                <span class="mr-4"><?=$text->getValue()?></span>
                                                                <span><?=$text->getDescription()?></span>
                                                            </div>
                                                        <?endforeach;?>
                                                    </div>
                                                <?endif;?>

                                            </div>
                                        <?endforeach;?>
                                    </div>
                                </div>
                            </div>
                        <?endif;?>
                    <?endif;?>
                <?endif;?>

                <?if(!empty($region->getCon())):
                    $conReq = Util::getSectionObject($textTableString, [
                        'select' => ['NAME'],
                        'filter' => ['ID' => $region->getCon()->getValue()]
                    ]);

                    if(!empty($conReq)):
                        $conEl = Util::getElementsObject($textTableString, [
                            'select' => ['NAME', 'PREVIEW_TEXT', 'text'],
                            'filter' => ['IBLOCK_SECTION_ID' => $conReq->getId()],
                        ]);

                        if(!empty($conEl)):?>
                            <div class="<?=comp::get("col")?>">
                                <div class="w-100 h-100 p-32 xl:p-48 d-f fd-c rg-24 r-24 bc-w bw-1 bs-s bcr-E5E5E5">

                                    <h2 class="mb-0 f-24 ftw-5 ff-ot lh-1" <?=Util::iblockSectionEditAddId($conReq)?>><?=$conReq->getName()?></h2>

                                    <div class="w-100 f-14 xl:f-16">
                                        <div class="<?=comp::get("row")?> rg-32">
                                            <?
                                            $conIndex = 0;
                                            foreach($conEl as $con):
                                                $conIndex++;
                                                ?>
                                                <div class="w-100 lg:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                                    <div class="w-100 h-100 d-f fd-c rg-16 <?=($conIndex <= 2) ? "lg:pb-32 lg:bbw-1 lg:bbs-s" : ""?> bbc-E5E5E5" <?=Util::iblockElementEditAddId($con)?>>
                                                        <div class="w-100 d-f fd-c ai-s rg-4">
                                                            <div class="ftw-6"><?=$con->getName()?></div>

                                                            <?if(!empty($con->getText()->getAll())):?>
                                                                <div class="d-f fd-c">
                                                                    <?foreach($con->getText()->getAll() as $phone):?>
                                                                        <a class="h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3" href="<?=$phone->getValue()?>"><?=$phone->getDescription()?></a>
                                                                    <?endforeach;?>
                                                                </div>
                                                            <?endif;?>
                                                        </div>

                                                        <?if(!empty($con->getPreviewText())):?>
                                                            <div class="d-f fd-c ai-s"><?=$con->getPreviewText()?></div>
                                                        <?endif;?>
                                                    </div>
                                                </div>
                                            <?endforeach;?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?endif;?>
                    <?endif;?>
                <?endif;?>

            </div>

        </div>
    </div>
</section>