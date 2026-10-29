<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$user = CurrentUser::get();
$langText = "LangText{$lang}";
$langReview = "LangReview{$lang}";

if(!empty($arParams['elementsId'])):

    $revTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT'], ['ID' => $arParams['titleElementId']]);
    $studentList = Data::getElementsObject($langReview, [
        'select' => ['NAME', "PREVIEW_TEXT", 'DETAIL_TEXT', 'PREVIEW_PICTURE'],
        'filter' => ['ID' => $arParams['elementsId']],
        'limit' => 4
    ]);

    if(!empty($studentList) && !empty($studentList->getIdList())):?>
        <section class="w-100">
            <div class="<?=comp::get('container')?> d-f fd-c rg-32">

                <?if(!empty($revTitle) && !empty($revTitle->getId())):?>
                    <div class="w-100 lg:w-6/12 xl:w-4/12 d-f fd-c rg-16" <?=Data::iblockSectionEditAddId($revTitle)?>>
                        <h2 class="<?=comp::get('h2')?>"><?=$revTitle->getName()?></h2>
                        <?if(!empty($revTitle->getPreviewText())):?>
                            <div class="f-14"><?=$revTitle->getPreviewText()?></div>
                        <?endif?>
                    </div>
                <?endif?>

                <div class="<?=comp::get('row')?> rg-32">
                    <?foreach ($studentList as $student):?>
                        <div class="w-100 lg:w-6/12 <?=comp::get("setcol")?>">
                            <div class="w-100 h-100 p-32 d-f fd-c rg-32 r-12 bc-w of-h" <?=Data::iblockElementEditAddId($student)?>>
                                <div class="w-100 d-f ai-c cg-24">
                                    <div class="bc-w r-100 w-60px h-60px mnw-60px mnh-60px of-h fg-0">
                                        <?if(!empty($student->getPreviewPicture())):?>
                                            <?=Data::getImageId($student->getPreviewPicture(), [
                                                "alt" => strip_tags($student->getName()),
                                                "loading" => "lazy",
                                                "class" => "w-60px h-60px mnw-60px mnh-60px oft-cv op-c r-100",
                                            ])?>
                                        <?endif?>
                                    </div>
                                    <div class="fg-1 d-f fd-c rg-4">
                                        <div class="f-18 ftw-6"><?=$student->getName()?></div>
                                        <?if(!empty($student->getPreviewText())):?>
                                            <div class="f-12"><?=$student->getPreviewText()?></div>
                                        <?endif?>
                                    </div>
                                </div>

                                <?if(!empty($student->getDetailText())):?>
                                    <div class="w-100 f-14"><?=$student->getDetailText()?></div>
                                <?endif?>
                            </div>
                        </div>
                    <?endforeach?>
                </div>
            </div>
        </section>
    <?endif?>
<?endif?>