<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Util::getLang();
$langText = "LangText{$lang}";
$langService = "LangService{$lang}";

$serviceTitle = Data::getElementObject($langText, ['NAME', 'link'], ['ID' => $arParams['elementId']]);
$serviceList = Data::getElementsObject($langService, [
    'select' => ['NAME', 'PREVIEW_TEXT', 'CODE', 'IBLOCK_SECTION_ID', 'sum.ELEMENT', 'svg'],
    'limit' => 6
]);

if(!empty($serviceList) && empty($serviceList->IsEmpty())):?>
    <section class="w-100">
        <div class="<?=comp::get('container-full')?>">
            <div class="w-100 r-24 bc-w pix-32 pby-48 xxl:pix-0 xxl:pby-86">
                <div class="<?=comp::get('container-p')?> d-f fd-c rg-48">

                    <?if(!empty($serviceTitle) && !empty($serviceTitle->getId())):?>
                        <div class="w-100 xl:w-10/12" <?=Data::iblockElementEditAddId($serviceTitle)?>>
                            <h2 class="<?=comp::get("h2")?>"><?=$serviceTitle->getName()?></h2>
                        </div>
                    <?endif?>

                    <div class="w-100">
                        <div class="<?=comp::get('row')?> rg-32">
                            <?foreach ($serviceList as $service):?>
                                <div class="w-100 md:w-6/12 xl:w-4/12 <?=comp::get('setcol')?>">
                                    <a href="<?=Data::getDetailPageUrlApiCode($langService, $service)?>" class="w-100 h-100 d-f fd-c rg-24 jc-s" <?=Data::iblockElementEditAddId($service)?>>

                                        <div class="d-f">
                                            <?if(!empty($service->getSvg())):?>
                                                <?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($service->getSvg()->getValue()))?>
                                            <?else:?>
                                                <svg width="80" height="64" viewBox="0 0 80 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.659969 62.204C1.40393 63.324 2.65588 64 3.99981 64H63.997C65.5969 64 67.0449 63.048 67.6729 61.576L79.6723 33.576C79.9352 32.9677 80.0426 32.3036 79.9848 31.6435C79.9269 30.9833 79.7056 30.348 79.3409 29.7948C78.9762 29.2415 78.4795 28.7878 77.8956 28.4744C77.3118 28.1611 76.6591 27.9981 75.9965 28H71.9966V16C71.9966 11.588 68.4088 8 63.997 8H37.3783L27.1547 0H7.99963C3.58783 0 0 3.588 0 8V60H0.0279986C0.0190834 60.7806 0.238767 61.5467 0.659969 62.204ZM63.997 16V28H15.9993C14.3993 28 12.9514 28.952 12.3234 30.424L7.99963 40.516V16H63.997Z" fill="#21A78E"/></svg>
                                            <?endif?>
                                        </div>

                                        <div class="xl:f-20 ftw-5 ls--1 tt-l ff-ud"><?=$service->getName()?></div>

                                        <?if(!empty($service->getPreviewText())):?>
                                            <div class="f-14 fg-1"><?=$service->getPreviewText()?></div>
                                        <?endif?>

                                        <?if(!empty($service->getSum()) && !empty($service->getSum()->getElement())):?>
                                            <div class="xl:f-18 ftw-6 d-f cg-8 ff-ud">
                                                <div class="tt-l">Стоимость:</div>
                                                <div class="c-21A78E"><?=$service->getSum()->getElement()->getName()?></div>
                                            </div>
                                        <?endif?>
                                    </a>
                                </div>
                            <?endforeach?>
                        </div>
                    </div>

                    <?if(!empty($serviceTitle->getLink())):?>
                        <div class="w-100 d-f jc-c">
                            <a href="<?=$serviceTitle->getLink()->getValue()?>" class="<?=comp::get("btnPurpleLg")?>"><?=$serviceTitle->getLink()->getDescription()?></a>
                        </div>
                    <?endif?>

                </div>
            </div>
        </div>
    </section>
<?endif?>