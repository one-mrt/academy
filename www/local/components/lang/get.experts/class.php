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
use Bitrix\Main\UserTable;

class LangGetExperts extends CBitrixComponent
{
    private object $query;

    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    private function getFilterData()
    {
        $request = Context::getCurrent()->getRequest();

        $session = Application::getInstance()->getSession();
        $sessionFilter = $session->get('exFilter');

        $this->arResult['filter'] = [];

        if($this->arParams['ajax'] == 'y') {
            $this->arResult['filter']['name'] = htmlspecialcharsbx($request->get('name'));
            $this->arResult['filter']['sort'] = htmlspecialcharsbx($request->get('sort'));
            $this->arResult['filter']['spec'] = intval($request->get('spec'));
            $this->arResult['filter']['type'] = intval($request->get('type'));
        } else {
            if(!empty($request->get('name')))
                $this->arResult['filter']['name'] = htmlspecialcharsbx($request->get('name'));
            elseif(!empty($sessionFilter['name'])) $this->arResult['filter']['name'] = $sessionFilter['name'];

            if(!empty($request->get('sort')))
                $this->arResult['filter']['sort'] = htmlspecialcharsbx($request->get('sort'));
            elseif(!empty($sessionFilter['sort'])) $this->arResult['filter']['sort'] = $sessionFilter['sort'];

            if(!empty($request->get('spec')))
                $this->arResult['filter']['spec'] = intval($request->get('spec'));
            elseif(!empty($sessionFilter['spec'])) $this->arResult['filter']['spec'] = $sessionFilter['spec'];

            if(!empty($request->get('type')))
                $this->arResult['filter']['type'] = intval($request->get('type'));
            elseif(!empty($sessionFilter['type'])) $this->arResult['filter']['type'] = $sessionFilter['type'];
        }

        $session->set('exFilter', $this->arResult['filter']);
    }

    private function initMessage()
    {
        if(empty($this->arResult['filter'])) {
            $this->arResult['message'] = "Нет экспертов";
        } else {
            $this->arResult['message'] = "Ничего не найдено";
        }
    }

    private function applyFilter()
    {
        if(!empty($this->arResult['filter'])) {
            if(!empty($this->arResult['filter']['name'])) {
                $this->query->where(
                    Query::filter()->logic('or')
                        ->whereLike("LAST_NAME", "%{$this->arResult['filter']['name']}%")
                        ->whereLike("NAME", "%{$this->arResult['filter']['name']}%")
                );
            }

            if(!empty($this->arResult['filter']['sort'])) {
                switch($this->arResult['filter']['sort']) {
                    // case 'new':
                    //     $this->query->addOrder('DATE_CREATE', 'DESC');
                    //     break;
                    // case 'old':
                    //     $this->query->addOrder('DATE_CREATE', 'ASC');
                    //     break;
                    // case 'deda':
                    //     $this->query->addOrder('ded.VALUE', 'ASC');
                    //     break;
                    // case 'dedd':
                    //     $this->query->addOrder('ded.VALUE', 'DESC');
                    //     break;
                    // case 'skua':
                    //     $this->query->addOrder('PRICE.PRICE', 'ASC');
                    //     break;
                    // case 'skud':
                    //     $this->query->addOrder('PRICE.PRICE', 'DESC');
                    //     break;
                    default:
                        // $this->query->addOrder('DATE_CREATE', 'DESC');
                        break;
                }

                $this->query->addOrder('ID', 'DESC');
            }

            if(!empty($this->arResult['filter']['spec'])) {
                $this->query->where('UF_SPEC', $this->arResult['filter']['spec']);
            }

            if(!empty($this->arResult['filter']['type'])) {
                $this->query->where('UF_WORKTYPE', $this->arResult['filter']['type']);
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

    private function getExpert()
    {
        $this->initNav();
        
        $this->query = UserTable::query()
            ->setSelect([
                'NAME', 'LAST_NAME', 'PERSONAL_PHOTO', 'IS_ONLINE', 'GROUPS.GROUP_ID', 'LOGIN', 'UF_SCORE', 'UF_COUNT'
            ])
            ->where('ACTIVE', 'Y')
            ->where('GROUPS.GROUP_ID', 5)
            ->setCacheTtl(3000)
            ->cacheJoins(true);

        $this->applyFilter();
        $this->applyNav();

        $this->arResult['expert'] = $this->query->fetchCollection();

        unset($this->query);
    }

    public function executeComponent()
    {
        $this->getFilterData();
        $this->initMessage();

        $this->arResult['detailPage'] = "/experts/";
        $this->arResult['type'] = $this->arParams['type'];

        $this->getExpert();

        if($this->arResult['nav']->getPageCount() > $this->arResult['nav']->getCurrentPage()) {
            $this->arResult['next'] = $this->arResult['nav']->getCurrentPage()+1;
        } else {
            $this->arResult['next'] = 1;
        }

        $this->IncludeComponentTemplate();
    }

}