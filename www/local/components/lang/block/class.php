<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


class LangBlock extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function executeComponent()
    {
        $dir = $_SERVER['DOCUMENT_ROOT'];
        $siteTemplatePath = SITE_TEMPLATE_PATH;
        $this->arResult['file'] = "{$dir}{$siteTemplatePath}/.block/{$this->arParams['path']}";

        $this->IncludeComponentTemplate();
    }
}