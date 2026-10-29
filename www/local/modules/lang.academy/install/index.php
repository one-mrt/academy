<?
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Iblock\Elements\ElementLangOrderOfferTable;

IncludeModuleLangFile(__FILE__);

if (class_exists('lang_academy')) {
	return;
}

class lang_academy extends CModule {

	const MODULE_ID = 'lang.academy';
	var $MODULE_ID = 'lang.academy';
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
            'OnBeforeProlog',
            $this->MODULE_ID,
            'Lang\\Academy\\Util',
            'setUserActiveOnline'
        );

		EventManager::getInstance()->registerEventHandler(
            'im',
            'OnAfterConfirmNotify',
            $this->MODULE_ID,
            'Lang\\Academy\\Util',
            'onSendNotifyResponsible'
        );

		EventManager::getInstance()->registerEventHandler(
            'rest',
            'OnRestServiceBuildDescription',
            $this->MODULE_ID,
            'Lang\\Academy\\Rest',
            'OnRestServiceBuildDescription'
        );

		EventManager::getInstance()->registerEventHandler(
            'sale',
            'OnSaleOrderBeforeSaved',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onUpdateOrder'
        );

		EventManager::getInstance()->registerEventHandler(
            'sale',
            'OnSaleOrderSaved',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onPullUpdateOrder'
        );

		EventManager::getInstance()->registerEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onUpdateServiceOrder'
        );

		EventManager::getInstance()->registerEventHandler(
            'iblock',
            'OnIBlockPropertyBuildList',
            $this->MODULE_ID,
            'Lang\\Academy\\PropertyPaySystem',
            'GetUserTypeDescription'
        );

		return true;
	}

	public function UnInstallEvents()
	{
		EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnBeforeProlog',
            $this->MODULE_ID,
            'Lang\\Academy\\Util',
            'setUserActiveOnline'
        );
	
		EventManager::getInstance()->unRegisterEventHandler(
            'im',
            'OnAfterConfirmNotify',
            $this->MODULE_ID,
            'Lang\\Academy\\Util',
            'onSendNotifyResponsible'
        );

		EventManager::getInstance()->unRegisterEventHandler(
            'rest',
            'OnRestServiceBuildDescription',
            $this->MODULE_ID,
            'Lang\\Academy\\Rest',
            'OnRestServiceBuildDescription'
        );

		EventManager::getInstance()->unRegisterEventHandler(
            'sale',
            'OnSaleOrderBeforeSaved',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onUpdateOrder'
        );

		EventManager::getInstance()->unRegisterEventHandler(
            'sale',
            'OnSaleOrderSaved',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onPullUpdateOrder'
        );

		EventManager::getInstance()->unRegisterEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onUpdateServiceOrder'
        );

		EventManager::getInstance()->unRegisterEventHandler(
            'iblock',
            'OnIBlockPropertyBuildList',
            $this->MODULE_ID,
            'Lang\\Academy\\PropertyPaySystem',
            'GetUserTypeDescription'
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