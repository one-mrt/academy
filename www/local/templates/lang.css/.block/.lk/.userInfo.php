<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// .userInfo.php
use Lang\Css\Component as comp;
use Bitrix\Main\ORM\Query\Query;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\ORM\Fields\ExpressionField;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langReview = "LangReview";
$docRoot = $_SERVER['DOCUMENT_ROOT'];

$likeTitle = Data::getSectionObject($langText, ['select' => ['NAME'], 'filter' => ['ID' => 64]]);
$likeList = Data::getElementsObject($langText, ['select' => ['NAME', 'svg'],'filter' => ['IBLOCK_SECTION_ID' => 64]]);
$ratingList = Data::getElementsObject($langText, ['select'=>['NAME','svg'], 'filter' => ['IBLOCK_SECTION_ID' => 65]]);


// $reviewRating = Data::getTableString($langReview)::query()
//     ->setSelect(['251', '252'])
//     ->addGroup('ur.IBLOCK_GENERIC_VALUE')
//     ->where('ACTIVE', 'Y')
//     ->where('ur.VALUE', $user->getId())
//     ->registerRuntimeField(new ExpressionField('251', 'AVG(%s)', ['score.IBLOCK_GENERIC_VALUE']))
//     ->registerRuntimeField(new ExpressionField('252', 'COUNT(%s)', ['ID']))
//     // ->setCacheTtl(3600)
//     // ->cacheJoins(true)
//     ->exec()
//     ->fetch();

$reviewRating[251] = $user->getUfScore() ?? 0;
$reviewRating[252] = $user->getUfCount() ?? 0;

$userFullName = "";

if(!empty($user->getName())) {
    $userFullName .= $user->getName();

    if(!empty($user->getLastName())) {
        $userFullName .= " {$user->getLastName()}";
    }
} elseif(!empty($user->getLastName())) {
    $userFullName .= $user->getLastName();
} else {
    $userFullName .= "Эксперт - {$user->getId()}";
}
?>

<div class="w-100 d-f ai-c fw-w lg:fw-n cg-16 xxl:cg-32 rg-32 bc-w r-24 p-24">

    <div class="w-100 lg:w-a d-f ai-c cg-16 xxl:cg-24 rg-32 fg-1">

        <div class="d-f pn-r">
            <?if(!empty($user->getPersonalPhoto())):?>
                <?=Data::getImageId($user->getPersonalPhoto(), [
                    "alt" => $userFullName,
                    "fetchpriority" => "high",
                    "class" => "w-90px h-90px xxl:w-120px xxl:h-120px r-100 oft-cv op-c",
                ])?>
            <?else:?>
                <div class="w-90px h-90px xxl:w-120px xxl:h-120px bc-w r-100 bw-1 bs-s bcr-c5c5c566" title="<?=$userFullName?>"></div>
            <?endif?>

            <div class="d-f pn-a rt-6px b-0 sm:rt-8px sm:b-16px c-<?=(!empty($user->getIsOnline())) ? "05c936" : "c90536"?>" title="<?=(!empty($user->getIsOnline())) ? "Онлайн" : "Офлайн"?>">
                <svg width="12" height="12" viewBox="0 0 8 8" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><circle cx="4" cy="4" r="4"/></svg>
            </div>
        </div>

        <div class="d-f fd-c rg-16 xxl:rg-24 fg-1">

            <div class="d-f fd-c rg-8 fg-1">
                <div class="d-f fd-c ff-ud xl:f-20 xxl:f-22 ftw-5 bi-st bgc-t tf-t"><?=$user->getName()?> <?=$user->getLastName()?></div>
                <div class="ftw-5 c-636363 f-12 sm:f-14">Зарегистрирован <?=FormatDate('j F Y', $user->getDateRegister())?></div>
            </div>

            <?/*if(!empty($likeList) && !empty($likeList->getIdList())):?>
                <div class="w-100 d-f ai-c cg-16 lh-1 fg-0">
                    <div class="f-14 ws-nw d-n sm:d-b" <?=Data::iblockSectionEditAddId($likeTitle)?>><?=$likeTitle->getName()?></div>
                    <div class="d-f cg-16 xxl:cg-16">
                        <?foreach($likeList as $like):?>
                            <div class="d-f ai-c cg-8" title="<?=$like->getName()?>" <?=Data::iblockElementEditAddId($like)?>>
                                <?if(!empty($like->getSvg())):
                                    $svgFile = $docRoot . \CFile::GetPath($like->getSvg()->getValue());
                                    ?>
                                    <div class="d-f w-18px>svg h-a>svg md:w-20px>svg"><?=file_get_contents($svgFile)?></div>
                                <?endif;?>
                                <div class="xxl:f-20 ftw-6 bi-st bgc-t tf-t lh-1">500</div>
                            </div>
                        <?endforeach?>
                    </div>
                </div>
            <?endif;*/?>
        </div>
    </div>

    <?if(!empty($ratingList) && !empty($ratingList->getIdList())):?>
        <div class="d-f cg-16 xxl:cg-24">
            <?foreach($ratingList as $rating):?>
                <div class="p-16 d-f fd-c rg-16 r-12 bc-w bw-1 bs-s bcr-c5c5c566" <?=Data::iblockElementEditAddId($rating)?>>
                    <?if(!empty($rating->getSvg())):
                        $svgFile = $docRoot . \CFile::GetPath($rating->getSvg()->getValue());
                        ?>
                        <div class="d-f"><?=file_get_contents($svgFile)?></div>
                    <?endif;?>

                    <div class="d-f fd-c rg-8">
                        <div class="f-18 ftw-6 lh-1"><?=$reviewRating[$rating->getId()] ?? 0?></div>
                        <div class="f-12 tt-l ws-nw"><?=$rating->getName()?></div>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    <?endif;?>
</div>