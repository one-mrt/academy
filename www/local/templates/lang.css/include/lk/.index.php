<?
use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util as Data;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util;
use Bitrix\Main\UserTable;
use Bitrix\Main\Engine\CurrentUser;

Loader::includeModule('sale');
Loader::includeModule('catalog');

$lang = Util::getLang();
$langText = "LangText{$lang}";
$currentUser = CurrentUser::get();
$user = UserTable::getList([
    'select' => ['NAME', 'LOGIN', 'LAST_NAME', 'PERSONAL_PHOTO', 'IS_ONLINE', 'DATE_REGISTER', 'PERSONAL_NOTES', 'UF_SPEC', 'UF_WORKTYPE'],
    'filter' => ['ID' => $currentUser->getId()],
    'cache' => Data::getCache(900)
])->fetchObject();
?>

<section class="w-100 d-f fd-c fg-1">
    <div class="w-100 d-f fd-c fg-1">
        <div class="<?=comp::get("container-2")?> d-f fd-c fg-1">
            <div class="<?=comp::get("row")?> fg-1">
                
                <?@include_once('.sidebar.php');?>

                <?@include_once($arParams['content']);?>

            </div>
        </div>
    </div>
</section>