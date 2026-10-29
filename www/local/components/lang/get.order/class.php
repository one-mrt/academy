<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\UI\PageNavigation;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Catalog\PriceTable as Price;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

use Bitrix\Iblock\Elements\ElementLangOrderOfferTable;

class LangGetOrder extends CBitrixComponent
{
    private string $langServiceOrder = "LangServiceOrder";
    private string $langOfferOrder = "LangOrderOffer";
    private object $query;
    private object $user;
    private object $session;

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);

        $this->user = CurrentUser::get();
        $this->query = Data::getTableString($this->langServiceOrder)::query();
        $this->session = Application::getInstance()->getLocalSession('lkOrderFilter');
    }

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    private function getFilterData()
    {
        $this->arResult['filter'] = [];

        if($this->arParams['ajax'] == 'y') {
            $this->arResult['filter']['tab'] = htmlspecialcharsbx($this->request->get('tab'));
            $this->arResult['filter']['name'] = htmlspecialcharsbx($this->request->get('name'));
            $this->arResult['filter']['status'] = htmlspecialcharsbx($this->request->get('status'));
            $this->arResult['filter']['spec'] = intval($this->request->get('spec'));
            $this->arResult['filter']['type'] = intval($this->request->get('type'));
        } else {

            if(!empty($this->request->get('tab'))) {
                $this->arResult['filter']['tab'] = htmlspecialcharsbx($this->request->get('tab'));
            } elseif(!empty($this->session->get('tab'))) {
                $this->arResult['filter']['tab'] = $this->session->get('tab');
            }

            if(!empty($this->request->get('name'))) {
                $this->arResult['filter']['name'] = htmlspecialcharsbx($this->request->get('name'));
            } elseif(!empty($this->session->get('name'))) {
                $this->arResult['filter']['name'] = $this->session->get('name');
            }

            if(!empty($this->request->get('status'))) {
                $this->arResult['filter']['status'] = htmlspecialcharsbx($this->request->get('status'));
            } elseif(!empty($this->session->get('status'))) {
                $this->arResult['filter']['status'] = $this->session->get('status');
            }

            if(!empty($this->request->get('spec'))) {
                $this->arResult['filter']['spec'] = intval($this->request->get('spec'));
            } elseif(!empty($this->session->get('spec'))) {
                $this->arResult['filter']['spec'] = $this->session->get('spec');
            }

            if(!empty($this->request->get('type'))) {
                $this->arResult['filter']['type'] = intval($this->request->get('type'));
            } elseif(!empty($this->session->get('type'))) {
                $this->arResult['filter']['type'] = $this->session->get('type');
            }
        }

        $this->arParams['type'] = $this->arResult['filter']['tab'] ?? $this->arParams['type'];

        foreach($this->arResult['filter'] as $key => $item) {
            $this->session->set($key, $item);
        }
    }

    private function initMessage()
    {
        if(empty($this->arResult['filter'])) {
            if($this->arParams['type'] == 'buy') {
                $this->arResult['message'] = "Вы пока ничего не заказывали";
            } else {
                $this->arResult['message'] = "У вас пока нет работ";
            }
        } else {
            $this->arResult['message'] = "Ничего не найдено";
        }
    }

    private function applyFilter()
    {
        if(!empty($this->arResult['filter'])) {
            if(!empty($this->arResult['filter']['name'])) {
               $this->query->whereLike("NAME", "%{$this->arResult['filter']['name']}%");
            }

            if(!empty($this->arResult['filter']['status'])) {
                $this->query->where('STATUS.STATUS_ID', $this->arResult['filter']['status']);
            }

            if(!empty($this->arResult['filter']['spec'])) {
                $this->query->where('pred.VALUE', $this->arResult['filter']['spec']);
            }

            if(!empty($this->arResult['filter']['type'])) {
                $this->query->where('tWork.VALUE', $this->arResult['filter']['type']);
            }
        }
    }

    private function initNav()
    {
        $this->arResult['nav'] = new PageNavigation('nav');
        $this->arResult['nav']->allowAllRecords(false)
            ->setPageSize(4)
            ->initFromUri();
    }

    private function applyNav()
    {
        $totalCount = $this->query->queryCountTotal();
        $this->arResult['nav']->setRecordCount($totalCount);
        $this->query->setOffset($this->arResult['nav']->getOffset());
        $this->query->setLimit($this->arResult['nav']->getLimit());
    }

    private function getOrder()
    {
        $this->initNav();
        
        // Должно быть принято хотя бы одно предложение в приватном заказе, инче приватные не показывать
        // Если приватный и принят
        $this->query = $this->query
            ->setOrder(['DATE_CREATE' => 'DESC', 'ID' => 'DESC'])
            ->setSelect([
                'ID', 'IBLOCK_ID', 'CODE', 'IBLOCK_SECTION_ID', 'NAME', 'PREVIEW_TEXT', 'DATE_CREATE', 'CREATED_BY',
                'uex.value', 'private.value', 'order.value',
                'STATUS.RESPONSIBLE_ID', 'STATUS.USER_ID', 'STATUS.STATUS_ID',
                'PRICE.PRICE', 'PRICE.CURRENCY',
                'S_ID.NAME'
            ])
            ->registerRuntimeField((new Reference('STATUS', Order::class, Join::on('this.order.VALUE','ref.ID')))->configureJoinType(Join::TYPE_LEFT))
            ->registerRuntimeField((new Reference('PRICE', Price::class, Join::on('this.ID','ref.PRODUCT_ID')))->configureJoinType(Join::TYPE_LEFT))
            ->registerRuntimeField((new Reference('S_ID', StatusLangTable::class, Join::on('this.STATUS.STATUS_ID', 'ref.STATUS_ID')->where("ref.LID", "=", 'ru')))->configureJoinType(Join::TYPE_LEFT))
            ->where('STATUS.RESPONSIBLE_ID', $this->user->getId());

        $this->applyFilter();
        $this->applyNav();

        $this->arResult['order'] = $this->query->fetchCollection();

        unset($this->query);
    }

    private function getOrderBuy()
    {
        $this->initNav();
        
        $this->query = $this->query
            ->setOrder(['DATE_CREATE' => 'DESC', 'ID' => 'DESC'])
            ->setSelect([
                'ID', 'IBLOCK_ID', 'CODE', 'IBLOCK_SECTION_ID', 'NAME', 'PREVIEW_TEXT', 'DATE_CREATE', 'CREATED_BY',
                'order.value',
                'STATUS.USER_ID', 'STATUS.STATUS_ID',
                'PRICE.PRICE', 'PRICE.CURRENCY',
                'S_ID.NAME'
            ])
            ->registerRuntimeField((new Reference('STATUS', Order::class, Join::on('this.order.VALUE','ref.ID')))->configureJoinType(Join::TYPE_LEFT))
            ->registerRuntimeField((new Reference('PRICE', Price::class, Join::on('this.ID','ref.PRODUCT_ID')))->configureJoinType(Join::TYPE_LEFT))
            ->registerRuntimeField((new Reference('S_ID', StatusLangTable::class, Join::on('this.STATUS.STATUS_ID', 'ref.STATUS_ID')->where("ref.LID", "=", 'ru')))->configureJoinType(Join::TYPE_LEFT))
            ->where(
                Query::filter()->logic('or')
                    ->where('CREATED_BY', $this->user->getId())
                    ->where('STATUS.USER_ID', $this->user->getId())
            );

        $this->applyFilter();
        $this->applyNav();

        $this->arResult['order'] = $this->query->fetchCollection();

        unset($this->query);
    }

    public function executeComponent()
    {
        $this->getFilterData();
        $this->initMessage();

        $this->arResult['detailPage'] = Data::getIblockObject($this->langServiceOrder,
            ['select' => ['DETAIL_PAGE_URL']])
            ?->getDetailPageUrl();
        $this->arResult['type'] = $this->arParams['type'];

        if($this->arParams['type'] == 'buy') {
            $this->getOrderBuy();
        } else {
            $this->getOrder();
        }

        if($this->arResult['nav']->getPageCount() > $this->arResult['nav']->getCurrentPage()) {
            $this->arResult['next'] = $this->arResult['nav']->getCurrentPage()+1;
        } else {
            $this->arResult['next'] = 0;
        }

        $this->IncludeComponentTemplate();
    }

}