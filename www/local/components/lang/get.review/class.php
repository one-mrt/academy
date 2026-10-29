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
use Bitrix\Main\UserTable;

class LangGetReview extends CBitrixComponent
{
    private string $langReview = "LangReview";
    private object $query;
    private ?object $review;
    private array $userPhoto = [];

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);

        // $this->user = CurrentUser::get();
        $this->query = Data::getTableString($this->langReview)::query();
        // $this->review = Data::getTableString($this->langReview)::createObject();

        // $this->arResult['currentUserId'] = $this->user->getId();
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams['user'] = (intval($arParams['user']) > 0) ? intval($arParams['user']) : 0;
        $arParams['count'] = (intval($arParams['count']) > 0) ? intval($arParams['count']) : 2;

        return $arParams;
    }

    private function getUser()
    {
        if(empty($this->arParams['user'])) {
            $this->arParams['user'] = intval($this->request->get('user'));
        }
    }

    private function initMessage()
    {
        if(!empty($this->arParams['user']) || empty($this->arResult['review'])) {
            $this->arResult['message'] = "Пока нет отзывов";
        }
    }

    private function initNav()
    {
        $this->arResult['nav'] = new PageNavigation('nav');
        $this->arResult['nav']->allowAllRecords(false)
            ->setPageSize($this->arParams['count'])
            ->initFromUri();
    }

    private function applyNav()
    {
        $totalCount = $this->query->queryCountTotal();
        $this->arResult['nav']->setRecordCount($totalCount);
        $this->query->setOffset($this->arResult['nav']->getOffset());
        $this->query->setLimit($this->arResult['nav']->getLimit());
    }

    private function getUserPhoto()
    {
        $users =& $this->userPhoto;

        UserTable::getList([
            'select' => ['ID', 'PERSONAL_PHOTO'],
            'filter' => ['ID' => $this->review->getUoCollection()->getValueList()],
            'cache' => Data::getCache(),
        ])->fetchCollection()
        ->walk(static function($user, $key) use (&$users) {
            $users[$user->getId()] = $user->getPersonalPhoto();
        });
        
        unset($users);
    }

    private function prepareReview()
    {
        if(!empty($this->review) && !$this->review->isEmpty()) {
            $this->getUserPhoto();

            foreach($this->review as $review) {
                $this->arResult['review'][$review->getId()] = [
                    'name' => $review->getName(),
                    'text' => $review->getDetailText(),
                    'score' => $review->getScore()?->getValue(),
                ];

                if(!empty($review->getPreviewPicture())) {
                    $this->arResult['review'][$review->getId()]['picture'] = $review->getPreviewPicture();
                } else {
                    $this->arResult['review'][$review->getId()]['picture'] = $this->userPhoto[$review->getUo()?->getValue()]; // $user->getPersonalPhoto();
                }

                $this->arResult['review'][$review->getId()]['dol'] = $review->getPreviewText();
            }
        }
    }

    private function getReview()
    {
        $this->initNav();
        
        $this->query
            ->setOrder(['DATE_CREATE' => 'DESC', 'ID' => 'DESC'])
            ->setSelect(['NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PREVIEW_PICTURE', 'score.VALUE', 'ur.VALUE', 'uo.VALUE'])
            ->where('ACTIVE', 'Y')
            ->where('ur.VALUE', $this->arParams['user'])
            ->setCacheTtl(360000000)
            ->cacheJoins(true);

        $this->applyNav();

        $this->review = $this->query->fetchCollection();

        unset($this->query);
    }

    public function executeComponent()
    {
        $this->getUser();

        if(!empty($this->arParams['user'])) {
            $this->getReview();
            $this->prepareReview();

            if($this->arResult['nav']->getPageCount() > $this->arResult['nav']->getCurrentPage()) {
                $this->arResult['next'] = $this->arResult['nav']->getCurrentPage()+1;
            } else {
                $this->arResult['next'] = 0;
            }
        }

        $this->initMessage();
        $this->IncludeComponentTemplate();
    }

}