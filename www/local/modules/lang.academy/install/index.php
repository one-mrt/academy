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
        Loader::includeModule('iblock');
        Loader::includeModule('lang.academy');

        $eventManager = EventManager::getInstance();

		$eventManager->registerEventHandler(
            'main',
            'OnBeforeProlog',
            $this->MODULE_ID,
            'Lang\\Academy\\Util',
            'setUserActiveOnline'
        );

		$eventManager->registerEventHandler(
            'im',
            'OnAfterConfirmNotify',
            $this->MODULE_ID,
            'Lang\\Academy\\Util',
            'onSendNotifyResponsible'
        );

		$eventManager->registerEventHandler(
            'rest',
            'OnRestServiceBuildDescription',
            $this->MODULE_ID,
            'Lang\\Academy\\Rest',
            'OnRestServiceBuildDescription'
        );

		$eventManager->registerEventHandler(
            'sale',
            'OnSaleOrderBeforeSaved',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onUpdateOrder'
        );

		$eventManager->registerEventHandler(
            'sale',
            'OnSaleOrderSaved',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onPullUpdateOrder'
        );

		$eventManager->registerEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onUpdateServiceOrder'
        );

		$eventManager->registerEventHandler(
            'iblock',
            'OnIBlockPropertyBuildList',
            $this->MODULE_ID,
            'Lang\\Academy\\PropertyPaySystem',
            'GetUserTypeDescription'
        );

        // EventManager::getInstance()->registerEventHandler(
        //     Bitrix\Iblock\Elements\ElementLangReviewTable::class,
        //     DataManager::EVENT_ON_BEFORE_ADD,
        //     'lang.academy',
        //     Lang\Academy\ReviewRating::class,
        //     'onAfterAddReview'
        // );

        $em = \Bitrix\Main\ORM\EventManager::getInstance();

        $em->registerEventHandler(
            Bitrix\Iblock\Elements\ElementLangReviewTable::class,
            DataManager::EVENT_ON_AFTER_ADD,
            $this->MODULE_ID,
            Lang\Academy\ReviewRating::class,
            'onAfterAddReview'
        );

		return true;
	}

	public function UnInstallEvents()
	{
        Loader::includeModule('iblock');
        Loader::includeModule('lang.academy');
        
        $eventManager = EventManager::getInstance();

		$eventManager->unRegisterEventHandler(
            'main',
            'OnBeforeProlog',
            $this->MODULE_ID,
            'Lang\\Academy\\Util',
            'setUserActiveOnline'
        );
	
		$eventManager->unRegisterEventHandler(
            'im',
            'OnAfterConfirmNotify',
            $this->MODULE_ID,
            'Lang\\Academy\\Util',
            'onSendNotifyResponsible'
        );

		$eventManager->unRegisterEventHandler(
            'rest',
            'OnRestServiceBuildDescription',
            $this->MODULE_ID,
            'Lang\\Academy\\Rest',
            'OnRestServiceBuildDescription'
        );

		$eventManager->unRegisterEventHandler(
            'sale',
            'OnSaleOrderBeforeSaved',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onUpdateOrder'
        );

		$eventManager->unRegisterEventHandler(
            'sale',
            'OnSaleOrderSaved',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onPullUpdateOrder'
        );

		$eventManager->unRegisterEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            'Lang\\Academy\\Order',
            'onUpdateServiceOrder'
        );

		$eventManager->unRegisterEventHandler(
            'iblock',
            'OnIBlockPropertyBuildList',
            $this->MODULE_ID,
            'Lang\\Academy\\PropertyPaySystem',
            'GetUserTypeDescription'
        );

        $em = \Bitrix\Main\ORM\EventManager::getInstance();

        $em->unRegisterEventHandler(
            Bitrix\Iblock\Elements\ElementLangReviewTable::class,
            DataManager::EVENT_ON_AFTER_ADD,
            $this->MODULE_ID,
            Lang\Academy\ReviewRating::class,
            'onAfterAddReview'
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