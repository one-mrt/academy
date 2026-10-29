<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/routing_index.php');
CHTTP::SetStatus("404 Not Found");
define("ERROR_404", "Y");
require("{$_SERVER["DOCUMENT_ROOT"]}/bitrix/header.php");
$APPLICATION->SetTitle("404 Not Found");

use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?>

<div class="w-100 h-100 fg-1 d-f fd-c jc-c ai-c xl:pt-16">
	<div class="<?=comp::get("container")?> d-f fd-c fg-1">
        <div class="w-100 pt-64 pb-64 r-20 bc-w d-f ai-c fg-1">
            <div class="w-100 d-f ai-e jc-c cg-8 ff-ot">
                <h1 class="ftw-4 ta-c md:ta-l mb-0 f-64 md:f-95 lh-40px md:lh-70px">404</h1>
                <h2 class="mb-0 ta-c md:ta-l lh-1 f-14 ftw-4">Страница не найдена.</h2>
            </div>
        </div>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>