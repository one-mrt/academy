<?
namespace Lang\Academy;

use Lang\Main\Iblock\Util as Data;

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\Text\HtmlFilter;
use Bitrix\Main\ORM\Fields\Relations\Reference;

use Bitrix\Sale\Order;
use Bitrix\Sale\Internals\StatusLangTable;

use Bitrix\Catalog\Model\Price;

use Bitrix\Pull\Event as PullEvent;

class Offer
{
    private string $langOrderOffer = "LangOrderOffer";
    private string $langServiceOrder = "LangServiceOrder";
    private ?string $apiService;
    private ?string $apiOffer;

    private ?object $query;
    private ?object $queryOffer;
    private ?object $service;
    private ?object $offer;

    private ?string $action = null;
    private ?int $orderId = 0;
    private ?int $offerId = 0;
    private ?int $serviceId = 0;
    private ?int $chatId = 0;
    private ?int $offerSum = 0;
    private ?string $private = 'N';
    private ?int $answer = 0;
    private ?int $answerSelected = 0;
    private ?int $customer = 0;
    private ?string $actionValue;
    private ?string $orderUrl;
    private ?object $newChat;
    private ?array $param;

    public function __construct(array $param)
    {
        Loader::includeModule('im');
        Loader::includeModule('sale');
        Loader::includeModule('catalog');

        $this->param = $param;
        $this->initObjectVar();

        // Util::langWriteLog([$this->orderId, $this->offerId, $this->action, $this->actionValue]);

        if($this->isOffer()) {
            $this->orderUrl = Data::getDetailPageUrlApiCode($this->langServiceOrder, $this->service);
            $this->chatId = intval($this->service->getChat()?->getValue());
            $this->serviceId = intval($this->service->getId());
            $this->private = $this->service->getPrivate()?->getValue();
            $this->answerSelected = intval($this->service->getUex()?->getValue());
            $this->customer = intval($this->service->getCreatedBy());

            $this->answer = intval($this->offer->getUsr()?->getValue());
            $this->offerSum = intval($this->offer->getSum()?->getValue());

            switch($this->action) {
                case "selected":
                    if($this->actionValue == 'Y') {
                        $this->acceptCustomer();
                    } elseif($this->actionValue == 'N') {
                        $this->cancelCustomer();
                    }
                    break;
                case "confirm":
                    if($this->actionValue == 'Y') {
                        $this->acceptConfirm();
                    } elseif($this->actionValue == 'N') {
                        $this->cancelConfirm();
                    }
                    break;
                // case "private":
                //     if($this->actionValue == 'N') {
                //         $this->privateCancel();
                //     }
                //     break;
                case "withdraw":
                    if($this->actionValue == 'Y') {
                        $this->withdrawAnswer();
                    }
                    break;
            }
        } elseif(!empty($this->service) && $this->action == "private" && $this->actionValue == 'N') {
            $this->orderUrl = Data::getDetailPageUrlApiCode($this->langServiceOrder, $this->service);
            $this->chatId = intval($this->service->getChat()?->getValue());
            $this->serviceId = intval($this->service->getId());
            $this->private = $this->service->getPrivate()?->getValue();
            $this->answerSelected = intval($this->service->getUex()?->getValue());
            $this->customer = intval($this->service->getCreatedBy());
            $this->answer = intval($this->service->getUex()?->getValue());
            
            // if($this->action == "private" && $this->actionValue == 'N') {
                $this->privateCancel();
            // }
        }
    }

    private function initObjectVar()
    {
        $this->action = HtmlFilter::encode($this->param['action']);
        $this->orderId = intval($this->param['orderId']);
        $this->offerId = intval($this->param['offerId']);
        $this->serviceId = intval($this->param['serviceId']);
        $this->actionValue = HtmlFilter::encode($this->param['value']);
        $this->newChat = new \CIMChat(0);

        $this->apiService = Data::getTableString($this->langServiceOrder);
        $this->apiOffer = Data::getTableString($this->langOrderOffer);

        $this->query = $this->apiService::query();
        $this->queryOffer = $this->apiOffer::query();
    }

