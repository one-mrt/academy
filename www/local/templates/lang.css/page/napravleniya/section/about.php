<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\Type\DateTime;

if(!empty($section) && !empty($section->getUfAbout())):

    $aboutText = Util::getElementObject($textSiteTableString, ['NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'text', 'el'], ['ID' => $section->getUfAbout()]);

    if(!empty($aboutText)):?>
        <section class="w-100">
            <div class="<?=comp::get('container')?>" <?=Util::iblockElementEditAddId($aboutText)?>>
                <div class="<?=comp::get('row')?> rg-32">
                    <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                        <div class="w-100 h-100 p-32 d-f fd-c rg-32 r-24 bc-w">

                            <div class="w-100 d-f fd-c rg-16 fg-1">
                                <h2 class="mb-0 f-24 md:f-32 ftw-5 ff-ot"><?=$aboutText->getName()?></h2>

                                <?if(!empty($aboutText->getPreviewText())):?>
                                    <div class="f-14 xl:f-16"><?=$aboutText->getPreviewText()?></div>
                                <?endif;?>
                            </div>
                            
                            <?if(!empty($aboutText->getText()->getAll())):?>
                                <div class="w-100 fg-0 d-f fd-c rg-16">
                                    <div class="ftw-5"><?=$aboutText->getDetailText()?></div>
                                    <div class="d-f fw-w g-16 lh-1">
                                        <?foreach($aboutText->getText()->getAll() as $vText):?>
                                            <div class="f-12 xl:f-14 pt-8 pr-16 pb-8 pl-16 bc-e5ebfa99 r-999"><?=$vText->getValue()?></div>
                                        <?endforeach;?>
                                    </div>
                                </div>
                            <?endif;?>
                        </div>
                    </div>

                    <?if(!empty($aboutText->getEl()->getAll())):?>
                        <div class="w-100 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                            <div class="<?=comp::get("row")?> rg-32">
                                <?foreach($aboutText->getEl()->getAll() as $aEValue):
                                    $eValue = Util::getElementObject($textSiteTableString, ['NAME', "PREVIEW_TEXT", 'PREVIEW_PICTURE'], ['ID' => $aEValue->getValue()]);

                                    if(!empty($eValue)):
                                        $eModPrev = "";
                                        $aElPrev = "";
                                        if(!empty($eValue->getPreviewPicture()))
                                        {
                                            $aElPrev = 'data-bg="' . \CFile::GetPath($eValue->getPreviewPicture()) . '"';
                                            $eModPrev = "c-w bgs-cr bgr-nr bp-c bi-load lazyload";
                                        }
                                        ?>
                                        <div class="w-100 sm:w-6/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0">
                                            <div class="w-100 h-100 p-24 r-12 d-f fd-c jc-e rg-8 lh-1 bc-e5ebfa99 <?=$eModPrev?>" <?=$aElPrev?> <?=Util::iblockElementEditAddId($eValue)?>>
                                                <div class="f-18 md:f-24 ftw-5 ff-ot pt-0 md:pt-32 lg:pt-64"><?=$eValue->getName()?></div>
                                                <?if(!empty($eValue->getPreviewText())):?>
                                                    <div class="f-14 md:f-16"><?=$eValue->getPreviewText()?></div>
                                                <?endif;?>
                                            </div>
                                        </div>
                                    <?endif;?>
                                <?endforeach;?>
                            </div>
                        </div>
                    <?endif;?>

                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>