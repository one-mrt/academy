<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\UI\PageNavigation;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;

class LangBlog extends CBitrixComponent
{
    private string $langBlog = "LangBlog";
    private object $query;

    public function onPrepareComponentParams($arParam)
    {
        return $arParam;
    }

    private function getFilterData()
    {
        $request = Context::getCurrent()->getRequest();
        $session = Application::getInstance()->getSession();
        $sessionFilter = $session->get('blogFilter');

        $this->arResult['filter'] = [];

        if($this->arParams['type'] == 'ajax') {
            if(!empty($request->get('name'))) {
                $this->arResult['filter']['name'] = htmlspecialcharsbx($request->get('name'));
            }

            if(!empty($request['type']) && is_array($request['type'])) {
                foreach($request['type'] as $kType => $type) {
                    if(intval($type) > 0) {
                        $this->arResult['filter']['type'][$kType] = intval($type);
                    }
                }
            }
        } else {
            if(!empty($request->get('name'))) {
                $this->arResult['filter']['name'] = htmlspecialcharsbx($request->get('name'));
            } elseif(!empty($sessionFilter['name'])) {
                $this->arResult['filter']['name'] = $sessionFilter['name'];
            }

            if(!empty($request['type']) && is_array($request['type'])) {
                foreach($request['type'] as $kType => $type) {
                    if(intval($type) > 0) {
                        $this->arResult['filter']['type'][$kType] = intval($type);
                    }
                }
            } elseif(!empty($sessionFilter['type'])) {
                $this->arResult['filter']['type'] = $sessionFilter['type'];
            }
        }

        foreach($this->arResult['filter'] as $key => $value) {
            if(empty($value)) {
                unset($this->arResult['filter'][$key]);
            }
        }

        $session->set('blogFilter', $this->arResult['filter']);
    }

    private function initMessage()
    {
        if(empty($this->arResult['filter'])) {
            $this->arResult['message'] = "Здесь пока нет статей";
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

            if(!empty($this->arResult['filter']['type'])) {
                $this->query->whereIn('type.VALUE', $this->arResult['filter']['type']);
            }
        }
    }

    private function initNav()
    {
        $this->arResult['nav'] = new PageNavigation('nav');
        $this->arResult['nav']->allowAllRecords(false)
            ->setPageSize(6)
            ->initFromUri();
    }

    private function applyNav()
    {
        $totalCount = $this->query->queryCountTotal();
        $this->arResult['nav']->setRecordCount($totalCount);
        $this->query->setOffset($this->arResult['nav']->getOffset());
        $this->query->setLimit($this->arResult['nav']->getLimit());
    }

    private function getBlog()
    {
        $this->initNav();
        
        $this->query = Data::getTableString($this->langBlog)::query()
            ->setSelect([
                'ID', 'IBLOCK_ID', 'CODE', 'IBLOCK_SECTION_ID',
                'NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'SHOW_COUNTER',
                'DATE_CREATE', 'ACTIVE_FROM', 'TIMESTAMP_X',
                'autor.ELEMENT'
            ])
            ->setOrder(['ACTIVE_FROM' => 'DESC', 'ID' => 'DESC'])
            ->where(
                Query::filter()->logic('and')
                    ->where(
                        Query::filter()->logic('or')
                            ->where('ACTIVE_TO', '>=', new DateTime())
                            ->where('ACTIVE_TO', null)
                    )
                    ->where(
                        Query::filter()->logic('or')
                            ->where('ACTIVE_FROM', '<=', new DateTime())
                            ->where('ACTIVE_FROM', null)
                    )
            )
            ->where('ACTIVE', 'Y')
            ->setCacheTtl(43200000)
            ->cacheJoins(true);

        $this->applyFilter();
        $this->applyNav();

        $this->arResult['blog'] = $this->query->fetchCollection();

        unset($this->query);
    }

    public function executeComponent()
    {
        $this->getFilterData();
        $this->initMessage();

        $this->arResult['detailPage'] = Data::getIblockObject($this->langBlog, ['select' => ['DETAIL_PAGE_URL']])
            ?->getDetailPageUrl();
        $this->arResult['type'] = $this->arParams['type'];

        $this->getBlog();

        if($this->arResult['nav']->getPageCount() > $this->arResult['nav']->getCurrentPage()) {
            $this->arResult['next'] = $this->arResult['nav']->getCurrentPage()+1;
        } else {
            $this->arResult['next'] = 0;
        }

        // echo '<div><pre><code>', print_r($this->arResult['filter'], 1), '</code></pre></div>';

        $this->IncludeComponentTemplate();
    }
}