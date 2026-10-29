<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Engine\CurrentUser;

$user = CurrentUser::get();

if(!empty($user->getId())):?>
    <style>
        .bx-im-next-layout-dialog-messages .bx-pull-status-wrap {
            z-index: 1;
        }
        .bx-desktop-tab-content-active {display: flex;height: 100%;}
        .bx-desktop .bx-messenger-box {width: 100%;}
        .bx-messenger-box-dialog[style*="display: block"] {
            display: flex!important;
        }
        .bx-im-component-chat-name-text {max-width: 12rem;}
        .bx-messenger-cl {
            height: auto;
            margin: 0;
        }
        .bx-im-recent-item-wrap {
            border-bottom: 1px solid #f5f6f6;

            .bx-im-recent-item {
                height: auto;
                margin: 0;
                padding: 0;
                padding-block: 0.5rem;
                padding-inline: 1rem;
                display: flex;
                column-gap: 1rem;

                .bx-im-recent-avatar-wrap {
                    width: auto;
                }

                .bx-im-recent-item-content {
                    display: flex;
                    flex-direction: column;
                    row-gap: 0.25rem;
                    border: none;
                    padding: 0;
                    margin: 0;

                    .bx-im-recent-item-content-header {
                        margin-bottom: 0;
                    }

                    .bx-im-recent-message-text-wrap {
                        max-width: 12rem;
                        font-size: 0.625rem;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        overflow: hidden;
                    }
                }
            }
        }
        .bx-messenger-box-contact-active {
            .lang-search--close {
                display: flex;
            }
        }

        .bx-messenger-panel {
            height: auto;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            column-gap: 1rem;

            .bx-messenger-panel-avatar {
                position: static;
                margin: 0;
                padding: 0;
                display: flex;
            }

            .bx-messenger-panel-title {
                padding: 0;
            }
            
            .bx-messenger-panel-desc {
                height: auto;
                padding: 0;
            }

            &.bx-messenger-context-chat {

                .bx-messenger-panel-mute {
                    display: flex;
                    margin: 0;
                    padding: 0;
                    background-image: none;
                    align-items: center;
                    justify-content: center;
                    width: 1.75rem;
                    height: 1.75rem;
                    opacity: 1;
                    transition: all 0.3S;
                    /* color: ; */

                    .lang--mute {
                        display: flex;
                    }

                    .lang--unmute {
                        display: none;
                    }

                    svg {
                        height: 0.75rem;
                        width: auto;
                    }

                    &:hover {
                        color: #724a98;
                    }

                    &.bx-messenger-panel-unmute {
                        border-color: #724a98;
                        background-color: #724a98;
                        color: #fff;

                        .lang--mute {
                            display: none;
                        }

                        .lang--unmute {
                            display: flex;
                        }
                    }
                }
            }
        }

        .bx-messenger-textarea-place {
            max-height: 12.5rem;
        }
    </style>
    
    <?$APPLICATION->IncludeComponent(
        "lang:messenger",
        "lang.mess",
        Array(
            // "DESIGN" => "DESKTOP",
            // "CONTEXT" => "POPUP-FULLSCREEN"
        )
    );?>

    <?
    // echo '<pre>';
    // print_r(\Bitrix\Im\V2\Permission\Filter::getRoleGetListFilter($ormParams, \Bitrix\Im\V2\Permission\ActionGroup::ManageMessages, 'RELATION', 'CHAT'));
    // echo '</pre>';
    ?>
    <?/*<script>
        BX.addCustomEvent("onImConfirmNotify", function(data) {
            console.info(data)
        });
        BX.addCustomEvent(window, "onImConfirmNotify", function(data) {
            console.info(data)
        });
        BX.addCustomEvent(window, "bxImClickConfirmNotify", function(data) {
            console.info(data)
        });
    </script>*/?>
<?endif;?>