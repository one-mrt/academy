<?defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

IncludeModuleLangFile(__FILE__);

if (class_exists('lang_route')) return;

class lang_route extends CModule {

	const MODULE_ID = 'lang.route';
	var $MODULE_ID = 'lang.route';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $PARTNER_NAME;
	var $PARTNER_URI;
	var $MODULE_GROUP_RIGHTS;
	var $strError = '';
	
	function __construct() {

		$arModuleVertion = array();
		include(dirname(__FILE__)."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("{$this->MODULE_ID}_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("{$this->MODULE_ID}_MODULE_DESCRIPTION");
		$this->MODULE_GROUP_RIGHTS = 'N';
		$this->PARTNER_NAME = Loc::getMessage("{$this->MODULE_ID}_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("{$this->MODULE_ID}_PARTNER_URI");
	}

	function installDB($arParams = array())	{
		return true;
	}

	function inInstallDB($arParams = array()) {
		return true;
	}

	function installEvents() {
		return true;
	}

	function inInstallEvents() {
		return true;
	}

	function installFiles($arParams = array()) {
		
	}

	function inInstallFiles() {

	}

	function doInstall() {
		ModuleManager::registerModule($this->MODULE_ID);
	}

	function doUninstall() {
        ModuleManager::unregisterModule($this->MODULE_ID);
	}
}
?>