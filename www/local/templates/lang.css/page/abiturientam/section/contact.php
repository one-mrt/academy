<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($section->getUfForm())):

    $secContact = Util::getSectionObject($textSiteTableString, ['select' => ['NAME'], 'filter' => ['ID' => $section->getUfForm()]]);

    if(!empty($secContact)):
        $contactList = Util::getElementsObject($textSiteTableString, [
            'select' => ['NAME', 'PREVIEW_TEXT', 'svg', 'text'],
            'filter' => ['IBLOCK_SECTION_ID' => $secContact->getId()]
        ]);

        if(!empty($contactList)):?>
            <section class="w-100">
                <div class="<?=comp::get("container")?>">
                    <div class="w-100 p-32 xl:p-64 bc-EDF0F9 r-48">
                        <div class="<?=comp::get("row")?> rg-32 jc-c">

                            <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                                <h2 class="mb-0 ftw-6 xl:f-40 ff-ot ta-c lh-1" <?=Util::iblockSectionEditAddId($secContact)?>><?=$secContact->getName()?></h2>
                            </div>

                            <div class="w-100 xl:w-8/12 pr-16 pl-16 fg-0 fs-0">
                                <div class="<?=comp::get("row")?> rg-32">
                                    
                                    <?foreach($contactList as $contact):?>
                                        <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                            <div class="d-f cg-16" <?=Util::iblockElementEditAddId($contact)?>>
                                                <?if(!empty($contact->getSvg())):
                                                    $svg = \CFile::GetPath($contact->getSvg()->getValue());
                                                    ?>
                                                    <div class="d-f"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$svg}")?></div>
                                                <?endif;?>
                                                <div class="d-f fd-c rg-16">
                                                    <div class="ftw-5 xl:f-24 ff-ot"><?=$contact->getName()?></div>
                                                    <?if(!empty($contact->getPreviewText())):?>
                                                        <div class=""><?=$contact->getPreviewText()?></div>
                                                    <?endif;?>

                                                    <?if(!empty($contact->getText()->getAll())):?>
                                                        <div class="d-f fd-c rg-8">
                                                            <?foreach($contact->getText()->getAll() as $text):?>
                                                                <?if(strpos($text->getValue(), 'tel:') !== false || strpos($text->getValue(), 'mailto:') !== false):?>
                                                                    <a href="<?=$text->getValue()?>" class="tdl-u tdt-1 tuo-2 h:c-py tn-all-3"><?=$text->getDescription()?></a>
                                                                <?else:?>
                                                                    <div class=""><?=$text->getValue()?> <?=$text->getDescription()?></div>
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