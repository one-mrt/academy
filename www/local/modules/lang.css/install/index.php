<?
use Bitrix\Main\Loader;
use Lang\Css\Modifier;
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;
use Lang\Css\Base\PropertyPrefixTable;
use Lang\Css\Base\EventTable;
use Lang\Css\Base\PseudoTable;
use Lang\Css\Base\BreakpointTable;
use Lang\Css\Base\CssTable;
use Lang\Css\Base\StyleTable;
use Lang\Css\Base\StyleSiteTable;
use Lang\Css\Base\ModifierPageTable;
use Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);

if (class_exists('lang_css'))
{
	return;
}

class lang_css extends CModule {

	const MODULE_ID = 'lang.css';
	var $MODULE_ID = 'lang.css';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $PARTNER_NAME;
	var $PARTNER_URI;
	var $MODULE_GROUP_RIGHTS;
	var $strError = '';
	
	function __construct()
	{
		$arModuleVertion = [];
		include(dirname(__FILE__)."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("{$this->MODULE_ID}_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("{$this->MODULE_ID}_MODULE_DESCRIPTION");
		$this->MODULE_GROUP_RIGHTS = 'N';
		$this->PARTNER_NAME = Loc::getMessage("{$this->MODULE_ID}_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("{$this->MODULE_ID}_PARTNER_URI");
	}

	public function InstallDB()
	{
		return true;
	}

	public function UnInstallDB()
	{
		return true;
	}

	function InstallEvents()
	{
		EventManager::getInstance()->registerEventHandler(
            'main',
            'OnPanelCreate',
            $this->MODULE_ID,
            'Lang\\Css\\BitrixEvent',
            'addBtnUpdateStyle'
        );

		return true;
	}

	public function UnInstallEvents()
	{
		EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnPanelCreate',
            $this->MODULE_ID,
            'Lang\\Css\\BitrixEvent',
            'addBtnUpdateStyle'
        );

		return true;
	}

	function InstallFiles($arParams = [])
	{
		return true;
	}

	function UnInstallFiles()
	{
		return true;
	}

	function DoInstall()
	{
		ModuleManager::registerModule($this->MODULE_ID);

		$this->InstallDB();
        $this->InstallEvents();
		$this->InstallFiles();

		return true;
	}

	function DoUninstall()
	{
		$this->UnInstallDB();
		$this->UnInstallEvents();
		$this->UnInstallFiles();
		ModuleManager::unregisterModule($this->MODULE_ID);

		return true;
	}
}
?>