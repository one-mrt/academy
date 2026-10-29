<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Pull\Event as PullEvent;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Iblock\ORM\PropertyValue;
use Bitrix\Catalog\PriceTable as Price;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;

use Lang\Academy\Messages;
use Lang\Academy\Util as Academy;
use Lang\Academy\Order as AOrder;
use Bitrix\Im\Recent;

Loader::includeModule("sale");
Loader::includeModule("im");

class LangLkOrderEdit extends CBitrixComponent
{
    private object $user;
    private object $order;
    private object $query;
    private object $chat;
    private string $langServiceOrder = "LangServiceOrder";
    private string $lkSpecTable = "LangLkSpec";
    private string $langLkTypeWork = "LangLkTypeWork";
    private string $langTransact = "LangTransact";
    private array $langStatusResult = ['RN', 'CP', 'MN', 'DT', 'CN', 'F'];

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);

        $this->user = CurrentUser::get();
        $this->query = Data::getTableString($this->langServiceOrder)::query();
        $this->arResult['currentUserId'] = $this->user->getId();
        $this->chat = new \CIMChat(0);
    }

    private function getSelectList(string $key, string $apiCode)
    {
        $this->arResult[$key] = [];
        $list = Data::getElementsObject($apiCode, ['select' => ['NAME']]);

        if(!empty($list) && !empty($list->getIdList()))
            foreach ($list as $item)
                $this->arResult[$key][$item->getId()] = $item->getName();
    }

    public function onPrepareComponentParams($arParam)
    {
        if(intval($this->request->getPost('orderId')) > 0) {
            $arParam['orderId'] = intval($this->request->getPost('orderId'));
        } else {
            $arParam['orderId'] = intval($arParam['orderId']);
        }

        if($arParam['orderId'] <= 0) {
            $this->arResult['error']['notorder'] = "Нет такого заказа";
        }

        if(intval($this->arResult['currentUserId']) <= 0) {
            $this->arResult['error']['notuser'] = "Нет такого пользователя";
        }

        switch($this->request->getPost('typeEdit')) {
            case "description":
                $this->arResult['typeEdit'] = "description";
                $this->query->addSelect('PREVIEW_TEXT');
                break;
            case "file":
                $this->arResult['typeEdit'] = "file";
                $this->query->addSelect('file.VALUE');
                break;
            case "all":
                $this->getSelectList("typeWork", $this->langLkTypeWork);
                $this->getSelectList("specList", $this->lkSpecTable);

                $this->arResult['typeEdit'] = "all";
                $this->query
                    // ->addSelect('sSpec.VALUE')
                    ->addSelect('pred.VALUE')
                    ->addSelect('tWork.VALUE')
                    ->addSelect('ded.VALUE')
                    ->addSelect('anti.VALUE')
                    ->addSelect('PRICE.PRICE')
                    ->addSelect('PRICE.CURRENCY')
                    ->registerRuntimeField((new Reference('PRICE', Price::class, Join::on('this.ID','ref.PRODUCT_ID')))->configureJoinType(Join::TYPE_LEFT));
                break;
            case "fileResult":
                $this->arResult['typeEdit'] = "fileResult";
                $this->query->addSelect('res.VALUE');
                break;
            case "status":
                $this->arResult['typeEdit'] = "status";
                $this->arResult['statusEdit'] = htmlspecialcharsbx($this->request->getPost("status"));
                break;
            default:
                $this->arResult['error']["notedit"] = "Нечего редактировать";
                $this->arResult['error']["status"] = $this->request->getPost('typeEdit');
                break;
        }

        return $arParam;
    }

    private function getParser()
    {
        $this->parser = new \CTextParser();
        $this->parser->allow = array(
            "HTML" => "N",
            "ANCHOR" => "Y",  // ссылки
            "BIU" => "Y",    // жирный, курсив, подчеркнутый
            // "IMG" => "Y",   // изображения
            // "QUOTE" => "Y", // цитаты
            // "CODE" => "Y",   // блоки кода
            // "FONT" => "Y",   // шрифты
            "LIST" => "Y",   // списки
            // "SMILES" => "Y", // смайлики
            // "NL2BR" => "Y", // перенос строк
            // "VIDEO" => "Y", // видео
            // "TABLE" => "Y", // таблицы
            "CUT_ANCHOR" => "N", // запрет на вставку закрывающего тега ссылки
            // "ALIGN" => "Y"  // выравнивание
        );
    }

    private function getUserEdit()
    {
        $user = UserTable::getList([
            'select' => ['ID', 'NAME', 'LAST_NAME', 'LOGIN'],
            'filter' => ['ID' => $this->arResult['currentUserId'], 'ACTIVE' => 'Y'],
        ])->fetchObject();
        $this->arResult['userEditName'] = Academy::getUserFullName(null, $user);
        $this->arResult['userEditUrl'] = "/experts/{$user->getLogin()}/";
    }

    private function getUserAnswer()
    {
        if(!empty($this->order->getUex())) {
            $userAnswer = UserTable::getList([
                'select' => ['ID'],
                'filter' => ['ID' => $this->order->getUex()?->getValue(), 'ACTIVE' => 'Y'],
            ])->fetchObject();

            if(!empty($userAnswer)) {
                $this->arResult['answerId'] = $this->order->getUex()->getValue();
            }

        }
    }

    private function getUserType()
    {
        $current = intval($this->arResult['currentUserId']);
        $customer = intval($this->arResult['customerId']);
        $answer = intval($this->arResult['answerId']);

        if($customer == $current) {
            $this->arResult['userType'] = "customer";
        } elseif($answer == $current) {
            $this->arResult['userType'] = "answer";
        } elseif($current == AOrder::MANAGER) {
            $this->arResult['userType'] = "manager";
        }
    }

    private function isUserCustomer(): bool
    {
        if($this->arResult['userType'] == "customer") {
            return true;
        }

        return false;
    }

    private function isUserAnswer(): bool
    {
        if($this->arResult['userType'] == "answer") {
            return true;
        }

        return false;
    }

    private function isUserManager(): bool
    {
        if($this->arResult['userType'] == "manager") {
            return true;
        }

        return false;
    }

    private function isEditStatus(): ?string
    {
        $current = $this->arResult['statusId'];
        $edit = $this->arResult['statusEdit'];
        $userType = $this->arResult['userType'];

        if(!empty($userType) && !empty($current) && !empty($edit)) {
            if(!empty(AOrder::ACCESS_STATUS[$userType][$current])) {
                if(in_array($edit, AOrder::ACCESS_STATUS[$userType][$current])) {
                    return $edit;
                }
            }
        }

        return null;
    }

    private function getDefaultFields()
    {
        $this->arResult['serviceId'] = $this->order->getId();

        if(!empty($this->order->getOrder())) {
            $this->arResult['id'] = $this->order->getOrder()?->getValue();
        }

        if(!empty($this->order->getCreatedBy())) {
            $this->arResult['customerId'] = $this->order->getCreatedBy();
        }

        if(!empty($this->order->get('STATUS'))) {
            $this->arResult['statusId'] = $this->order?->get('STATUS')?->getStatusId();
        }
    }

    private function setSave()
    {
        $this->addPullMessage();
        $this->arResult['saveSuccess'] = 'y';
    }

    private function customerEditDescription()
    {
        if($this->isUserCustomer() && $this->arResult['statusId'] == "RF" && $this->arResult['typeEdit'] == "description") {
            $this->arResult['description'] = htmlspecialcharsbx($this->request->getPost('description'));

            if(!empty($this->arResult['description'])) {
                $saveDescription = $this->order->setPreviewText($this->arResult['description'])->save();

                if($saveDescription->isSuccess()) {
                    $this->setSave();
                } else {
                    $this->arResult['error']['description'] = "Не получилось обновить описание заказа. Попробуйте перезагрузить страницу и попробовать еще раз";
                }
            } else {
                $this->arResult['description'] = $this->order->getPreviewText();
                $this->arResult['error']['description'] = "Поле описание заказа не может быть пустым";
            }
        }
    }

    private function fileCheck()
    {
        $this->arResult['file'] = $this->request->getFile('file');

        if(!empty($this->arResult['file']['name'])) {
            $fileSize = 0;

            foreach($this->arResult['file']['size'] as $key => $size) {
                if($this->arResult['file']['error'][$key] == '1') {
                    $this->arResult["error"][] = "Ошибка загрузки файла";
                }

                if(!in_array($this->arResult['file']['type'][$key], Academy::getAcceptFile())) {
                    $this->arResult["error"][] = "Не верный формат файла ({$this->arResult['file']['name'][$key]})";
                }

                $fileSize += intval($size);
            }

            if($fileSize > 5242880) {
                $this->arResult["error"][] = "Превышен размер файла в 5 MB";
            }
        }
    }

    private function addMessageFileLoad()
    {
        if($this->arResult['typeEdit'] == "file") {
            Messages::editFileOrder([
                'user' => $this->arResult['currentUserId'],
                'chatId' => $this->arResult["chatId"],
                'url' => $this->arResult["userEditUrl"],
                'userName' => $this->arResult['userEditName']
            ]);
        } elseif($this->arResult['typeEdit'] == "fileResult") {
            Messages::editFileResultOrder([
                'user' => $this->arResult['currentUserId'],
                'chatId' => $this->arResult["chatId"],
                'url' => $this->arResult["userEditUrl"],
                'userName' => $this->arResult['userEditName']
            ]);
        }
    }

    private function saveFileOrder()
    {
        if(!empty($this->arResult['fileId'])) {

            $fieldEdit = "";

            if($this->arResult['typeEdit'] == "file") {
                $fieldEdit = "file";
            } elseif($this->arResult['typeEdit'] == "fileResult") {
                $fieldEdit = "res";
            }

            if(!empty($fieldEdit)) {
                $this->order->removeAll($fieldEdit);
                
                foreach($this->arResult['fileId'] as $file) {
                    $this->order->addTo($fieldEdit, new PropertyValue($file));
                }

                $fileSave = $this->order->save();

                if($fileSave->isSuccess()) {
                    $this->addMessageFileLoad();
                    $this->setSave();
                } else {
                    $this->arResult['error']['description'] = "Не получилось обновить файлы заказа. Попробуйте перезагрузить страницу и попробовать еще раз";
                }
            }
        }
    }

    private function editFileOrder()
    {
        $this->fileCheck();

        if(!empty($this->arResult['file']) && empty($this->arResult['error'])) {
            $this->loadFile();
            $this->saveFileOrder();
        }
    }

    private function customerEditFile()
    {
        if($this->isUserCustomer() && $this->arResult['statusId'] == "RF" && $this->arResult['typeEdit'] == "file") {
            $this->editFileOrder();
        }
    }

    private function customerEditAll()
    {
        if($this->isUserCustomer() && $this->arResult['statusId'] == "RF" && $this->arResult['typeEdit'] == "all") {
            $type = intval($this->request->getPost('type'));
            $spec = intval($this->request->getPost('spec'));
            $date = htmlspecialcharsbx($this->request->getPost('date'));
            $summ = intval($this->request->getPost('summ'));
            $isPlag = $this->request->getPost('isPlag');
            $plag = intval($this->request->getPost('plag'));

            if($type > 0 && key_exists($type, $this->arResult['typeWork'])) {
                $this->order->set('tWork', $type);
            } else {
                $this->arResult['error'] = "Поле 'Тип работы' не может быть пустым";
            }

            if($spec > 0 && key_exists($spec, $this->arResult['specList'])) {
                $this->order->set('pred', $spec);
            } else {
                $this->arResult['error'] = "Поле 'Предмет' не может быть пустым";
            }

            if(!empty($date)) {
                if(strtotime($date) >= strtotime(date('d-m-Y'))) {
                    $date = date('d.m.Y H:i', strtotime($date));
                    $this->order->set('ded', $date);
                } else {
                    $this->arResult['error'][] = "Текущая дата должна быть больше сегодняшней";
                }
            } else {
                $this->arResult['error'][] = "Поле Дата сдачи не может быть пустым";
            }

            if($summ > 0) {
                $price = Price::getList(['select'=>['ID', 'PRICE', 'CURRENCY', 'CATALOG_GROUP_ID'],'filter'=>['PRODUCT_ID' => $this->arResult['serviceId']]])->fetch();

                if(!empty($price)) {
                    Price::update(
                        intval($price['ID']),
                        [
                            'PRICE' => $summ,
                            'CATALOG_GROUP_ID' => $price['CATALOG_GROUP_ID'],
                            'CURRENCY'=> $price['CURRENCY'],
                            'PRODUCT_ID' => $this->arResult['serviceId']
                        ]
                    );

                    $order = Order::load($this->arResult['id']);

                    if(!empty($order) && !empty($order->getId())) {
                        $paymentCollection = $order->getPaymentCollection();

                        foreach($paymentCollection as $payment) {
                            $payment->setField('SUM', $order->getPrice());
                            break;
                        }

                        $basket = $order->getBasket();
                        $basket->refresh();
                        $order->doFinalAction(true);
                        $order->save();
                    }
                }
            }

            if(!empty($isPlag)) {
                if($plag > 0) {
                    $this->order->set('anti', $plag);
                } else {
                    $this->arResult['error'][] = "Уникальность текста должна быть больше нуля";
                }
            } else {
                $this->order->set('anti', "");
            }

            if(empty($this->arResult['error'])) {
                $saveOrder = $this->order->save();

                if($saveOrder->isSuccess()) {
                    $this->setSave();
                } else {
                    $this->arResult['error'][] = "Не удалось сохранить заказ. Попробуйте перезагрузить страницу и попробовать еще";
                }
            }
        }
    }

    private function userStatusMessageData()
    {
        $statusName = StatusLangTable::getList([
            'select' => ['NAME'],
            'filter' => ['LID' => SITE_ID, 'STATUS_ID' => $this->arResult['statusEdit']],
            'cache' => Data::getCache(60*60*24*7)
        ])->fetchObject();
        $this->arResult['statusEditName'] = $statusName->getName();
    }

    private function messageNotifySendStatus()
    {
        if(!$this->isUserManager()) {
            $param = [
                'toUser' => $this->arResult['customerId'],
                'orderUrl' => $this->arResult['urlOrder'],
                'orderId' => $this->arResult['id'],
                'status' => $this->arResult['statusEditName']
            ];

            switch($this->arResult['statusEdit']) {
                case 'RF': // Черновик
                case 'N': // Открыт
                case 'PE': // Приватный
                    Messages::statusDefaultNotifyManager($param);
                    break;
                case 'OP': // Ожидание оплаты
                case 'RN': // Выполняется
                case 'CP': // Выполнен
                case 'MN': // На доработке
                case 'DT': // Спор
                case "CN": // Отменент
                case "F": // Закрыт
                    Messages::statusDefaultNotifyManager($param);
                    $param['toUser'] = $this->arResult['answerId'];
                    Messages::statusDefaultNotifyManager($param);
                    break;
            }
        }
    }

    private function editStatus()
    {
        $order = Order::load($this->arResult['id']);
        $paymentCollection = $order->getPaymentCollection();

        if(!empty($order) && !empty($order->getId())) {

            $paid = $paymentCollection->isPaid();

            // Если есть оплаты то нужно вернуть деньги
            if($this->arResult['statusEdit'] == 'CN') {
                if($paid) {
                    foreach($paymentCollection as $payment) {
                        $payment->setReturn("Y");
                        break;
                    }

                    Data::getTableString($this->langTransact)::query()
                        ->setSelect(['ID'])
                        ->where("order.VALUE", $this->arResult['id'])
                        ->fetchCollection()
                        ->walk(static function($transact, $key) use (&$processedKeys) {
                            $transact->setActive(false);
                        })->save();
                }

                $order->setField("CANCELED", "Y");
            } elseif($this->arResult['statusEdit'] == 'F') {
                $order->setField('STATUS_ID', $this->arResult['statusEdit']);
            } else {
                $order->setField('STATUS_ID', $this->arResult['statusEdit']);
            }

            $order->doFinalAction(true);
            $orderSave = $order->save();

            if($orderSave->isSuccess()) {
                $this->setSave();
                $this->userStatusMessageData();

                switch($this->arResult['statusEdit']) {
                    case 'N': // Открыт
                        if(!$this->isUserManager()) {
                            Messages::statusPublished([
                                'user' => $this->arResult['currentUserId'],
                                'chatId' => $this->arResult['chatId'],
                                'urlOrder' => $this->arResult['urlOrder'],
                                'orderId' => $this->arResult['id']
                            ]);
                        }
                        break;
                    case 'DT': // Спор
                        if(!$this->isUserManager()) {
                            Messages::statusDispute([
                                'user' => $this->arResult['currentUserId'],
                                'chatId' => $this->arResult['chatId'],
                                'urlExpert' => $this->arResult['userEditUrl'],
                                'expertName' => $this->arResult['userEditName'],
                                'status' => $this->arResult['statusEditName']
                            ]);
                        }
                        // Подключить менеджера
                        $this->chat->AddUser($this->arResult['chatId'], AOrder::MANAGER);
                        break;
                    case "CN": // Отменен
                        // $this->userStatusMessageData();

                        if(!$this->isUserManager()) {
                            Messages::statusDefault([
                                'user' => $this->arResult['currentUserId'],
                                'chatId' => $this->arResult['chatId'],
                                'urlExpert' => $this->arResult['userEditUrl'],
                                'expertName' => $this->arResult['userEditName'],
                                'status' => $this->arResult['statusEditName']
                            ]);
                        }
                        
                        Recent::hide($this->arResult['chatId']);
                        $this->chat->DeleteUser($this->arResult['chatId'], AOrder::MANAGER, false);

                        if($paid) {
                            // Сообщение что средства вернулись на на баланс заказчику
                        }
                        break;
                    case 'F':
                        // Попросить оставить отзыв
                        
                        // $this->userStatusMessageData();
                        Messages::statusDefault([
                            'user' => $this->arResult['currentUserId'],
                            'chatId' => $this->arResult['chatId'],
                            'urlExpert' => $this->arResult['userEditUrl'],
                            'expertName' => $this->arResult['userEditName'],
                            'status' => $this->arResult['statusEditName']
                        ]);

                        Recent::hide($this->arResult['chatId']);
                        $this->chat->DeleteUser($this->arResult['chatId'], AOrder::MANAGER, false);

                        if($paid) {
                            // Начислить исполнителю
                            $user = $order->getField("RESPONSIBLE_ID");
                            $sum = number_format($order->getPrice(), 0, '.', ' ');
                            $pay = \CSaleUserAccount::UpdateAccount(
                                $user,
                                $order->getPrice(),
                                $order->getCurrency(),
                                "MANUAL",
                                $this->arResult['id'],
                                "Оплата заказа №{$this->arResult['id']}"
                            );

                            if($pay) {
                                Data::getTableString($this->langTransact)::query()
                                    ->setSelect(['ID'])
                                    ->where("order.VALUE", $this->arResult['id'])
                                    ->fetchCollection()
                                    ->walk(static function($transact, $key) use (&$processedKeys) {
                                        $transact->setActive(false);
                                    })->save();
                                Messages::foundsCreditedAccountAnswer([
                                    'user' => $user,
                                    'sum' => $sum,
                                    'url' => $this->arResult['urlOrder'],
                                    'orderId' => $this->arResult['id']
                                ]);
                            }
                        }
                        break;
                    default:
                        // $this->userStatusMessageData();
                        
                        if(!$this->isUserManager()) {
                            Messages::statusDefault([
                                'user' => $this->arResult['currentUserId'],
                                'chatId' => $this->arResult['chatId'],
                                'urlExpert' => $this->arResult['userEditUrl'],
                                'expertName' => $this->arResult['userEditName'],
                                'status' => $this->arResult['statusEditName']
                            ]);
                        }
                        break;
                }

                $this->messageNotifySendStatus();
            } else {
                $arResult['error'][] = "Что то пошло не так";
            }
        }
    }

    private function customerEditStatus()
    {
        if($this->isUserCustomer() && $this->isEditStatus() && $this->arResult['typeEdit'] == "status") {
            $this->editStatus();
        }
    }

    private function answerEditFile()
    {
        if($this->isUserAnswer() && in_array($this->arResult['statusId'], $this->langStatusResult) && $this->arResult['typeEdit'] == "fileResult") {
            $this->editFileOrder();
        }
    }

    private function answerEditStatus()
    {
        if($this->isUserAnswer() && $this->isEditStatus() && $this->arResult['typeEdit'] == "status") {
            $this->editStatus();
        }
    }

    private function managerEditStatus()
    {
        if($this->isUserManager() && $this->isEditStatus() && $this->arResult['typeEdit'] == "status") {
            $this->editStatus();
        }
    }

    private function prepareOrder()
    {
        $this->getDefaultFields();
        $this->getUserAnswer();
        $this->getUserType();

        $this->arResult['urlOrder'] = "/lk/order/{$this->order->getCode()}/";
        $this->arResult['chatId'] = $this->order->getChat()?->getValue();

        if($this->isUserCustomer()) {
            if($this->arResult['statusId'] == "RF") {
                switch($this->arResult['typeEdit']) {
                    case "description":
                        if(!empty($this->order->getPreviewText())) {
                            $this->arResult['description'] = $this->order->getPreviewText();
                        }
                        break;
                    case "file":
                        if(!empty($this->order->getFile()) && !empty($this->order->getFile()->getAll())) {
                            $this->arResult['file'] = [];

                            $fileType = Academy::getAcceptFileIcon();

                            foreach($this->order->getFile()->getAll() as $fileValue) {
                                $file = \CFile::GetFileArray($fileValue->getValue());
                                $this->arResult['file'][] = [
                                    'name' => $file['ORIGINAL_NAME'],
                                    'svg' => $fileType[$file['CONTENT_TYPE']],
                                    'src' => $file['SRC']
                                ];
                            }
                        }
                        break;
                    case "all":
                        // if(!empty($this->order->getSspec())) {
                        //     $this->arResult['spec'] = $this->order->getSspec()?->getValue();
                        // }

                        if(!empty($this->order->getTwork())) {
                            $this->arResult['work'] = $this->order->getTwork()?->getValue();
                        }

                        if(!empty($this->order->getPred())) {
                            $this->arResult['pred'] = $this->order->getPred()?->getValue();
                        }

                        if(!empty($this->order->getAnti())) {
                            $this->arResult['anti'] = $this->order->getAnti()?->getValue();
                        } else {
                            $this->arResult['anti'] = "";
                        }

                        if(!empty($this->order->getDed())) {
                            $this->arResult['date'] = $this->order->getDed()?->getValue();
                        }

                        if(!empty($this->order->get('PRICE'))) {
                            if(intval($this->order->get('PRICE')->getPrice()) > 0) {
                                $this->arResult['price'] = intval($this->order->get('PRICE')->getPrice());
                            } else {
                                $this->arResult['price'] = 0;
                            }
                        }
                        break;
                }

                // $this->arResult['editDraft'] = 'y';
            }

            if(!empty($statusEdit = $this->isEditStatus())) {
                $this->arResult['edit']['status'] = $statusEdit;
            }

        } elseif($this->isUserAnswer()) {

            if($this->arResult['typeEdit'] == "fileResult" && in_array($this->arResult['statusId'], $this->langStatusResult)) {

                if(!empty($this->order->getRes()) && !empty($this->order->getRes()->getAll())) {
                    $this->arResult['fileRes'] = [];

                    $fileType = Academy::getAcceptFileIcon();

                    foreach($this->order->getRes()->getAll() as $fileValue) {
                        $file = \CFile::GetFileArray($fileValue->getValue());
                        $this->arResult['fileRes'][] = [
                            'name' => $file['ORIGINAL_NAME'],
                            'svg' => $fileType[$file['CONTENT_TYPE']],
                            'src' => $file['SRC']
                        ];
                    }
                }

                if($this->isUserAnswer()) {
                    $this->arResult['editFileRes'] = 'y';
                }
            }

            if(!empty($statusEdit = $this->isEditStatus())) {
                $this->arResult['edit']['status'] = $statusEdit;
            }
        } elseif($this->isUserManager()) {
            if(!empty($statusEdit = $this->isEditStatus())) {
                $this->arResult['edit']['status'] = $statusEdit;
            }
        } else {
            $this->arResult['error']['notEdit'] = "Нечего редактировать";
        }
    }

    private function getOrder()
    {
        $this->query
            ->addSelect('ID')
            ->addSelect('CREATED_BY')
            ->addSelect('CODE')
            ->addSelect('order.VALUE')
            ->addSelect('uex.VALUE')
            ->addSelect('chat.VALUE')
            ->addSelect('STATUS.ID')
            ->addSelect('STATUS.USER_ID')
            ->addSelect('STATUS.STATUS_ID')
            ->registerRuntimeField((new Reference('STATUS', Order::class, Join::on('this.order.VALUE','ref.ID')))->configureJoinType(Join::TYPE_LEFT))
            ->where('ACTIVE', 'Y')
            ->where('order.VALUE', $this->arParams['orderId']);

        $this->order = $this->query->fetchObject();

        if(empty($this->order)) {
            $this->arResult['error'] = 'Нет такого заказа';
        } else {
            $this->getParser();
            $this->prepareOrder();
        }
    }

    private function isEdit(): bool
    {
        if($this->request->getPost('edit') == 'y') {
            return true;
        } else {
            return false;
        }
    }

    private function isIncludeTemplate(): bool
    {
        $isInclude = true;

        foreach($this->arResult['error'] as $code => $message) {
            switch($code) {
                case "notorder":
                case "notedit":
                case "notuser":
                    $isInclude = false;
                    break;
            }
        }

        return $isInclude;
    }

    private function loadFile()
    {
        $folder = rand();
       
        $fileUploadDir = "{$_SERVER['DOCUMENT_ROOT']}/upload/oser/{$folder}";
        mkdir($fileUploadDir, 0755, true);

        foreach($this->arResult['file']['name'] as $key => $name) {
            $fileName = basename($name);
            $pathFile = "{$fileUploadDir}/{$fileName}";
            $linkFile = move_uploaded_file($this->arResult['file']['tmp_name'][$key], $pathFile);

            $this->arResult['removeFile'][] = $pathFile;
            $this->arResult['fileId'][] = \CFile::SaveFile(\CFile::MakeFileArray($pathFile), 'iblock'); //\CFile::MakeFileArray($pathFile);
        }

        if(!empty($this->arResult['removeFile'])) {
            foreach($this->arResult['removeFile'] as $file) {
                unlink($file);
            }

            rmdir($fileUploadDir);
        }
    }

    private function addPullMessage()
    {
        $users = [$this->arResult['answerId'], $this->arResult['customerId']];
        if($this->isUserManager()) {
            $users[] = AOrder::MANAGER;
        }

        PullEvent::add($users, [
            'module_id' => 'sale',
            'command' => 'langOrderUpdate',
            'params' => [
                'id' => $this->arResult['id'],
                'serviceId' => $this->arResult['serviceId'],
                'updateField' => 'all',
            ]
        ]);
    }

    public function executeComponent()
    {
        if(empty($this->arResult['error'])) {
            $this->getOrder();

            if(empty($this->arResult['error'])) {
                
                if($this->isEdit()) {
                    $this->getUserEdit();
                    $this->customerEditDescription();
                    $this->customerEditFile();
                    $this->customerEditAll();
                    $this->answerEditFile();
                    $this->customerEditStatus();
                    $this->answerEditStatus();
                    $this->managerEditStatus();
                }
            }
        }

        if($this->isIncludeTemplate()) {
            $this->includeComponentTemplate();
        }
    }
}