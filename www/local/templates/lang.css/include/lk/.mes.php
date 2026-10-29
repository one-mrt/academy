<?
use Lang\Main\Iblock\Util as Data;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util;
use Bitrix\Main\UserTable;
use Bitrix\Main\Engine\CurrentUser;

use Bitrix\Intranet\Integration\Templates\Air\AirTemplate;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetMode;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Composite\StaticArea;
use Bitrix\Main\Loader;
use Bitrix\Im\V2\Application;

\Bitrix\Main\UI\Extension::load('main.sidepanel');

// Loader::includeModule('im');
// Loader::includeModule('intranet');

// \Bitrix\Main\UI\Extension::load([
//     'ui.viewer',
// 	'intranet.sidepanel.air',
// 	'intranet.skip-to-content',
// 	'socialnetwork.slider',
// 	'calendar.sliderloader',
// 	'ui.counter',
// 	'ui.buttons',
// 	'ui.icon-set.solid',
// 	'ui.icon-set.outline',
// ]);

// $asset = Asset::getInstance();
// $asset->addCss(SITE_TEMPLATE_PATH . '/dist/bitrix24.bundle.css', true);
// $asset->addJs(SITE_TEMPLATE_PATH . '/dist/bitrix24.bundle.js', true);

$image = Data::getElementObject($langText, ['NAME', 'PREVIEW_PICTURE'], ['ID' => 248]);

$name = "Сообщения";
$title = "Сообщения";
?>

<div class="w-100 xl:w-8/12 pr-16 pl-16 fg-0 fs-0">
    <div class="w-100 h-100 pn-r of-h p-32 bc-w r-24">
        <?if(!empty($image) && !empty($image->getPreviewPicture())) {
            echo Data::getImageId($image->getPreviewPicture(), [
                "alt" => strip_tags($image->getName()),
                "fetchpriority" => "high",
                "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
            ]);
        }?>

        <div class="w-100 d-f fd-c rg-32 pn-r">
            <script>
                BX.SidePanel.Instance.open("<?=SITE_TEMPLATE_PATH?>/.block/.messanger.php", {
                    cacheable: false,
                    allowChangeHistory: false,
                    events: {
                        onClose: (event) => {
                            // if (window.hasUnsavedChanges === true)
                            // {
                                event.denyAction();
                                console.log('close');
                            // }
                        },
                    }
                });

                // BX.SidePanel.Instance.open("lang:route", {
                //     contentCallback: function (slider) {
                //         return new Promise(function(resolve, reject) {
                //             BX.ajax.runAction('lang:route.Page.iframeAction', {
                //                 data: {
                //                     id: 2208
                //                 }
                //             }).then(function(response) {
                //                 resolve({ html: response.data.html });
                //             },function (response) {
                //                 console.log(response);				
                //             })
                //         });
                //     },
                //     // animationDuration: 100,
                //     // width: 370
                // });
            </script>
            <div><?//require 'rout.php'?></div>
            <?
            // \Bitrix\Main\UI\Extension::load('im.v2.application.quick-access');
            ?>
            <?/*<link rel='stylesheet' type="text/css" href='/bitrix/js/im/css/im.min.css'>*/?>
            <?/*$APPLICATION->IncludeComponent(
                "bitrix:im.messenger",
                "iframe",
                Array(
                    "CONTEXT" => "FULLSCREEN",
                ),
                false, Array("HIDE_ICONS" => "Y")
            )*/?>

            <?/*<div class="app__right-bar" id="right-bar">
                <? if (AirTemplate::shouldShowImBar()): ?>
                    <div class="air-chat-bar" role="region" aria-label="<?= Loc::getMessage('BITRIX24_CHAT_BAR_ARIA') ?>"><?php
                        $dynamicArea = new StaticArea('chat-bar');
                        $dynamicArea->setAssetMode(AssetMode::STANDARD);
                        $dynamicArea->startDynamicArea();
                        // $APPLICATION->IncludeComponent('bitrix:intranet.chat.bar', '');
                        // $application = new Application();
                        // \Bitrix\Main\UI\Extension::load('im.v2.application.quick-access');
                        
                        // echo '<pre>';
                        // print_r($application->getTemplate(Application::QUICK_ACCESS_APP_NAME));
                        // echo '</pre>';
                        ?>

                        <div class="im-chat-recent-list" id="bx-im-external-recent-list"></div>
                        <script><?//=$application->getTemplate(Application::QUICK_ACCESS_APP_NAME)?></script>
                            
                        <?$dynamicArea->finishDynamicArea();?>
                    </div>
                <? endif ?>
            </div>*/?>
        </div>
    </div>
</div>

<?
$APPLICATION->SetTitle(strip_tags($title));
$APPLICATION->SetPageProperty("title", strip_tags($name));
// $APPLICATION->SetPageProperty("description", strip_tags($seo['ELEMENT_META_DESCRIPTION']));
// $APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
?>