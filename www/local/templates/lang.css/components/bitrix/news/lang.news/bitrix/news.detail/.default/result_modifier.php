<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


// prev next links
$this->__component->SetResultCacheKeys(array(
    "DETAIL_PAGE_URL",
    "LINKRU",
    "LINKEN"
));

$arResult["LINKRU"] = $arResult["PROPERTIES"]["LINKRU"]["VALUE"];
$arResult["LINKEN"] = $arResult["PROPERTIES"]["LINKEN"]["VALUE"];





