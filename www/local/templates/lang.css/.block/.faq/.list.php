<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['sectionId'])):
    $faqTitle = Data::getSectionObject($langText, ['select' => ['NAME', 'DESCRIPTION', 'PICTURE'],'filter' => ['ID' => $arParams['sectionId']]]);
    $faqList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT', 'svg'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);
    $jsonList = [
        "@context" => "https://schema.org",
        "@type" => "FAQPage",
        "mainEntity" => []
    ];

    if(!empty($faqList) && !$faqList->isEmpty()):?>
        <section class="w-100">
            <div class="<?=comp::get('container-full')?>">
                <div class="w-100 pix-32 pby-32 xl:pby-48 r-24 bw-1 bs-s bcr-c5c5c566 of-h pn-r rele of-h">

                    <?if(!empty($faqTitle) && !empty($faqTitle->getPicture())):?>
                        <?=Data::getImageId($faqTitle->getPicture(), [
                            "alt" => strip_tags($faqTitle->getName()),
                            "fetchpriority" => "high",
                            "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c"
                        ])?>
                    <?endif?>

                    <div class="<?=comp::get('container-p')?> d-f fd-c rg-32 pn-r">

                        <div class="w-100">
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

                        <?if(!empty($faqTitle) && !empty($faqTitle->getId())):?>
                            <div class="w-100 xl:w-10/12" <?=Data::iblockSectionEditAddId($faqTitle)?>>
                                <h1 class="<?=comp::get("h2")?>"><?=$faqTitle->getName()?></h1>
                            </div>
                        <?endif?>

                        <?if(!empty($faqList) && !empty($faqList->getIdList())):?>
                            <div class="<?=comp::get('row')?> rg-16 lg:rg-32">
                                <?foreach ($faqList as $item):
                                    $jsonList['mainEntity'][] = [
                                        "@type" => "Question",
                                        "name" => strip_tags($item->getName()),
                                        "acceptedAnswer" => [
                                            "@type" => "Answer",
                                            "text" => strip_tags($item->getPreviewText())
                                        ]
                                    ];
                                    ?>
                                    <div class="w-100 lg:w-6/12 <?=comp::get("setcol")?>">
                                        <div class="lang-faq--item w-100 d-f fd-c r-12 bc-w of-h bw-1 bs-s bcr-c5c5c566" <?=Data::iblockElementEditAddId($item)?>>
                                            <div class="lang-faq--title d-f ai-c cg-16 pt-16 pr-24 pb-16 pl-24 r-12 cr-p h:bc-dt h:c-w tn-all-3">
                                                <div class="w-100 fg-1 md:f-20 lg:f-18 xxl:f-20 ff-ud ftw-5 ta-l tt-l ls--1"><?=$item->getName()?></div>
                                                <div class="fg-0">
                                                    <div class="lang-faq--icon d-f r-100% bi-pyg h:bc-py c-w p-8 lg:p-12 tn-all-3">
                                                        <svg width="20" height="20" viewBox="0 0 16 16" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><line y1="8" x2="16" y2="8"/><line x1="8" y1="16" x2="8"/></svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="lang-faq--description w-100 of-a d-n pt-16 pr-24 pb-16 pl-24 f-12 lg:f-14">
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
                            <script type="application/ld+json"><?=json_encode($jsonList)?></script>
                        <?endif?>
                    </div>

                </div>
            </div>
        </section>
    <?endif?>
<?endif?>