<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Util::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['sectionId'])):
    $faqTitle = Data::getSectionObject($langText, ['select' => ['NAME'],'filter' => ['ID' => $arParams['sectionId']]]);
    $faqList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT', 'svg'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);

    if(!empty($faqList) && !empty($faqList->getIdList())):?>
        <section class="w-100">
            <div class="<?=comp::get('container')?> d-f fd-c rg-32">

                <?if(!empty($faqTitle) && !empty($faqTitle->getId())):?>
                    <div class="w-100 xl:w-10/12" <?=Data::iblockSectionEditAddId($faqTitle)?>>
                        <h2 class="<?=comp::get("h2")?>"><?=$faqTitle->getName()?></h2>
                    </div>
                <?endif?>

                <?if(!empty($faqList) && !empty($faqList->getIdList())):?>
                    <div class="<?=comp::get('row')?> rg-16 xl:rg-32">
                        <?foreach ($faqList as $item):?>
                            <div class="w-100 xl:w-6/12 <?=comp::get('setcol')?>">
                                <div class="lang-faq--item w-100 d-f fd-c r-12 bc-w of-h bw-1 bs-s bcr-c5c5c566" <?=Util::iblockElementEditAddId($item)?>>
                                    <div class="lang-faq--title d-f ai-c cg-16 pt-16 pr-24 pb-16 pl-24 r-12 cr-p h:bc-dt h:c-w tn-all-3">
                                        <div class="w-100 fg-1 ff-ud lg:f-20 ftw-5 ta-l tt-l ls--1"><?=$item->getName()?></div>
                                        <div class="fg-0">
                                            <div class="lang-faq--icon d-f r-100% bi-pyg h:bc-py c-w p-12 tn-all-3">
                                                <svg width="20" height="20" viewBox="0 0 16 16" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><line y1="8" x2="16" y2="8"/><line x1="8" y1="16" x2="8"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lang-faq--description w-100 of-a d-n pt-16 pr-24 pb-16 pl-24 f-14">
                                        <?if(!empty($item->getPreviewText())):?>
                                            <?=$item->getPreviewText()?>
                                        <?endif;?>
                                    </div>
                                </div>
                            </div>
                        <?endforeach?>
                    </div>

                    <script>
                        document.querySelectorAll('.lang-faq--item').forEach((item) => {
                            item.querySelector('.lang-faq--title').addEventListener('click', (e) => {
                                item.querySelector('.lang-faq--icon').classList.toggle('tm-r45');
                                item.querySelector('.lang-faq--description').classList.toggle('d-n');
                            })
                        });
                    </script>
                <?endif?>
            </div>
        </section>
    <?endif?>
<?endif?>