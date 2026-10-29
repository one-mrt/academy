<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\UserTable;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

$user = CurrentUser::get();
$lang = Data::getLang();
$langText = "LangText{$lang}";
$jsFilePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;

if(empty($user->getId())):?>
    <?
    $social = Data::getElementsObject($langText, ['select' => ['NAME', 'link', 'svg'],'filter' => ['IBLOCK_SECTION_ID' => 2]]);
    if(!empty($social) || !empty($social->getIdList())):?>
        <div class="d-n md:d-f ai-c cg-16">
            <?foreach($social as $item):
                $svg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($item->getSvg()->getValue()));
                ?>
                <a href="<?=$item->getLink()->getValue()?>" title="<?=$item->getName()?>" class="d-f ai-c c-py h:c-dt tn-all-3" <?=Data::iblockElementEditAddId($item)?>><?=$svg?></a>
            <?endforeach;?>
        </div>
    <?endif;?>
    <button aria-label="Открыть форму - Авторизация" class="lang-auth--button pt-12 pr-24 pb-12 pl-24 bw-1 bs-s r-4 ff-ud f-16 lh-1 ftw-6 tt-u ws-nw bc-py c-w bcr-py h:bc-t h:c-dt a:tm-s0.9 tn-all-3">Вход</button>
<?else:
    $userAuth = UserTable::getList([
        'select' => ['NAME', 'LOGIN', 'LAST_NAME', 'PERSONAL_PHOTO'],
        'filter' => ['ID' => $user->getId()],
        'cache' => Data::getCache(0)
    ])->fetchObject();
    ?>
    <div class="lang-userauth--element w-100 h-100 d-f cg-16 ai-c jc-e md:pn-r">
        <div class="lang-userauth--close w-100 h-100 pn-f t-0 rt-0 b-0 l-0 z-1 d-n d-f bf-b6"></div>

        <div class="lang-messanger--wrap w-100 h-100 pn-f t-0 rt-0 b-0 l-0 d-f d-n ai-c jc-e bc-f4eff95e bf-b6 z-3">
            <div class="lang-messanger--container h-100 w-100 lg:mw-960px d-f fd-c">
                <div class="lang-messanger--content w-100 h-100%-110px pn-r t-110 t-200% tp-t tnd-500ms bc-w lg:btlr-12">
                    <button class="messanger--close pn-a t--32px rt-32px m-0 p-0 bw-0 c-dt h:c-py tn-all-3 bc-t"><svg width="24" height="24" viewBox="0 0 13 13" fill="none" stroke="currentColor"><line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"></line><line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"></line></svg></button>
                </div>
            </div>
        </div>

        <button class="lang-order--add pn-r z-3 m-0 p-0 d-f ai-c bw-0 bc-t c-7B7B7B h:c-py tn-all-3" title="Создать заказ">
            <svg width="24" height="24" viewBox="0 0 100 100" fill="currentColor"><path d="M0 50C0 36.7392 5.26784 24.0215 14.6447 14.6447C24.0215 5.26784 36.7392 0 50 0C63.2608 0 75.9785 5.26784 85.3553 14.6447C94.7322 24.0215 100 36.7392 100 50C100 63.2608 94.7322 75.9785 85.3553 85.3553C75.9785 94.7322 63.2608 100 50 100C36.7392 100 24.0215 94.7322 14.6447 85.3553C5.26784 75.9785 0 63.2608 0 50ZM50 25C48.894 25 47.8334 25.4393 47.0514 26.2214C46.2693 27.0034 45.83 28.064 45.83 29.17V45.83H29.17C28.064 45.83 27.0034 46.2693 26.2214 47.0514C25.4393 47.8334 25 48.894 25 50C25 51.106 25.4393 52.1666 26.2214 52.9486C27.0034 53.7307 28.064 54.17 29.17 54.17H45.83V70.83C45.83 71.936 46.2693 72.9966 47.0514 73.7786C47.8334 74.5607 48.894 75 50 75C51.106 75 52.1666 74.5607 52.9486 73.7786C53.7307 72.9966 54.17 71.936 54.17 70.83V54.17H70.83C71.936 54.17 72.9966 53.7307 73.7786 52.9486C74.5607 52.1666 75 51.106 75 50C75 48.894 74.5607 47.8334 73.7786 47.0514C72.9966 46.2693 71.936 45.83 70.83 45.83H54.17V29.17C54.17 28.064 53.7307 27.0034 52.9486 26.2214C52.1666 25.4393 51.106 25 50 25Z"/></svg>
        </button>

        <button class="lang-messanger--notify pn-r z-3 m-0 p-0 d-f ai-c bw-0 bc-t c-7B7B7B h:c-py tn-all-3" title="Общие уведомления">
            <div class="d-f">
                <svg width="19" height="24" viewBox="0 0 34 42" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.7692 42C20.0338 42 21.8184 39.6906 21.8184 36.4364H11.7095C11.7095 39.6906 13.494 42 16.7692 42Z"/>
                    <path d="M33.1554 31.1038C31.5388 28.9728 28.3581 27.7237 28.3581 18.1816C28.3581 8.38762 24.0333 4.45113 20.0023 3.50637C19.6244 3.4119 19.3515 3.28593 19.3515 2.88703V2.58261C19.3515 1.17597 18.1968 -0.0102252 16.7901 0.000272131C15.3835 -0.0207225 14.2288 1.17597 14.2288 2.58261V2.88703C14.2288 3.27543 13.9558 3.4119 13.5779 3.50637C9.53647 4.46163 5.22207 8.38762 5.22207 18.1816C5.22207 27.7237 2.04139 28.9624 0.424805 31.1038C-0.614429 32.4789 0.372319 34.4419 2.09388 34.4419H31.4968C33.2079 34.4419 34.1946 32.4685 33.1554 31.1038Z"/>
                </svg>
            </div>
            <div class="notify--count w-16px h-16px pn-a t--8 rt--4 d-f ai-c jc-c f-10 lh-1 r-100 bc-000 c-w">0</div>
        </button>

        <button class="lang-messanger--dialog pn-r z-3 m-0 p-0 d-f ai-c bw-0 bc-t c-7B7B7B h:c-py tn-all-3" title="Уведомления диалогов и заказов">
            <div class="d-f">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.8 0H7.2C3.2292 0 0 3.2292 0 7.2V22.8C0 23.1183 0.126428 23.4235 0.351472 23.6485C0.576515 23.8736 0.88174 24 1.2 24H16.8C20.7708 24 24 20.7708 24 16.8V7.2C24 3.2292 20.7708 0 16.8 0ZM10.8 12.6C10.8 13.0774 10.6104 13.5352 10.2728 13.8728C9.93523 14.2104 9.47739 14.4 9 14.4C8.8968 14.4 8.7984 14.3868 8.7 14.37C8.6004 14.382 8.5032 14.4 8.4 14.4C7.92533 14.4 7.46131 14.2592 7.06663 13.9955C6.67195 13.7318 6.36434 13.357 6.18269 12.9184C6.00104 12.4799 5.95351 11.9973 6.04612 11.5318C6.13872 11.0662 6.3673 10.6386 6.70294 10.3029C7.03859 9.9673 7.46623 9.73872 7.93178 9.64612C8.39734 9.55351 8.8799 9.60104 9.31844 9.78269C9.75698 9.96434 10.1318 10.272 10.3955 10.6666C10.6592 11.0613 10.8 11.5253 10.8 12C10.8 12.102 10.782 12.2004 10.77 12.3C10.7856 12.3984 10.8 12.4968 10.8 12.6ZM15.6 14.4C15.4968 14.4 15.3996 14.382 15.3 14.37C15.201 14.3885 15.1007 14.3986 15 14.4C14.5226 14.4 14.0648 14.2104 13.7272 13.8728C13.3896 13.5352 13.2 13.0774 13.2 12.6C13.2 12.498 13.2144 12.3984 13.23 12.3C13.218 12.2004 13.2 12.1032 13.2 12C13.2 11.5253 13.3408 11.0613 13.6045 10.6666C13.8682 10.272 14.243 9.96434 14.6816 9.78269C15.1201 9.60104 15.6027 9.55351 16.0682 9.64612C16.5338 9.73872 16.9614 9.9673 17.2971 10.3029C17.6327 10.6386 17.8613 11.0662 17.9539 11.5318C18.0465 11.9973 17.999 12.4799 17.8173 12.9184C17.6357 13.357 17.328 13.7318 16.9334 13.9955C16.5387 14.2592 16.0747 14.4 15.6 14.4Z"/>
                </svg>
            </div>
            <div class="dialog--count w-16px h-16px pn-a t--8 rt--4 d-f ai-c jc-c f-10 lh-1 r-100 bc-000 c-w">0</div>
        </button>

        <?if(!empty($userAuth->getPersonalPhoto())):?>
            <button class="lang-userauth--btn of-h pn-r z-2 m-0 p-0 d-f ai-c bw-0 bc-t r-100 a:tm-s0.9 tn-all-3">
                <?=Data::getImageId($userAuth->getPersonalPhoto(), [
                    "alt" => $userAuth->getName(),
                    "fetchpriority" => "high",
                    "class" => "w-42px h-42px oft-cv op-c h:tm-s1.1 tn-all-3",
                ])?>
            </button>
        <?else:?>
            <button class="lang-userauth--btn of-h pn-r z-2 m-0 p-0 d-f ai-c bw-0 bc-t r-100 a:tm-s0.9 tn-all-3">
                <div class="w-42px h-42px bc-w bw-1 bs-s bcr-636363 r-100"></div>
            </button>
        <?endif?>

        <div class="lang-userauth--menu pn-a z-1 t--16px rt--16px l--16px pt-74 pb-24 d-f d-n fd-c rg-24 bc-w r-12 bxs-au">
            <div class="w-100 d-f bbw-1 bbs-s bbc-E5E5E5 pb-16 pix-24">
                <a href="/lk/" class="d-f ai-c cg-16 pn-r z-1 h:c-py tn-all-3">
                    <?if(!empty($userAuth->getPersonalPhoto())):?>
                        <?=Data::getImageId($userAuth->getPersonalPhoto(), [
                            "alt" => $userAuth->getName(),
                            "fetchpriority" => "high",
                            "class" => "w-42px h-42px r-100 oft-cv op-c",
                        ])?>
                    <?else :?>
                        <button class="lang-userauth--btn of-h pn-r z-2 m-0 p-0 d-f ai-c bw-0 bc-t r-100 a:tm-s0.9 tn-all-3">
                            <div class="w-42px h-42px bc-w bw-1 bs-s bcr-636363 r-100"></div>
                        </button>
                    <?endif?>
                    <div class="d-f fd-c rg-8 fg-1">
                        <div class="ff-ud ftw-5 f-14">
                            <?if(!empty($userAuth->getName()) || !empty($userAuth->getLastName())):?>
                                <?if(!empty($userAuth->getName())):?>
                                    <span class=""><?=$userAuth->getName()?></span>
                                <?endif;?>
                                <?if(!empty($userAuth->getLastName())):?>
                                    <span class=""><?=$userAuth->getLastName()?></span>
                                <?endif?>
                            <?else:?>
                                <span class=""><?=$userAuth->getLogin()?></span>
                            <?endif;?>
                        </div>
                    </div>
                </a>
            </div>
            <div class="w-100 d-f fd-c ai-e pix-24">
                <div class="d-f fd-c rg-8 ai-e f-14">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:menu", 
                        "lang.user",
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
                    <a class="h:c-py tdt-1 tuo-4 tdc-py tn-all-3 h:tdl-u" href="?logout=yes&<?=bitrix_sessid_get()?>">Выйти</a>
                </div>
            </div>
        </div>
    </div>
    <script><?=file_get_contents("{$jsFilePath}/js/lk/userPanel.js")?></script>
    <script>new LG.userPanel()</script>
<?endif?>