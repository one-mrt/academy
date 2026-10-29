<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\ORM\Query\Join;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\ORM\Fields\Relations\Reference;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langService = "LangService{$lang}";
$user = CurrentUser::get();

$iblockService = Data::getIblockObject($langService, ['select' => ['DETAIL_PAGE_URL']]);

$serviceList = Data::getElementsObject($langService, [
    'select' => ['NAME', 'CODE', 'IBLOCK_SECTION_ID', 'svg.VALUE', 'SM.NAME'],
    'runtime' => [
        (new Reference('SM', Data::getTableString($langText), Join::on('this.sum.VALUE','ref.ID')))->configureJoinType(Join::TYPE_LEFT)
    ],
    'limit' => 5
]);
?>

<div class="w-100 xl:w-4/12 <?=comp::get('setcol')?> d-f fd-c rg-32">

    <div class="w-100 pn-r of-h p-32 d-f fd-c rg-24 r-24 bc-w">
        <?
        $navLink = Data::getElementObject($langText, ['NAME', 'link'], ['ID' => 241]);
        $navTitle = Data::getElementObject($langText, ['NAME'], ['ID' => 463]);

        if(!empty($navLink) && !empty($navLink->getLink())):?>
            <div class="w-100 d-f" <?=Data::iblockElementEditAddId($navLink)?>>
                <?if(!empty($user->getId())):?>
                    <a href="<?=$navLink->getLink()->getValue()?>" class="<?=comp::get("btnPurpleLg", 'w-100')?>"><?=$navLink->getLink()->getDescription()?></a>
                <?else:?>
                    <button class="lang-form--order-add <?=comp::get("btnPurpleLg", 'w-100')?>"><?=$navLink?->getLink()?->getDescription()?></button>
                <?endif?>
            </div>
        <?endif?>

        <div class="w-100 d-n xl:d-f fd-c rg-32">
            <?if(!empty($navTitle) && !empty($navTitle->getId())):?>
                <div class="w-100 ff-ud f-20 ftw-6 tt-l" <?=Data::iblockElementEditAddId($navTitle)?>><?=$navTitle->getName()?></div>
            <?endif?>

            <?if(!empty($serviceList) && !$serviceList->isEmpty()):?>
                <?foreach($serviceList as $service):?>
                    <a href="<?=Data::getDetailPageUrl($iblockService->getDetailPageUrl(), $service)?>" class="w-100 d-f cg-24 ai-c h:c-py tn-all-3" <?=Data::iblockElementEditAddId($service)?>>
                        <?if(!empty($service->getSvg())):?>
                            <div class="d-f w-50px>svg h-a>svg"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($service->getSvg()->getValue()))?></div>
                        <?endif;?>
                        <div class="fg-1 d-f fd-c rg-8">
                            <div class="f-18 ftw-6 tt-l"><?=$service->getName()?></div>
                            <?if(!empty($service->get('SM'))):?>
                                <div class="ftw-5"><?=$service?->get("SM")?->getName()?></div>
                            <?endif;?>
                        </div>
                    </a>
                <?endforeach?>
            <?endif;?>
        </div>
    </div>

    <?if(!empty($arParams['bannerId'])):
        $workBanner = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE', 'link'], ['ID' => $arParams['bannerId']]);

        if(!empty($workBanner) && !empty($workBanner->getId())):?>
            <div class="w-100 pn-r of-h r-24 bc-FAFAFA d-n xl:d-f" <?=Data::iblockElementEditAddId($workBanner)?>>

                <?if(!empty($workBanner->getPreviewPicture())):?>
                    <?=Data::getImageId($workBanner->getPreviewPicture(), [
                        "alt" => strip_tags($workBanner->getName()),
                        "loading" => "lazy",
                        "class" => "w-100 h-a xl:ar-10x12 r-24 oft-cv op-c",
                    ]);?>
                <?endif;?>

                <div class="w-100 h-100 pn-a t-0 rt-0 b-0 l-0 p-32 d-f fd-c rg-32">
                    <div class="w-100 fg-1 ff-ud tt-l f-18 lg:f-20 ftw-5"><?=$workBanner->getName()?></div>
                    <?if(!empty($workBanner->getLink()) && !empty($workBanner->getLink()->getValue())):?>
                        <div class="w-100 d-f jc-c">
                            <a href="<?=$workBanner->getLink()->getValue()?>" class="<?=comp::get("btnBlueFillLg")?>"><?=$workBanner->getLink()->getDescription()?></a>
                        </div>
                    <?endif?>
                </div>
            </div>
        <?endif;?>
    <?endif;?>

</div>