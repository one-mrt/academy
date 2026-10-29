<?
namespace Lang\Academy;

use ArrayAccess;
use Bitrix\Main\Event;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Sale\PaySystem;
use Bitrix\Catalog\Model\Price;
use Bitrix\Sale\Order as BXOrder;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Engine\Response\Redirect;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Pull\Event as PullEvent;

use Bitrix\Im\Recent;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Entity\EventResult;

Loc::loadMessages(__FILE__);

Loader::includeModule('im');
Loader::includeModule('sale');
Loader::includeModule('pull');
Loader::includeModule('catalog');
Loader::includeModule('lang.iblock');

class Order
{
    private static string $langServiceOrder = "LangServiceOrder";
    /**
     * Доступность статусов для пользователя
     * customer - владелец
     * answer - ответственный
     */
    public const ACCESS_STATUS = [
        'customer' => [
            'RF' => ['N', 'CN'],
            'N' => ['CN'],
            'PE' => ['N', 'CN'],
            'OP' => ['DT', 'CN'],
            'RN' => ['DT'],
            'CP' => ['F', 'DT', 'MN'],
            'MN' => ['F', 'DT'],
            'DT' => ['F'],
            'CN' => [],
            'F' => []
        ],
        'answer' => [
            'RF' => [],
            'N' => [],
            'PE' => [],
            'OP' => [],
            'RN' => ['CP', 'DT', 'CN'],
            'CP' => ['MN', 'DT', 'CN'],
            'MN' => ['DT', 'CP', 'CN'],
            'DT' => ['CN'],
            'CN' => [],
            'F' => []
        ],
        'manager' => [
            'RF' => [],
            'N' => ['PE', 'OP', 'RN', 'CP', 'MN', 'DT', 'CN', 'F'],
            'PE' => ['N', 'OP', 'RN', 'CP', 'MN', 'DT', 'CN', 'F'],
            'OP' => ['N', 'PE', 'RN', 'CP', 'MN', 'DT', 'CN', 'F'],
            'RN' => ['N', 'PE', 'OP', 'CP', 'MN', 'DT', 'CN', 'F'],
            'CP' => ['N', 'PE', 'OP', 'RN', 'MN', 'DT', 'CN', 'F'],
            'MN' => ['N', 'PE', 'OP', 'RN', 'CP', 'DT', 'CN', 'F'],
            'DT' => ['N', 'PE', 'OP', 'RN', 'CP', 'MN', 'CN', 'F'],
            'CN' => ['N', 'PE', 'OP', 'RN', 'CP', 'MN', 'DT', 'F'],
            'F' => ['N', 'PE', 'OP', 'RN', 'CP', 'MN', 'DT', 'CN']
        ]
    ];

    public const MANAGER = 15;
    public const MANAGER_GROUP = 14;

    public static function onUpdateOrder(Event $event)
    {
        $order = $event->getParameter('ENTITY');

        if($order->isCanceled()) {
            $order->setField('STATUS_ID', 'CN');
        }
    }

    public static function onPullUpdateOrder(Event $event)
    {
        $order = $event->getParameter('ENTITY');

        if(!$order || $event->getParameter('IS_NEW') || !$order->isChanged()) {
            return;
        }

        if(!$order->isChanged('STATUS_ID')) {
            return;
        }

        $propertyCollection = $order->getPropertyCollection();
        $basket = $order->getBasket();
        $chat = 0;
        $serviceId = 0;
        $answer = $order->getField("RESPONSIBLE_ID");
        $current = CurrentUser::get()->getId();
        $customer = $order->getUserId();
        $statusId = $order->getField('STATUS_ID');
        $newChat = new \CIMChat(0);

        foreach ($basket as $basketItem) {
            $serviceId = $basketItem->getProductId();
            break;
        }

        foreach ($propertyCollection as $property) {
            switch($property->getField('CODE')) {
                case "chat":
                    $chat = $property->getValue();
                    break;
                case "ded":
                    // $property->setValue();
                    break;
            }
        }

        if(!empty($current) && $current != $customer && $current != $answer) {

            $statusName = StatusLangTable::getList([
                'select' => ['NAME'],
                'filter' => ['LID' => SITE_ID, 'STATUS_ID' => $statusId],
                'cache' => Data::getCache(60*60*24*7)
            ])->fetchObject();

            $serviceItem = Data::getElementObject(self::$langServiceOrder, ['CODE'], ['ID' => $serviceId]);
            $paramNotify = [
                'toUser' => $customer,
                'orderUrl' => Data::getDetailPageUrlApiCode(self::$langServiceOrder, $serviceItem),
                'orderId' => $order->getId(),
                'status' => $statusName?->getName()
            ];

            switch($statusId) {
                case 'RF':
                case 'N':
                case 'PE':
                    // Только заказчику
                    Messages::statusDefaultNotifyManager($paramNotify);
                    break;
                case 'OP':
                case 'RN':
                case 'CP':
                case 'MN':
                    // Заказчик и исполнитель
                    $paramNotify['orderUrl'] = "/lk{$paramNotify['orderUrl']}";
                    Messages::statusDefaultNotifyManager($paramNotify);
                    $paramNotify['toUser'] = $answer;
                    Messages::statusDefaultNotifyManager($paramNotify);
                    break;
                case 'DT':
                    $newChat->AddUser($chat, self::MANAGER);
                    $paramNotify['orderUrl'] = "/lk{$paramNotify['orderUrl']}";
                    Messages::statusDefaultNotifyManager($paramNotify);
                    $paramNotify['toUser'] = $answer;
                    Messages::statusDefaultNotifyManager($paramNotify);
                    break;
                case "CN":
                case "F":
                    Recent::hide($chat);
                    $newChat->DeleteUser($chat, self::MANAGER, false);
                    $paramNotify['orderUrl'] = "/lk{$paramNotify['orderUrl']}";
                    Messages::statusDefaultNotifyManager($paramNotify);
                    $paramNotify['toUser'] = $answer;
                    Messages::statusDefaultNotifyManager($paramNotify);
                    break;
            }
            
            Messages::statusDefaultManager([
                'user' => $current,
                'chatId' => $chat,
                'status' => $statusName->getName()
            ]);
        }

        PullEvent::add([$customer, $answer], [
            'module_id' => 'sale',
            'command' => 'langOrderStatusUpdate',
            'params' => [
                'id' => $order->getId(),
                'serviceId' => $serviceId,
                'updateField' => 'status',
                'statusId' => $statusId,
            ]
        ]);
    }

