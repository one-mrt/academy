<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Application;
use Bitrix\Main\Engine\CurrentUser;

class LangTransact extends CBitrixComponent
{
    private array $message = [
        "ORDER_PAY" => "Оплата заказа",
        "ORDER_UNPAY" => "Возвтрат средств по заказу"
    ];
    public function onPrepareComponentParams($arParam)
    {
        return $arParam;
    }

    private function getFilterData()
    {
        $request = Context::getCurrent()->getRequest();
        $session = Application::getInstance()->getSession();
        $sessionFilter = $session->get('transactFilter');

        $this->arResult['filter'] = [];
        $this->arResult['next'] = (intval($request->get('page')) > 0) ? intval($request->get('page')) : 1;

        if($this->arParams['type'] == 'ajax') {
            if(!empty($request->get('debit'))) {
                $this->arResult['filter']['debit'] = (htmlspecialcharsbx($request->get('debit')) == 'y') ? 'y' : 'n';
            }

            $this->arResult['filter']['date'] = htmlspecialcharsbx($request->get('date'));
        } else {
            if(!empty($request->get('debit'))) {
                $this->arResult['filter']['debit'] = (htmlspecialcharsbx($request->get('debit')) == 'y') ? 'y' : 'n';
            } elseif(!empty($sessionFilter['debit'])) {
                $this->arResult['filter']['debit'] = $sessionFilter['debit'];
            }

            if(!empty($request->get('date'))) {
                $this->arResult['filter']['date'] = htmlspecialcharsbx($request->get('date'));
            } elseif(!empty($sessionFilter['date'])) {
                $this->arResult['filter']['date'] = $sessionFilter['date'];
            }
        }

        foreach($this->arResult['filter'] as $key => $value) {
            if(empty($value)) {
                unset($this->arResult['filter'][$key]);
            }
        }

        $session->set('transactFilter', $this->arResult['filter']);
    }

    private function getNavStartParam() : array
    {
        return [
            // "nTopCount" => 3, // Ограничить до 20 записей
            "iNumPage" => $this->arResult['next'],
            "nPageSize" => 3,
        ];
    }

    public function executeComponent()
    {
        Loader::includeModule('sale');

        $this->arResult['transact'] = [];
        $this->arResult['count'] = 0;
        $user = CurrentUser::get();
        $arFilter = ["USER_ID" => $user->getId()];

        $this->getFilterData();

        foreach($this->arResult['filter'] as $fName => $fValue) {
            switch($fName) {
                case 'debit':
                    $arFilter['DEBIT'] = $fValue == 'y' ? 'Y' : 'N';
                    break;
                case 'date':
                    $arFilter['>=TRANSACT_DATE'] = date('d.m.Y', strtotime($fValue));
                    break;
            }
        }

        $transactList = CSaleUserTransact::GetList(
            ["TRANSACT_DATE" => "DESC", "ID" => "DESC"],
            $arFilter, false,
            $this->getNavStartParam(),
            ["AMOUNT", "CURRENCY", "DEBIT", "DESCRIPTION", "ORDER_ID", 'NOTES', "TRANSACT_DATE"]
        );

        if($transactList->NavPageCount > $transactList->NavPageNomer) {
            $this->arResult['next'] = $transactList->NavPageNomer + 1;
            $this->arResult['NavBtnShow'] = false;
        }

        $countTransact = CSaleUserTransact::GetList([],["USER_ID" => $user->getId()],["USER_ID", "COUNT" => "USER_ID"],false,["USER_ID"])->Fetch();

        if(!empty($countTransact)) {
            $this->arResult['count'] = $countTransact['CNT'];
        }

	    while ($transact = $transactList->Fetch()) {
            $sum = SaleFormatCurrency($transact["AMOUNT"], $transact["CURRENCY"]);

            if($transact["DEBIT"] == 'Y') {
                $sum = "+{$sum}";
            } else {
                $sum = "-{$sum}";
            }

            $message = "";

            if(!empty($transact['NOTES'])) {
                $message = $transact['NOTES'];
            } else {
                $message = "{$this->message[$transact["DESCRIPTION"]]} №{$transact['ORDER_ID']}";
            }

            $this->arResult['transact'][] = [
                "sum" => $sum,
                "date" => date("d.m.Y", strtotime($transact["TRANSACT_DATE"])),
                "desc" => $message,
                "debit" => $transact["DEBIT"]
            ];
        }

        $this->IncludeComponentTemplate();

        return $this->arResult;
    }
}