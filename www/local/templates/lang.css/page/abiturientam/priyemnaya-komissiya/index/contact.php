<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($section) && !empty($section->getUfForm())):
    $title = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $section->getUfForm()]]);

    if(!empty($title)):
        $contactList = Util::getElementsObject($textSiteTableString, [
            'select' => ['NAME', 'PREVIEW_TEXT', 'svg', 'text'],
            'filter' => ['IBLOCK_SECTION_ID' => $title->getId()]
        ]);
        if(!empty($contactList)):?>
            <section class="w-100">
                <div class="<?=comp::get("container")?>">
                    <div class="w-100 p-32 xl:p-64 d-f fd-c rg-32 xl:rg-48 bc-EDF0F9 r-48">
                        
                        <h2 class="mb-0 ftw-6 f-24 lg:f-32 xl:f-40 ff-ot ta-c lh-1" <?=Util::iblockSectionEditAddId($title)?>><?=$title->getName()?></h2>

                        <div class="<?=comp::get("row")?> rg-32 jc-c">
                            <div class="w-100 xl:w-8/12 pr-16 pl-16 fg-0 fs-0">
                                <div class="<?=comp::get("row")?> rg-32">
                                    <?foreach ($contactList as $contact):?>
                                        <div class="w-100 md:w-6/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                            <div class="d-f cg-16" <?=Util::iblockElementEditAddId($contact)?>>
                                                <?if(!empty($contact->getSvg())):?>
                                                    <div class="d-f"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($contact->getSvg()->getValue()))?></div>
                                                <?endif;?>
                                                <div class="d-f fd-c rg-16">
                                                    <div class="ftw-5 f-18 xl:f-24 ff-ot"><?=$contact->getName()?></div>

                                                    <?if(!empty($contact->getPreviewText())):?>
                                                        <div class="d-f fd-c f-14 lg:f-16"><?=$contact->getPreviewText()?></div>
                                                    <?endif;?>

                                                    <?if(!empty($contact->getText()->getAll())):?>
                                                        <div class="d-f fd-c rg-8 f-14 lg:f-16">
                                                            <?foreach($contact->getText()->getAll() as $cont):?>
                                                                <?if(strpos($cont->getValue(), 'tel:') !== false || strpos($cont->getValue(), 'mailto:') !== false):?>
                                                                    <a href="<?=$cont->getValue()?>" class="tdl-u tdt-1 tuo-2 h:c-py tn-all-3"><?=$cont->getDescription()?></a>
                                                                <?else:?>
                                                                    <div class=""><?=$cont->getValue()?> <?=$cont->getDescription()?></div>
                                                                <?endif;?>
                                                            <?endforeach;?>
                                                        </div>
                                                    <?endif;?>
                                                </div>
                                            </div>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?endif;?>
    <?endif;?>

<?endif;?>