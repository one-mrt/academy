<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Currency\CurrencyManager;

class LangAccount extends CBitrixComponent
{
	// const E_SALE_MODULE_NOT_INSTALLED = 10000;
	// const E_NOT_AUTHORIZED = 10001;
	const IBLOCK_TRANSACT = "LangTransact";

	private $userId = 0;

	protected function checkRequirements()
	{
		global $USER, $APPLICATION;

		if(!Loader::includeModule("sale")) {
			$this->arResult['ERRORS'][self::E_SALE_MODULE_NOT_INSTALLED] = Loc::getMessage("SALE_MODULE_NOT_INSTALL");
			return false;
		}

		if (!CBXFeatures::IsFeatureEnabled('SaleAccounts'))
			return false;

		if (!$USER->IsAuthorized())
		{
			if ($this->arParams['AUTH_FORM_IN_TEMPLATE'] !== 'Y')
			{
				$APPLICATION->AuthForm(Loc::getMessage("SALE_ACCESS_DENIED"), false, false, 'N', false);
			}
			else
			{
				$this->arResult['ERRORS'][self::E_NOT_AUTHORIZED] = Loc::getMessage("SALE_ACCESS_DENIED");
			}
			return false;
		}

		return true;
	}

	public function __construct($component = null)
	{
		parent::__construct($component);

		$user = CurrentUser::get();

		$this->userId = $user->getId();

		Loc::loadMessages(__FILE__);
	}

	public function getBlockSum() : int
	{
		$sum = 0;
		$langTransact = "LangTransact";

		$transactList = Data::getTableString($langTransact)::query()
			->registerRuntimeField(
				new \Bitrix\Main\Entity\ExpressionField('sm', 'SUM(%s)', ['sum.IBLOCK_GENERIC_VALUE'])
			)
			->setSelect(['sm', 'u' => 'uTo.IBLOCK_GENERIC_VALUE'])
			->addGroup('u')
			->where('ACTIVE', 'Y')
			->where('uTo.VALUE', $this->userId)
			->exec();

		foreach ($transactList as $sumTransact) {
			$sum = (int)($sumTransact['sm']);
			break;
		}

		return $sum;
	}

	public function executeComponent()
	{
		if ($this->checkRequirements()) {
			$this->obtainAccountData();
		}

		$this->includeComponentTemplate();
	}

	private function obtainAccountData()
	{
		$this->arResult["sum"] = 0;
		$this->arResult['sblock'] = 0;
		$this->arResult['block'] = $this->getBlockSum();

		$accountList = CSaleUserAccount::GetList(
			["CURRENCY" => "ASC"],
			["USER_ID" => (int)($this->userId)],
			false,
			false,
			["ID", "CURRENT_BUDGET"]
		);

		while ($account = $accountList->Fetch()) {
			$this->arResult['sblock'] = (intval($account["CURRENT_BUDGET"]) + intval($this->arResult['block']));
			$this->arResult["sum"] = intval($account["CURRENT_BUDGET"]);

			break;
		}

		$this->arResult['list'] = [
			310 => [
				'sum' => SaleFormatCurrency($this->arResult['sblock'], CurrencyManager::getBaseCurrency())
			],
			311 => [
				'sum' => SaleFormatCurrency($this->arResult['sum'], CurrencyManager::getBaseCurrency()),
			],
			312 => [
				'sum' => SaleFormatCurrency($this->arResult['block'], CurrencyManager::getBaseCurrency()),
			]
		];
	}
}
