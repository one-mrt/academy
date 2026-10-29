<?
include_once("{$_SERVER['DOCUMENT_ROOT']}/bitrix/modules/main/include/routing_index.php");
$template404 = "/local/templates/lang.css/page/404.php";
if(file_exists("{$_SERVER['DOCUMENT_ROOT']}{$template404}"))
{
	include_once("{$_SERVER['DOCUMENT_ROOT']}{$template404}");
}
?>