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

class LangGetOrderPublic extends CBitrixComponent
{
    private string $langServiceOrder = "LangServiceOrder";
    private object $query;
    private object $session;

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    private function getFilterData()
    {
        $request = Context::getCurrent()->getRequest();
        $this->session = Application::getInstance()->getLocalSession('opFilter');

        $this->arResult['filter'] = [];

        if($this->arParams['type'] == 'ajax') {
            $this->arResult['filter']['name'] = htmlspecialcharsbx($request->get('name'));
            $this->arResult['filter']['sort'] = htmlspecialcharsbx($request->get('sort'));
            $this->arResult['filter']['spec'] = intval($request->get('spec'));
            $this->arResult['filter']['type'] = intval($request->get('type'));
        } else {
            if(!empty($request->get('name'))) {
                $this->arResult['filter']['name'] = htmlspecialcharsbx($request->get('name'));
            } elseif(!empty($this->session->get('name'))) {
                $this->arResult['filter']['name'] = $this->session->get('name');
            }

            if(!empty($request->get('sort'))) {
                $this->arResult['filter']['sort'] = htmlspecialcharsbx($request->get('sort'));
            } elseif(!empty($this->session->get('sort'))) {
                $this->arResult['filter']['sort'] = $this->session->get('sort');
            }

            if(!empty($request->get('spec'))) {
                $this->arResult['filter']['spec'] = intval($request->get('spec'));
            } elseif(!empty($this->session->get('spec'))) {
                $this->arResult['filter']['spec'] = $this->session->get('spec');
            }

            if(!empty($request->get('type'))) {
                $this->arResult['filter']['type'] = intval($request->get('type'));
            } elseif(!empty($this->session->get('type'))) {
                $this->arResult['filter']['type'] = $this->session->get('type');
            }
        }

        foreach($this->arResult['filter'] as $key => $item) {
            $this->session->set($key, $item);
        }
    }

    private function initMessage()
    {
        if(empty($this->arResult['filter'])) {
            $this->arResult['message'] = "Пока нет свободных заказов";
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

            if(!empty($this->arResult['filter']['sort'])) {
                switch($this->arResult['filter']['sort']) {
                    case 'new':
                        $this->query->addOrder('DATE_CREATE', 'DESC');
                        break;
                    case 'old':
                        $this->query->addOrder('DATE_CREATE', 'ASC');
                        break;
                    case 'deda':
                        $this->query->addOrder('ded.VALUE', 'ASC');
                        break;
                    case 'dedd':
                        $this->query->addOrder('ded.VALUE', 'DESC');
                        break;
                    case 'skua':
                        $this->query->addOrder('PRICE.PRICE', 'ASC');
                        break;
                    case 'skud':
                        $this->query->addOrder('PRICE.PRICE', 'DESC');
                        break;
                    default:
                        $this->query->addOrder('DATE_CREATE', 'DESC');
                        break;
                }

                $this->query->addOrder('ID', 'DESC');
            }

            if(!empty($this->arResult['filter']['spec'])) {
                $this->query->where('pred.VALUE', $this->arResult['filter']['spec']);
            }

            if(!empty($this->arResult['filter']['type'])) {
                $this->query->where('tWork.VALUE', $this->arResult['filter']['type']);
            }
        } else {
            $this->query->addOrder('DATE_CREATE', 'DESC');
            $this->query->addOrder('ID', 'DESC');
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
        $user = CurrentUser::get();
        $this->initNav();
        
        $this->query = Data::getTableString($this->langServiceOrder)::query()
            ->setSelect([
                'ID', 'IBLOCK_ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'PREVIEW_TEXT', 'DATE_CREATE', 'SHOW_COUNTER',
                'tWork.ELEMENT', 'sSpec.SECTION', 'pred.ELEMENT', 'ded.VALUE', 'order.VALUE',
                'STATUS.STATUS_ID', 'PRICE.PRICE', 'PRICE.CURRENCY', 'S_ID.NAME'
            ])
            ->registerRuntimeField((new Reference('STATUS', Order::class, Join::on('this.order.VALUE','ref.ID')))->configureJoinType(Join::TYPE_LEFT))
            ->registerRuntimeField((new Reference('PRICE', Price::class, Join::on('this.ID','ref.PRODUCT_ID')))->configureJoinType(Join::TYPE_LEFT))
            ->registerRuntimeField((new Reference('S_ID', StatusLangTable::class, Join::on('this.STATUS.STATUS_ID', 'ref.STATUS_ID')->where("ref.LID", "=", 'ru')))->configureJoinType(Join::TYPE_LEFT));
            // ->setCacheTtl(0)
            // ->cacheJoins(true);

        if(!empty($user->getId())) {
            $this->query
                ->where(
                    Query::filter()->logic('or')
                        ->where('STATUS.STATUS_ID', 'N')
                        ->where(
                            Query::filter()->logic('and')
                                ->where('private.VALUE', 'Y')
                                ->where('uex.VALUE', $user->getId())
                                ->where('STATUS.STATUS_ID', 'PE')
                        )
                        ->where(
                            Query::filter()->logic('and')
                                ->where('private.VALUE', 'Y')
                                ->where('CREATED_BY', $user->getId())
                                ->where('STATUS.STATUS_ID', 'PE')
                        )
                );
        } else {
            $this->query
                ->where('STATUS.STATUS_ID', 'N');
        }

        $this->applyFilter();
        $this->applyNav();

        $this->arResult['order'] = $this->query->fetchCollection();

        unset($this->query);
    }

    public function executeComponent()
    {
        $this->getFilterData();
        $this->initMessage();

        $this->arResult['detailPage'] = Data::getIblockObject($this->langServiceOrder,['select' => ['DETAIL_PAGE_URL']])
            ?->getDetailPageUrl();
        $this->arResult['type'] = $this->arParams['type'];

        $this->getOrder();

        if($this->arResult['nav']->getPageCount() > $this->arResult['nav']->getCurrentPage()) {
            $this->arResult['next'] = $this->arResult['nav']->getCurrentPage()+1;
        } else {
            $this->arResult['next'] = 0;
        }

        $this->IncludeComponentTemplate();
    }

}