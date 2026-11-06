<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $USER;
global $APPLICATION;

use Bitrix\Main\Application;

$pathCss = "{$_SERVER['DOCUMENT_ROOT']}/local/templates/lang.css";
$request = Application::getInstance()->getContext()->getRequest();
?>

<?
$fileStyle = "{$pathCss}/css/css.min.css";

if($USER->IsAdmin() && $_GET['langUpdate'] == 'y'):?>
    <script data-skip-moving="" src="https://modcss.ru/local/templates/lang.css/js/lang.css.js"></script>
    <script>
        LG.CSS({
            apikey: "f1d6f386-014E-44ae-8b7219ABfe8b82fc-72781898",
            site: "acmhelp.ru",
            pathSaveStyle: "/local/templates/lang.css/php.css/save/",
            modifier: `pn-f t-0 rt--200% b-0 md:pn-r md:rt-0 tp-rt tnd-500ms tm-r45
                tm-r180 sm:mw-480px md:p-64 bc-EDF0F9 c-f00 pix-8 pby-8 pix-16 mi--8 h:bi-ggnf
                mxh-200px pt-52 bc-C5C5C5 ofy-a p-48 p-64 sm:p-64 mi-16 sm:f-14 sm:f-16
                lg:f-10 bc-c90536 fv:ow-0 fv:os-n bcr-f5f6f6 h:bi-n h:bc-F4F4FD bcr-F4F4FD z-4 bbc-525c6914`,
            breakpoint: {
                sm: 576,
                md: 768,
                lg: 992,
                xl: 1200,
                xxl: 1400
            },
            property: {
                'transition': {
                    'all-3': 'all 0.3S',
                },
                'color': {
                    'c90536': "#c90536",
                    '05C936': "#05c936",
                    'py': '#724a98',
                    'dt': '#000',
                    // '527CC8': '#527CC8',
                },
                'background-color': {
                    'c90536': "#c90536",
                    'py': '#724A98',
                    'dt': '#000'
                },
                'border-color': {
                    'py': '#724A98',
                    'dt': '#000',
                    'c5c5c566': '#c5c5c566',
                },
                'text-decoration-color': {
                    'py': '#724A98',
                    'dt': '#000',
                },
                'accent-color': {
                    'py': '#724a98',
                },
                'border-bottom-color': {},
                'border-left-color': {},
                'fill': {},
                "background-image": {
                    "load": "url('/local/templates/lang.css/img/bg.load.animation.svg')",
                    "pyg": "linear-gradient(90deg, #A16ABB 0%, #724A98 100%)",
                    'syt': 'linear-gradient(0deg, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), linear-gradient(90deg, #3A70D2 0%, #95B5E1 100%)',
                    'ggn': 'linear-gradient(90deg, #5DDCC4 0%, #21A78E 63.03%)',
                    'ggnf': 'linear-gradient(90deg, #21A78E 0%, #21A78E 63.03%)',
                    'gbe': 'linear-gradient(90deg, #6A8FC3 0%, #527CC8 100%)',
                    'gbef': 'linear-gradient(90deg, #527CC8 0%, #527CC8 100%)',
                    'sc': 'linear-gradient(0deg, #AA66CA, #AA66CA)',
                    'st': 'linear-gradient(90deg, #AA66CA 0%, #6D30A7 100%)',
                    'stf': 'linear-gradient(90deg, #6D30A7 0%, #6D30A7 100%)',
                    'str': 'linear-gradient(90deg, #6D30A7 0%, #AA66CA 100%)',
                    'sb': 'linear-gradient(90deg, #527CC8 0%, #3F4791 100%)',
                    'sbb': 'linear-gradient(0deg, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), linear-gradient(90deg, #527CC8 0%, #6A8FC3 100%)',
                    'sbbf': 'linear-gradient(0deg, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), linear-gradient(90deg, #6A8FC3 0%, #6A8FC3 100%)',
                    'as': 'linear-gradient(90.68deg, #046877 7.93%, #006F97 98.79%)',
                    'ft': 'linear-gradient(90deg, #527CC8 0%, #724A98 100%)',
                    'mbn': 'linear-gradient(90deg, #43EACA 0%, #527CC8 67.31%, #0C4DA8 100%)',
                    'bgb': 'linear-gradient(29.41deg, #724A98 17.01%, #A16ABB 65.02%)',
                    'hb': 'linear-gradient(0deg, #724A98, #724A98)',
                },
                'box-shadow': {
                    '2': '0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23)',
                    '3': '0 12px 24px rgb(0 0 0 / 7%), 0 6px 12px rgb(0 0 0 / 14%)',
                    'py': '0 12px 24px #DD445A12, 0 6px 12px #DD445A24',
                    'au': '0 16px 32px rgb(114 74 152 / 12%), 0 12px 24px rgb(114 74 152 / 22%)'
                },
                'font-family': {
                    'mn': '"Montserrat", sans-serif',
                    'ud': '"Unbounded", sans-serif'
                },
                'transition-property': {
                    'h': 'height',
                    'oy': 'opacity',
                }
            }
        });
    </script>
<?endif;?>

<style>
    <?
    // include "{$pathCss}/php.css/font/manrope.php";
    include "{$pathCss}/php.css/font/unbounded.php";
    include "{$pathCss}/php.css/font/montserrat.php";
    echo "@layer oldstyle,default,component,modifier;@layer default {";
    include "{$pathCss}/php.css/reboot.php";
    echo "}";
    ?>
</style>
<style lang-css=""><?=file_exists($fileStyle) ? file_get_contents($fileStyle) : ""?></style>

<style>@layer tag {<?=file_get_contents("{$pathCss}/css/swiper.min.css");?>}</style>