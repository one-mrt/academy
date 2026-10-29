<?
global $USER;

use Bitrix\Main\Localization\Loc;

// include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/php.css/baseVar.php';

// echo '<pre>';
// print_r($cssGlobal);
// echo '</pre>';

?>

<?//if($USER->IsAdmin()):?>
    <style>
        .lang-special {
            background-color: #FFF!important;
            color: #000!important;
            font-size: 16px!important;
        }
        .lang-special--container {}
        .lang-special--block {}
        .lang-special--title {}
        .lang-special--button {}
        .lang-special--font {}
        .lang-special--fontSize {}
        .lang-special--kerning {}
        .lang-special--interval {}
        .lang-special--color {}
        .lang-special--color:hover {
            background-color:#EBEBEB!important;
            border-color:#EBEBEB!important;
            color: #000!important;
        }
        
        .lang-special--image {}
        .lang-special--panel {}
        .lang-special--text {}
        
        .ruc-special {
            .swiper-slide {
                /* height: auto!important; */
            }
            &.special-font-sansserif * {
                font-family: Arial,Helvetica,sans-serif!important;
            }

            &.special-font-serif * {
                font-family: "Times New Roman",Times,serif!important;
            }

            &.special-font-mono * {
                font-family: "Courier New",Courier,monospace!important;
            }

            &.special-kerning-solid * {

            }

            &.special-kerning-normal * {
                letter-spacing:0.15em!important;
            }

            &.special-kerning-copy * {
                letter-spacing:0.3em!important;
            }

            &.special-interval-solid * {
                
            }

            &.special-interval-normal * {
                line-height:1.5!important;
            }

            &.special-interval-copy * {
                line-height:2!important;
            }

            &.special-color-white {
                *:not([data-bg], [data-wrapper], ymaps, special),
                *::placeholder,
                *::after,
                *::before {
                    background-image: none;
                    background-color: #FFF!important;
                    border-color: #000!important;
                    color: #000!important;
                    text-decoration-color: #000!important;
                }

                .bc-t, [data-wrapper] {background-color: transparent!important}

                [class*="hover:bc-"] {
                    * {
                        background-color: transparent!important;
                    }
                }

                [class*="hover:bc-"]:hover, .bx-calendar-active {
                    background-color:#000!important;
                    color:#FFF!important;

                    * {
                        background-color: transparent!important;
                        color:#FFF!important;
                    }
                }
            }

            &.special-color-black {
                *:not([data-bg], [data-wrapper], ymaps, special),
                *::placeholder,
                *::after,
                *::before {
                    background-image: none;
                    background-color: #000!important;
                    border-color: #FFF!important;
                    color: #FFF!important;
                    text-decoration-color:#FFF!important;
                }

                .bc-t, [data-wrapper] {background-color: transparent!important}

                [class*="hover:bc-"] {
                    * {
                        background-color: transparent!important;
                    }
                }

                [class*="hover:bc-"]:hover, .bx-calendar-active {
                    background-color:#FFF!important;
                    color:#000!important;

                    * {
                        background-color: transparent!important;
                        color:#000!important;
                    }
                }
            }

            &.special-color-yellow {
                *:not([data-bg], [data-wrapper], ymaps, special),
                *::placeholder,
                *::after,
                *::before {
                    background-image:none;
                    background-color:#ff0!important;
                    border-color:#000!important;
                    color:#000!important;
                    text-decoration-color:#000!important;
                }

                .bc-t, [data-wrapper] {background-color: transparent!important}

                [class*="hover:bc-"] {
                    * {
                        background-color: transparent!important;
                    }
                }

                [class*="hover:bc-"]:hover, .bx-calendar-active {
                    background-color:#000!important;
                    color:#FFF!important;

                    * {
                        background-color: transparent!important;
                        color:#FFF!important;
                    }
                }
            }

            &.special-color-blue {
                *:not([data-bg], [data-wrapper], ymaps, special),
                *::placeholder,
                *::after,
                *::before {
                    background-image:none;
                    background-color:#9FD7FF!important;
                    border-color:#000!important;
                    color:#000!important;
                    text-decoration-color:#000!important;
                }

                .bc-t, [data-wrapper] {background-color: transparent!important}

                [class*="hover:bc-"] {
                    * {
                        background-color: transparent!important;
                    }
                }

                [class*="hover:bc-"]:hover, .bx-calendar-active {
                    background-color:#000!important;
                    color:#FFF!important;

                    * {
                        background-color: transparent!important;
                        color:#FFF!important;
                    }
                }
            }

            &.special-color-green {
                *:not([data-bg], [data-wrapper], ymaps, special),
                *::placeholder,
                *::after,
                *::before {
                    background-image:none;
                    background-color:#000!important;
                    border-color:#A9DD38!important;
                    color:#A9DD38!important;
                    text-decoration-color:#A9DD38!important;
                }

                .bc-t, [data-wrapper] {background-color: transparent!important}

                [class*="hover:bc-"] {
                    * {
                        background-color: transparent!important;
                    }
                }

                [class*="hover:bc-"]:hover, .bx-calendar-active {
                    background-color:#A9DD38!important;
                    color:#000!important;

                    * {
                        background-color: transparent!important;
                        color:#000!important;
                    }
                }
                
            }

            &.special-image-off {
                [data-bg], img, svg:not([data-nospecial]), ymaps {
                    border: 1px dashed!important;
                    border-color: inherit!important;
                }

                [data-bg] {
                    background-image: none!important;
                    background: none!important;
                }

                img, svg:not([data-nospecial]) *, ymaps * {
                    opacity: 0!important;
                }
            }

            &.special-image-blackWhite {
                [data-bg], img, svg:not([data-nospecial]), ymaps {
                    filter: grayscale(1)!important;
                }
            }
            
            special.lang-special {
                background-color:#FFF!important;
                color:#000!important;
                padding-top: 16px;
                padding-bottom: 16px;

                .lang-special--block button {
                    color: #000!important;
                    padding-top: 12px;
                    padding-right: 16px;
                    padding-bottom: 12px;
                    padding-left: 16px;
                }

                * {
                    font-family: "Gotham", sans-serif!important;
                    font-size: 16px!important;
                    letter-spacing:normal!important;
                    line-height:1!important;
                }

                .b-EBEBEB {
                    border-color: #EBEBEB!important;
                }

                .bc-EBEBEB {
                    background-color: #EBEBEB!important;
                }
                
                .hover\:bc-EBEBEB:hover {
                    background-color: #EBEBEB!important;
                }

                .special-white {
                    background-color: #FFF!important;
                    border-color:#EBEBEB!important;
                    color: #000!important;
                }

                button.special-black {
                    background-color: #000!important;
                    border-color:#000!important;
                    color: #FFF!important;
                }

                .special-black:hover {
                    background-color: #EBEBEB!important;
                    border-color:#EBEBEB!important;
                    color: #000!important;
                }

                .special-yellow {
                    background-color: rgb(255,255,0)!important;
                    border-color:rgb(255,255,0)!important;
                    color: #000!important;
                }

                .special-blue {
                    background-color: rgb(159, 215, 255)!important;
                    border-color:rgb(159, 215, 255)!important;
                    color: #000!important;
                }
                
                .special-green {
                    background-color:rgb(169, 221, 56)!important;
                    border-color:rgb(169, 221, 56)!important;
                    color: #000!important;
                }

                button.panel-reset {
                    background-color: #FFF!important;
                    color: #000!important;
                    
                    svg, path {
                        border: none!important;
                        background-color: #0000!important;
                        color: inherit!important;
                        opacity: 1!important;
                    }
                }
            }
        }
    </style>
    <special class="lang-special w-100 bbw-1 bbs-s bbc-EBEBEB pt-16 pb-16 pn-r pn-a t--100% tn-all-3">
        <special class="lang-special--container w-100 pr-16 pl-16 mr-ao ml-ao d-f fw-w jc-c ai-s g-16">

            <special class="lang-special--block font-block d-f fd-c g-16 ai-c jc-c">
                <special class="lang-special--title ta-c"><?=Loc::getMessage("LANG_SPECIAL_FONT")?></special>
                <special class="lang-special--button button-font d-f">
                    <button class="lang-special--font font-arial bc-t m-0 btw-1 brw-0 bbw-1 blw-1 bts-s bbs-s bls-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c btlr-16 blr-16 tn-all-3"
                        data-value="sansserif"
                        data-text="<?=Loc::getMessage("LANG_SPECIAL_FONT_SANSSERIF")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_FONT_ARIAL_TITLE")?>">Arial</button>
                    <button class="lang-special--font font-times bc-t m-0 bw-1 bs-s bcr-EBEBEB hover:bc-EBEBEB pt-6 pr-16 pb-6 pl-16 lh-1 ta-c d-f ai-c jc-c tn-all-3"
                        data-value="serif"
                        data-text="<?=Loc::getMessage("LANG_SPECIAL_FONT_SERIF")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_FONT_TIMES_TITLE")?>">Times</button>
                    <button class="lang-special--font font-courier bc-t m-0 btw-1 brw-1 bbw-1 blw-0 bts-s bbs-s brs-s bcr-EBEBEB hover:bc-EBEBEB pt-6 pr-16 pb-6 pl-16 lh-1 ta-c d-f ai-c jc-c btrr-16 brr-16 tn-all-3"
                        data-value="mono"
                        data-text="<?=Loc::getMessage("LANG_SPECIAL_FONT_MONO")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_FONT_COURIER_TITLE")?>">Courier</button>
                </special>
                <special class="lang-special--text font-text ta-c"><?=Loc::getMessage("LANG_SPECIAL_FONT_STANDART")?></special>
            </special>

            <special class="lang-special--block fontSize-block d-f fd-c g-16 ai-c jc-c">
                <special class="lang-special--title ta-c"><?=Loc::getMessage("LANG_SPECIAL_FONTSIZE")?></special>
                <special class="lang-special--button d-f">
                    <button class="lang-special--fontSize fontSize-minus bc-t m-0 btw-1 brw-1 bbw-1 blw-1 bts-s bbs-s bls-s brs-s bcr-EBEBEB hover:bc-EBEBEB pt-6 pr-16 pb-6 pl-16 lh-1 ta-c d-f ai-c jc-c btlr-16 blr-16 tn-all-3"
                        data-text=""
                        title="<?=Loc::getMessage("LANG_SPECIAL_FONTSIZE_MINUS")?>">A-</button>
                    <button class="lang-special--fontSize fontSize-plus bc-t m-0 btw-1 brw-1 bbw-1 blw-0 bts-s bbs-s brs-s bcr-EBEBEB hover:bc-EBEBEB pt-6 pr-16 pb-6 pl-16 lh-1 ta-c d-f ai-c jc-c btrr-16 brr-16 tn-all-3"
                        data-text=""
                        title="<?=Loc::getMessage("LANG_SPECIAL_FONTSIZE_PLUS")?>">A+</button>
                </special>
                <special class="lang-special--text ta-c">16 <?=Loc::getMessage("LANG_SPECIAL_FONTSIZE_TEXT")?></special>
            </special>

            <special class="lang-special--block kerning-block d-f fd-c g-16 ai-c jc-c">
                <special class="lang-special--title ta-c"><?=Loc::getMessage("LANG_SPECIAL_KERNING")?></special>
                <special class="lang-special--button d-f">
                    <button class="lang-special--kerning kerning-solid bc-t m-0 btw-1 brw-0 bbw-1 blw-1 btw-sd bbs-s bls-s bcr-EBEBEB hover:bc-EBEBEB pt-6 pr-16 pb-6 pl-16 lh-1 ta-c d-f ai-c jc-c btlr-16 blr-16 tn-all-3"
                        data-value="solid" data-text="<?=Loc::getMessage("LANG_SPECIAL_KERNING_NORMAL_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_KERNING_NORMAL_TITLE")?>">1.0</button>
                    <button class="lang-special--kerning kerning-normal bc-t m-0 bw-1 bs-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c tn-all-3"
                        data-value="normal" data-text="<?=Loc::getMessage("LANG_SPECIAL_KERNING_MIDDLE_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_KERNING_MIDDLE_TITLE")?>">1.5</button>
                    <button class="lang-special--kerning kerning-copy bc-t m-0 btw-1 brw-1 bbw-1 blw-0 bts-s bbs-s brs-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c btrr-16 brr-16 tn-all-3"
                        data-value="copy" data-text="<?=Loc::getMessage("LANG_SPECIAL_KERNING_BIG_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_KERNING_BIG_TITLE")?>">2.0</button>
                </special>
                <special class="lang-special--text ta-c"><?=Loc::getMessage("LANG_SPECIAL_KERNING_NORMAL")?></special>
            </special>

            <special class="lang-special--block interval-block d-f fd-c g-16 ai-c jc-c">
                <special class="lang-special--title ta-c"><?=Loc::getMessage("LANG_SPECIAL_INTERVAL")?></special>
                <special class="lang-special--button d-f">
                    <button class="lang-special--interval interval-solid bc-t m-0 btw-1 brw-0 bbw-1 blw-1 bts-s bbs-s bls-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c btlr-16 blr-16 tn-all-3"
                        data-value="solid" data-text="<?=Loc::getMessage("LANG_SPECIAL_INTERVAL_NORMAL_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_INTERVAL_NORMAL_TITLE")?>">1.0</button>
                    <button class="lang-special--interval interval-normal bc-t m-0 bw-1 bs-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c tn-all-3"
                        data-value="normal" data-text="<?=Loc::getMessage("LANG_SPECIAL_INTERVAL_MIDDLE_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_INTERVAL_MIDDLE_TITLE")?>">1.5</button>
                    <button class="lang-special--interval interval-copy bc-t m-0 btw-1 brw-1 bbw-1 blw-0 bts-s bbs-s brs-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c btrr-16 brr-16 tn-all-3"
                        data-value="copy" data-text="<?=Loc::getMessage("LANG_SPECIAL_INTERVAL_BIG_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_INTERVAL_BIG_TITLE")?>">2.0</button>
                </special>
                <special class="lang-special--text ta-c"><?=Loc::getMessage("LANG_SPECIAL_INTERVAL_NORMAL")?></special>
            </special>

            <special class="lang-special--block color-block d-f fd-c g-16 ai-c jc-c">
                <special class="lang-special--title ta-c"><?=Loc::getMessage("LANG_SPECIAL_COLOR")?></special>
                <special class="lang-special--button d-f">
                    <button class="lang-special--color special-white bc-t m-0 btw-1 brw-0 bbw-1 blw-1 bts-s bbs-s bls-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c btlr-16 blr-16 tn-all-3"
                        data-value="white" data-text="<?=Loc::getMessage("LANG_SPECIAL_COLOR_WHITE_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_COLOR_WHITE_TITLE")?>">C</button>
                    <button class="lang-special--color special-black bc-t m-0 btw-1 brw-1 bbw-1 blw-1 bs-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c tn-all-3"
                        data-value="black" data-text="<?=Loc::getMessage("LANG_SPECIAL_COLOR_BLACK_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_COLOR_BLACK_TITLE")?>">C</button>
                    <button class="lang-special--color special-yellow bc-t m-0 btw-1 brw-1 bbw-1 blw-0 bts-s brs-s bbs-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c tn-all-3"
                        data-value="yellow" data-text="<?=Loc::getMessage("LANG_SPECIAL_COLOR_YELLOW_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_COLOR_YELLOW_TITLE")?>">C</button>
                    <button class="lang-special--color special-blue bc-t m-0 btw-1 brw-1 bbw-1 blw-0 bts-s brs-s bbs-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c tn-all-3"
                        data-value="blue" data-text="<?=Loc::getMessage("LANG_SPECIAL_COLOR_BLUE_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_COLOR_BLUE_TITLE")?>">C</button>
                    <button class="lang-special--color special-green bc-t m-0 btw-1 brw-1 bbw-1 blw-0 bts-s bbs-s brs-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c btrr-16 brr-16 tn-all-3"
                        data-value="green" data-text="<?=Loc::getMessage("LANG_SPECIAL_COLOR_GREEN_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_COLOR_GREEN_TITLE")?>">C</button>
                </special>
                <special class="lang-special--text ta-c"><?=Loc::getMessage("LANG_SPECIAL_COLOR_WHITE")?></special>
            </special>

            <special class="lang-special--block image-block d-f fd-c g-16 ai-c jc-c">
                <special class="lang-special--title ta-c"><?=Loc::getMessage("LANG_SPECIAL_IMAGE")?></special>
                <special class="lang-special--button d-f">
                    <button class="lang-special--image image-off bc-t m-0 btw-1 brw-0 bbw-1 blw-1 bts-s bbs-s bls-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c btlr-16 blr-16 tn-all-3"
                        data-value="off" data-text="<?=Loc::getMessage("LANG_SPECIAL_IMAGE_OFF_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_IMAGE_OFF_TITLE")?>"><?=Loc::getMessage("LANG_SPECIAL_IMAGE_OFF")?></button>
                    <button class="lang-special--image image-blackWhite bc-t m-0 bw-1 bs-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c tn-all-3"
                        data-value="blackWhite" data-text="<?=Loc::getMessage("LANG_SPECIAL_IMAGE_BLACK_WHITE_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_IMAGE_BLACK_WHITE_TITLE")?>"><?=Loc::getMessage("LANG_SPECIAL_IMAGE_BLACK_WHITE")?></button>
                    <button class="lang-special--image image-color bc-t m-0 btw-1 brw-1 bbw-1 blw-0 bts-s bbs-s brs-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c btrr-16 brr-16 tn-all-3"
                        data-value="color" data-text="<?=Loc::getMessage("LANG_SPECIAL_IMAGE_COLOR_TITLE")?>"
                        title="<?=Loc::getMessage("LANG_SPECIAL_IMAGE_COLOR_TITLE")?>"><?=Loc::getMessage("LANG_SPECIAL_IMAGE_COLOR")?></button>
                </special>
                <special class="lang-special--text ta-c"><?=Loc::getMessage("LANG_SPECIAL_IMAGE_COLOR_TEXT")?></special>
            </special>

            <special class="lang-special--block panel-block d-f fd-c g-16 jc-c">
                <special class="lang-special--title ta-c"><?=Loc::getMessage("LANG_SPECIAL_PANEL")?></special>
                <special class="lang-special--button d-f">
                    <button class="lang-special--panel panel-reset bc-t m-0 btw-1 brw-1 bbw-1 blw-1 bts-s bbs-s bls-s brs-s bcr-EBEBEB hover:bc-EBEBEB pt-16 pr-16 pb-16 pl-16 lh-1 ta-c d-f ai-c jc-c btlr-8 blr-8 tn-all-3"
                        title="<?=Loc::getMessage("LANG_SPECIAL_PANEL_REFRESH_TITLE")?>">
                        <svg width="24" height="16" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 576 512"><path d="M272 416c17.7 0 32-14.3 32-32s-14.3-32-32-32H160c-17.7 0-32-14.3-32-32V192h32c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-64-64c-12.5-12.5-32.8-12.5-45.3 0l-64 64c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8l32 0 0 128c0 53 43 96 96 96H272zM304 96c-17.7 0-32 14.3-32 32s14.3 32 32 32l112 0c17.7 0 32 14.3 32 32l0 128H416c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l64 64c12.5 12.5 32.8 12.5 45.3 0l64-64c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8l-32 0V192c0-53-43-96-96-96L304 96z"/></svg>
                    </button>
                    <button class="lang-special--panel panel-off bc-t m-0 btw-1 brw-1 bbw-1 blw-0 bts-s bbs-s brs-s bcr-EBEBEB hover:bc-EBEBEB pt-12 pr-16 pb-12 pl-16 lh-1 ta-c d-f ai-c jc-c btrr-16 brr-16 tn-all-3"
                        title="<?=Loc::getMessage("LANG_SPECIAL_PANEL_OFF_TITLE")?>"><?=Loc::getMessage("LANG_SPECIAL_PANEL_OFF")?></button>
                </special>
            </special>

        </special>
    </special>

    <script>
        LG.Special = () => {

            this.getCookie = (name) => {
                let matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));

                return matches ? decodeURIComponent(matches[1]) : undefined;
            }

            this.setCookie = (name, value, options = {}) => {
                options = {
                    path: '/',
                    ...options
                }

                if (options.expires instanceof Date)
                {
                    options.expires = options.expires.toUTCString();
                }

                let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

                for (let optionKey in options)
                {
                    let optionValue = options[optionKey];

                    updatedCookie += "; " + optionKey;

                    if (optionValue !== true)
                    {
                        updatedCookie += "=" + optionValue;
                    }
                }

                document.cookie = updatedCookie;
            }

            let _t = this;

            this.fontDefault = 'default';
            this.fontSizeDefault = 16;
            this.kerningDefault = 'solid';
            this.intervalDefault = 'solid';
            this.imageDefault = 'color';
            this.fontSizeMin = 14;
            this.fontSizeMax = 24;

            this.fontCurrent = this.getCookie('specialfont');
            this.fontSizeCurrent = Number(this.getCookie('specialfontsize') ? this.getCookie('specialfontsize') : this.fontSizeDefault);
            this.kerningCurrent = this.getCookie('specialkerning') ? this.getCookie('specialkerning') : 'solid';
            this.intervalCurrent = this.getCookie('specialinterval') ? this.getCookie('specialinterval') : 'solid';
            this.colorCurrent = this.getCookie('specialcolor');
            this.imageCurrent = this.getCookie('specialimage') ? this.getCookie('specialimage') : 'color';
            this.panelCurrent = this.getCookie('specialpanel');

            this.header = document.querySelector('header');

            this.panel = document.querySelector('.lang-special');
            this.fontBlock = document.querySelector('.lang-special--block.font-block');
            this.fontText = this.fontBlock.querySelector('.lang-special--text');

            this.fontSizeBlock = document.querySelector('.lang-special--block.fontSize-block');
            this.fontSizeText = this.fontSizeBlock.querySelector('.lang-special--text');

            this.kerningBlock = document.querySelector('.lang-special--block.kerning-block');
            this.kerningText = this.kerningBlock.querySelector('.lang-special--text');

            this.intervalBlock = document.querySelector('.lang-special--block.interval-block');
            this.intervalText = this.intervalBlock.querySelector('.lang-special--text');

            this.colorBlock = document.querySelector('.lang-special--block.color-block');
            this.colorText = this.colorBlock.querySelector('.lang-special--text');

            this.imageBlock = document.querySelector('.lang-special--block.image-block');
            this.imageText = this.imageBlock.querySelector('.lang-special--text');

            this.panelBlock = document.querySelector('.lang-special--block.panel-block');

            this.setFont = (font) => {
                this.fontBlock.querySelectorAll('[data-value]').forEach(el => {
                    el.classList.remove('bc-EBEBEB');
                });
                document.documentElement.classList.remove('special-font-sansserif', 'special-font-serif', 'special-font-mono');

                if(font)
                {
                    let selectFont = document.querySelector(`[data-value="${font}"]`);
                    document.documentElement.classList.add(`special-font-${font}`);
                    selectFont.classList.add('bc-EBEBEB');
                    this.fontText.innerText = selectFont.getAttribute('data-text');
                }
            }
            
            this.setFontFull = (font) => {
                this.setCookie('specialfont', font);
                this.setFont(font);
                this.fontCurrent = font;
            }

            this.setFontPanel = () => {
                let btn = this.fontBlock.querySelectorAll('.lang-special--font');

                btn.forEach(el => {
                    el.addEventListener('click', this.setFontFull.bind(this, el.getAttribute('data-value')));
                })
            }

            this.setFontSizeDocument = () => {
                document.documentElement.style.fontSize = `${this.fontSizeCurrent}px`;
                this.fontSizeText.innerText = `${this.fontSizeCurrent} <?=Loc::getMessage("LANG_SPECIAL_FONTSIZE_TEXT")?>`;
            }

            this.setFontSizePlus = () => {
                if(this.fontSizeMax > this.fontSizeCurrent)
                {
                    this.fontSizeCurrent++;
                    this.setFontSizeDocument();
                    this.setCookie('specialfontsize', this.fontSizeCurrent);
                }
            }

            this.setFontSizeMinus = () => {
                if(this.fontSizeMin < this.fontSizeCurrent)
                {
                    this.fontSizeCurrent--;
                    this.setFontSizeDocument();
                    this.setCookie('specialfontsize', this.fontSizeCurrent);
                }
            }

            this.setFontSize = () => {
                let btnMinus = this.fontSizeBlock.querySelector('.fontSize-minus');
                let btnPlus = this.fontSizeBlock.querySelector('.fontSize-plus');

                btnMinus.addEventListener('click', this.setFontSizeMinus.bind(this));
                btnPlus.addEventListener('click', this.setFontSizePlus.bind(this));
            }

            this.setKerningDocument = (kerning) => {
                this.kerningBlock.querySelectorAll('[data-value]').forEach(el => {
                    el.classList.remove('bc-EBEBEB');
                });
                document.documentElement.classList.remove('special-kerning-solid', 'special-kerning-normal', 'special-kerning-copy');

                if(kerning)
                {
                    let selectKerning = this.kerningBlock.querySelector(`[data-value="${kerning}"]`);
                    document.documentElement.classList.add(`special-kerning-${kerning}`);
                    selectKerning.classList.add('bc-EBEBEB');
                    this.kerningText.innerText = selectKerning.getAttribute('data-text');
                }
            }

            this.setKerningFull = (kerning) => {
                this.setCookie('specialkerning', kerning);
                this.setKerningDocument(kerning);
                this.kerningCurrent = kerning;
            }

            this.setKerning = () => {
                let btn = this.kerningBlock.querySelectorAll('.lang-special--kerning');

                btn.forEach(el => {
                    el.addEventListener('click', this.setKerningFull.bind(this, el.getAttribute('data-value')))
                });
            }

            this.setIntervalDocument = (interval) => {
                this.intervalBlock.querySelectorAll('[data-value]').forEach(el => {
                    el.classList.remove('bc-EBEBEB');
                });
                document.documentElement.classList.remove('special-interval-solid', 'special-interval-normal', 'special-interval-copy');

                if(interval)
                {
                    let selectInterval = this.intervalBlock.querySelector(`[data-value="${interval}"]`);
                    document.documentElement.classList.add(`special-interval-${interval}`);
                    selectInterval.classList.add('bc-EBEBEB');
                    this.intervalText.innerText = selectInterval.getAttribute('data-text');
                }
            }

            this.setIntervalFull = (interval) => {
                this.setCookie('specialinterval', interval);
                this.setIntervalDocument(interval);
                this.intervalCurrent = interval;
            }

            this.setInterval = () => {
                let btn = this.intervalBlock.querySelectorAll('.lang-special--interval');

                btn.forEach(el => {
                    el.addEventListener('click', this.setIntervalFull.bind(this, el.getAttribute('data-value')))
                });
            }

            this.setColorDocument = (color) => {
                this.colorBlock.querySelectorAll('[data-value]').forEach(el => {
                    el.classList.remove('bc-EBEBEB');
                });
                document.documentElement.classList.remove('special-color-white', 'special-color-black', 'special-color-yellow', 'special-color-blue', 'special-color-green');

                if(color)
                {
                    let selectColor = this.colorBlock.querySelector(`[data-value="${color}"]`);
                    document.documentElement.classList.add(`special-color-${color}`);
                    selectColor.classList.add('bc-EBEBEB');
                    this.colorText.innerText = selectColor.getAttribute('data-text');
                }
            }

            this.setColorFull = (color) => {
                this.setCookie('specialcolor', color);
                this.setColorDocument(color);
                this.colorCurrent = color;
            }

            this.setColor = () => {
                let btn = this.colorBlock.querySelectorAll('.lang-special--color');
                
                btn.forEach(el => {
                    el.addEventListener('click', this.setColorFull.bind(this, el.getAttribute('data-value')))
                });
            }

            this.setImageDocument = (image) => {
                this.imageBlock.querySelectorAll('[data-value]').forEach(el => {
                    el.classList.remove('bc-EBEBEB');
                });
                document.documentElement.classList.remove('special-image-off', 'special-image-blackWhite', 'special-image-color');

                if(image)
                {
                    let selectImage = this.imageBlock.querySelector(`[data-value="${image}"]`);
                    document.documentElement.classList.add(`special-image-${image}`);
                    selectImage.classList.add('bc-EBEBEB');
                    this.imageText.innerText = selectImage.getAttribute('data-text');
                }
            }

            this.setImageFull = (image) => {
                this.setCookie('specialimage', image);
                this.setImageDocument(image);
                this.imageCurrent = image;
            }

            this.setImage = () => {
                let btn = this.imageBlock.querySelectorAll('.lang-special--image');

                btn.forEach(el => {
                    el.addEventListener('click', this.setImageFull.bind(this, el.getAttribute('data-value')))
                });
            }

            this.resetFake = () => {
                document.documentElement.classList.remove('special-font-sansserif', 'special-font-serif', 'special-font-mono');
                this.fontBlock.querySelectorAll('[data-value]').forEach(el => {el.classList.remove('bc-EBEBEB')});

                document.documentElement.style.fontSize = `${this.fontSizeDefault}px`;

                document.documentElement.classList.remove('special-kerning-solid', 'special-kerning-normal', 'special-kerning-copy');
                this.kerningBlock.querySelectorAll('[data-value]').forEach(el => {el.classList.remove('bc-EBEBEB')});

                document.documentElement.classList.remove('special-interval-solid', 'special-interval-normal', 'special-interval-copy');
                this.intervalBlock.querySelectorAll('[data-value]').forEach(el => {el.classList.remove('bc-EBEBEB')});

                document.documentElement.classList.remove('special-color-white', 'special-color-black', 'special-color-yellow', 'special-color-blue', 'special-color-green');
                this.colorBlock.querySelectorAll('[data-value]').forEach(el => {el.classList.remove('bc-EBEBEB')});

                document.documentElement.classList.remove('special-image-off', 'special-image-blackWhite', 'special-image-color');
                this.imageBlock.querySelectorAll('[data-value]').forEach(el => {el.classList.remove('bc-EBEBEB')});
            }

            this.reset = () => {
                this.resetFake();
                this.fontCurrent = '';
                this.setCookie('specialfont', '');
                this.setCookie('specialfontsize', '');
                this.setCookie('specialkerning', '');
                this.setCookie('specialinterval', '');
                this.setCookie('specialcolor', '');
                this.setCookie('specialimage', '');

                this.setKerningDocument(this.kerningDefault);
                this.setIntervalDocument(this.intervalDefault);
                this.setImageDocument(this.imageDefault);

                this.fontText.innerText = '<?=Loc::getMessage("LANG_SPECIAL_FONT_STANDART")?>';
                this.fontSizeText.innerText = `${this.fontSizeDefault} <?=Loc::getMessage("LANG_SPECIAL_FONTSIZE_TEXT")?>`;
                this.kerningText.innerText = `<?=Loc::getMessage("LANG_SPECIAL_KERNING_NORMAL")?>`;
                this.intervalText.innerText = `<?=Loc::getMessage("LANG_SPECIAL_INTERVAL_NORMAL")?>`;
                this.colorText.innerText = `<?=Loc::getMessage("LANG_SPECIAL_COLOR_WHITE")?>`;
                this.imageText.innerText = `<?=Loc::getMessage("LANG_SPECIAL_IMAGE_COLOR_TEXT")?>`;
            }

            this.resetPanel = () => {
                let btn = this.panelBlock.querySelector('.panel-reset');

                btn.addEventListener('click', this.reset.bind(this));
            }

            this.offPanel = () => {
                this.resetFake();
                this.panel.style.marginTop = `-${this.panel.clientHeight+5}px`;
                setTimeout(() => {
                    this.panel.classList.add('pn-a');
                }, 300);

                if(this.header.classList.contains('lang-ipage'))
                {
                    this.header.classList.add('pn-f');
                    this.header.classList.remove('pn-sy');
                }
            }

            this.onPanel = () => {
                this.panel.classList.remove('pn-a');
                this.panel.style.marginTop = `0`;
                setTimeout(() => {
                    if(this.header.classList.contains('lang-ipage'))
                    {
                        this.header.classList.remove('pn-f');
                        this.header.classList.add('pn-sy');
                    }
                }, 300)
            }

            this.togglePanel = (cookie=true) => {
                if(this.getCookie('specialon') == 'n')
                {
                    this.onPanel();
                    this.setCookie('specialon', 'y');
                    this.setFont(this.fontCurrent);
                    this.setFontSizeDocument();
                    this.setKerningDocument(this.kerningCurrent);
                    this.setIntervalDocument(this.intervalCurrent);
                    this.setColorDocument(this.colorCurrent);
                    this.setImageDocument(this.imageCurrent);
                    window.scrollTo(0, 0);
                }
                else
                {
                    this.offPanel();
                    this.setCookie('specialon', 'n');
                }
            }

            this.btnOffPanel = () => {
                document.querySelector('.lang-special--panel.panel-off').addEventListener('click', this.togglePanel.bind(this));
            }

            this.init = () => {
                this.setFontPanel();
                this.setFontSize();
                this.setKerning();
                this.setInterval();
                this.setColor();
                this.setImage();
                this.resetPanel();
                this.btnOffPanel();

                if(this.getCookie('specialon') == 'y')
                {
                    this.onPanel();
                    this.setFont(this.fontCurrent);
                    this.setFontSizeDocument();
                    this.setKerningDocument(this.kerningCurrent);
                    this.setIntervalDocument(this.intervalCurrent);
                    this.setColorDocument(this.colorCurrent);
                    this.setImageDocument(this.imageCurrent);
                }
                else
                {
                    this.offPanel();
                }

                document.querySelectorAll('.lang-ver--block').forEach((btn) => {
                    btn.addEventListener('click', this.togglePanel.bind(this));
                });
            }
            
            this.init();
        }

        document.addEventListener('DOMContentLoaded', () => {
            LG.Special()
        });
    </script>
<?//endif;?>