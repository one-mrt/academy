<?
use Lang\Main\Iblock\Util as Data;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util;
use Bitrix\Main\UserTable;
use Bitrix\Main\Engine\CurrentUser;

$navLink = Data::getElementObject($langText, ['NAME', 'link'], ['ID' => 241]);
?>

<div class="w-100 xl:w-4/12 pr-16 pl-16 fg-0 fs-0 d-n xl:d-f fd-c">
    <div class="w-100 h-100 pn-r of-h p-32 d-f fd-c rg-32 bc-w r-12">

        <div class="w-100 d-f ai-c cg-24">
            <?if(!empty($user->getPersonalPhoto())):?>
                <?=Data::getImageId($user->getPersonalPhoto(), [
                    "alt" => $user->getName(),
                    "fetchpriority" => "high",
                    "class" => "xl:w-90px xl:h-90px r-100 oft-cv op-c",
                ])?>
            <?else :?>
                <div class="xl:w-90px xl:h-90px bc-w bw-1 bs-s bcr-636363 r-100"></div>
            <?endif?>
            <div class="d-f fd-c rg-8 fg-1">
                <div class="d-f fd-c ff-ud ftw-6 xl:f-18">
                    <?if(!empty($user->getName()) || !empty($user->getLastName())):?>
                        <?if(!empty($user->getName())):?>
                            <div class=""><?=$user->getName()?></div>
                        <?endif;?>
                        <?if(!empty($user->getLastName())):?>
                            <div class=""><?=$user->getLastName()?></div>
                        <?endif?>
                    <?else:?>
                        <div class=""><?=$user->getLogin()?></div>
                    <?endif;?>
                </div>
                <div class="c-636363 f-14 lh-1 ftw-5"><?=(!empty($user->getIsOnline())) ? "Сейчас онлайн" : "Сейчас офлайн"?></div>
            </div>
        </div>

        <?if(!empty($navLink) && !empty($navLink->getLink())):?>
            <div class="w-100 d-f" <?=Data::iblockElementEditAddId($navLink)?>>
                <a href="<?=$navLink->getLink()->getValue()?>" class="w-100 <?=comp::get("btnPurpleLg")?>"><?=$navLink->getLink()->getDescription()?></a>
            </div>
        <?endif?>

        <div class="w-100 d-f fd-c rg-24">
            <?$APPLICATION->IncludeComponent(
                "bitrix:menu", 
                "lang.lk",
                array(
                    "ROOT_MENU_TYPE" => "lk",
                    "MAX_LEVEL" => "1",
                    "USE_EXT" => "N",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => [],
                    "CHILD_MENU_TYPE" => "",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N"
                ),
                false,
                ['HIDE_ICONS' => 'Y']
            );?>

            <div class="w-100">
                <a class="d-f ai-c cg-16 f-14 xl:f-18 ftw-6 lh-1 tn-all-3 h:c-py" href="/?logout=yes&<?=bitrix_sessid_get()?>">
                    <div class="d-f">
                        <svg width="20" height="17" viewBox="0 0 20 17" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M19.7246 9.14509C20.0918 8.78839 20.0918 8.21161 19.7246 7.85871L14.1002 2.39063C13.8307 2.12879 13.4284 2.0529 13.0768 2.1933C12.7253 2.33371 12.4988 2.66763 12.4988 3.03571V6.07143H8.12421C7.08915 6.07143 6.24939 6.88728 6.24939 7.89286V9.10714C6.24939 10.1127 7.08915 10.9286 8.12421 10.9286H12.4988V13.9643C12.4988 14.3324 12.7253 14.6663 13.0768 14.8067C13.4284 14.9471 13.8307 14.8712 14.1002 14.6094L19.7246 9.14509ZM6.24939 2.42857C6.94073 2.42857 7.49927 1.88594 7.49927 1.21429C7.49927 0.542634 6.94073 0 6.24939 0H3.74963C1.67952 0 0 1.6317 0 3.64286V13.3571C0 15.3683 1.67952 17 3.74963 17H6.24939C6.94073 17 7.49927 16.4574 7.49927 15.7857C7.49927 15.1141 6.94073 14.5714 6.24939 14.5714H3.74963C3.0583 14.5714 2.49976 14.0288 2.49976 13.3571V3.64286C2.49976 2.97121 3.0583 2.42857 3.74963 2.42857H6.24939Z"/></svg>
                    </div>
                    <div class="">Выйти</div>
                </a>
            </div>
        </div>

    </div>
</div>