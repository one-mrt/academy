<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Main\Localization\Loc;
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

Loader::includeModule('im');
Loader::includeModule('sale');
Loader::includeModule('catalog');

class LangOrderOfferList extends CBitrixComponent
{
    private ?object $user;
    private ?object $offer;
    private string $langText = "LangText";
    private string $langOrderOffer = "LangOrderOffer";
    private string $langServiceOrder = "LangServiceOrder";

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);

        $this->user = CurrentUser::get();
        $this->arResult['userId'] = intval($this->user->getId());

        $this->offer = Data::getTableString($this->langOrderOffer)::createObject();
    }

    public function onPrepareComponentParams($arParam)
    {
        $arParams['toUser'] = intval($arParam['toUser']);
        $arParams['orderId'] = intval($arParam['orderId']);
        $arParams['elementId'] = intval($arParam['elementId']);

        return $arParam;
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
            'photo' => $user->getPersonalPhoto()
        ];
    }

    private function getOfferUser()
    {
        if(!empty($this->arResult['userId'])) {
            $this->arResult['isCustomer'] = 'n';
            $filter = Query::filter()
                ->where('ACTIVE', 'Y')
                ->where('ofr.VALUE', $this->arParams['elementId']);

            // Заказчик - владелец
            if($this->arParams['toUser'] == $this->arResult['userId']) {
                $this->arResult['isCustomer'] = 'y';
            } else {
                // Исполнитель
                $this->arResult['isCustomer'] = 'n';
                $filter->where('usr.VALUE', $this->arResult['userId']);
            }

            $offerList = Data::getTableString($this->langOrderOffer)::getList([
                'order' => ['DATE_CREATE' => 'DESC', 'ID' => 'DESC'],
                'select' => [
                    'ID', 'PREVIEW_TEXT', 'DATE_CREATE', 'CREATED_BY', 'ofr.VALUE', 'sum.VALUE', 'usr.VALUE',
                    'USER.NAME', 'USER.LAST_NAME', 'USER.LOGIN', 'USER.PERSONAL_PHOTO',
                ],
                'filter' => $filter,
                'runtime' => [
                    (new Reference('USER', UserTable::class, Join::on('this.CREATED_BY', 'ref.ID')))->configureJoinType('left'),
                ],
                'cache' => Data::getCache(0),
            ])->fetchCollection();

            if(!empty($offerList) && !empty($offerList->getIdList())) {
                foreach ($offerList as $offer) {
                    $sum = intval($offer->getSum()?->getValue());

                    $this->arResult['offer'][] = [
                        'id' => $offer->getId(),
                        'user' => $this->getUser($offer),
                        'comment' => $offer->getPreviewText(),
                        'date' => FormatDate('j F Y', $offer->getDateCreate()),
                        'sum' => SaleFormatCurrency($sum, CurrencyManager::getBaseCurrency()),
                    ];
                }
            }
        }
    }

    public function executeComponent()
    {
        $this->getOfferUser();
        $this->IncludeComponentTemplate();
    }
}