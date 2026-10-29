<?
namespace Lang\Region;

use Lang\Region;
use Bitrix\Main\Loader;
use Lang\Main\Iblock\Util;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\ActionFilter;

class Page extends Controller {

    public function listAction()
    {
        $bitrixComp = new \CBitrixComponent;
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        $siteTemplatePath = SITE_TEMPLATE_PATH;
        $folder = $_REQUEST['folder'];

        $templatePath = "{$documentRoot}{$siteTemplatePath}";
        $pageList = "{$siteTemplatePath}/page/{$folder}/list.php";
        $pageStandart = "{$siteTemplatePath}/page/standard.php";
        $page404 = "{$siteTemplatePath}/page/404.php";

        $lang = Util::getLang();
        $region = Region::getCurrent(['select' => ['NAME', 'prefix', 'domain']]);
        $site = ucfirst($region->getPrefix()->getValue());

        $textTableString = "LangText{$lang}";
        $textSiteTableString = "LangText{$site}";
        $structureTableString = "LangStructure{$site}";
        $structureIblock = Util::getIblockObject($structureTableString, []);

        $param = [
            "templatePath" => $templatePath,
            "bitrixComp" => $bitrixComp,
            'region' => $region,
            "lang" => $lang,
            "site" => $site,
            "textTableString" => $textTableString,
            "textSiteTableString" => $textSiteTableString,
            "structureTableString" => $structureTableString,
            'folder' => $folder,
        ];

        if(!empty($structureIblock))
        {
            $section = Util::getSectionObject($structureTableString, [
                'select' => ['NAME', 'UF_LINK', 'UF_PAGE', 'UF_TEXT', 'UF_BAN', 'UF_IFS', 'UF_SOT', 'UF_NUM', 'UF_QUO', 'UF_DOC', 'UF_FORM', 'UF_MENU', 'UF_FAQ', 'UF_HTML'],
                'filter' => ['CODE' => $folder, 'DEPTH_LEVEL' => 1],
            ]);

            if(!empty($section))
            {
                $param['section'] = $section;

                if(!empty($section->getUfPage()))
                {
                    return $this->renderView($pageStandart, $param);
                }
                else
                {
                    if(empty(file_exists("{$templatePath}/page/{$folder}/")))
                    {
                        if(empty(file_exists("{$documentRoot}/{$folder}/index.php")))
                        {
                            Util::get404();
                        }
                        else
                        {
                            return $this->renderView("/{$folder}/index.php", $param);
                        }
                    }
                    else
                    {
                        // Показывает страницу папки
                        return $this->renderView($pageList, $param);
                    }
                }
            }
            elseif(empty($section))
            {
                $element = Util::getElementObject($structureTableString,
                    ['NAME', 'link', 'page', 'ban', 'menu', 'quo', 'forma', 'contact', 'faq', 'img', 'text', 'html', 'sot'],
                    ['CODE' => $folder]
                );

                if(!empty($element))
                {
                    $param['element'] = $element;

                    if(!empty(file_exists("{$templatePath}/page/{$folder}/")))
                    {
                        return $this->renderView($pageList, $param);
                    }
                    elseif(!empty($element->getPage()))
                    {
                        if($element->getPage()->getValue() == "Y")
                        {
                            return $this->renderView($pageStandart, $param);
                        }
                        else
                        {
                            Util::get404();
                        }
                    }
                    else
                    {
                        Util::get404();
                    }
                }
                else
                {
                    Util::get404();
                }
            }
            else
            {
                Util::get404();
            }
        }

        return $this->renderView($page404, $param);
    }

