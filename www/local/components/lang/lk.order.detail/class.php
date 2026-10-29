<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Academy\Util as Academy;
use Lang\Academy\Order as AOrder;
use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\UserTable;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Catalog\PriceTable as Price;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Main\ORM\Fields\Relations\Reference;

Loader::includeModule("sale");

class LangLkOrderDetail extends CBitrixComponent
{
    private object $user;
    private ?object $order;
    private object $parser;
    private string $langServiceOrder = "LangServiceOrder";
    private string $langOfferOrder = "LangOrderOffer";
    private string $langReview = "LangReview";
    private array $langStatusResult = ['RN', 'CP', 'MN', 'DT', 'CN', 'F'];

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);

        $this->user = CurrentUser::get();
        $this->order = Data::getTableString($this->langServiceOrder)::query();

        $this->arResult['currentUserId'] = $this->user->getId();
    }

    public function onPrepareComponentParams($arParam)
    {
        if(intval($this->request->getPost('serviceId')) > 0) {
            $arParam['order'] = intval($this->request->getPost('serviceId'));
        } else {
            $arParam['order'] = intval($arParam['order']);
        }

        if($arParam['order'] <= 0) {
            $this->arResult['error'][] = "Нет такого заказа";
        }

        return $arParam;
    }

    private function getParser() {
        $this->parser = new \CTextParser();
        $this->parser->allow = array(
            "HTML" => "N",
            "ANCHOR" => "Y",  // ссылки
            "BIU" => "Y",    // жирный, курсив, подчеркнутый
            // "IMG" => "Y",   // изображения
            // "QUOTE" => "Y", // цитаты
            // "CODE" => "Y",   // блоки кода
            // "FONT" => "Y",   // шрифты
            "LIST" => "Y",   // списки
            // "SMILES" => "Y", // смайлики
            // "NL2BR" => "Y", // перенос строк
            // "VIDEO" => "Y", // видео
            // "TABLE" => "Y", // таблицы
            "CUT_ANCHOR" => "N", // запрет на вставку закрывающего тега ссылки
            // "ALIGN" => "Y"  // выравнивание
        );
    }

    private function setSeo()
    {
        global $APPLICATION;

        $seo = Data::getSeoPropertyElement($this->langServiceOrder, $this->order->getId());

        $APPLICATION->SetTitle(strip_tags($seo['ELEMENT_PAGE_TITLE']));
        $APPLICATION->SetPageProperty("title", strip_tags($seo['ELEMENT_META_TITLE']));
        $APPLICATION->SetPageProperty("description", strip_tags($seo['ELEMENT_META_DESCRIPTION']));
        $APPLICATION->SetPageProperty("keywords", strip_tags($seo['ELEMENT_META_KEYWORDS']));
        $APPLICATION->AddChainItem(strip_tags($seo['ELEMENT_PAGE_TITLE']), Data::getDetailPageUrlApiCode($this->langServiceOrder, $this->order));
        \CIBlockElement::CounterInc($this->order->getId());
    }

    private function getUserAnswer()
    {
        if(!empty($this->order->getUex()) && intval($this->order->getUex()?->getValue()) > 0) {
            $userAnswer = UserTable::getList([
                'select' => ['NAME', 'LAST_NAME', 'LOGIN', 'PERSONAL_PHOTO'],
                'filter' => ['ID' => $this->order->getUex()?->getValue(), 'ACTIVE' => 'Y'],
            ])->fetchObject();

            if(!empty($userAnswer)) {
                $answerName = "";

                if(!empty($userAnswer->getName())) {
                    $answerName = "{$userAnswer->getName()} ";
                }

                if(!empty($userAnswer->getLastName())) {
                    $answerName .= $userAnswer->getLastName();
                }

                if(empty($answerName)) {
                    $answerName = $userAnswer->getLogin();
                }

                $this->arResult['order']['answerId'] = $this->order->getUex()->getValue();
                $this->arResult['order']['answer'] = '<a target="_blank" href="/experts/'.$userAnswer->getLogin().'/" class="h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3">' . $answerName . '</a>';
            } else {
                $this->arResult['order']['answer'] = 'Не активный пользователь';
            }

        } else {
            $this->arResult['order']['answer'] = 'Не выбран';
        }
    }

    private function getUserType()
    {
        $current = intval($this->arResult['currentUserId']);
        $customer = intval($this->arResult['order']['customerId']);
        $answer = intval($this->arResult['order']['answerId']);

        if($customer == $current) {
            $this->arResult['userType'] = "customer";
        } elseif($answer == $current) {
            $this->arResult['userType'] = "answer";
        } elseif(in_array(AOrder::MANAGER_GROUP, $this->user->getUserGroups())) {
            $this->arResult['userType'] = "manager";
        }
    }

    private function isUserCustomer(): bool
    {
        if($this->arResult['userType'] == "customer") {
            return true;
        }

        return false;
    }

    private function isUserAnswer(): bool
    {
        if($this->arResult['userType'] == "answer") {
            return true;
        }

        return false;
    }

    private function getAccessStatus()
    {
        $current = $this->arResult['order']['statusId'];
        $userType = $this->arResult['userType'];

        if(!empty($userType) && !empty($current)) {
            if(!empty(AOrder::ACCESS_STATUS[$userType][$current])) {

                $statusList = StatusLangTable::getList([
                    'select' => ['NAME', 'STATUS_ID'],
                    'filter' => ['LID' => SITE_ID, 'STATUS_ID' => AOrder::ACCESS_STATUS[$userType][$current]],
                    'cache' => Data::getCache(60*60*24*7)
                ])->fetchCollection();

                $this->arResult['statusEdit'] = [];

                foreach(AOrder::ACCESS_STATUS[$userType][$current] as $status) {
                    foreach($statusList as $statusName) {
                        if($statusName->getStatusId() == $status) {
                            $this->arResult['statusEdit'][$status] = $statusName->getName();
                            break;
                        }
                    }
                }
            }
        }
    }

    private function privateAcceptAnswerOrder()
    {
        if($this->arResult['currentUserId'] == $this->order?->getUex()?->getValue() && $this->order?->getPrivate()?->getValue() == 'Y') {
            $offerOrder = Data::getTableString($this->langOfferOrder)::query()
                ->setSelect(['ID'])
                ->where('ofr.VALUE', $this->order->getId())
                ->where('acp.VALUE', 'Y')
                ->fetchCollection();

            if($offerOrder->isEmpty()) {
                Data::get404();
            }
        }
    }

    private function prepareOrder() {
        
        $this->arResult['order'] = [
            'name' => $this->order->getName(),
        ];

        if(!empty($this->order->getPreviewText())) {
            $this->arResult['order']['description'] = $this->parser->convertText($this->order->getPreviewText());
        }

        if(!empty($this->order->getOrder())) {
            $this->arResult['order']['id'] = $this->order->getOrder()?->getValue();
        }

        if(!empty($this->order->getSspec())) {
            $this->arResult['order']['spec'] = $this->order->getSspec()->getSection()?->getName();
        }

        if(!empty($this->order->getTwork())) {
            $this->arResult['order']['work'] = $this->order->getTwork()->getElement()?->getName();
        }

        if(!empty($this->order->getAnti())) {
            $this->arResult['order']['anti'] = $this->order->getAnti()?->getValue();
        } else {
            $this->arResult['order']['anti'] = "Не выбрано";
        }

        if(!empty($this->order->getDed())) {
            $this->arResult['order']['date'] = \FormatDate('j F Y', strtotime($this->order->getDed()?->getValue()));
        }

        if(!empty($this->order->get('PRICE'))) {
            if(intval($this->order->get('PRICE')->getPrice()) > 0) {
                $this->arResult['order']['price'] = \SaleFormatCurrency($this->order->get('PRICE')->getPrice(), $this->order->get('PRICE')->getCurrency());
            } else {
                $this->arResult['order']['price'] = "По договоренности";
            }

            $this->arResult['order']['priceNum'] = intval($this->order->get('PRICE')->getPrice());
        }

        if(!empty($this->order->getCreatedBy())) {
            $this->arResult['order']['customerId'] = $this->order->getCreatedBy();
        }

        if(!empty($this->order->get('S_ID'))) {
            $this->arResult['order']['statusId'] = $this->order?->get('STATUS')?->getStatusId();
            $this->arResult['order']['status'] = $this->order?->get("S_ID")?->getName();
        }

        if(!empty($this->order->getRev()?->getValue())) {
            $this->arResult['order']['review'] = $this->order->getRev()->getValue();

            $rev = Data::getTableString($this->langReview)::query()
                ->addSelect('NAME')
                ->addSelect('score.VALUE')
                ->where('ID', $this->arResult['order']['review'])
                ->fetchObject();

            if(!empty($rev)) {
                $this->arResult['order']['reviewScore'] = intval($rev->getScore()?->getValue());
                $this->arResult['order']['reviewName'] = $rev->getName();
            }
        }

        $this->getUserAnswer();
        $this->getUserType();
        $this->getAccessStatus();

        if(!empty($this->order->getChat())) {
            $this->arResult['order']['chat'] = intval($this->order->getChat()?->getValue());
        }

        if(!empty($this->order->getFile()) && !empty($this->order->getFile()->getAll())) {
            $this->arResult['order']['file'] = [];

            $fileType = Academy::getAcceptFileIcon();

            foreach($this->order->getFile()->getAll() as $fileValue) {
                $file = \CFile::GetFileArray($fileValue->getValue());
                $this->arResult['order']['file'][] = [
                    'name' => $file['ORIGINAL_NAME'],
                    'svg' => $fileType[$file['CONTENT_TYPE']],
                    'src' => $file['SRC']
                ];
            }
        }

        if(in_array($this->arResult['order']['statusId'], $this->langStatusResult)) {

            if(!empty($this->order->getRes()) && !empty($this->order->getRes()->getAll())) {
                $this->arResult['order']['fileRes'] = [];

                $fileType = Academy::getAcceptFileIcon();

                foreach($this->order->getRes()->getAll() as $fileValue) {
                    $file = \CFile::GetFileArray($fileValue->getValue());
                    $this->arResult['order']['fileRes'][] = [
                        'name' => $file['ORIGINAL_NAME'],
                        'svg' => $fileType[$file['CONTENT_TYPE']],
                        'src' => $file['SRC']
                    ];
                }
            }

            $this->arResult['showResult'] = "y";

            if($this->isUserAnswer()) {
                $this->arResult['editFileRes'] = 'y';
            }
        }

        if($this->isUserCustomer()) {
            if($this->arResult['order']['statusId'] == "RF") {
                $this->arResult['editDraft'] = 'y';
            }

            if($this->arResult['order']['statusId'] == "OP") {
                $this->arResult['pay'] = 'y';
            }

            if(empty($this->arResult['order']['review']) && !empty($this->arResult['order']['answerId']) && $this->arResult['order']['statusId'] == 'F') {
                $this->arResult['reviewAdd'] = 'y';
            }
        }
    }

    private function getOrder()
    {
        $this->order->setSelect([
                'ID', 'IBLOCK_ID', 'CODE', 'IBLOCK_SECTION_ID', 'CREATED_BY', 'NAME', 'PREVIEW_TEXT', 'DATE_CREATE', 'private.VALUE',
                'sSpec.SECTION', 'pred.ELEMENT', 'tWork.ELEMENT', 'ded.VALUE', 'anti.VALUE',
                'file.VALUE', 'order.VALUE', 'chat.VALUE', 'uex.VALUE', 'res.VALUE', 'rev.VALUE',
                'STATUS.ID', 'STATUS.USER_ID', 'STATUS.STATUS_ID', 'STATUS.RESPONSIBLE_ID',
                'PRICE.PRICE', 'PRICE.CURRENCY',
                'S_ID.NAME'
            ])
            ->registerRuntimeField((new Reference('STATUS', Order::class, Join::on('this.order.VALUE','ref.ID')))->configureJoinType(Join::TYPE_LEFT))
            ->registerRuntimeField((new Reference('PRICE', Price::class, Join::on('this.ID','ref.PRODUCT_ID')))->configureJoinType(Join::TYPE_LEFT))
            ->registerRuntimeField((new Reference('S_ID', StatusLangTable::class, Join::on('this.STATUS.STATUS_ID', 'ref.STATUS_ID')->where("ref.LID", "=", 'ru')))->configureJoinType(Join::TYPE_LEFT))
            ->where('ID', $this->arParams['order']);

        if(!in_array(AOrder::MANAGER_GROUP, $this->user->getUserGroups())) {
            $this->order->where(
                Query::filter()->logic('or')
                    ->where('CREATED_BY', $this->arResult['currentUserId'])
                    ->where('STATUS.RESPONSIBLE_ID', $this->arResult['currentUserId'])
            );
        }
        
        $this->order = $this->order->fetchObject();

        if(empty($this->order)) {
            Data::get404();
            $this->arResult['error'] = 'Нет такого заказа';
        } else {
            // $this->privateAcceptAnswerOrder();
            $this->prepareOrder();
        }
    }

    public function executeComponent()
    {
        if(empty($this->arResult['error'])) {
            $this->getParser();
            $this->getOrder();

            if(empty($this->arResult['error'])) {
                $this->includeComponentTemplate();
                $this->setSeo();

                return $this->arResult['order'];
            }
        }
    }
}