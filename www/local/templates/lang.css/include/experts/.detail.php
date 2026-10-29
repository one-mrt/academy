<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Bitrix\Main\UserTable;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

Loader::includeModule('sale');

$parser = new CTextParser();
$parser->allow = array(
    "HTML" => "N",
    "ANCHOR" => "Y",  // ссылки
    "BIU" => "Y",    // жирный, курсив, подчеркнутый
    // "IMG" => "Y",   // изображения
    // "QUOTE" => "Y", // цитаты
    // "CODE" => "Y",   // блоки кода
    // "FONT" => "Y",   // шрифты
    "LIST" => "Y",   // списки
    // "SMILES" => "Y", // смайлики
    // "NL2BR" => "Y", // перенос строк
    // "VIDEO" => "Y", // видео
    // "TABLE" => "Y", // таблицы
    "CUT_ANCHOR" => "N", // запрет на вставку закрывающего тега ссылки
    // "ALIGN" => "Y"  // выравнивание
);
$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;

$lang = Util::getLang();
$langText = "LangText{$lang}";
$langSpec = "LangLkSpec{$lang}";
$langReview = "LangReview";
$currentUser = CurrentUser::get();

$user = UserTable::getList([
    'select' => ['NAME', 'LOGIN', 'LAST_NAME', 'PERSONAL_PHOTO', 'IS_ONLINE', 'DATE_REGISTER', 'PERSONAL_NOTES', 'UF_SPEC', 'UF_WORKTYPE'],
    'filter' => ['ID' => $arParams['param']['userId']],
    'cache' => Data::getCache(3600)
])->fetchObject();
?>

