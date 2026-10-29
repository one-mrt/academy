<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\UserTable;
use Bitrix\Main\Application;
use Bitrix\Catalog\Model\Price;
use Bitrix\Catalog\ProductTable;
use Bitrix\Catalog\Model\Product;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Iblock\ORM\PropertyValue;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Engine\Response\Redirect;
use Bitrix\Im\Model\MessageTable;
use Bitrix\Main\Localization\Loc;

use Lang\Academy\Messages;
use Lang\Academy\Util as Academy;

use Bitrix\Sale\Order;
use Bitrix\Sale\Basket;
use Bitrix\Currency\CurrencyManager;
use Bitrix\Sale\Delivery\Services\Manager as Delivery;
use Bitrix\Sale\PaySystem\Manager as PaySystem;

use Bitrix\Im\Chat;
use Bitrix\Im\V2\Chat as v2Chat;

Loader::includeModule('im');
Loader::includeModule('sale');
Loader::includeModule("catalog");

class LangOrder extends CBitrixComponent
{
    private object $user;
    private object $order;
    private object $query;
    private $servicOrderTable = "LangServiceOrder";
    private $lkSpecTable = "LangLkSpec";

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);

        $this->user = CurrentUser::get();
        $this->query = Data::getTableString($this->servicOrderTable)::query();
        $this->arResult['userId'] = $this->user->getId();
    }

    public function onPrepareComponentParams($arParam){return $arParam;}

    private function getSelectList(string $key, string $apiCode)
    {
        $this->arResult[$key] = [];
        $list = Data::getElementsObject($apiCode, ['select' => ['NAME']]);

        if(!empty($list) && !empty($list->getIdList())) {
            foreach ($list as $item) {
                $this->arResult[$key][$item->getId()] = $item->getName();
            }
        }
    }

    private function prepareString(string $name = ""): string
    {
        $name = strip_tags($name);
        $name = htmlspecialcharsbx($name);

        return $name;
    }

    private function getFieldsRequestData()
    {
        $this->arResult['name'] = $this->prepareString($this->request->getPost('name'));
        $this->arResult['type'] = intval($this->request->getPost('type'));
        $this->arResult['spec'] = intval($this->request->getPost('spec'));
        $this->arResult['date'] = $this->prepareString($this->request->getPost('date'));
        $this->arResult['description'] = $this->request->getPost('description');
        $this->arResult['file'] = $this->request->getFile('file');
        $this->arResult['summ'] = intval($this->request->getPost('summ')) ?? 0;
        $this->arResult['isPlag'] = htmlspecialcharsbx($this->request->getPost('isPlag'));
        $this->arResult['plag'] = intval($this->request->getPost('plag'));

        if(empty($this->arResult['name']))
            $this->arResult["error"][] = "Поле <b>Наименование</b> не может быть пустым";

        if(empty($this->arResult['date'])) {
            $this->arResult["error"][] = "Поле <b>Срок сдачи</b> не может быть пустым";
        } else {
            $this->arResult['date'] = date('d.m.Y H:i', strtotime($this->arResult['date']));
        }

        if(empty($this->arResult['description']))
            $this->arResult["error"][] = "Поле <b>Описание</b> не может быть пустым";

        if(empty($this->arResult['type']))
            $this->arResult["error"][] = "Поле <b>Тип работы</b> не может быть пустым";
        elseif(!array_key_exists($this->arResult['type'], $this->arResult['typeWork']))
            $this->arResult['error'][] = "<b>Тип работы</b> отсутствует";

        if(empty($this->arResult['spec']))
            $this->arResult["error"][] = "Поле <b>Наименование предмета</b> не может быть пустым";
        elseif(!array_key_exists($this->arResult['spec'], $this->arResult['specList']))
            $this->arResult['error'][] = "<b>Наименование предмета</b> отсутствует";

        if(!empty($this->arResult['file']['name'][0]) && empty($this->arResult['file']['error'][0])) {
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
                $this->arResult["error"][] = "Превышен размер файла в 5Mb";
            }
        }

        if(!empty($this->arResult['isPlag'])) {
            if(empty($this->arResult['plag'])) {
                $this->arResult["error"][] = "Поле <b>Уникальность текста</b> не может быть пустым";
            }
        }
        
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

    private function createChat()
    {
        $chat = new \CIMChat;

        $this->arResult['chatId'] = $chat->add([
            'TITLE' => "Чат заказа №{$this->arResult['orderId']}",
            "DESCRIPTION" => "",
            'COLOR' => 'RED',
            'TYPE' => v2Chat::IM_TYPE_CHAT,
            'AUTHOR_ID' => $this->arResult['userId'],
            // "EXTRANET" => "Y",
            // 'AVATAR_ID' => $avatarId,//аватарка чата
		    // 'USERS' => [7],
            // 'MESSAGE' => 'Чат открыт!',
            // "ENTITY_TYPE" => "order",
            // "ENTITY_ID" => $this->arResult['orderId'],
            "MANAGE_USERS_ADD" => v2Chat::ROLE_OWNER, // - Право на добавление участников
            "MANAGE_USERS_DELETE" => v2Chat::ROLE_OWNER, // - Право на удаление участников
            "MANAGE_UI" => v2Chat::ROLE_OWNER, // - Право на управление интерфейсом чата
            "MANAGE_SETTINGS" => v2Chat::ROLE_OWNER, // - Право на управление настройками чата
            // "MANAGE_MESSAGES" => v2Chat::ROLE_OWNER, // - Право на управление сообщениями
            "CAN_POST" => v2Chat::ROLE_MEMBER, // - Право на отправку сообщений
        ]);

        if($this->arResult['chatId'] > 0) {
            $this->query->set('chat', $this->arResult['chatId'])->save();

            $propertyCollection = $this->order->getPropertyCollection();

            foreach ($propertyCollection as $property) {
                switch($property->getField('CODE')) {
                    case "chat":
                        $property->setValue($this->arResult['chatId']);
                        break;
                    case "ded":
                        $property->setValue($this->arResult['date']);
                        break;
                }
            }

            $this->order->doFinalAction(true);
            $this->order->save();

            MessageTable::query()
                ->addSelect('ID')
                ->where('CHAT_ID', $this->arResult['chatId'])
                ->fetchCollection()
                ->walk(static function($message) {
                    MessageTable::delete($message->getId());
                });

            $userData = UserTable::getList([
                'select' => ['NAME', 'LAST_NAME', 'LOGIN'],
                'filter' => ['ID' => $this->arResult['userId']]
            ])->fetchObject();

            $userName = Academy::getUserFullName(null, $userData);
            $tableServiceOrder = Data::getTableString($this->servicOrderTable);
            $tableServiceOrder::getEntity()->cleanCache();

            Messages::addOrderCustomer([
                'user' => $this->arResult['userId'],
                'chatId' => $this->arResult['chatId'],
                'orderId' => $this->arResult['orderId'],
                'urlOrder' => $this->arResult['urlOrder'],
                'urlExpert' => "/experts/{$userData->getLogin()}/",
                'expertName' => $userName,
            ]);

            if($this->arResult['private'] != 'y') {
                Messages::statusDraft([
                    'user' => $this->arResult['userId'],
                    'chatId' => $this->arResult['chatId'],
                    'orderId' => $this->arResult['orderId'],
                    'urlOrder' => $this->arResult['urlOrder'],
                ]);

                if($this->arResult['public'] != 'y') {
                    new Redirect($this->arResult['urlOrder'], true)
                        ->setStatus("307 Temporary Redirect")
                        ->send();
                } else {
                    $this->arResult['published'] = 'y';
                    $this->arResult['publishedUrl'] = $this->arResult['urlOrder'];
                }
            } elseif($this->arResult['private'] == 'y') {
                $userData = UserTable::getList([
                    'select' => ['NAME', 'LAST_NAME', 'LOGIN'],
                    'filter' => ['ID' => $this->arResult['toUser']]
                ])->fetchObject();
                $userName = Academy::getUserFullName(null, $userData);
                Messages::statusNewPrivateOrder([
                    'user' => $this->arResult['userId'],
                    'toUser' => $this->arResult['toUser'],
                    'chatId' => $this->arResult['chatId'],
                    'orderId' => $this->arResult['orderId'],
                    'urlOrder' => $this->arResult['urlOrder'],
                    'userName' => $userName,
                    'urlExpert' => "/experts/{$userData->getLogin()}/"
                ]);
                Messages::addNewPrivateAnswerOrder([
                    'toUser' => $this->arResult['toUser'],
                    'fromUser' => $this->arResult['userId'],
                    'orderId' => $this->arResult['orderId'],
                    'url' => "/order/{$this->arResult['orderCode']}/",
                    'productId' => $this->arResult['productId'],
                ]);

                $this->arResult['published'] = 'y';
                $this->arResult['publishedUrl'] = $this->arResult['urlOrder'];
            }
        } else {
            $this->arResult['error'][] = $this->arResult['chatId']->LAST_ERROR;
        }
    }

    private function createOrder()
    {
        $siteId = Context::getCurrent()->getSite();

        $product = [
            'PRODUCT_ID' => $this->arResult['productId'],
            'NAME' => $this->arResult['name'],
            'PRICE' => $this->arResult['summ'],
            'CURRENCY' => 'RUB',
            'QUANTITY' => 1,
            'LID' => $siteId,
            'PRODUCT_PROVIDER_CLASS' => '\CCatalogProductProvider',
        ];

        $this->order = Order::create($siteId, $this->arResult['userId']);
        $this->order->setPersonTypeId(1);
        $this->order->setField('CURRENCY', CurrencyManager::getBaseCurrency());

        if($this->arResult['private'] != 'y') {
            $this->order->setField('STATUS_ID', 'RF');
        } elseif($this->arResult['private'] == 'y') {
            $this->order->setField('STATUS_ID', 'PE');
        }

        $basket = Basket::loadItemsForFUser($this->arResult['userId'], $siteId);
        $basketItem = $basket->createItem('catalog', $product['PRODUCT_ID']);
        $basketItem->setFields($product);
        $this->order->setBasket($basket);

        $shipmentCollection = $this->order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem(Delivery::getObjectById(1));
        $shipmentItemCollection = $shipment->getShipmentItemCollection();

        foreach ($basket as $basketItem) {
            $item = $shipmentItemCollection->createItem($basketItem);
            $item->setQuantity($basketItem->getQuantity());
        }

        $paymentCollection = $this->order->getPaymentCollection();
        $payment = $paymentCollection->createItem(PaySystem::getObjectById(1));

        $payment->setField("SUM", $this->order->getPrice());
        $payment->setField("CURRENCY", $this->order->getCurrency());

        $this->order->doFinalAction(true);
        $result = $this->order->save();

        if ($result->isSuccess()) {
            $orderSave = $this->query->set('order', $this->order->getId())->save();

            if($orderSave->isSuccess()) {
                $this->arResult['orderId'] = $this->order->getId();

                $this->arResult['orderCode'] = \CUtil::translit("{$this->arResult['orderId']}-{$this->arResult['name']}", "ru", [
                    "max_len" => "50",
                    "change_case" => "L",
                    "replace_space" => "-",
                    "replace_other" => "-",
                ]);

                $this->query->setCode($this->arResult['orderCode']);
                $this->arResult['urlOrder'] = "/lk/order/{$this->arResult['orderCode']}/";
                $this->createChat();
            } else {
                \CIBlockElement::Delete($this->arResult['productId']);
                $this->arResult['error'] = $orderSave->getErrorMessages();
            }
        } else {
            \CIBlockElement::Delete($this->arResult['productId']);
            $this->arResult['error'][] = $result->getError();
        }
    }

    private function createProduct()
    {
        $newService = new \CIBlockElement;

        $sectionService = Data::getElementObject($this->lkSpecTable, ['IBLOCK_SECTION_ID'], ['ID' => $this->arResult['spec']]);

        $serviceData = [
            'IBLOCK_ID' => Data::getIblockObject($this->servicOrderTable, ['select' => ['ID']])->getId(),
            'CREATED_BY' => $this->arResult['userId'],
            'NAME' => $this->arResult['name'],
            'PREVIEW_TEXT' => $this->arResult['description'],
            'CODE' => rand(),
            'PROPERTY_VALUES' => [
                'uow' => $this->arResult['userId'],
                'tWork' => $this->arResult['type'],
                'sSpec' => $sectionService->getIblockSectionId(),
                'pred' => $this->arResult['spec'],
            ]
        ];

        if(!empty($this->arResult['date'])) {
            $serviceData['PROPERTY_VALUES']['ded'] = $this->arResult['date'];
        }

        if(!empty($this->arResult['plag'])) {
            $serviceData['PROPERTY_VALUES']['anti'] = $this->arResult['plag'];
        }

        if($this->arResult['private'] == 'y') {
            $serviceData['PROPERTY_VALUES']['uex'] = $this->arResult['toUser'];
            $serviceData['PROPERTY_VALUES']['private'] = 'Y';
        }

        if(!empty($this->arResult['file'])) {
            $this->loadFile();
            
            foreach($this->arResult['fileId'] as $file) {
                $serviceData['PROPERTY_VALUES']['file'][] = [
                    'VALUE' => $file,
                ];
            }
        }

        $serviceId = $newService->Add($serviceData);

        if($serviceId) {

            $this->query = $this->query
                ->addSelect('ID')
                ->where('ID', $serviceId)
                ->fetchObject();

            $this->arResult['productId'] = $serviceId;

            $resultProduct = Product::add([
                'ID' => $this->arResult['productId'],
                'TYPE' => ProductTable::TYPE_SERVICE,
                'AVAILABLE' => 'Y'
            ]);

            if($resultProduct->isSuccess()) {
                $resultPrice = Price::add([
                    'PRODUCT_ID' => $this->arResult['productId'],
                    'CATALOG_GROUP_ID' => 1,
                    'PRICE' => $this->arResult['summ'],
                    'CURRENCY' => 'RUB',
                ]);

                if($resultPrice->isSuccess()) {
                    $this->createOrder();
                } else {
                    \CIBlockElement::Delete($this->arResult['productId']);

                    $this->arResult['error'] = $resultPrice->getErrorMessages();
                }
            } else {
                \CIBlockElement::Delete($this->arResult['productId']);

                $this->arResult['error'] = $resultProduct->getErrorMessages();
            }
        } else {
            $this->arResult['error'] = $newService->LAST_ERROR;
        }
    }

    private function prepareFieldsRequestData()
    {
        $this->arResult['private'] = htmlspecialcharsbx($this->request->getPost('private'));
        $this->arResult['public'] = htmlspecialcharsbx($this->request->getPost('public'));

        if($this->arResult['private'] == 'y') {
            $this->arResult['toUser'] = intval($this->request->getPost('toUser'));

            if($this->arResult['toUser'] <= 0) {
                $this->arResult["error"][] = 'Неизвестный пользователь';
            } else {
                $userData = UserTable::getList([
                    'select' => ['NAME', 'LAST_NAME', 'LOGIN'],
                    'filter' => ['ID' => $this->arResult['toUser']]
                ])->fetchObject();

                if(!empty($userData)) {
                    $this->arResult['toUserName'] = Academy::getUserFullName(null, $userData);
                }
            }
        }

        if(!empty($this->request->isPost()) && ($this->request->getPost('submit') == 'y')) {
            if(check_bitrix_sessid()) {
                $this->getFieldsRequestData();

                if(empty($this->arResult["error"])) {
                    $this->createProduct();
                }
            } else {
                $this->arResult["error"][] = "Ваша сессия истекла. Отправьте сообщение повторно.";
            }
        }
    }

    public function executeComponent()
    {
        $this->arResult['error'] = [];

        $this->getSelectList("typeWork", "LangLkTypeWork");
        $this->getSelectList("specList", $this->lkSpecTable);
        $this->prepareFieldsRequestData();
        $this->IncludeComponentTemplate();
    }
}