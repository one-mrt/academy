<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Engine\Response\Redirect;

use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\ORM\Fields\Relations\Reference;

use Bitrix\Pull\Event as PullEvent;

use Bitrix\Im\Model\MessageTable;

use Bitrix\Catalog\Model\Price;

use Bitrix\Sale\Order;
use Bitrix\Currency\CurrencyManager;

use Lang\Main\Iblock\Util as Data;
use Lang\Academy\Messages;
use Lang\Academy\Util as AUtil;
use Lang\Academy\Offer;

Loader::includeModule('im');
Loader::includeModule('sale');
Loader::includeModule('catalog');

class LangOrderOffer extends CBitrixComponent
{
    private ?object $user;
    private ?object $offer;
    private ?object $offerList;
    private ?object $query;
    private string $langText = "LangText";
    private string $langOrderOffer = "LangOrderOffer";
    private string $langServiceOrder = "LangServiceOrder";
    private ?string $apiService;
    private ?string $apiOffer;

    private array $typeError = [
        'customer' => 'Не верный владелец заказа',
        'serviceId' => 'Не верная услуга',
        'orderId' => 'Не верный заказ',
    ];

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);

        $this->user = CurrentUser::get();
        $this->arResult['userId'] = intval($this->user->getId());

        $this->apiService = Data::getTableString($this->langServiceOrder);
        $this->apiOffer = Data::getTableString($this->langOrderOffer);

        $this->offer = $this->apiOffer::createObject();
        $this->query = $this->apiOffer::query();
    }

    // customer - владелец заказа
    // orderId - id заказа
    // serviceId - id услуги
    public function onPrepareComponentParams($arParam)
    {
        $arParams['customer'] = intval($arParam['customer']);
        $arParams['orderId'] = intval($arParam['orderId']);
        $arParams['serviceId'] = intval($arParam['serviceId']);

        return $arParam;
    }

    private function getFormContent()
    {
        $formEl = Data::getElementObject($this->langText, ['NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'link'], ['ID' => 398]);

        if(!empty($formEl) && !empty($formEl->getId())) {
            $this->arResult['title'] = $formEl->getName();
            $this->arResult["description"] = $formEl->getPreviewText();
            $this->arResult['edit'] = Data::iblockElementEditAddId($formEl);

            if(!empty($formEl->getLink()) && !empty($formEl->getLink()->getDescription())) {
                $this->arResult['btnName'] = $formEl->getLink()->getDescription();
            }

            if(!empty($formEl) && !empty($formEl->getPreviewPicture())) {
                $this->arResult["bgImage"] = Data::getImageId($formEl->getPreviewPicture(), [
                    "alt" => strip_tags($formEl->getName()),
                    "fetchpriority" => "high",
                    "class" => "w-100 h-100 pn-a t-0 rt-0 b-0 l-0 oft-cv op-c",
                ]);
            }
        }
    }

    private function getError()
    {
        foreach($this->typeError as $type => $message) {
            if($this->arResult[$type] <= 0) {
                if($this->arParams[$type] <= 0) {
                    $this->arResult['error'][$type] = $message;
                } else {
                    $this->arResult[$type] = $this->arParams[$type];
                }
            }
        }
    }

    private function isCancel(): bool
    {
        if($this->arResult['cancel'] == 'y') {
            return true;
        }

        return false;
    }

    private function isCancelConfirm(): bool
    {
        if($this->arResult['cancelConfirm'] == 'y') {
            return true;
        }

        return false;
    }

    private function isAccept(): bool
    {
        if($this->arResult['accept'] == 'y') {
            return true;
        }

        return false;
    }

    private function isAcceptConfirm(): bool
    {
        if($this->arResult['acceptConfirm'] == 'y') {
            return true;
        }

        return false;
    }

    private function isWithdraw(): bool
    {
        if($this->arResult['withdraw'] == 'y') {
            return true;
        }

        return false;
    }

    private function isAction(): bool
    {
        if($this->isCancel() || $this->isAccept() || $this->isWithdraw() || $this->isCancelConfirm() || $this->isAcceptConfirm()) {
            return true;
        }

        return false;
    }

    private function isError(): bool
    {
        if(!empty($this->arResult['error'])) {
            return true;
        }

        return false;
    }

    private function isAdd(): bool
    {
        if($this->arResult['add'] == 'y') {
            return true;
        }

        return false;
    }

    private function isSave(): bool
    {
        if($this->arResult['save'] == 'y' && $this->isAdd()) {
            return true;
        }

        return false;
    }

    private function isCustomer(): bool
    {
        if($this->arResult['customer'] == $this->arResult['userId']) {
            $this->arResult['isCustomer'] = 'y';

            return true;
        }

        $this->arResult['isCustomer'] = 'n';
        return false;
    }

    private function getFieldsRequestData()
    {
        $this->arResult['add'] = htmlspecialcharsbx($this->request->getPost('add'));
        $this->arResult['save'] = htmlspecialcharsbx($this->request->getPost('save'));
        $this->arResult['customer'] = intval($this->request->get('customer'));
        $this->arResult['serviceId'] = intval($this->request->get('serviceId'));
        $this->arResult['orderId'] = intval($this->request->get('orderId'));
        $this->arResult['navPage'] = htmlspecialcharsbx($this->request->get("nav"));
        $this->arResult['cancel'] = htmlspecialcharsbx($this->request->getPost('cancel'));
        $this->arResult['cancelConfirm'] = htmlspecialcharsbx($this->request->getPost('cancelConfirm'));
        $this->arResult['accept'] = htmlspecialcharsbx($this->request->getPost('accept'));
        $this->arResult['acceptConfirm'] = htmlspecialcharsbx($this->request->getPost('acceptConfirm'));
        $this->arResult['withdraw'] = htmlspecialcharsbx($this->request->getPost('withdraw'));
        $this->arResult['offerId'] = intval($this->request->getPost("offerId"));

        if($this->isSave()) {
            $this->arResult['summ'] = intval(preg_replace('/[^0-9]/', '', $this->request->getPost("summ"))) ?? 0;
            $this->arResult['message'] = htmlspecialcharsbx(strip_tags($this->request->getPost('message')));

            if($this->arResult['summ'] <= 0) {
                $this->arResult['error'][] = 'Сумма должна быть больше нуля';
            }

            $length = mb_strlen($this->arResult['message']);

            if($length > 100) {
                $this->arResult['error'][] = "Количество символов не должно превышать 100";
            }
        }

        if($this->isAction()) {
            if($this->arResult['offerId'] <= 0) {
                $this->arResult['error']['offerId'] = "Нет такого предложения";
            }
        }

        $this->getError();

        if(!$this->isError()) {
            $service = $this->apiService::query()
                ->setSelect(['ID', 'CODE', 'uex.VALUE'])
                ->where('order.VALUE', $this->arResult['orderId'])
                ->where('ID', $this->arResult['serviceId'])
                ->fetchObject();

            if(!empty($service) && !empty($service->getId())) {
                $this->arResult['orderUrl'] = "/order/{$service->getCode()}/";
                $this->arResult['selected'] = $service->getUex()?->getValue();
            } else {
                $this->arResult['error'][] = "Нет такого заказа";
            }
        }
    }
    
    // Оповещение заказчика
    private function sendNotifyCustomer()
    {
        $sum = SaleFormatCurrency($this->arResult['summ'], CurrencyManager::getBaseCurrency());
        $orderId = $this->arResult['orderId'];
        $toUser = $this->arResult['customer'];
        $fromUser = $this->arResult['userId'];
        $offerId = $this->arResult['offerId'];
        $serviceId = $this->arResult['serviceId'];
        $orderUrl = $this->arResult['orderUrl'];
        $tag = "order|selected|{$orderId}|{$toUser}|{$fromUser}|{$offerId}|{$serviceId}";

        $this->arResult['messageId'] = Messages::addNewOfferOrder([
            'toUser' => $toUser,
            'fromUser' => $fromUser,
            'tag' => $tag,
            'orderUrl' => $orderUrl,
            'orderId' => $orderId,
            'sum' => $sum,
        ]);

        $this->offer
            ->set('msg', $this->arResult['messageId'])
            ->save();

        PullEvent::add([$toUser, $fromUser], [
            'module_id' => "lang.academy",
            'command' => 'langOrderPublicUpdate',
            'params' => [
                'id' => $this->arResult['orderId'],
                'offerId' => $this->arResult['offerId'],
                'serviceId' => $this->arResult['serviceId'],
                'customer' => $this->arResult['customer'],
            ]
        ]);
    }

    private function setOffer()
    {
        $this->offer
            ->setName("Предложение по заказу №{$this->arResult['serviceId']}")
            ->setCreatedBy($this->arResult['userId'])
            ->setPreviewText($this->arResult['message'])
            ->set('sum', $this->arResult['summ'])
            ->set('ofr', $this->arResult['serviceId'])
            ->set('usr', $this->arResult['userId']);

        $offerSave = $this->offer->save();

        if($offerSave->isSuccess()) {
            $this->arResult['offerId'] = $offerSave->getId();

            $this->sendNotifyCustomer();
            $this->arResult['addSuccess'] = 'y';
        } else {
            $this->arResult['error'] = "Сервис временно недоступен попробуйте позже";
        }
    }

    private function sendMessage()
    {
        if(!empty($this->request->isPost())) {
            if(check_bitrix_sessid()) {

                if(!$this->isError()) {
                    $this->setOffer();
                }

            } else {
                $this->arResult["error"][] = "Ваша сессия истекла. Отправьте сообщение повторно.";
            }
        }
    }

    private function getUser($offer)
    {
        $userName = "";
        $user = $offer->get("USER");

        if(!empty($user?->getName())) {
            $userName = "{$user->getName()} ";
        }

        if(!empty($user?->getLastName())) {
            $userName .= "{$user->getLastName()}";
        }

        if(empty($userName)) {
            $userName = $user?->getLogin();
        }

        return [
            'name' => $userName,
            'photo' => $user->getPersonalPhoto(),
            'url' => "/experts/{$user->getLogin()}/"
        ];
    }

    private function initNav()
    {
        $this->arResult['nav'] = new PageNavigation('nav');
        $this->arResult['nav']->allowAllRecords(false)
            ->setPageSize(2)
            ->initFromUri();
    }

    private function applyNav()
    {
        $totalCount = $this->query->queryCountTotal();
        $this->arResult['nav']->setRecordCount($totalCount);
        $this->query->setOffset($this->arResult['nav']->getOffset());
        $this->query->setLimit($this->arResult['nav']->getLimit());
    }

    private function nextNavPage()
    {
        if($this->arResult['nav']->getPageCount() > $this->arResult['nav']->getCurrentPage()) {
            $this->arResult['next'] = $this->arResult['nav']->getCurrentPage()+1;
        } else {
            $this->arResult['next'] = 0;
        }
    }

    private function isPreOrder(): bool
    {
        $offerAccept = $this->apiOffer::query()
            ->setSelect(['ID', 'sel.VALUE'])
            ->where('ofr.VALUE', $this->arResult['serviceId'])
            ->where('sel.VALUE', 'Y')
            ->fetchCollection();

        if(!empty($offerAccept) && !$offerAccept->isEmpty()) {
            $this->arResult['preOrder'] = 'y';
            return true;
        }

        $this->arResult['preOrder'] = 'n';
        return false;
    }

    private function getOfferUser()
    {
        if(!empty($this->arResult['userId'])) {
            $this->initNav();

            $this->query
                ->setOrder(['DATE_CREATE' => 'DESC', 'ID' => 'DESC'])
                ->setSelect([
                    'ID', 'PREVIEW_TEXT', 'DATE_CREATE', 'CREATED_BY',
                    'ofr.VALUE', 'sum.VALUE', 'usr.VALUE', 'sel.VALUE', 'acp.VALUE',
                    'USER.NAME', 'USER.LAST_NAME', 'USER.LOGIN', 'USER.PERSONAL_PHOTO',
                ])
                ->where('ACTIVE', 'Y')
                ->where('ofr.VALUE', $this->arResult['serviceId'])
                // ->registerRuntimeField((new Reference('')))
                ->registerRuntimeField((new Reference('USER', UserTable::class, Join::on('this.CREATED_BY', 'ref.ID')))->configureJoinType('left'));

            if($this->isPreOrder()) {
                $this->query->where('sel.VALUE', 'Y');
            }

            // if($this->arResult['selected'] > 0) {
            //     $this->query->where('acp.VALUE', 'Y');
            // }

            if(!$this->isCustomer()) {
                $this->query->where('usr.VALUE', $this->arResult['userId']);
            }

            $this->applyNav();

            $this->offerList = $this->query->fetchCollection();

            if(!empty($this->offerList) && !$this->offerList->isEmpty()) {
                $this->arResult['offer'] = [];

                foreach($this->offerList as $offer) {
                    $sum = intval($offer->getSum()->getValue());

                    $dayMillisec = strtotime('+12 hours', strtotime($offer->getDateCreate()));

                    $date = new DateTime(date('Y-m-d H:i:s'));
                    // // $date->modify('+24 hours');
                    $date2 = new DateTime(date('Y-m-d H:i:s', $dayMillisec)); //new DateTime(strtotime($offer->getDateCreate()) + $dayMillisec);
                    // $date2->modify('+24 hours');

                    $diff = $date->diff($date2);

                    // echo date('Y-m-d H:i:s', strtotime($offer->getDateCreate())) . '<br>';
                    // echo date('Y-m-d H:i:s', $dayMillisec) . '<br>';

                    // echo $diff->format("%H:%I:%S (Полных дней: %a)") . '<br>';

                    // echo '<pre>';
                    // var_dump($diff);
                    // echo '</pre>';

                    // echo $diff->h, ' ', $diff->i, ' ', $diff->s;

                    $time = $dayMillisec - strtotime(date('d.m.Y H:i:s'));
                    $timeEnd = $dayMillisec - $time;
                    // echo $time;

                    $this->arResult['offer'][$offer->getId()] = [
                        'id' => $offer->getId(),
                        'user' => $this->getUser($offer),
                        'comment' => $offer->getPreviewText(),
                        'date' => FormatDate('j F Y H:i', $offer->getDateCreate()),
                        'sum' => SaleFormatCurrency($sum, CurrencyManager::getBaseCurrency()),
                        'accept' => $offer->getAcp()?->getValue(),
                        'preorder' => $offer->getSel()?->getValue() == 'Y' ? 'y' : 'n',
                        'timeEnd' => $diff,
                    ];
                    // echo date('d.m.Y H:i:s') . '<br>';
                    // echo date('d.m.Y H:i:s', $timeEnd) . '<br>';
                    // echo date('d.m.Y H:i:s', strtotime($offer->getDateCreate()));

                    if($offer->getCreatedBy() == $this->arResult['selected']) {
                        $this->arResult['offer'][$offer->getId()]['selected'] = 'y';
                    }
                }
            }
        }
    }

    public function executeComponent()
    {
        $this->getFieldsRequestData();

        if(!$this->isAction()) {
            $this->getFormContent();
            $this->getOfferUser();

            if(empty($this->arResult['offer']) && $this->isSave()) {
                $this->sendMessage();
            }

            if($this->isAdd()) {
                $this->includeComponentTemplate('add');
            } elseif(!empty($this->arResult['navPage'])) {
                $this->includeComponentTemplate('nav');
            } else {
                $this->IncludeComponentTemplate();
            }

            $this->nextNavPage();
        } elseif(!$this->isError()) {

            if($this->isCancel()) {
                new Offer([
                    'type' => 'order',
                    'action' => 'selected',
                    'value' => 'N',
                    'orderId' => $this->arResult['orderId'],
                    'serviceId' => $this->arResult['serviceId'],
                    'offerId' => $this->arResult['offerId'],
                ]);
            }

            if($this->isAccept()) {
                new Offer([
                    'type' => 'order',
                    'action' => 'selected',
                    'value' => 'Y',
                    'orderId' => $this->arResult['orderId'],
                    'serviceId' => $this->arResult['serviceId'],
                    'offerId' => $this->arResult['offerId'],
                ]);
            }

            if($this->isCancelConfirm()) {
                new Offer([
                    'type' => 'order',
                    'action' => 'confirm',
                    'value' => 'N',
                    'orderId' => $this->arResult['orderId'],
                    'serviceId' => $this->arResult['serviceId'],
                    'offerId' => $this->arResult['offerId'],
                ]);
            }

            if($this->isAcceptConfirm()) {
                new Offer([
                    'type' => 'order',
                    'action' => 'confirm',
                    'value' => 'Y',
                    'orderId' => $this->arResult['orderId'],
                    'serviceId' => $this->arResult['serviceId'],
                    'offerId' => $this->arResult['offerId'],
                ]);
            }

            if($this->isWithdraw()) {
                new Offer([
                    'action' => 'withdraw',
                    'value' => 'Y',
                    'orderId' => $this->arResult['orderId'],
                    'serviceId' => $this->arResult['serviceId'],
                    'offerId' => $this->arResult['offerId'],
                ]);
            }
        }

        return $this->arResult;
    }
}