    public function sectionAction()
    {
        $param = [];

        $bitrixComp = new \CBitrixComponent;
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        $siteTemplatePath = SITE_TEMPLATE_PATH;
        $folder = $_REQUEST['folder'];
        $sectionCode = $_REQUEST['section'];

        $templatePath = "{$documentRoot}{$siteTemplatePath}";
        $pageSection = "{$siteTemplatePath}/page/{$folder}/section.php";
        $pageSectionElement = "{$siteTemplatePath}/page/{$folder}/{$sectionCode}/list.php";
        $pageStandart = "{$siteTemplatePath}/page/standard.php";
        $page404 = "{$siteTemplatePath}/page/404.php";

        $lang = Util::getLang();
        $region = Region::getCurrent(['select' => ['NAME', 'prefix', 'domain']]);
        $site = ucfirst($region->getPrefix()->getValue());

        $textTableString = "LangText{$lang}";
        $textSiteTableString = "LangText{$site}";
        $structureTableString = "LangStructure{$site}";

        $structureIblock = Util::getIblockObject($structureTableString, []);

        $param = [
            "templatePath" => $templatePath,
            "bitrixComp" => $bitrixComp,
            'region' => $region,
            "lang" => $lang,
            "site" => $site,
            "textTableString" => $textTableString,
            "textSiteTableString" => $textSiteTableString,
            "structureTableString" => $structureTableString,
            'folder' => $folder,
            'sectionCode' => $sectionCode,
        ];

        if(!empty($structureIblock))
        {
            $section = Util::getSectionObject($structureTableString, [
                'select' => ['NAME', 'UF_LINK', 'UF_PAGE', 'UF_DIN', 'UF_TEXT', 'UF_BAN', 'UF_IFS', 'UF_SOT', 'UF_NUM', 'UF_QUO', 'UF_DOC', 'UF_FORM', 'UF_MENU', 'UF_FAQ', 'UF_HTML'],
                'filter' => ['CODE' => $sectionCode, 'DEPTH_LEVEL' => 2],
            ]);

            if(empty($section))
            {
                $section = Util::getSectionObject($structureTableString, [
                    'select' => ['NAME', 'UF_LINK', 'UF_PAGE', 'UF_DIN', 'UF_TEXT', 'UF_BAN', 'UF_IFS', 'UF_SOT', 'UF_NUM', 'UF_QUO', 'UF_DOC', 'UF_FORM', 'UF_MENU', 'UF_FAQ', 'UF_HTML'],
                    'filter' => ['CODE' => $folder, 'DEPTH_LEVEL' => 1]
                ]);

                if(!empty($section))
                {
                    $param['folderSection'] = $section;

                    if(empty($section->getUfDin()))
                    {
                        $element = Util::getElementObject($structureTableString,
                            ['NAME', 'link', 'page', 'ban', 'menu', 'quo', 'forma', 'contact', 'faq', 'img', 'text', 'html', 'sot'],
                            ['CODE' => $sectionCode, 'IBLOCK_SECTION_ID' => $section->getId()]
                        );

                        if(!empty($element))
                        {
                            $param['element'] = $element;

                            if(!empty(file_exists("{$templatePath}/page/{$folder}/{$sectionCode}/")))
                            {
                                return $this->renderView($pageSectionElement, $param);
                            }
                            elseif(!empty($element->getPage()))
                            {
                                if($element->getPage()->getValue() == "Y")
                                {
                                    return $this->renderView($pageStandart, $param);
                                }
                                else
                                {
                                    Util::get404();
                                }
                            }
                            else
                            {
                                Util::get404();
                            }
                        }
                        elseif(!empty($section->getUfPage()))
                        {
                            // Показывает стандартную страницу
                            return $this->renderView($pageStandart, $param);
                        }
                        elseif(empty($section->getUfPage()))
                        {
                            Util::get404();
                        }
                        else
                        {
                            Util::get404();
                        }
                    }
                    else
                    {
                        return $this->renderView($pageSection, $param);
                    }
                }
                else
                {
                    Util::get404();
                }
            }
            else
            {
                // Загружаем раздел
                $param['section'] = $section;
                $param['folderSection'] = Util::getSectionObject($structureTableString, [
                    'select' => ['NAME', 'UF_LINK'],
                    'filter' => ['CODE' => $folder, 'DEPTH_LEVEL' => 1]
                ]);

                if(!empty($section->getUfPage()))
                {
                    // Показывает стандартную страницу
                    return $this->renderView($pageStandart, $param);
                }
                elseif(empty($section->getUfPage()))
                {
                    if(!empty(file_exists("{$templatePath}/page/{$folder}/{$sectionCode}/")))
                    {
                        return $this->renderView($pageSectionElement, $param);
                    }
                    else
                    {
                        Util::get404();
                    }
                }
                else
                {
                    Util::get404();
                }
            }
        }

        return $this->renderView($page404, $param);
    }

