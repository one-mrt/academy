<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['sectionId'])):
    $edgeTitle = Data::getSectionObject($langText, ['select' => ['NAME', 'DESCRIPTION'], 'filter' => ['ID' => $arParams['sectionId']]]);
    $edgeList = Data::getElementsObject($langText, ['select' => ['NAME', 'PREVIEW_TEXT', 'svg'], 'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);

    if(!empty($edgeList) && !$edgeList->isEmpty()):?>
        <section class="w-100">
            <div class="<?=comp::get('container')?> d-f fd-c rg-32 xl:rg-48">

                <?if(!empty($edgeTitle) && !empty($edgeTitle->getId())):?>
                    <div class="w-100 d-f fd-c rg-16" <?=Data::iblockSectionEditAddId($edgeTitle)?>>
                        <h2 class="<?=comp::get("h2")?>"><?=$edgeTitle->getName()?></h2>
                        <?if(!empty($edgeTitle->getDescription())):?>
                            <div class="f-12 lg:f-14"><?=$edgeTitle->getDescription()?></div>
                        <?endif?>
                    </div>
                <?endif?>

                <div class="w-100">
                    <div class="<?=comp::get("row")?> rg-32">
                        <?foreach($edgeList as $edge):?>
                            <div class="w-100 sm:w-6/12 xl:w-3/12 <?=comp::get("setcol")?> d-f fd-c rg-32">
                                <div class="w-100 h-100 d-f fd-c rg-16" <?=Data::iblockElementEditAddId($edge)?>>
                                    <div class="xl:f-18 ftw-6 d-f ai-c cg-8">
                                        <div class="d-f lh-1">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 0C4.48606 0 0 4.48606 0 10C0 15.5139 4.48606 20 10 20C15.5139 20 20 15.5139 20 10C20 4.48606 15.5139 0 10 0ZM15.2043 6.64856L8.74279 14.3409C8.67191 14.4253 8.58371 14.4935 8.48418 14.5409C8.38464 14.5882 8.27608 14.6136 8.16587 14.6154H8.15288C8.04507 14.6153 7.93846 14.5926 7.83998 14.5487C7.7415 14.5048 7.65335 14.4407 7.58125 14.3606L4.81202 11.2837C4.74169 11.2091 4.68698 11.1212 4.65111 11.0251C4.61524 10.9291 4.59892 10.8268 4.60313 10.7244C4.60733 10.622 4.63197 10.5214 4.67559 10.4286C4.71922 10.3359 4.78094 10.2527 4.85715 10.1842C4.93336 10.1156 5.0225 10.0629 5.11935 10.0293C5.21619 9.99567 5.31878 9.98173 5.42109 9.98832C5.5234 9.9949 5.62336 10.0219 5.7151 10.0676C5.80683 10.1134 5.8885 10.177 5.95529 10.2548L8.13269 12.674L14.0264 5.65913C14.1586 5.50629 14.3457 5.41161 14.5471 5.39555C14.7486 5.3795 14.9483 5.44337 15.103 5.57335C15.2577 5.70333 15.3551 5.88899 15.3741 6.09018C15.393 6.29138 15.3321 6.49196 15.2043 6.64856Z" fill="url(#paint0_linear_146_1118)"/>
                                                <defs>
                                                    <linearGradient id="paint0_linear_146_1118" x1="10" y1="0" x2="10" y2="20" gradientUnits="userSpaceOnUse">
                                                        <stop stop-color="#49BCC9"/>
                                                        <stop offset="1" stop-color="#2F65B8"/>
                                                    </linearGradient>
                                                </defs>
                                            </svg>
                                        </div>
                                        <div class=""><?=$edge->getName()?></div>
                                    </div>
                                    <?if(!empty($edge->getPreviewText())):?>
                                        <div class="f-12 lg:f-14"><?=$edge->getPreviewText()?></div>
                                    <?endif?>
                                </div>
                            </div>
                        <?endforeach?>
                    </div>
                </div>

            </div>
        </section>
    <?endif?>
<?endif;?>