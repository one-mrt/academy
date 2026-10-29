<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Currency;
use Bitrix\Main;
use Bitrix\Main\Context as MContext;
use Bitrix\Main\Security\Sign\Signer;
use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\CurrentUser;

use Bitrix\Sale;
use Bitrix\Sale\Registry;
use Bitrix\Sale\PaySystem;
use Bitrix\Sale\PaySystem\BaseServiceHandler;
use Bitrix\Sale\PaySystem\Manager as PayManager;
use Bitrix\Sale\PaySystem\Context as PayContext;
use Bitrix\Sale\Internals\SiteCurrencyTable;
use Bitrix\Sale\Delivery\Services\EmptyDeliveryService;

use Lang\Main\Iblock\Util as Data;


class LangAccountPay extends \CBitrixComponent
{
	protected ?object $error;
	protected ?object $registry = null;
	private ?object $user;
	private ?object $basket;
	private ?object $order;
	private bool $savePropertyCharge = true;
	private string $langPaySystemIn = "LangPaySystemIn";

	public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);

        $this->user = CurrentUser::get();
        $this->arResult['userId'] = intval($this->user?->getId());
    }

	public function onPrepareComponentParams($params)
	{
		$this->error = new ErrorCollection();

		if (!$this->checkModules()) {
			return $params;
		}

		$params['PATH_TO_BASKET'] = trim((string)($params['PATH_TO_BASKET'] ?? ''));
		$params['PATH_TO_PAYMENT'] = trim((string)($params['PATH_TO_PAYMENT'] ?? ''));
		$params['SELL_CURRENCY'] = trim((string)($params['SELL_CURRENCY'] ?? ''));
		$params['REDIRECT_TO_CURRENT_PAGE'] = (string)($params['REDIRECT_TO_CURRENT_PAGE'] ?? 'N');

		if ($params['PATH_TO_BASKET'] === '') {
			$params['PATH_TO_BASKET'] = '/personal/cart';
		}

		if ($params['PATH_TO_PAYMENT'] === '') {
			$params['PATH_TO_PAYMENT'] = '/personal/order/payment';
		}

		if ($params['SELL_CURRENCY'] === '') {
			$params['SELL_CURRENCY'] = SiteCurrencyTable::getSiteCurrency(SITE_ID);
		}

		if (!isset($params['PERSON_TYPE'])) {
			$params['PERSON_TYPE'] = 1;
		}

		if (empty($params['SELL_CURRENCY'])) {
			$params['SELL_CURRENCY'] = SiteCurrencyTable::getSiteCurrency(SITE_ID);
		} else {
			$params["SELL_CURRENCY"] = trim($params["SELL_CURRENCY"]);
		}

		$params['PRODUCT_PROVIDER_CLASS'] = (string)($params['PRODUCT_PROVIDER_CLASS'] ?? '');

		if ($params["PRODUCT_PROVIDER_CLASS"] === '') {
			$params["PRODUCT_PROVIDER_CLASS"] = "\\Bitrix\\Sale\\ProviderAccountPay";
		}

		if (isset($params['SELL_TOTAL'])) {
			$params['SELL_TOTAL'] = array_diff($params['SELL_TOTAL'], ['']);
		}

		if (!isset($params["ELIMINATED_PAY_SYSTEMS"]) || !is_array($params["ELIMINATED_PAY_SYSTEMS"])) {
			$params["ELIMINATED_PAY_SYSTEMS"] = [];
		}

		$params["ELIMINATED_PAY_SYSTEMS"][] = PayManager::getInnerPaySystemId();
		$params['NAME_CONFIRM_TEMPLATE'] = 'confirm_template';
		$params["TEMPLATE_PATH"] = $this->getTemplateName();

		if (empty($params['RETURN_URL'])) {
			$params['RETURN_URL'] = (new PayContext())->getUrl();
		}

		return $params;
	}

	protected function checkModules()
	{
		if (!Loader::includeModule('sale')) {
			$this->error->setError(new Error(Loc::getMessage('SAP_MODULE_NOT_INSTALL')));
			return false;
		}

		if (!CBXFeatures::IsFeatureEnabled('SaleAccounts')) {
			$this->error->setError(new Error(Loc::getMessage('SAP_FEATURE_NOT_ALLOW')));
			return false;
		}

		return true;
	}

	private function formanNumber($number = 0)
    {
        return \SaleFormatCurrency($number, $this->arParams['SELL_CURRENCY']);
    }

	private function mathPrice()
	{
		if($this->arResult['mx'] > 0 && $this->arResult['sumPay'] > $this->arResult['mx']) {
			$this->error->setError(new Error("Сумма пополнения не должна превашать лимита"));
		} else {
			if($this->arResult['com'] > 0) {
				$com = ($this->arResult['com'] / 100) * $this->arResult['sumPay'];

				if($com >= $this->arResult['mncom']) {
					$this->arResult['scom'] = floatval($this->arResult['sumPay'] + $com);
					$this->arResult['scomPrint'] = $this->formanNumber($this->arResult['scom']);
				} else {
					$this->arResult['scom'] = floatval($this->arResult['sumPay'] + $this->arResult['mncom']);
					$this->arResult['scomPrint'] = $this->formanNumber($this->arResult['scom']);
				}
			} else {
				if($this->arResult['mncom'] > 0) {
					$this->arResult['scom'] = floatval($this->arResult['sumPay'] + $com);
					$this->arResult['scomPrint'] = $this->formanNumber($this->arResult['scom']);
				} else {
					$this->arResult['scom'] = floatval($this->arResult['sumPay']);
					$this->arResult['scomPrint'] = $this->formanNumber($this->arResult['scom']);
				}
			}
		}
	}

	private function getDataPay()
	{
		$this->arResult['paySystem'] = intval($this->request->getPost('paySystem'));
		$this->arResult['sumPay'] = floatval(preg_replace('/[^0-9]/', '', $this->request->getPost("buyMoney")));
		$this->arResult['pay'] = htmlspecialcharsbx($this->request->getPost('pay'));

		if(!check_bitrix_sessid()) {
			$this->error->setError(new Error(Loc::getMessage('SAP_INVALID_TOKEN')));
		}

		if($this->arResult['paySystem'] <= 0) {
			$this->error->setError(new Error("Выберите платежную систему"));
		} else {
			$paySystem = Data::getTableString($this->langPaySystemIn)::query()
				->setSelect(['ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'com.VALUE', 'mncom.VALUE', 'mn.VALUE', 'mx.VALUE', 'pays.VALUE'])
				->where('ACTIVE', 'Y')
				->where('ID', $this->arResult['paySystem'])
				->setCacheTtl(360000)
				->cacheJoins(true)
				->fetchObject();

			if(!empty($paySystem) && !empty($paySystem->getPays()?->getValue())) {
				$this->arResult['paySystem'] = $paySystem->getPays()?->getValue();
				$this->arResult['payElement'] = $paySystem->getId();
				$this->arResult['com'] = floatval($paySystem->getCom()?->getValue()) ?? 0;
				$this->arResult['mncom'] = floatval($paySystem->getMncom()?->getValue()) ?? 0;
				$this->arResult['mn'] = floatval($paySystem->getMn()?->getValue()) ?? 0;
				$this->arResult['mx'] = floatval($paySystem->getMx()?->getValue()) ?? 0;

				$this->arResult['mnPrint'] = $this->formanNumber($this->arResult['mn']);
				$this->arResult['mxPrint'] = $this->formanNumber($this->arResult['mx']);

				if($this->arResult['mn'] > $this->arResult['sumPay']) {
					$this->arResult['sumPay'] = $this->arResult['mn'];
				} elseif($this->arResult['mx'] > $this->arResult['sumPay']) {
					$this->arResult['sumPay'] = $this->arResult['mx'];
				}

				$this->mathPrice();
			} else {
				$this->error->setError(new Error("Выберите платежную систему"));
			}
		}

		if ($this->arResult['userId'] <= 0) {
			$this->error->setError(new Error(Loc::getMessage('SALE_ACCESS_DENIED')));
		}
	}

	private function isPay(): bool
	{
		if($this->arResult['pay'] == 'y') {
			return true;
		}

		return false;
	}

	protected function getPaySystem()
	{
		$paymentCollection = $this->order->getPaymentCollection();
		// $paym = $paymentCollection->getItemById($this->arResult['paySystem']);

		$paySystemList = PayManager::getListWithRestrictions($paymentCollection->createItem());

		foreach ($paySystemList as $payment) {
			if (!in_array($payment['ID'], $this->arParams['ELIMINATED_PAY_SYSTEMS'])) {
				
				if (!empty($payment["LOGOTIP"])) {
					$payment["LOGOTIP"] = CFile::GetFileArray($payment['LOGOTIP']);

					$fileTemp = CFile::ResizeImageGet(
						$payment["LOGOTIP"]["ID"],
						array("width" => "95", "height" =>"95"),
						BX_RESIZE_IMAGE_PROPORTIONAL,
						true
					);

					$payment["LOGOTIP"] = $fileTemp["src"];
				}

				if($this->arResult['paySystem'] == $payment['ID']) {
					$this->arResult['paySystemData'] = $payment;
					break;
				}
			}
		}

		if(empty($this->arResult['paySystemData'])) {
			$this->error->setError(new Error(Loc::getMessage("SAP_EMPTY_PAY_SYSTEM_LIST")));
		}
	}

	protected function fillArrayResult()
	{
		if ($this->arParams["SELL_VALUES_FROM_VAR"] === "Y") {
			if (!empty($this->arParams['SELL_VAR_PRICE_VALUE'])) {
				$this->arResult['SELL_VAR_PRICE_VALUE'] = (float)($this->arParams['SELL_VAR_PRICE_VALUE']);
			} else {
				$this->error->setError(new Error(Loc::getMessage("SAP_EMPTY_VAR_PRICE_VALUE")));
			}
		}

		$this->savePropertyCharge = false;
		$this->initBasket();
		$this->initOrder();
		$this->getPaySystem();

		if ($this->error->isEmpty()) {
			$parsedFormat = \CCurrencyLang::getParsedCurrencyFormat($this->arParams["SELL_CURRENCY"]);

			if (!empty($parsedFormat)) {
				$index = array_search('#', $parsedFormat);

				if ($index !== false) {
					$parsedFormat[$index] = '';
				}

				$this->arResult['FORMATED_CURRENCY'] = trim(implode('', $parsedFormat));
			}

			$signer = new Signer;
			$ajaxParams = array(
				'PERSON_TYPE' => (int)$this->arParams['PERSON_TYPE'],
				'SELL_CURRENCY' => $this->arParams['SELL_CURRENCY'],
				'NAME_CONFIRM_TEMPLATE' => $this->arParams['NAME_CONFIRM_TEMPLATE'],
				'PATH_TO_PAYMENT' => $this->arParams['PATH_TO_PAYMENT'],
				'RETURN_URL' => $this->arParams['RETURN_URL'],
			);

			$this->arResult['SIGNED_PARAMS'] = base64_encode($signer->sign(serialize($ajaxParams), 'account.pay'));
		}
	}

	protected function setRegistry()
	{
		$this->registry = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);
	}

	protected function formatResultErrors()
	{
		if (!$this->error->isEmpty()) {
			foreach ($this->error->toArray() as $error) {
				$this->arResult['error'][] = $error->getMessage();
			}
		}
	}

	protected function initOrderShipment()
	{
		$shipmentCollection = $this->order->getShipmentCollection();
		$shipment = $shipmentCollection->createItem();
		$shipmentItemCollection = $shipment->getShipmentItemCollection();

		foreach($this->order->getBasket() as $item) {
			$shipmentItem = $shipmentItemCollection->createItem($item);
			$shipmentItem->setQuantity(1);
		}

		$emptyDeliveryServiceId = EmptyDeliveryService::getEmptyDeliveryServiceId();
		$shipment->setField('DELIVERY_ID', $emptyDeliveryServiceId);
	}

	protected function initBasket()
	{
		$productId = (int)($this->arResult['scom'] * 100);
		$basketClassName = $this->registry->getBasketClassName();
		$this->basket = $basketClassName::create(SITE_ID);

		$basketItem = $this->basket->createItem('sale', $productId);

		$sumPay = number_format($this->arResult['sumPay'], 2, '.', ' ');

		$productFields = array(
			"BASE_PRICE" => $this->arResult['scom'],
			"CURRENCY" => $this->arParams["SELL_CURRENCY"],
			"QUANTITY" => 1,
			"LID" => SITE_ID,
			"DELAY" => "N",
			"CAN_BUY" => "Y",
			"NAME" => "Занесение на внутренний счет {$sumPay} ₽", // str_replace("#SUM#", SaleFormatCurrency($this->arResult['sumPay'], $this->arParams["SELL_CURRENCY"]), Loc::getMessage("SAP_BASKET_NAME", array('#NAME#' => 'NAME'))),
			"PRODUCT_PROVIDER_CLASS" => $this->arParams["PRODUCT_PROVIDER_CLASS"]
		);

		$result = $basketItem->setFields($productFields);

		if (!$result->isSuccess()) {
			$this->error->add($result->getErrors());
		}

		$propertyCollection = $basketItem->getPropertyCollection();

		if ($this->savePropertyCharge) {
			$propertyItem = $propertyCollection->createItem();
			$result = $propertyItem->setFields([
				'NAME' => 'SUM_OF_CHARGE',
				'CODE' => 'SUM_OF_CHARGE',
				'VALUE' => $this->arResult['sumPay'],
				'SORT' => 100
			]);

			if (!$result->isSuccess()) {
				$this->error->add($result->getErrors());
			}
		}
	}

	protected function initOrder()
	{
		$orderClassName = $this->registry->getOrderClassName();
		$this->order = $orderClassName::create(SITE_ID, $this->arResult['userId'], $this->arParams['SELL_CURRENCY']);
		$result = $this->order->setBasket($this->basket);
		$result = $this->order->setPersonTypeId($this->arParams['PERSON_TYPE']);

		if (!$result->isSuccess()) {
			$this->error->add($result->getErrors());
		}

		if (!$result->isSuccess()) {
			$this->error->add($result->getErrors());
		}

		$this->initOrderShipment();
	}

	protected function orderPayment()
	{
		$this->savePropertyCharge = true;
		$this->initBasket();

		if(!$this->basket) {
			return;
		}

		$this->initOrder();

		if (!$this->order) {
			return;
		}

		$paySystemObject = PayManager::getObjectById($this->arResult['paySystem']);

		if (empty($paySystemObject)) {
			$this->error->setError(new Error(Loc::getMessage('SAP_ERROR_ORDER_PAYMENT_SYSTEM')));

			return;
		}

		$paymentCollection = $this->order->getPaymentCollection();
		$payment = $paymentCollection->createItem();

		$paymentResult = $payment->setFields(array(
			'SUM' => $this->order->getPrice(),
			'CURRENCY'=> $this->arParams['SELL_CURRENCY'],
			'PAY_SYSTEM_ID' => $paySystemObject->getField('ID'),
			'PAY_SYSTEM_NAME' => $paySystemObject->getField('NAME')
		));

		if (!$paymentResult->isSuccess()) {
			$this->error->add($paymentResult->getErrors());
			return;
		}

		if ($firstProfileId = Sale\OrderUserProperties::getFirstId($this->order->getPersonTypeId(), $this->order->getUserId())) {
			$resultUserProperties = Sale\Internals\UserPropsValueTable::getList([
				'filter' => array("=USER_PROPS_ID" => $firstProfileId),
				'select' => array("ORDER_PROPS_ID", "VALUE")
			]);

			while ($userProperty = $resultUserProperties->fetch()) {
				$propertiesValueList[$userProperty["ORDER_PROPS_ID"]] = $userProperty['VALUE'];
			}

			if (!empty($propertiesValueList) && is_array($propertiesValueList)) {
				$propertyCollection = $this->order->getPropertyCollection();

				foreach ($propertyCollection as $property) {
					$propertyOrderId = (int)($property->getField('ORDER_PROPS_ID'));

					if (!empty($propertiesValueList[$propertyOrderId])) {
						$property->setValue($propertiesValueList[$propertyOrderId]);
					}
				}
			}
		}

		$affiliateID = CSaleAffiliate::GetAffiliate();

		if ($affiliateID > 0) {
			$affiliateBase = CSaleAffiliate::GetList([], ["SITE_ID" => MContext::getCurrent()->getSite(), "ID" => $affiliateID]);
			$affiliates = $affiliateBase->Fetch();

			if (count($affiliates) > 1) {
				$this->order->setField('AFFILIATE_ID', $affiliateID);
			}
		}

		$resultSaving = $this->order->save();

		if ($resultSaving->isSuccess()) {
			$values = $paySystemObject->getFieldsValues();

			$this->arResult['order'] = [
				"ORDER_ID" => $this->order->getId(),
				"ORDER_DATE" => $this->order->getDateInsert()->toString(),
				"PAYMENT_ID" => $payment->getId(),
				"IS_CASH" => $paySystemObject->isCash() || $paySystemObject->getField("ACTION_FILE") === 'cash',
				"NAME_CONFIRM_TEMPLATE" => $this->arParams['NAME_CONFIRM_TEMPLATE']
			];

			if ($returnUrl = $this->arParams['RETURN_URL']) {
				$paySystemObject->getContext()->setUrl($returnUrl);
			}

			$paySystemBufferedOutput = $paySystemObject->initiatePay($payment, null, BaseServiceHandler::STRING);

			if ($paySystemBufferedOutput->isSuccess()) {
				$this->arResult['order']["TEMPLATE"] = $paySystemBufferedOutput->getTemplate();
				$this->arResult['paySuccess'] = 'y';
			} else {
				$this->error->add($paySystemBufferedOutput->getErrors());
			}
		} else {
			$this->error->add($resultSaving->getErrors());
		}
	}

	public function executeComponent()
	{
		$templateName = null;

		$this->getDataPay();

		if ($this->error->isEmpty()) {
			$this->setRegistry();

			if($this->isPay()) {
				$this->orderPayment();
				$templateName = $this->arParams['NAME_CONFIRM_TEMPLATE'];
			} else {
				$this->fillArrayResult();
			}
		}

		$this->formatResultErrors();
		$this->includeComponentTemplate($templateName);
	}
}