    private function isOffer(): bool
    {
        if($this->orderId > 0) {
            $this->service = $this->query
                ->setSelect([
                    'ID', 'IBLOCK_ID', 'CODE', 'IBLOCK_SECTION_ID', 'CREATED_BY',
                    'chat.VALUE', 'order.VALUE', 'uex.VALUE', 'private.VALUE',
                    'STATUS.STATUS_ID', 'STATUS.RESPONSIBLE_ID'
                ])
                ->where('order.VALUE', $this->orderId)
                ->where(
                    Query::filter()->logic('and')
                        ->where('STATUS.STATUS_ID', '!=', 'RF')
                        ->where('STATUS.STATUS_ID', '!=', 'F')
                        ->where('STATUS.STATUS_ID', '!=', 'CN')
                )
                ->where(
                    Query::filter()->logic('or')
                        ->where('STATUS.RESPONSIBLE_ID', null)
                        ->where('STATUS.RESPONSIBLE_ID', '<=', 0)
                )
                ->registerRuntimeField((new Reference('STATUS', Order::class, Join::on('this.order.VALUE', 'ref.ID'))))
                ->fetchObject();
        }

        if(!empty($this->service) && $this->offerId > 0) {
            $this->offer = $this->queryOffer
                ->setSelect(['ID', 'usr.VALUE', 'msg.VALUE', 'acp.VALUE', 'sum.VALUE'])
                ->where('ID', $this->offerId)
                ->setLimit(1)
                ->fetchObject();
        }

        if(!empty($this->service) && !empty($this->offer)) {
            return true;
        }

        return false;
    }

    /**
     * Выбираем предложение - Заказчик
     */
    private function acceptCustomer()
    {
        NotifyMessage::Delete(intval($this->offer->getMsg()?->getValue()));

        $messageId = Messages::confirmAnswerOrder([
            'toUser' => $this->answer, // Кому
            'fromUser' => $this->customer, // Кто
            'orderId' => $this->orderId,
            'offerId' => $this->offerId,
            'serviceId' => $this->serviceId,
            'url' => $this->orderUrl,
        ]);

        $this->offer
            ->set("msg", $messageId)
            ->set('sel', 'Y')
            ->save();

        PullEvent::add([$this->answer, $this->customer], [
            'module_id' => "lang.academy",
            'command' => 'langOrderPublicUpdate',
            'params' => [
                'id' => $this->orderId,
                'offerId' => $this->offerId,
                'serviceId' => $this->serviceId,
                'customer' => $this->customer
            ]
        ]);
    }

    /**
     * Отказываемся от предложения - Заказчик
     */
    private function cancelCustomer()
    {
        NotifyMessage::Delete(intval($this->offer->getMsg()?->getValue()));
        $this->offer->setActive(false)->set("msg", "")->save();

        $param = [
            'toUser' => $this->answer, // Кому - Исполнитель
            'fromUser' => $this->customer,  // Кто - Заказчик
            'orderId' => $this->orderId,
            'offerId' => $this->offerId,
            'serviceId' => $this->serviceId,
            'url' => $this->orderUrl
        ];

        Messages::cancelAnswerOrder($param);

        PullEvent::add([$this->answer, $this->customer], [
            'module_id' => "lang.academy",
            'command' => 'langOrderPublicUpdate',
            'params' => [
                'id' => $this->orderId,
                'offerId' => $this->offerId,
                'serviceId' => $this->serviceId,
                'customer' => $this->customer,
            ]
        ]);
    }

    /**
     * Отзываем предложение - Исполнитель
     */
    private function withdrawAnswer()
    {
        NotifyMessage::Delete(intval($this->offer->getMsg()?->getValue()));
        $this->offer
            ->setActive(false)
            ->set("msg", "")
            ->set('sel', 'N')
            ->save();

        Messages::cancelWithdrawAnswer([
            'toUser' => $this->answer,
            'orderId' => $this->orderId,
            'url' => $this->orderUrl,
        ]);

        PullEvent::add([$this->answer, $this->customer], [
            'module_id' => "lang.academy",
            'command' => 'langOrderPublicUpdate',
            'params' => [
                'id' => $this->orderId,
                'offerId' => $this->offerId,
                'serviceId' => $this->serviceId,
                'customer' => $this->customer
            ]
        ]);
    }

    private function getParamMessage()
    {
        return [
            'toUser' => $this->answer,
            'fromUser' => $this->customer,
            'orderId' => $this->orderId,
            'offerId' => $this->offerId,
            'serviceId' => $this->serviceId,
            'url' => "/lk{$this->orderUrl}",
        ];
    }

