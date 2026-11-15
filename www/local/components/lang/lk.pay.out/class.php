<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Application;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Catalog\PriceTable as Price;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\SiteTable;
use Bitrix\Iblock\PropertyTable;

use Bitrix\Main\Text\HtmlFilter;

use Lang\Academy\Util as Academy;
use Lang\Academy\Messages;

class LangPayOut extends CBitrixComponent
{
    private array $field = [
        'lName' => [
            'title' => 'Введите фамилию',
            'placeholder' => 'Иванов',
            'type' => "text",
            'inputype' => 'text'
        ],
        'nName' => [
            'title' => 'Введите имя',
            'placeholder' => 'Иван',
            'type' => "text",
            'inputype' => 'text'
        ],
        'fName' => [
            'title' => 'Введите отчество',
            'placeholder' => 'Иванович',
            'type' => "text",
            'inputype' => 'text'
        ],
        'inn' => [
            'title' => 'Введите ИНН',
            'placeholder' => '10-12 цифр',
            'min' => 10,
            'max' => 12,
            'type' => "inn",
            'inputype' => 'tel'
        ],
        'card' => [
            'title' => 'Введите номер карты',
            'placeholder' => 'XXXX XXXX XXXX XXXX',
            'min' => 16,
            'max' => 16,
            'type' => "card",
            'inputype' => 'tel'
        ],
        'nCard' => [
            'title' => 'Введите имя владельца карты',
            'placeholder' => 'IVAN IVANOV',
            'type' => "nameCard",
            'inputype' => 'text'
        ],
        'spb' => [
            'title' => 'Введите номер телефона',
            'placeholder' => '+7 (999) 999-99-99',
            'type' => "phone",
            'inputype' => 'tel'
        ],
        'cos' => [
            'title' => 'Введите номер кошелька',
            'placeholder' => 'Укажите номер кошелька',
            'type' => "wallet",
            'inputype' => 'text'
        ],
    ];
    private string $langBalansOutAccount = "LangBalansOutAccount";
    private string $langBalansPaySystem = "LangBalansPaySystem";
    private string $langBalansOutLead = "LangBalansOutLead";
    private object $paySystems;
    private object $payAccount;
    private object $user;
    // private object $session;

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);

        $this->user = CurrentUser::get();
        $this->paySystems = Data::getTableString($this->langBalansPaySystem)::query();
        $this->payAccount = Data::getTableString($this->langBalansOutAccount)::query();

        $this->arResult['userId'] = intval($this->user->getId());

        if($this->arResult['userId'] > 0) {
            $this->arResult['userName'] = Academy::getUserFullName($this->arResult['userId']);

            $budgetData = CSaleUserAccount::GetList(
                ["CURRENCY" => "ASC"],
                ["USER_ID" => ($this->arResult['userId'])],
                false,
                false,
                ["ID", "CURRENT_BUDGET", 'CURRENCY']
            );

            if(!empty($budget = $budgetData->Fetch())) {
                $this->arResult['budget'] = intval($budget["CURRENT_BUDGET"]);
                $this->arResult['currency'] = $budget['CURRENCY'];
            } else {
                $this->arResult['budget'] = 0;
                $this->arResult['currency'] = CurrencyManager::getBaseCurrency();
            }
        } else {
            $this->arResult['error'][] = "Уууу";
        }
    }

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    private function isAccount(): bool
    {
        foreach($this->arResult['accountList'] as $account) {
            if($account['id'] == $this->arResult['payCard']) {
                return true;
            }
        }

        return false;
    }

    private function isSumOut(): bool
    {
        if($this->arResult['budgetOut'] >= $this->arResult['sumPayNumber']) {
            return true;
        }

        return false;
    }

    private function isPaySystem(): bool
    {
        foreach($this->arResult['accountList'] as $account) {
            if($account['pay']['id'] == $this->arResult['paySystem']) {
                return true;
            }
        }

        return false;
    }

    private function getPostParams()
    {
        if(intval($this->request->getPost('paySystem')) > 0) {
            $this->arResult['paySystem'] = intval($this->request->getPost('paySystem'));
        } else {
            $this->arResult['error']['notpay'] = "Не выбрана платежная система";
        }

        if($this->request->getPost('addPaySystem') == 'y') {
            $this->arResult['addPaySystem'] = 'y';
        }

        if($this->request->getPost('addPaySum') == 'y') {
            $this->arResult['addPaySum'] = 'y';
            $this->arResult['sumPay'] = HtmlFilter::encode($this->request->getPost('sumPay'));
            $this->arResult['sumPayNumber'] = intval(preg_replace('/[^0-9]/', '', $this->arResult['sumPay']));
            $this->arResult['payCard'] = intval($this->request->getPost('payCard'));
        }
    }

    private function prepareAddPaySystem()
    {
        foreach($this->arResult['payData']['field'] as $fieldName => &$field) {
            $newField = HtmlFilter::encode($this->request->getPost($fieldName));

            if(!empty($newField)) {
                $field['value'] = $newField;
            } else {
                $this->arResult['error'][] = "Поле {$field['title']} не должно быть пустым";
            }
        }
    }

    private function addPaySystem()
    {
        if($this->arResult['addPaySystem'] == 'y') {
            $this->prepareAddPaySystem();

            if(empty($this->arResult['error'])) {

                $newPayAccount = Data::getTableString($this->langBalansOutAccount)::createObject()
                    ->setActive(true)
                    ->set('pays', $this->arResult['paySystem'])
                    ->set('usr', $this->arResult['userId']);

                $accountNumber = "";

                foreach($this->arResult['payData']['field'] as $fieldName => $field) {
                    $newPayAccount->set($fieldName, $field['value']);

                    switch($field['type']) {
                        case "card":
                            $accountNumber = "*".substr($field['value'], -4);
                            break;
                        case "wallet":
                        case "phone":
                            $accountNumber = $field['value'];
                            break;
                    }
                }

                $save = $newPayAccount
                    ->setName("{$this->arResult['payData']['name']} {$accountNumber}")
                    ->save();

                if($save->isSuccess()) {
                    unset($this->arResult['accountList']);
                    unset($this->arResult['message']);
                    unset($this->arResult['error']);
                    $this->arResult['savePaySystem'] = 'y';
                    $this->getPaySystem();
                } else {
                    $this->arResult['error'][] = "Не удалось сохранить {$this->arResult['payData']['name']}";
                }
            }
        }
    }

    private function formanNumber($number = 0)
    {
        return \SaleFormatCurrency($number, $this->arResult['currency']);
    }

    private function sendMessageOutSum()
    {
        // Платежная система
        // Счет пользователя
        // Сумма вывода
        // Пользователь

        $site = SiteTable::getList([
            'select' => ['SERVER_NAME'],
            'filter' => ['LID' => SITE_ID],
            'cache' => Data::getCache()
        ])->fetchObject();

        $mailFields = [
            'leadName' => "{$this->arResult['userName']} - Заявка на вывод средств",
            'leadId' => $this->arResult['lead'],
            'linkLead' => "https://{$site->getServerName()}/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=16&type=lang_ru_financy&lang=ru&ID={$this->arResult['lead']}&find_section_section=0&WF=Y",
            'userName' => $this->arResult['userName'],
            'sumCom' => $this->arResult['sumComEndPrint'],
            'sumOut' => $this->arResult['sumPayNumberPrint'],
            'payTable' => "",
            'cardTable' => "",
        ];

        $picturePay = "https://{$site->getServerName()}" . \CFile::GetPath($this->arResult['payData']['picture']);
        $mnCom = $this->formanNumber($this->arResult['payData']['mncom']);
        $mn = $this->formanNumber($this->arResult['payData']['mn']);
        $mx = $this->formanNumber($this->arResult['payData']['mx']);

        $mailFields['payTable'] = '<table style="width:100%;border-collapse: collapse;" border="1">';
        $mailFields['payTable'] .= "<tr><td colspan=\"2\" style=\"padding: 4px 8px;\"><img src=\"{$picturePay}\" width=\"50\" height=\"50\" style=\"object-position: center;object-fit: cover;\"></td></tr>";
        $mailFields['payTable'] .= "<tr><td style=\"padding: 4px 8px;\">Наименование платежной системы</td>";
        $mailFields['payTable'] .= "<td style=\"padding: 4px 8px;\">{$this->arResult['payData']['name']}</td></tr>";
        $mailFields['payTable'] .= "<tr><td style=\"padding: 4px 8px;\">Коммисия</td>";
        $mailFields['payTable'] .= "<td style=\"padding: 4px 8px;\">{$this->arResult['payData']['com']}%</td></tr>";
        $mailFields['payTable'] .= "<tr><td style=\"padding: 4px 8px;\">Минимальная сумма комиссии</td>";
        $mailFields['payTable'] .= "<td style=\"padding: 4px 8px;\">{$mnCom}</td></tr>";
        $mailFields['payTable'] .= "<tr><td style=\"padding: 4px 8px;\">Минимальная сумма вывода</td>";
        $mailFields['payTable'] .= "<td style=\"padding: 4px 8px;\">{$mn}</td></tr>";
        $mailFields['payTable'] .= "<tr><td style=\"padding: 4px 8px;\">Максимальная сумма вывода</td>";
        $mailFields['payTable'] .= "<td style=\"padding: 4px 8px;\">{$mx}</td></tr>";
        $mailFields['payTable'] .= '</table>';

        // $propertyCodeName = array_keys($this->arResult['payData']['sort']);
        $propertyCodeName = [];
        $select = ['IBLOCK_ID'];

        foreach($this->arResult['payData']['sort'] as $iCodeName => $item) {
            $select["{$iCodeName}_"] = "{$iCodeName}.VALUE";
            $propertyCodeName[] = $iCodeName;
        }

        $payAccount = Data::getTableString($this->langBalansOutAccount)::query()
            ->setSelect($select)
            ->where('ACTIVE', 'Y')
            ->where('usr.VALUE', $this->arResult['userId'])
            ->where('pays.VALUE', $this->arResult['paySystem'])
            ->where('ID', $this->arResult['payCard'])
            ->fetch();

        $property = PropertyTable::getList([
            'filter' => ['IBLOCK_ID' => $payAccount['IBLOCK_ID'], 'CODE' => $propertyCodeName],
            'select' => ['NAME', 'CODE'],
            'cache' => Data::getCache()
        ])->fetchAll();

        $mailFields['cardTable'] = '<table style="width:100%;border-collapse: collapse;" border="1">';

        foreach($property as $prop) {
            $mailFields['cardTable'] .= "<tr><td style=\"padding: 4px 8px;\">{$prop["NAME"]}</td>";
            $mailFields['cardTable'] .= "<td style=\"padding: 4px 8px;\">{$payAccount["{$prop['CODE']}_"]}</td></tr>";
        }

        $mailFields['cardTable'] .= '</table>';

        Event::send([
            "EVENT_NAME" => "onOrderSumOut",
            'MESSAGE_ID' => 182,
            "LID" => SITE_ID,
            "C_FIELDS" => $mailFields,
        ]);

        Messages::budgetOutOrder([
            'toUser' => $this->arResult['userId'],
            'leadId' => $this->arResult['lead'],
            'sumOut' => $this->arResult['sumPayNumberPrint']
        ]);

        // Academy::langWriteLog([$this->arResult, $mailFields, $payAccount, $select, $property], 'mailFields');
    }

    private function calcSumOut()
    {
        // Введенная сумма к выводу
        // $this->arResult['sumPayNumber'];
        // Расчитать сумму к выводу, она должна быть меньше или ровна budget

        if($this->arResult['payData']['com'] > 0) {
            $percent = $this->arResult['payData']['com'] / 100;
            $com = $this->arResult['sumPayNumber'] * $percent;

            if($com > $this->arResult['payData']['mncom']) {
                $this->arResult['sumComEnd'] = intval($this->arResult['sumPayNumber'] + $com);
            } else {
                $this->arResult['sumComEnd'] = intval($this->arResult['sumPayNumber'] + $this->arResult['payData']['mncom']);
            }
        } else {
            $this->arResult['sumComEnd'] = $this->arResult['sumPayNumber'];
        }

        $this->arResult['sumComEndPrint'] = $this->formanNumber($this->arResult['sumComEnd']);
        $this->arResult['sumPayNumberPrint'] = $this->formanNumber($this->arResult['sumPayNumber']);
        
    }

    // Окончательный вывод с отправкой сообщения и списанием со счета
    private function addPaySum()
    {
        if($this->arResult['addPaySum'] == 'y') {
            if($this->isSumOut() && $this->isAccount() && $this->isPaySystem()) {

                $this->calcSumOut();

                $lead = Data::getTableString($this->langBalansOutLead)::createObject()
                    ->setName("{$this->arResult['userName']} - Заявка на вывод средств")
                    ->set('pays', $this->arResult['paySystem'])
                    ->set('acc', $this->arResult['payCard'])
                    ->set('sum', $this->arResult['sumComEndPrint']) // $this->arResult['sumPay'])
                    ->set('usr', $this->arResult['userId']);

                $saveLead = $lead->save();

                if($saveLead->isSuccess()) {
                    $outSumUpdate = \CSaleUserAccount::UpdateAccount(
                        $this->arResult['userId'],
                        "-{$this->arResult['sumComEnd']}",
                        $this->arResult['currency'],
                        "MANUAL",
                        0,
                        "Вывод средств"
                    );
                    
                    if(intval($outSumUpdate) > 0) {
                        $this->arResult['saveSumOut'] = 'y';
                        $this->arResult['lead'] = $saveLead->getId();
                        $this->sendMessageOutSum();
                    } else {
                        $lead->delete();
                        $this->arResult['error'][] = "Внутренняя проблема, попробуйте вывести позже";
                    }
                } else {
                    $this->arResult['error'][] = "Внутренняя проблема, попробуйте вывести позже";
                }
            } else {
                $this->arResult['error'][] = "Некоректная сумма вывода";
            }
        }
    }

    private function getPaySystem()
    {
        $payData = $this->paySystems
            ->setSelect(['ID', 'NAME', 'PREVIEW_PICTURE', 'com.VALUE', 'mncom.VALUE', 'mn.VALUE', 'mx.VALUE', 'fd.ITEM'])
            ->where('ACTIVE', 'Y')
            ->where('ID', $this->arResult['paySystem'])
            ->fetchObject();

        if(!empty($payData)) {
            $this->arResult['payData']['id'] = $payData->getId();
            $this->arResult['payData']['name'] = $payData->getName();
            $this->arResult['payData']['picture'] = $payData->getPreviewPicture();
            $this->arResult['payData']['com'] = floatval($payData->getCom()?->getValue() ?? 0); // Комисия
            $this->arResult['payData']['mncom'] = floatval($payData->getMncom()?->getValue() ?? 0); // Минимальная сумма комисии (Руб)
            $this->arResult['payData']['mn'] = floatval($payData->getMn()?->getValue() ?? 0); // Минимальная сумма вывода (Руб)
            $this->arResult['payData']['mx'] = floatval($payData->getMx()?->getValue() ?? 0);
            $this->arResult['payData']['mnOut'] = $this->arResult['payData']['mn'];

            if($this->arResult['payData']['mx'] >= $this->arResult['budget']) {
                $this->arResult['payData']['mxOut'] = $this->arResult['budget'];
            } else {
                $this->arResult['payData']['mxOut'] = $this->arResult['payData']['mx'];
            }

            $this->arResult['payData']['mnPrint'] = $this->formanNumber($this->arResult['payData']['mn']);
            $this->arResult['payData']['mxPrint'] = $this->formanNumber($this->arResult['payData']['mx']);

            if($this->arResult['payData']['com'] > 0) {
                $com = ($this->arResult['payData']['com'] / 100) * $this->arResult['budget'];

                if($com > $this->arResult['payData']['mncom']) {
                    $budgetCom = $this->arResult['budget'] - $com;
                    $com2 = $budgetCom * ($this->arResult['payData']['com'] / 100);
                    $comEnd = $com - $com2;

                    $this->arResult['budgetOut'] = intval($budgetCom + $comEnd);
                    $this->arResult['comOut'] = ceil($com);
                    $this->arResult['comOutPrint'] = $this->formanNumber($this->arResult['comOut']);
                } else {
                    // $this->arResult['payData']['mnOut'] = $this->arResult['payData']['mn'] + $this->arResult['payData']['mncom'];
                    $this->arResult['budgetOut'] = intval($this->arResult['budget'] - $this->arResult['payData']['mncom']);
                    $this->arResult['comOut'] = ceil($this->arResult['payData']['mncom']);
                    $this->arResult['comOutPrint'] = $this->formanNumber($this->arResult['comOut']);
                }

                // Максимальная сумма вывода с учетом количества на счете
                $this->arResult['budgetOutFull'] = $this->arResult['budgetOut'] + $this->arResult['comOut'];
            } else {
                $this->arResult['budgetOut'] = intval($this->arResult['budget']);
                $this->arResult['budgetOutFull'] = $this->arResult['budgetOut'];
                $this->arResult['comOut'] = 0;
                $this->arResult['comOutPrint'] = $this->formanNumber($this->arResult['comOut']);
            }

            if($this->arResult['payData']['mn'] >= $this->arResult['budgetOut']) {
                $this->arResult['error']['payout'] = "Недостаточно средств для вывода";
            } else {
                // $this->arResult['sumOut'] = 
            }

            $this->arResult['payData']['sort'] = [];

            foreach($payData?->getFd()?->getAll() as $field) {
                $payDataField =& $this->arResult['payData']['field'][$field->getItem()->getXmlId()];
                $this->arResult['payData']['sort'][$field->getItem()->getXmlId()] = $field->getItem()->getSort();
                $payDataField = $this->field[$field->getItem()->getXmlId()];

                unset($payDataField);
            }

            asort($this->arResult['payData']['sort']);

            $payAccount = $this->payAccount
                ->setSelect(['ID', 'NAME'])
                ->where('ACTIVE', 'Y')
                ->where('usr.VALUE', $this->arResult['userId'])
                ->where('pays.VALUE', $this->arResult['paySystem'])
                ->fetchCollection();


            if(!empty($payAccount) && !$payAccount->isEmpty()) {
                foreach($payAccount as $account) {
                    $this->arResult['accountList'][] = [
                        'id' => $account->getId(),
                        'name' => $account->getName(),
                        'pay' => $this->arResult['payData']
                    ];
                }
            } else {
                $this->arResult['message']['notaccount'] = "Счетов не добавлено.";
            }
        } else {
            $this->arResult['error'] = "Не выбрана платежная система";
        }

        // Academy::langWriteLog($this->arResult);
    }

    public function executeComponent()
    {
        $this->getPostParams();

        if(empty($this->arResult['error'])) {
            $this->getPaySystem();
            $this->addPaySystem();
            $this->addPaySum();
        }

        if($this->arResult['saveSumOut'] == 'y') {
            $this->IncludeComponentTemplate("success");
        } else {
            $this->IncludeComponentTemplate();
        }
    }
}