    public function detailAction()
    {
        $param = [];
        $bitrixComp = new \CBitrixComponent;
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        $siteTemplatePath = SITE_TEMPLATE_PATH;
        $folder = $_REQUEST['folder'];
        $sectionCode = $_REQUEST['section'];
        $elementCode = $_REQUEST['element'];

        $templatePath = "{$documentRoot}{$siteTemplatePath}";
        $pageElement = "{$siteTemplatePath}/page/{$folder}/detail.php";
        $pageStandart = "{$siteTemplatePath}/page/standard.php";
        $page404 = "{$siteTemplatePath}/page/404.php";

        $lang = Util::getLang();
        $region = Region::getCurrent(['select' => ['NAME', 'prefix', 'domain']]);
        $site = ucfirst($region->getPrefix()->getValue());

        $textTableString = "LangText{$lang}";
        $textSiteTableString = "LangText{$site}";
        $structureTableString = "LangStructure{$site}";

        $structureIblock = Util::getIblockObject($structureTableString, []);

        $param = [
            "templatePath" => $templatePath,
            "bitrixComp" => $bitrixComp,
            "lang" => $lang,
            "site" => $site,
            "textTableString" => $textTableString,
            "textSiteTableString" => $textSiteTableString,
            "structureTableString" => $structureTableString,
            'folder' => $folder,
            'sectionCode' => $sectionCode,
            'elementCode' => $elementCode,
        ];

        $param['folderSection'] = Util::getSectionObject($structureTableString, [
            'select' => ['NAME', 'UF_LINK', 'UF_PAGE', 'UF_DIN', 'UF_TEXT', 'UF_BAN', 'UF_IFS', 'UF_SOT', 'UF_NUM', 'UF_QUO', 'UF_DOC', 'UF_FORM', 'UF_MENU', 'UF_FAQ', 'UF_HTML'],
            'filter' => ['CODE' => $folder, 'DEPTH_LEVEL' => 1]
        ]);

        if(!empty($structureIblock))
        {
            $section = Util::getSectionObject($structureTableString, [
                'select' => ['NAME', 'UF_LINK', 'UF_PAGE', 'UF_DIN', 'UF_TEXT', 'UF_BAN', 'UF_IFS', 'UF_SOT', 'UF_NUM', 'UF_QUO', 'UF_DOC', 'UF_FORM', 'UF_MENU', 'UF_FAQ', 'UF_HTML'],
                'filter' => ['CODE' => $sectionCode, 'DEPTH_LEVEL' => 2],
            ]);

            if(!empty($section))
            {
                $param['section'] = $section;
                $element = Util::getElementObject($structureTableString,
                    ['NAME', 'link', 'page', 'ban', 'menu', 'quo', 'forma', 'contact', 'faq', 'img', 'text', 'html', 'sot'],
                    ['CODE' => $elementCode, 'IBLOCK_SECTION_ID' => $section->getId()]
                );

                if(!empty($element))
                {
                    $param['element'] = $element;

                    if(!empty($element->getPage()))
                    {
                        if($element->getPage()->getValue() == 'Y')
                        {
                            return $this->renderView($pageStandart, $param);
                        }
                    }
                }
                else
                {
                    Util::get404();
                }
            }
            else
            {
                if(!empty(file_exists("{$templatePath}/page/{$folder}/")))
                {
                    return $this->renderView($pageElement, $param);
                }
                else
                {
                    Util::get404();
                }
            }
        }

        return $this->renderView($page404, $param);
    }

    protected function getDefaultPreFilters()
    {
        return [
            new ActionFilter\HttpMethod([
                ActionFilter\HttpMethod::METHOD_GET
            ]),
        ];
    }

    public function getPrimaryAutoWiredParameter()
	{
		return new \Bitrix\Main\Engine\AutoWire\ExactParameter(
			Page::class,
			'folder',
			function($className, $id) {
				return Folder::loadById($id);
			}
		);
	}

    // public function indexAction(string $folder)
    // {
    //     // $region = Region::getCurrent(['select' => ['prefix']]);
    //     // $site = ucfirst($region->getPrefix()->getValue());
    //     // $table = $this->getTableString("LangStructure{$site}");

    //     // echo $folder, '--1<br>';

    //     return $folder . '--1<br>';
    // }

    // public function getPath()
    // {
    //     return $this->path;
    // }
}

// class PageIndexAction extends \Bitrix\Main\Engine\Action implements \Bitrix\Main\Engine\Contract\RoutableAction
// {
//     public static function getControllerClass()
//     {
//         return Page::class;
//     }

//     public static function getDefaultName()
//     {
//         return 'index';
//     }

//     // public function indexAction(string $folder)
//     // {
//     //     // $region = Region::getCurrent(['select' => ['prefix']]);
//     //     // $site = ucfirst($region->getPrefix()->getValue());
//     //     // $table = $this->getTableString("LangStructure{$site}");

//     //     // echo $folder, '--1<br>';

//     //     // return $folder;
//     // }

//     public function indexAction()
//     {
//         return $this->renderView($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/page/folder.php");
//     }

//     public function run()
//     {
//         // ...
//     }
// }