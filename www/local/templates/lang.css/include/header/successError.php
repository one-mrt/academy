<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$errorText = Util::getElementObject($textTableString, ['NAME', 'link'], ['ID' => 205509]);
$successText = Util::getElementObject($textTableString, ['NAME', 'PREVIEW_TEXT', 'link'], ['ID' => 205508]);
?>

<?if(!empty($arResult["ERROR_MESSAGE"])):?>
    <div style="display: none;" class="lang-form--success fancybox__content ai-c jc-c" id="LangFormSuccess<?=$id?>">
        <style>
            .lang-ruk-form--window .fancybox__backdrop {
                background: rgba(244, 239, 249, 0.37);
                backdrop-filter: blur(6px)
            }

            .lang-form--success.fancybox__content {
                max-width: 100%;
                min-height: 32rem;
                padding: 2rem;
                border-radius: 1.5rem;
                background-color: #EDF0F9;
                box-shadow: 0 16px 32px rgb(221 68 90 / 12%), 0 12px 24px rgb(221 68 90 / 22%);
            }

            @media (min-width: 48rem) {
                .lang-form--success.fancybox__content {
                    max-width: 45rem;
                    min-height: 32rem;
                    padding: 4rem;
                    border-radius: 3rem;
                }
            }
        </style>
        <div class="h-100 d-f fd-c jc-c ai-c rg-32 ta-c">
            <div class="w-100">
                <div class="f-32 md:f-48 ftw-5"><?=$errorText->getName()?></div>
                <div class="w-100 d-f fd-c rg-8">
                    <?foreach($arResult["ERROR_MESSAGE"] as $v):?>
                        <div class=""><?=$v?></div>
                    <?endforeach?>
                </div>
            </div>
            <div class="w-100">
                <button data-fancybox-close="" class="<?=comp::get("btnPy")?>"><?=$errorText->getLink()->getDescription()?></button>
            </div>
        </div>
    </div>

    <script>
        Fancybox.close();
        Fancybox.defaults.buttons = ["zoom","slideShow","fullScreen","thumbs"];
        Fancybox.show([{
            src: "#LangFormSuccess<?=$id?>",
            type: "inline",
            closeButton: false,
            closeExisting: true,
            closeExisting: true,
            backdropClick: false,
            dragToClose: false,
        }]);
    </script>
    
<?endif?>

<?if(!empty($arResult["OK_MESSAGE"])):?>

    <?if($arParams["YANDEX_EVENT"]):?><script><?=$arParams["YANDEX_EVENT"]?></script><?endif?>

    <div style="display: none;" class="lang-form--success fancybox__content ai-c jc-c" id="LangFormSuccess<?=$id?>">
        <style>
            .lang-ruk-form--window .fancybox__backdrop {
                background: rgba(244, 239, 249, 0.37);
                backdrop-filter: blur(6px)
            }
            .lang-form--success.fancybox__content {
                max-width: 100%;
                min-height: 32rem;
                padding: 2rem;
                border-radius: 1.5rem;
                background-color: #EDF0F9;
                box-shadow: 0 16px 32px rgb(221 68 90 / 12%), 0 12px 24px rgb(221 68 90 / 22%);
            }

            @media (min-width: 48rem) {
                .lang-form--success.fancybox__content {
                    max-width: 45rem;
                    min-height: 32rem;
                    padding: 4rem;
                    border-radius: 3rem;
                }
            }
        </style>
        <div class="h-100 d-f fd-c jc-c ai-c rg-32 ta-c">
            <div class="d-f c-py">
                <svg width="76" height="76" viewBox="0 0 76 76" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M53.1834 24.7714C53.1834 24.7714 36.4206 53.9837 36.1309 54.2449C35.1737 55.1474 33.6704 55.1001 32.775 54.1501L21.9165 44.1274C21.0187 43.1774 21.0686 41.6576 22.0258 40.7789C22.9829 39.8764 24.4863 39.9237 25.384 40.8737L33.8817 48.7113L49.0699 22.3964C49.7254 21.2801 51.1765 20.8761 52.3141 21.5411C53.4494 22.1824 53.8389 23.6551 53.1834 24.7714ZM38 0C17.0145 0 0 17.005 0 38C0 58.995 17.0145 76 38 76C58.9855 76 76 58.995 76 38C76 17.005 58.9855 0 38 0Z"/>
                </svg>
            </div>
            <div class="w-100 d-f fd-c ai-c">
                <div class="f-32 md:f-48 ftw-5"><?=$successText->getName()?></div>
                <div class="d-f fd-c f-16 md:f-18 ftw-4 ta-c"><?=$successText->getPreviewText()?></div>
            </div>
            <div class="w-100">
                <button data-fancybox-close="" class="<?=comp::get("btnPy")?>"><?=$successText->getLink()->getDescription()?></button>
            </div>
        </div>
    </div>

    <script>
        Fancybox.close();
        Fancybox.defaults.buttons = ["zoom","slideShow","fullScreen","thumbs"];
        Fancybox.show([{
            src: "#LangFormSuccess<?=$id?>",
            type: "inline",
            closeButton: false,
            closeExisting: true,
            closeExisting: true,
            backdropClick: false,
            dragToClose: false,
        }]);
    </script>

<?endif?>