<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\UserTable;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Catalog\PriceTable as Price;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;

use Lang\Academy\Messages;
use Lang\Academy\Util as Academy;

Loader::includeModule("sale");
Loader::includeModule("im");

class LangLkOrderPay extends CBitrixComponent
{
    private object $user;
    private object $order;
    private string $langServiceOrder = "LangServiceOrder";
    private string $langTransact = "LangTransact";

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);

        $this->user = CurrentUser::get();
    }

    public function onPrepareComponentParams($arParam)
    {
        if(intval($this->request->getPost('orderId')) > 0) {
            $arParam['orderId'] = intval($this->request->getPost('orderId'));
        } else {
            $arParam['orderId'] = intval($arParam['orderId']);
        }

        if(intval($this->request->getPost('chatId')) > 0) {
            $arParam['chatId'] = intval($this->request->getPost('chatId'));
        } else {
            $arParam['chatId'] = intval($arParam['chatId']);
        }

        if($arParam['orderId'] <= 0) {
            $this->arResult['error']['notorder'] = "Нет такого заказа";
        }

        if($arParam['chatId'] <= 0) {
            $this->arResult['error']['notorder'] = "Нет такого заказа";
        }

        return $arParam;
    }

    private function isPay(): bool
    {
        if($this->request->getPost('pay') == 'y') {
            return true;
        } else {
            return false;
        }
    }

    private function setLog($message)
    {
        $date = date('d.m.Y');
        $path = "{$_SERVER['DOCUMENT_ROOT']}/local/modules/lang.academy/log";
        
        Academy::langWriteLog("{$date} {$message}", "{$path}/paylog.log");
    }

    private function setAccountSum($operation = "+")
    {
        if($operation == "-") {
            $message = "Оплата заказа №{$this->arResult['orderId']}";
        } else {
            $message = "Возврат оплаты заказа №{$this->arResult['orderId']}";
        }

        return \CSaleUserAccount::UpdateAccount(
            $this->arResult['user'],
            "{$operation}{$this->arResult['sum']}",
            $this->arResult['currency'],
            $message,
            $this->arResult['orderId'],
            $message
        );
    }

    private function setPayOrder()
    {
        $payment = $this->order->getPaymentCollection()->getItemByIndex(0);

        $payment->setField('COMMENTS', "Оплата заказа №{$this->arResult['orderId']}");
        $payment->setField("PS_STATUS_DESCRIPTION", "Оплата заказа №{$this->arResult['orderId']}");
        $payment->setField("PS_STATUS_MESSAGE", "Оплата заказа №{$this->arResult['orderId']}");
        $payment->setPaid('Y');

        $this->order->setFieldNoDemand('SUM_PAID', $this->arResult['sum']);
        $this->order->setField('STATUS_ID', 'RN');
        $this->order->doFinalAction(true);

        return $this->order->save();
    }

    private function setOrderService()
    {
        $service = Data::getTableString($this->langServiceOrder)::getList([
            'select' => ['ID'],
            'filter' => ['order.VALUE' => $this->arResult['orderId']]
        ])->fetchObject();

        if(!empty($service)) {
            $service->set('tct', $this->arResult['transact'])->save();
            return $service;
        }

        return false;
    }

    private function payOrder()
    {
        $paySum = Data::getTableString($this->langTransact)::createObject()
            ->setName("Оплата заказа №{$this->arResult['orderId']}")
            ->set('to', 6) // 'credit'
            ->set('order', $this->arResult['orderId'])
            ->set('uFrom', $this->arResult['user'])
            ->set('uTo', $this->arResult['responsible'])
            // ->set('tct', '')
            ->set('sum', $this->arResult['sum']);

        $paySumSave = $paySum->save();

        if($paySumSave->isSuccess()) {
            $this->arResult['transact'] = $paySumSave->getId();
            $this->setOrderService();
            $orderSave = $this->setPayOrder();

            if($orderSave->isSuccess()) {
                $status = StatusLangTable::getList([
                    'select' => ['NAME'],
                    'filter' => ['LID' => SITE_ID, 'STATUS_ID' => 'RN'],
                    'cache' => Data::getCache()
                ])->fetchObject();

                Messages::payOrder([
                    'user' => $this->arResult['user'],
                    'chatId' => $this->arResult['chatId']
                ]);

                Messages::statusChange([
                    'user' => $this->arResult['user'],
                    'chatId' => $this->arResult['chatId'],
                    'status' => $status->getName()
                ]);

                $this->arResult['paySuccess'] = 'y';
            } else {
                $this->setLog($orderSave->getErrorMessage());
                $this->arResult['error']['pay'] = "Не удалось оплатить заказ";
                $this->arResult['paySuccess'] = 'n';
            }
        } else {
            $this->setLog($paySumSave->getErrorMessage());
            $this->arResult['error']['pay'] = "Не удалось оплатить заказ";
            $this->arResult['paySuccess'] = 'n';
        }
    }

    private function getOrder()
    {
        $this->order = Order::load($this->arParams['orderId']);

        if(!empty($this->order) && !empty($this->order->getId()) && $this->order->getUserId() == $this->user->getId()) {
            $this->arResult['sum'] = intval($this->order->getPrice());
            $this->arResult['currency'] = $this->order->getCurrency();
            $this->arResult['orderId'] = $this->order->getId();
            $this->arResult['user'] = $this->order->getUserId();
            $this->arResult['responsible'] = $this->order->getField("RESPONSIBLE_ID");
            $this->arResult['chatId'] = $this->arParams['chatId'];

            $accountList = CSaleUserAccount::GetList(
                ["CURRENCY" => "ASC"],
                ["USER_ID" => ($this->user->getId())],
                false, false, ["ID", "CURRENT_BUDGET"]
            );

            $account = $accountList->Fetch();
            
            if(!empty($account) && intval($account['CURRENT_BUDGET']) >= $this->arResult['sum']) {
                $this->arResult["budget"] = intval($account["CURRENT_BUDGET"]);
            } else {
                $this->arResult["budget"] = intval($account["CURRENT_BUDGET"]) > 0 ? $account["CURRENT_BUDGET"] : 0;
                $this->arResult['error']['budget'] = "Недостаточно средств. На текущем счету " . \SaleFormatCurrency($this->arResult['budget'], $this->arResult['currency']);
            }


        } else {
            $this->arResult['error']['orderpay'] = "Нет такого заказа или заказ уже оплачен";
        }
    }

    private function isIncludeTemplate(): bool
    {
        $isInclude = true;

        foreach($this->arResult['error'] as $code => $message) {
            switch($code) {
                case "notorder":
                case "orderpay":
                    $isInclude = false;
                    break;
            }
        }

        return $isInclude;
    }

    public function executeComponent()
    {
        if(empty($this->arResult['error'])) {
            $this->getOrder();

            if(empty($this->arResult['error'])) {
                
                if($this->isPay()) {
                    $this->payOrder();
                }
            }
        }

        if($this->isIncludeTemplate()) {
            $this->includeComponentTemplate();
        }
    }
}