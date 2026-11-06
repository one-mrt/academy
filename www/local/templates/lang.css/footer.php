<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Context;
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\CurrentUser;

global $APPLICATION;
$siteTemplatePath = SITE_TEMPLATE_PATH;
$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;
$lang = Util::getLang();
$langText = "LangText{$lang}";

$user = CurrentUser::get();
?>
            </section>
        </main>

        <footer class="w-100 grow-0 order-3 bc-527CC8 pby-48 lg:pby-64 c-w">

            <div class="<?=comp::get('container')?>">
                <div class="<?=comp::get('row')?> rg-32">
                    <div class="w-6/12 md:w-4/12 lg:w-3/12 xl:w-20 <?=comp::get("setcol")?> d-f fd-c rg-32 c-w">
                        <?
                        $social = Util::getElementsObject($langText, ['select' => ['NAME', 'link', 'svg'],'filter' => ['IBLOCK_SECTION_ID' => 2]]);
                        if(!empty($social) || !empty($social->getIdList())):?>
                            <div class="w-100 d-f ai-c cg-16">
                                <?foreach($social as $item):
                                    $svg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($item->getSvg()->getValue()));
                                    ?>
                                    <a href="<?=$item->getLink()->getValue()?>" title="<?=$item->getName()?>" class="d-f ai-c c-w h:c-dt tn-all-3" <?=Util::iblockElementEditAddId($item)?>><?=$svg?></a>
                                <?endforeach;?>
                            </div>
                        <?endif;?>

                        <?
                        $copy = Util::getElementObject($langText, ['NAME'], ['ID' => 6]);
                        if(!empty($copy)):?>
                            <div class="f-12" <?=Util::iblockElementEditAddId($copy)?>><?=$copy->getName()?></div>
                        <?endif;?>

                        <?
                        $link = Util::getElementObject($langText, ['text'], ['ID' => 7]);
                        if(!empty($link)):?>
                            <div class="w-100 d-f fd-c rg-4 c-w f-12" <?=Util::iblockElementEditAddId($link)?>>
                                <?foreach($link->getText()->getAll() as $lValue):?>
                                    <a href="<?=$lValue->getValue()?>" class="h:tdl-u tdt-1 tuo-4 tdc-w tn-all-3"><?=$lValue->getDescription()?></a>
                                <?endforeach;?>
                            </div>
                        <?endif;?>
                    </div>

                    <div class="w-6/12 md:w-4/12 lg:w-3/12 xl:w-20 <?=comp::get("setcol")?> d-f fd-c rg-32">
                        <?
                        $aboutTitle = Util::getElementObject($langText, ['NAME'], ['ID' => 8]);
                        if(!empty($aboutTitle)):?>
                            <div class="f-18 ftw-6" <?=Util::iblockElementEditAddId($aboutTitle)?>><?=$aboutTitle->getName()?></div>
                        <?endif;?>

                        <?$APPLICATION->IncludeComponent(
                            "bitrix:menu", 
                            "lang.footer",
                            array(
                                "ROOT_MENU_TYPE" => "footer.about",
                                "MAX_LEVEL" => "1",
                                "USE_EXT" => "Y",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "MENU_CACHE_GET_VARS" => [],
                                "CHILD_MENU_TYPE" => "",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "N"
                            ),
                            false
                        );?>

                    </div>

                    <div class="w-6/12 md:w-4/12 lg:w-3/12 xl:w-20 <?=comp::get("setcol")?> d-f fd-c rg-32">
                        <?
                        $serviceTitle = Util::getElementObject($langText, ['NAME'], ['ID' => 9]);
                        if(!empty($serviceTitle)):?>
                            <div class="f-18 ftw-6" <?=Util::iblockElementEditAddId($serviceTitle)?>><?=$serviceTitle->getName()?></div>
                        <?endif;?>

                        <?$APPLICATION->IncludeComponent(
                            "bitrix:menu", 
                            "lang.footer",
                            array(
                                "ROOT_MENU_TYPE" => "footer.service",
                                "MAX_LEVEL" => "1",
                                "USE_EXT" => "Y",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "MENU_CACHE_GET_VARS" => [],
                                "CHILD_MENU_TYPE" => "",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "N"
                            ),
                            false
                        );?>
                    </div>

                    <div class="w-6/12 md:w-4/12 lg:w-3/12 xl:w-20 <?=comp::get("setcol")?> d-f fd-c rg-32">
                        <?
                        $expertTitle = Util::getElementObject($langText, ['NAME'], ['ID' => 10]);
                        if(!empty($expertTitle)):?>
                            <div class="f-18 ftw-6" <?=Util::iblockElementEditAddId($expertTitle)?>><?=$expertTitle->getName()?></div>
                        <?endif;?>

                        <?$APPLICATION->IncludeComponent(
                            "bitrix:menu", 
                            "lang.footer",
                            array(
                                "ROOT_MENU_TYPE" => "footer.expert",
                                "MAX_LEVEL" => "1",
                                "USE_EXT" => "Y",
                                "MENU_CACHE_TYPE" => "A",
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "MENU_CACHE_GET_VARS" => [],
                                "CHILD_MENU_TYPE" => "",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "N"
                            ),
                            false
                        );?>
                    </div>

                    <div class="w-10/12 sm:w-6/12 md:w-8/12 xl:w-20 <?=comp::get("setcol")?>">
                        <div class="<?=comp::get("row")?> rg-32">
                            <?
                            $contactTitle = Util::getElementObject($langText, ['NAME'], ['ID' => 11]);
                            if(!empty($contactTitle)):?>
                                <div class="w-100 <?=comp::get("setcol")?> f-18 ftw-6" <?=Util::iblockElementEditAddId($contactTitle)?>><?=$contactTitle->getName()?></div>
                            <?endif;?>

                            <div class="w-100 md:w-6/12 lg:w-4/12 xl:w-100 <?=comp::get("setcol")?> d-f fd-c rg-8">
                                <?
                                $phone = Util::getElementObject($langText, ['link'], ['ID' => 12]);
                                if(!empty($phone) && !empty($phone->getLink())):?>
                                    <a href="tel:<?=$phone->getLink()->getValue()?>" class="h:tdl-u tdt-1 tuo-4 tdc-w tn-all-3 f-14" <?=Util::iblockElementEditAddId($phone)?>><?=$phone->getLink()->getDescription()?></a>
                                <?endif;?>

                                <?
                                $email = Util::getElementObject($langText, ['link'], ['ID' => 13]);
                                if(!empty($email) && !empty($email->getLink())):?>
                                    <a href="tel:<?=$email->getLink()->getValue()?>" class="h:tdl-u tdt-1 tuo-4 tdc-w tn-all-3 f-14" <?=Util::iblockElementEditAddId($email)?>><?=$email->getLink()->getDescription()?></a>
                                <?endif;?>

                                <?
                                $address = Util::getElementObject($langText, ['NAME'], ['ID' => 14]);
                                if(!empty($address) && !empty($address->getId())):?>
                                    <div class="f-14" <?=Util::iblockElementEditAddId($address)?>><?=$address->getName()?></div>
                                <?endif;?>
                            </div>

                            <div class="w-100 md:w-6/12 lg:w-5/12 xl:w-100 <?=comp::get("setcol")?> d-f fd-c rg-8">
                                <?
                                $orderAdd = Util::getElementObject($langText, ['NAME', 'link'], ['ID' => 453]);
                                $authorAdd = Util::getElementObject($langText, ['NAME', 'link'], ['ID' => 454]);
                                if(!empty($orderAdd) && !empty($orderAdd->getLink())):?>
                                    <?if(!empty($user->getId())):?>
                                        <a <?=Util::iblockElementEditAddId($orderAdd)?> href="<?=$orderAdd->getLink()->getValue()?>" class="<?=comp::get("btnPurpleSsm")?>"><?=$orderAdd->getLink()->getDescription()?></a>
                                    <?else:?>
                                        <button class="lang-form--order-add <?=comp::get("btnPurpleSsm")?>"><?=$orderAdd->getLink()->getDescription()?></button>
                                    <?endif?>
                                <?endif;?>

                                <?if(!empty($authorAdd) && !empty($authorAdd->getLink())):?>
                                    <?if(!empty($user->getId())):?>
                                        <a <?=Util::iblockElementEditAddId($authorAdd)?> href="<?=$authorAdd->getLink()->getValue()?>" class="<?=comp::get("btnPurpleSsm")?>"><?=$authorAdd->getLink()->getDescription()?></a>
                                    <?else:?>
                                        <button class="lang-form--reg <?=comp::get("btnPurpleSsm")?>"><?=$authorAdd->getLink()->getDescription()?></button>
                                    <?endif;?>
                                <?endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </footer>

        <?CJSCore::Init(['ajax']);?>

        <?if(empty($user->getId())):?>
            <style class="brr-12 blr-12"></style>
            <div class="lang-modal--wrap w-100 h-100 pn-f t-0 rt-0 b-0 l-0 d-f d-n ai-s jc-c bc-f4eff95e bf-b6 z-2">
                <div class="lang-modal--container pn-r of-h bxs-au mi-16 brr-12 blr-12 tnd-500ms tp-t t-0 t--100% bc-w">
                    <div class="lang-modal--content w-100 h-100"></div>
                    <button class="lang-modal--close pn-a t-32px rt-32px m-0 p-0 bw-0 c-dt h:c-py tn-all-3 bc-t">
                        <svg width="24" height="24" viewBox="0 0 13 13" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"></line>
                            <line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"></line>
                        </svg>
                    </button>
                    <div class="lang-modal--preloader w-100 h-100 d-f fd-c m-0 z-2 ai-c jc-c pn-a t-0 rt-0 b-0 l-0 ofw-h bc-w">
                        <style>@-webkit-keyframes side-panel-loader-rotate{100% {-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@keyframes side-panel-loader-rotate{100%{-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@-webkit-keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}@keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}.loader--animation {-webkit-animation: side-panel-loader-rotate 2s linear infinite;animation: side-panel-loader-rotate 2s linear infinite;-webkit-transform-origin: center center;transform-origin: center center}.loader--path{stroke: #bfc3c8;stroke-width: 1.5;stroke-dasharray: 20, 200;stroke-dashoffset: 0;-webkit-animation: side-panel-loader-dash 1.5s ease-in-out infinite;animation: side-panel-loader-dash 1.5s ease-in-out infinite;stroke-linecap: round}</style>
                        <svg class="loader--animation w-100 h-100 mw-200px mxh-200px" viewBox="25 25 50 50"><circle class="loader--path" cx="50" cy="50" r="20" fill="none" stroke-miterlimit="10"></circle></svg>
                        <div class="mt-8 f-12 md:f-14">Пожалуйста подождите</div>
                    </div>
                </div>
            </div>

            <script><?=file_get_contents("{$templatePath}/js/lk/userAuth.js")?></script>
            <script>new LG.Auth()</script>
        <?else:?>
            <script class=""><?=file_get_contents("{$templatePath}/js/experts/orderPrivate.js")?></script>
            <script>new LG.AddOrder()</script>
        <?endif?>

        <?
        @include_once '.block/.footer/.chat.php';
        @include_once 'body/script.footer.php';
        @include_once 'body/shema.org.php';
        ?>

    </body>
</html>