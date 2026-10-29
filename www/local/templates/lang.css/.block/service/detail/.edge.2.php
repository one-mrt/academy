<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$lang = Data::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['elementId'])):
    $edgeTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'link', 'sec'], ['ID' => $arParams['elementId']]);
    $user = CurrentUser::get();

    if(!empty($edgeTitle) && !empty($edgeTitle->getSec())):
        $edgeList = Data::getElementsObject($langText, [
            'select' => ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE'],
            'filter' => ['IBLOCK_SECTION_ID' => $edgeTitle->getSec()->getValue()],
            'limit' => 5
        ]);
        $modCard = [
            1 => ['asp' => 'ar-10x9 md:ar-100x106 lg:ar-100x106'],
            2 => ['asp' => 'ar-16x9 md:ar-10x9 lg:ar-100x56'],
            3 => ['asp' => 'ar-16x9 md:ar-10x7 lg:ar-100x56'],
            4 => ['asp' => 'ar-10x9 md:ar-100x126 lg:ar-100x106'],
            5 => ['asp' => 'ar-1x1 lg:ar-i h-100']
        ];
        $modIndex = 1;
        ?>
        <section class="w-100">
            <div class="<?=comp::get("container")?> d-f fd-c rg-32 xl:rg-48">

                <?if(!empty($edgeTitle) && !empty($edgeTitle->getId())):?>
                    <div class="w-100 md:w-10/12 lg:w-8/12 d-f fd-c rg-16" <?=Data::iblockElementEditAddId($edgeTitle)?>>
                        <h2 class="<?=comp::get("h2")?>"><?=$edgeTitle->getName()?></h2>
                        <?if(!empty($edgeTitle->getPreviewText())):?>
                            <div class="f-12 xl:f-14"><?=$edgeTitle->getPreviewText()?></div>
                        <?endif;?>
                    </div>
                <?endif?>

                <?if(!empty($edgeList) && !$edgeList->IsEmpty()):?>
                    <div class="w-100">
                        <div class="<?=comp::get("row", 'md:mi--8 xl:mi--16', '')?> rg-32 md:rg-16 xl:rg-32">
                            <div class="w-100 md:w-4/12 xl:w-4/12 <?=comp::get("setcol", 'md:pix-8 xl:pix-16', '')?> d-f fd-c rg-32 md:rg-16 xl:rg-32">
                                <?foreach($edgeList as $edge):?>
                                    <div class="w-100 <?=$modCard[$modIndex]['asp']?> pn-r of-h p-32 md:p-24 xl:p-32 d-f ai-e bc-w c-w r-12" <?=Data::iblockElementEditAddId($edge)?>>

                                        <?if(!empty($edge->getPreviewPicture())):?>
                                            <?=Data::getImageId($edge->getPreviewPicture(), [
                                                "title" => strip_tags($edge->getName()),
                                                "alt" => strip_tags($edge->getName()),
                                                "loading" => "lazy",
                                                "class" => "pn-a t-0 rt-0 b-0 l-0 w-100 h-100 oft-cv op-c r-12",
                                            ])?>
                                        <?endif?>

                                        <div class="w-100 d-f fd-c rg-16 pn-r">
                                            <div class="f-20 md:f-14 lg:f-18 xl:f-20 ff-ud tt-l ftw-5 lh-1"><?=$edge->getName()?></div>
                                            <?if(!empty($edge->getPreviewText())):?>
                                                <div class="f-14 md:f-12 xl:f-14"><?=$edge->getPreviewText()?></div>
                                            <?endif;?>
                                        </div>

                                    </div>

                                    <?if($modIndex % 2 == 0):?>
                                        </div>
                                        <div class="w-100 md:w-4/12 xl:w-4/12 <?=comp::get("setcol", 'md:pix-8 xl:pix-16', '')?> d-f fd-c rg-32 md:rg-16 xl:rg-32">
                                    <?endif?>

                                    <?$modIndex++?>
                                <?endforeach?>
                            </div>
                        </div>
                    </div>
                <?endif?>

                <?if(!empty($edgeTitle->getLink())):?>
                    <div class="w-100 d-f jc-c">
                        <?if(!empty($user->getId())):?>
                            <a href="<?=$edgeTitle->getLink()->getValue()?>" class="<?=comp::get("btnPurpleLg")?>"><?=$edgeTitle->getLink()->getDescription()?></a>
                        <?else:?>
                            <?if($arParams['btnAction'] == 'orderAdd' || empty($arParams['btnAction'])):?>
                                <button class="lang-form--order-add <?=comp::get("btnPurpleLg")?>"><?=$edgeTitle?->getLink()?->getDescription()?></button>
                            <?elseif($arParams['btnAction'] == 'reg'):?>
                                <button class="lang-form--reg <?=comp::get("btnPurpleLg")?>"><?=$edgeTitle?->getLink()?->getDescription()?></button>
                            <?endif;?>
                        <?endif?>
                    </div>
                <?endif?>
            </div>
        </section>
    <?endif?>
<?endif?>