<script>LG.ExpertData = {userId: <?=$user->getId()?>}</script>
<div class="w-100 d-f fd-c rg-32 pn-r">

    <div class="w-100 d-f fd-c rg-24 xl:rg-32 fg-1">

        <?include_once("{$templatePath}/.block/.lk/.userInfo.php")?>

        <?if($currentUser->getId() != $user->getId()):?>
            <div class="w-100 d-f cg-24 xl:cg-32">
                <button class="<?=(!empty($currentUser->getId())) ? 'lang-user--messenger' : 'lang-auth--button'?> <?=comp::get("btnLkLg")?>">Написать</button>
                <button class="<?=(!empty($currentUser->getId())) ? 'lang-order--private' : 'lang-auth--button'?> <?=comp::get("btnLkLg")?> h:r-30 tn-all-3">Заказать</button>
            </div>

            <?if(!empty($currentUser->getId())):?>
                <script>
                    LG.MessageUser = class {
                        btn
                        user

                        constructor() {
                            this.user = LG.ExpertData.userId
                            this.btn = document.querySelector('.lang-user--messenger')
                            this.btn?.addEventListener('click', this.open.bind(this))
                        }

                        open() {
                            BXIM.openMessenger(this.user)
                        }
                    }
                    new LG.MessageUser()
                </script>
                <style class="c-008000 to-e bcr-008000 c-8c94a8"></style>
            <?endif;?>
        <?endif;?>
    </div>

    <?
    $aboutTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'link'], ['ID' => 259]);

    if(!empty($aboutTitle)):?>
        <div class="w-100 d-f fd-c rg-24 xl:rg-32 bc-w r-12 p-24" <?=Data::iblockElementEditAddId($aboutTitle)?>>
            <h2 class="mb-0 f-20 <?=comp::get("fontUd")?>"><?=$aboutTitle->getName()?></h2>

            <?if(!empty($user->getPersonalNotes())):?>
                <div class="lc:mb-0|* h:c-py|a tdt-1|a tuo-4|a tdc-py|a tdl-u|a h:tdl-u|a f-14"><?=$parser->convertText($user->getPersonalNotes())?></div>
            <?else:?>
                <div class="w-100 d-f fd-c rg-24">
                    <div class="tt-l"><?=$aboutTitle->getPreviewText()?></div>
                </div>
            <?endif;?>
        </div>
    <?endif;?>

    <?
    $specTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'link'], ['ID' => 258]);

    if(!empty($specTitle)):?>
        <div class="w-100 d-f fd-c rg-24 xl:rg-32 bc-w r-12 p-24" <?=Data::iblockElementEditAddId($specTitle)?>>
            <h2 class="mb-0 f-20 <?=comp::get("fontUd")?>"><?=$specTitle->getName()?></h2>                    
            <div class="w-100 d-f fd-c rg-24">
                <?if(!empty($user->getUfSpec())):?>
                    <div class="w-100">
                        <div class="<?=comp::get("row")?> rg-24">
                            <?
                            $specList = Data::getElementsObject($langSpec, [
                                'select' => ['NAME', 'IBLOCK_SECTION_ID'],
                                'filter' => ['ID' => $user->getUfSpec()]
                            ]);
                            $specCatList = Data::getSectionsObject($langSpec, [
                                'select' => ['NAME'],
                                'filter' => ['ID' => $specList->getIblockSectionIdList()]
                            ]);
                            foreach($specCatList as $cat):?>
                                <div class="w-100 xl:w-4/12 <?=comp::get("setcol")?> d-f fd-c rg-24 lh-1">
                                    <div class="w-100 ftw-6" <?=Data::iblockSectionEditAddId($cat)?>><?=$cat->getName()?></div>
                                    <div class="w-100 d-f fd-c rg-4 f-14">
                                        <?foreach($specList as $spec):?>
                                            <?if($spec->getIblockSectionId() == $cat->getId()):?>
                                                <div class="c-595959" <?=Data::iblockElementEditAddId($spec)?>><?=$spec->getName()?></div>
                                            <?endif;?>
                                        <?endforeach;?>
                                    </div>
                                </div>
                            <?endforeach?>
                        </div>
                    </div>
                <?else:?>
                    <div class="tt-l"><?=$specTitle->getPreviewText()?></div>
                <?endif;?>
            </div>
        </div>
    <?endif;?>


    <?
    $reviewTitle = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'link'], ['ID' => 396]);
    ?>
    <div class="lang--review w-100 d-f fd-c rg-24 xl:rg-32">

        <div class="w-100 d-f jc-b ai-c">
            <div class="fg-1">
                <?if(!empty($reviewTitle)):?>
                    <h2 class="mb-0 f-20 <?=comp::get("fontUd")?>" <?=Data::iblockElementEditAddId($reviewTitle)?>><?=$reviewTitle->getName()?></h2>
                <?endif;?>
            </div>
            <?/*if(!empty($currentUser->getId())):?>
                <div class="fg-0">
                    <button class="<?=comp::get("btnGreenSm")?>"><?=$reviewTitle->getLink()->getDescription()?></button>
                </div>
            <?endif;*/?>
        </div>

        <div class="lang-review--items w-100 d-f fd-c rg-32">
            <div class="<?=comp::get("row")?> rg-32">
                <?$APPLICATION->IncludeComponent(
                    "lang:get.review", "",
                    ['user' => $user->getId()],
                    false,["HIDE_ICONS"=>"Y"]
                );?>
            </div>
        </div>

    </div>
    
    <script><?=file_get_contents("{$templatePath}/js/main/util.js")?></script>
    <script><?=file_get_contents("{$templatePath}/js/experts/review.js")?></script>
    <script>new LG.Review()</script>
    

    <?include_once("{$templatePath}/.block/.lk/.statWork.php")?>
</div>

<?
$APPLICATION->SetTitle(strip_tags($userFullName));
$APPLICATION->SetPageProperty("title", strip_tags($userFullName));
$APPLICATION->SetPageProperty("description", strip_tags($user->getPersonalNotes()));
// $APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
$APPLICATION->AddChainItem($userFullName, "/experts/{$user->getLogin()}/");
?>