    public static function onUpdateServiceOrder(&$arFields)
    {
        if(!empty($arFields["RESULT"]) && $arFields['IBLOCK_ID'] == '12') {
            // Удача
            $userPull = [];
            $orderId = 0;
            $propertyNameList = PropertyTable::getList([
                'select' => ['ID', 'CODE'],
                'filter' => ["IBLOCK_ID" => $arFields['IBLOCK_ID'], 'ACTIVE' => 'Y', 'CODE' => ['uex', 'uow', 'order']],
                'cache' => Data::getCache((1000*60) * 60 * 24 * 7)
            ])->fetchCollection();

            foreach($propertyNameList as $property) {
                switch($property->getCode()) {
                    case 'uex':
                    case 'uow':
                        foreach($arFields['PROPERTY_VALUES'][$property->getId()] as $value) {
                            if(!empty($value['VALUE'])) {
                                $userPull[] = $value['VALUE'];
                            }
                        }

                        break;
                    case 'order':
                        foreach($arFields['PROPERTY_VALUES'][$property->getId()] as $value) {
                            if(!empty($value['VALUE'])) {
                                $orderId = $value['VALUE'];
                            }
                        }

                        break;
                }
            }

            if(!empty($userPull)) {
                PullEvent::add($userPull, [
                    'module_id' => 'sale',
                    'command' => 'langOrderUpdate',
                    'params' => [
                        'id' => $orderId,
                        'serviceId' => $arFields['ID'],
                        'updateField' => 'all',
                    ]
                ]);
            }
        } else {
            // Не удача
        }
    }


    public static function reSelectAnswer(int $serviceId)
    {
        $service = Data::getTableString("LangServiceOrder")::query()
            ->setSelect(['ID', "CREATED_BY", 'order.VALUE', 'uex.VALUE', 'chat.VALUE', 'res.VALUE'])
            ->where('ID', $serviceId)
            ->where('uex.VALUE', '>', 0)
            ->fetchObject();

        if(!empty($service)) {

            $customer = $service->getCreatedBy();
            $answer = $service->getUex()?->getValue();
            $orderId = $service->getOrder()->getValue();

            // Предложение
            $offer = Data::getTableString("LangOrderOffer")::query()
                ->setSelect(['ID', 'usr.VALUE', 'acp.VALUE', 'msg.VALUE', 'sel.VALUE'])
                ->where('ACTIVE', 'Y')
                ->where('ofr.VALUE', $service->getId())
                ->fetchObject();

            if($offer) {
                NotifyMessage::Delete(intval($offer->getMsg()?->getValue()));
                $offerId = $offer->getId();
                $offer
                    ->setActive(false)
                    ->set('msg', '')
                    ->set('sel', 'N')
                    ->set('acp', 'N')
                    ->save();
            }

            // Удаляем в чате
            $newChat = new \CIMChat(0);
            $newChat->DeleteUser(intval($service->getChat()?->getValue()), intval($answer), false);

            // Удаляем в заказе
            $order = BXOrder::load($orderId);

            if(!empty($order)) {
                $order->setField('RESPONSIBLE_ID', '');
                $order->setField('STATUS_ID', 'RF');
                $order->doFinalAction(true);
                $order->save();
            }

            $service
                ->set('uex', '')
                ->set('private', 'N');

            if(!empty($service->getRes()?->getAll())) {
                foreach($service->getRes()?->getAll() as $file) {
                    \CFile::Delete($file->getValue());
                }

                $service->removeAll('res');
            }

            $service->save();

            PullEvent::add($customer, [
                'module_id' => 'sale',
                'command' => 'langOrderUpdate',
                'params' => [
                    'id' => $orderId,
                    'serviceId' => $serviceId,
                    'updateField' => 'all',
                ]
            ]);

            PullEvent::add([$answer, $customer], [
                'module_id' => "lang.academy",
                'command' => 'langOrderPublicUpdate',
                'params' => [
                    'id' => $orderId,
                    'offerId' => $offerId,
                    'serviceId' => $serviceId,
                    'customer' => $customer,
                    'status' => 'updatePage',
                ]
            ]);
        }
    }
}