    private function isPrivate(): bool
    {
        // if(!empty($addUserOffer) && !empty($addUserOffer->getPrivate()) && !empty($addUserOffer->getUex())) {
        //     if(($addUserOffer->getPrivate()->getValue() == 'Y') && !empty($userOffer) && !empty($userOffer->getUsr())) {
        //         if($addUserOffer->getUex()->getValue() == $userOffer->getUsr()?->getValue()) {
        //             $isPrivate = true;
        //         }
        //     }
        // }

        // if((!empty($addUserOffer) && empty($addUserOffer->getUex())) || $isPrivate) {

        if($this->private == 'Y' && !empty($this->answerSelected)) {
            return true;
        }

        return false;
    }

    private function isSelectedAnswer(): bool
    {
        if(!empty($this->answerSelected)) {
            return true;
        }

        return false;
    }

    /**
     * Обновляем цену
     */
    private function updatePriceService()
    {
        $price = Price::getList([
            'select' => ['ID', 'PRICE', 'CURRENCY'],
            'filter' => ['PRODUCT_ID' => $this->serviceId]
        ])->fetch();

        if(!empty($price) && $this->offerSum != $price['PRICE'] && $this->offerSum > 0) {
            $sumN = number_format($this->offerSum, 0, '.', ' ');
            Messages::editPriceOrder([
                'user' => $this->customer,
                'chatId' => $this->chatId,
                'sum' => $sumN
            ]);
            Price::update(
                intval($price['ID']),
                ['PRICE' => $this->offerSum, 'CATALOG_GROUP_ID' => 1, 'CURRENCY'=> $price['CURRENCY'], 'PRODUCT_ID' => $this->serviceId]
            );
        }
    }

    /**
     * Обновляем статус
     */
    private function updateStatusOrder()
    {
        $order = Order::load($this->orderId);
        $basket = $order->getBasket();
        $paymentCollection = $order->getPaymentCollection();

        foreach($paymentCollection as $payment) {
            $payment->setField('SUM', $order->getPrice());
            break;
        }

        $basket->refresh();
        $order->setField('STATUS_ID', "OP");
        $order->setField('RESPONSIBLE_ID', $this->answer);
        $order->doFinalAction(true);
        $order->save();

        $status = StatusLangTable::getList([
            'select' => ['NAME'],
            'filter' => ['LID' => SITE_ID, 'STATUS_ID' => 'OP'],
            'cache' => Data::getCache()
        ])->fetchObject();

        Messages::statusPayNotifyCustomer([
            'toUser' => $this->customer,
            'status' => $status->getName(),
            'orderUrl' => "/lk{$this->orderUrl}",
            'orderId' => $this->orderId,
        ]);

        Messages::statusChange([
            'user' => $this->customer,
            'chatId' => $this->chatId,
            'status' => $status->getName()
        ]);
    }

    /**
     * Деактивируем все заявки кроме выбранной
     */
    private function cancelOfferDisable()
    {
        $param = [
            'fromUser' => $this->customer,
            'orderId' => $this->orderId,
            'url' => $this->orderUrl,
            'serviceId' => $this->serviceId
        ];

        $this->offer
            ->set('msg', '')
            ->set('acp', 'Y')
            ->set('sel', 'N')
            ->save();

        $this->apiOffer::query()
            ->setSelect(['ID', 'msg.VALUE', 'usr.VALUE'])
            ->where('ofr.VALUE', $this->serviceId)
            ->where('ID', '!=', $this->offerId)
            ->where('ACTIVE', 'Y')
            ->fetchCollection()
            ->walk(static function($offer) use (&$param): void {
                NotifyMessage::Delete(intval($offer->getMsg()?->getValue()));

                if(intval($offer->getUsr()->getValue()) > 0) {
                    $param['toUser'] = intval($offer->getUsr()->getValue());
                    $param['offerId'] = intval($offer->getId());
                    Messages::cancelAnswerOrderAll($param);
                }

                $offer
                    ->setActive(false)
                    ->set('msg', '')
                    ->set('sel', 'N');
            })
            ->save();

        // $this->queryOffer->save();
    }

