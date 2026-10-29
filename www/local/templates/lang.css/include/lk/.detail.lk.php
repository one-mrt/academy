<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Main\UI\Extension;
use Lang\Css\Component as comp;
use Bitrix\Main\Engine\CurrentUser;

Extension::load("langmess.messenger");

$user = CurrentUser::get();
$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;
?>

<style>
    .bx-im-dialog-list {

        /* display: flex;
        flex-direction: column;
        column-gap: 1rem; */

        .bx-im-dialog-group {
            padding: 0;
            padding-inline: 1rem;

            .bx-im-dialog-group-date {
                margin: 0.75rem 0;
                padding: 0.5rem 0.75rem;
                background-color: rgba(0, 0, 0, 0.2);
	            border: none;
                border-radius: 99999px;
                font-size: 0.75rem;
                text-align: center;
                line-height: 1;
                user-select: none;
            }
        }

        .bx-im-dialog-list-item {
            padding: 0;
            padding-inline: 1rem;
        
            &:last-child,
            &:nth-last-child(2) {
                margin-bottom: 1rem;
            }

            .bx-im-message {
                margin: 0;
                display: flex;
                column-gap: 1rem;

                .bx-im-message-avatar {
                    width: auto;
                    height: auto;

                    .bx-im-message-avatar-image {
                        margin: 0;
                    }
                }

                .bx-im-message-box {

                    .bx-im-message-content-wrap {
                        padding: 0;

                        .bx-im-message-content {
                            margin: 0;
                            padding: 0;

                            .bx-im-message-content-box {
                                padding: 0.5rem 0;
                                /* padding: 0.5rem 1rem;
                                background-color: #ffffffab;
                                border-radius: 0.25rem; */
                            }
                        }
                    }
                }

                &.bx-im-message-type-self {
                    .bx-im-message-box {

                        .bx-im-message-content-wrap {
                            .bx-im-message-content {
                                margin: 0;
                                padding: 0.375rem 0.75rem;
                                border-radius: 0.375rem;

                                .bx-im-message-content-box {
                                    padding: 0;
                                }
                            }
                        }
                    }
                }
            }

            .bx-im-message-type-opponent {
                margin: 0.75rem 0;

                .bx-im-message-box {

                    .bx-im-message-content-wrap {

                        .bx-im-message-content {
                            margin: 0;
                            padding: 0.375rem 0.75rem;
                            border-radius: 0.375rem;

                            .bx-im-message-content-box {
                                padding: 0;
                            }
                        }
                    }
                }
            }
        }
    }

    .bx-im-dialog-scroll-button-box {
        z-index: 2;
    }
</style>

