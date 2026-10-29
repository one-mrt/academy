<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Util::getLang();
$langText = "LangText{$lang}";

$studentTitle = Data::getSectionObject($langText, ['select' => ['NAME'],'filter' => ['ID' => $arParams['sectionId']]]);
$studentList = Data::getElementsObject($langText, [
    'select' => ['NAME', "PREVIEW_TEXT", 'DETAIL_TEXT', 'PREVIEW_PICTURE'],
    'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']],
    'limit' => 6
]);

if(!empty($studentList) && !empty($studentList->getIdList())):?>
    <section class="w-100">
        <div class="<?=comp::get('container')?> d-f fd-c rg-32">

            <?if(!empty($studentTitle) && !empty($studentTitle->getId())):?>
                <div class="w-100 xl:w-8/12" <?=Data::iblockSectionEditAddId($studentTitle)?>>
                    <h2 class="<?=comp::get("h2")?>"><?=$studentTitle->getName()?></h2>
                </div>
            <?endif?>

            <div class="<?=comp::get('row')?> rg-32">
                <?foreach ($studentList as $student):?>
                    <div class="w-100 md:w-6/12 lg:w-4/12 xl:w-4/12 <?=comp::get('setcol')?>">
                        <div class="w-100 h-100 p-32 lg:p-24 xl:p-32 d-f fd-c rg-32 r-12 bc-ffffff80 of-h bw-1 bs-s bcr-c5c5c566" <?=Data::iblockElementEditAddId($student)?>>
                            <div class="w-100 d-f ai-c cg-24">
                                <div class="bc-w r-100 w-60px h-60px mnw-60px mnh-60px of-h fg-0">
                                    <?if(!empty($student->getPreviewPicture())):?>
                                        <?=Data::getImageId($student->getPreviewPicture(), [
                                            "title" => strip_tags($student->getName()),
                                            "alt" => strip_tags($student->getName()),
                                            "loading" => "lazy",
                                            "class" => "w-60px h-60px mnw-60px mnh-60px oft-cv op-c r-100",
                                        ])?>
                                    <?endif?>
                                </div>
                                <div class="fg-1 d-f fd-c rg-4">
                                    <div class="f-18 md:f-16 xl:f-18 ftw-6"><?=$student->getName()?></div>
                                    <?if(!empty($student->getPreviewText())):?>
                                        <div class="f-12"><?=$student->getPreviewText()?></div>
                                    <?endif?>
                                </div>
                            </div>

                            <?if(!empty($student->getDetailText())):?>
                                <div class="w-100 f-14 lg:f-12 xl:f-14"><?=$student->getDetailText()?></div>
                            <?endif?>
                        </div>
                    </div>
                <?endforeach?>
            </div>
        </div>
    </section>
<?endif?>