    // Подтвержение исполнителем
    private function acceptConfirm()
    {
        $param = $this->getParamMessage();
        $this->apiService::getEntity()->cleanCache();

        // Если исполнитель не выбран или приватный
        if(!$this->isSelectedAnswer() || $this->isPrivate()) {
            $user = UserTable::getList([
                'select' => ['ID', 'NAME', 'LAST_NAME', 'LOGIN'],
                'filter' => ['ID' => $this->answer]
            ])->fetchObject();
            $userName = Util::getUserFullName(null, $user);

            NotifyMessage::Delete(intval($this->offer->getMsg()?->getValue()));
            Messages::acceptOfferOrder($param);
            Messages::selectedOfferOrder($param);

            Messages::selectedUserAnswerOrder([
                'user' => $this->answer,
                'chatId' => $this->chatId,
                'url' => "/experts/{$user->getLogin()}/",
                'userName' => $userName
            ]);

            $this->newChat->AddUser($this->chatId, $this->answer);
            $this->service->set('uex', $this->answer)->save();

            $this->updatePriceService();
            $this->updateStatusOrder();

            PullEvent::add([$this->answer, $this->customer], [
                'module_id' => "lang.academy",
                'command' => 'langOrderPublicUpdate',
                'params' => [
                    'id' => $this->orderId,
                    'offerId' => $this->offerId,
                    'serviceId' => $this->serviceId,
                    'customer' => $this->customer,
                ]
            ]);

            $this->cancelOfferDisable();

        } else {
            Messages::warningSelectedOfferUser($param);
        }
    }

    private function cancelConfirm()
    {
        NotifyMessage::Delete(intval($this->offer->getMsg()?->getValue()));

        $this->offer
            ->setActive(false)
            ->set('msg', '')
            ->set('acp', 'N')
            ->set('sel', 'N')
            ->save();

        // $userPrivate = UserTable::getList([
        //     'select' => ['ID', 'NAME', 'LAST_NAME', 'LOGIN'],
        //     'filter' => ['ID' => $this->answer]
        // ])->fetchObject();

        $userName = Util::getUserFullName($this->answer);

        Messages::cancelConfirmAnswer([
            'fromUser' => $this->customer,
            'toUser' => $this->answer,
            'orderId' => $this->orderId,
            'userName' => $userName,
            'url' => $this->orderUrl,
        ]);

        Messages::cancelConfirmChatOrder([
            'fromUser' => $this->customer,
            'toUser' => $this->answer,
            'chatId' => $this->chatId,
            'orderId' => $this->orderId,
            'userName' => $userName,
            'url' => $this->orderUrl,
        ]);

        PullEvent::add([$this->answer, $this->customer], [
            'module_id' => "lang.academy",
            'command' => 'langOrderPublicUpdate',
            'params' => [
                'id' => $this->orderId,
                'offerId' => $this->offerId,
                'serviceId' => $this->serviceId,
                'customer' => $this->customer,
            ]
        ]);
    }

    /**
     * Отказ исполнителя
     */
    private function privateCancel()
    {
        $userPrivate = UserTable::getList([
            'select' => ['ID', 'NAME', 'LAST_NAME', 'LOGIN'],
            'filter' => ['ID' => $this->answer]
        ])->fetchObject();

        $userName = Util::getUserFullName(null, $userPrivate);

        Messages::cancelPrivateChatOrder([
            'fromUser' => $this->customer,
            'toUser' => $this->answer,
            'chatId' => $this->chatId,
            'orderId' => $this->orderId,
            'userName' => $userName,
            'url' => $this->orderUrl,
        ]);

        Messages::cancelPrivateAnswerOrder([
            'fromUser' => $this->customer,
            'toUser' => $this->answer,
            'orderId' => $this->orderId,
            'userName' => $userName,
            'url' => $this->orderUrl,
        ]);

        PullEvent::add($this->customer, [
            'module_id' => 'sale',
            'command' => 'langOrderUpdate',
            'params' => [
                'id' => $this->orderId,
                'serviceId' => $this->serviceId,
                'updateField' => 'all',
            ]
        ]);

        PullEvent::add([$this->answer, $this->customer], [
            'module_id' => "lang.academy",
            'command' => 'langOrderPublicUpdate',
            'params' => [
                'id' => $this->orderId,
                'offerId' => $this->offerId,
                'serviceId' => $this->serviceId,
                'customer' => $this->customer,
            ]
        ]);

        $this->service->set('uex', "")->save();
    }
}