<section class="lang-order--detail w-100 d-f fd-c fg-1">
    <div class="<?=comp::get("container-full")?> d-f fd-c fg-1">
        <div class="<?=comp::get("row")?> fg-1 rg-32">
            <?/*
            <div class="w-100 xl:w-1/12 <?=comp::get("setcol")?>">
                <div class="w-100 h-100 p-24 xl:p-32 d-f fd-c rg-24 ai-c bc-fafafa r-12">

                    <?$APPLICATION->IncludeComponent(
                        "bitrix:menu", 
                        "lk.order",
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

                    <a class="d-f ai-c cg-16 f-12 ftw-6 lh-1 tn-all-3 h:c-py h:d-f>.name pn-r" href="/?logout=yes&<?=bitrix_sessid_get()?>" title="Выйти">
                        <div class="d-f">
                            <svg width="20" height="17" viewBox="0 0 20 17" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M19.7246 9.14509C20.0918 8.78839 20.0918 8.21161 19.7246 7.85871L14.1002 2.39063C13.8307 2.12879 13.4284 2.0529 13.0768 2.1933C12.7253 2.33371 12.4988 2.66763 12.4988 3.03571V6.07143H8.12421C7.08915 6.07143 6.24939 6.88728 6.24939 7.89286V9.10714C6.24939 10.1127 7.08915 10.9286 8.12421 10.9286H12.4988V13.9643C12.4988 14.3324 12.7253 14.6663 13.0768 14.8067C13.4284 14.9471 13.8307 14.8712 14.1002 14.6094L19.7246 9.14509ZM6.24939 2.42857C6.94073 2.42857 7.49927 1.88594 7.49927 1.21429C7.49927 0.542634 6.94073 0 6.24939 0H3.74963C1.67952 0 0 1.6317 0 3.64286V13.3571C0 15.3683 1.67952 17 3.74963 17H6.24939C6.94073 17 7.49927 16.4574 7.49927 15.7857C7.49927 15.1141 6.94073 14.5714 6.24939 14.5714H3.74963C3.0583 14.5714 2.49976 14.0288 2.49976 13.3571V3.64286C2.49976 2.97121 3.0583 2.42857 3.74963 2.42857H6.24939Z"/></svg>
                        </div>
                        <div class="name d-n pn-a l-32 ws-nw lh-1 pby-4 pix-8 r-4 bc-py c-w">Выйти</div>
                    </a>
                </div>
            </div>
            */?>
            <div class="order--content w-100 lg:w-7/12 <?=comp::get("setcol")?>">
                <?$order = $APPLICATION->IncludeComponent("lang:lk.order.detail", "", ['order' => $arParams['order']], false,['HIDE_ICONS' => 'Y'])?>
            </div>
            <div class="w-100 lg:w-5/12 <?=comp::get("setcol")?>">
                <div class="dialog--content w-100 h-100 d-f fd-c rg-24 bc-fafafa r-12 mnh-400px xl:mnh-700px of-h">
                    <div class="order--dialog w-100 h-100 fg-1"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="lang-edit--wrap w-100 h-100 pn-f t-0 rt-0 b-0 l-0 d-f d-n ai-s jc-e bc-f4eff95e bf-b6 z-3">
    <div class="lang-edit--container w-100 sm:mw-540px mnh-250px of-h pn-r t-0 t--100% mr-a ml-a d-f fd-c bxs-au tp-t tnd-500ms bc-w sm:brr-12 sm:blr-12">

        <div class="lang-edit--content w-100 h-100 ofx-h ofy-a pn-r p-32 md:p-32 d-f ai-c jc-c fg-1"></div>

        <button class="lang-edit--close pn-a t-32px rt-32px m-0 p-0 z-1 d-f bw-0 c-dt h:c-py bc-t tn-all-3">
            <svg width="24" height="24" viewBox="0 0 13 13" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                <line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"></line>
                <line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"></line>
            </svg>
        </button>
        <div class="lang-edit--preloader w-100 h-100 d-f fd-c m-0 z-2 ai-c jc-c pn-a t-0 rt-0 b-0 l-0 ofw-h bc-w">
            <style>@-webkit-keyframes side-panel-loader-rotate{100% {-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@keyframes side-panel-loader-rotate{100%{-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@-webkit-keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}@keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}.loader--animation {-webkit-animation: side-panel-loader-rotate 2s linear infinite;animation: side-panel-loader-rotate 2s linear infinite;-webkit-transform-origin: center center;transform-origin: center center}.loader--path{stroke: #bfc3c8;stroke-width: 1.5;stroke-dasharray: 20, 200;stroke-dashoffset: 0;-webkit-animation: side-panel-loader-dash 1.5s ease-in-out infinite;animation: side-panel-loader-dash 1.5s ease-in-out infinite;stroke-linecap: round}</style>
            <svg class="loader--animation w-100 h-100 mw-150px mxh-150px" viewBox="25 25 50 50"><circle class="loader--path" cx="50" cy="50" r="20" fill="none" stroke-miterlimit="10"></circle></svg>
            <div class="mt-8 f-12 md:f-14">Пожалуйста подождите</div>
        </div>
    </div>
</div>

<style class="bcr-8c94a8 bcr-c90536 r-6 pix-24 pby-12 sm:w-5/12 fl-e64231 c-t c-e64231 h:c-e64231 c:c-e64231~lb mxh-500px"></style>
<script>LG.LKOrderData = <?=json_encode(['id' => $order['id'], 'chat' => $order['chat'], 'serviceId' => $arParams['order']])?></script>
<script><?=file_get_contents("{$templatePath}/js/main/util.js")?></script>
<script><?=file_get_contents("{$templatePath}/js/main/modal.js")?></script>
<script><?=file_get_contents("{$templatePath}/js/lk/lkOrderEdit.js")?></script>
<script><?=file_get_contents("{$templatePath}/js/lk/lkOrderEditInfo.js")?></script>
<script><?=file_get_contents("{$templatePath}/js/lk/lkOrderAddReview.js")?></script>

<script>new LG.LKEditOrder()</script>
<script>new LG.LKEditOrderInfo()</script>
<script>new LG.LKOrderAddReview()</script>