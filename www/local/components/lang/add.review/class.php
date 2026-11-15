<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Academy\Util as Academy;
use Lang\Academy\Order as AOrder;
use Bitrix\Pull\Event as PullEvent;
use Lang\Academy\Messages;

use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\UserTable;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\Text\HtmlFilter;

class LangAddReview extends CBitrixComponent
{
    private object $user;
    private ?object $order;
    private ?object $review;
    private ?object $query;
    private object $parser;
    private string $langServiceOrder = "LangServiceOrder";
    private string $langReview = "LangReview";
    private ?object $sanitizer;

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);

        $this->user = CurrentUser::get();
        $this->query = Data::getTableString($this->langServiceOrder)::query();
        $this->review = Data::getTableString($this->langReview)::createObject();

        $this->arResult['currentUserId'] = $this->user->getId();
    }

    public function onPrepareComponentParams($arParam)
    {
        return $arParam;
    }

    private function getParser()
    {
        $this->parser = new \CTextParser();
        $this->parser->allow = [
            "HTML" => "N",
            "ANCHOR" => "Y",  // ссылки
            "BIU" => "Y",    // жирный, курсив, подчеркнутый
            "IMG" => "Y",   // изображения
            "QUOTE" => "Y", // цитаты
            "CODE" => "Y",   // блоки кода
            "FONT" => "Y",   // шрифты
            "LIST" => "Y",   // списки
            "SMILES" => "Y", // смайлики
            "NL2BR" => "Y", // перенос строк
            "VIDEO" => "Y", // видео
            "TABLE" => "Y", // таблицы
            "CUT_ANCHOR" => "Y", // запрет на вставку закрывающего тега ссылки
            "ALIGN" => "Y"  // выравнивание
        ];
    }

    private function getSanitizer()
    {
        $this->sanitizer = new CBXSanitizer();
        $this->sanitizer->AddTags([
            'p' => []
        ]);
    }

    private function isAdd(): bool
    {
        if($this->arResult['add'] == 'y') {
            return true;
        }

        return false;
    }

    private function getData()
    {
        if(intval($this->request->getPost('serviceId')) > 0) {
            $this->arResult['order'] = intval($this->request->getPost('serviceId'));
        }

        if($this->arResult['order'] <= 0) {
            $this->arResult['error']['noedit'] = "Вы не можете оставить отзыв";
        }

        if($this->request->getPost('add') == 'y') {
            $this->arResult['add'] = 'y';

            $this->arResult['score'] = intval($this->request->getPost('score'));
            $this->arResult['description'] = HtmlFilter::endcode($this->request->getPost('description'));

            if($this->arResult['score'] <= 0 && $this->arResult['score'] > 5) {
                $this->arResult['error']['noedit'] = "Вы не можете оставить отзыв";
            }

            $length = mb_strlen($this->arResult['description']);

            // $this->arResult['length'] = mb_strlen($this->request->getPost('description'));

            if($length > 400) {
                $this->arResult['error']['textlength'] = "Отзыв не может быть более 400 символов";
            }
        }
    }

    private function prepareOrder()
    {
        $this->arResult['id'] = intval($this->order->getId());
        $this->arResult['name'] = $this->order->getName();
        $this->arResult['customer'] = intval($this->order?->getCreatedBy());
        $this->arResult['code'] = $this->order->getCode();
        $this->arResult['order'] = intval($this->order->getOrder()?->getValue());
        $this->arResult['status'] = $this->order?->get('STATUS')?->getStatusId();
        $this->arResult['answer'] = intval($this->order?->get('STATUS')?->getResponsibleId());
        $this->arResult['chat'] = intval($this->order?->getChat()?->getValue());
    }

    private function getOrder()
    {
        $this->query->setSelect(['NAME', 'CREATED_BY', 'CODE', 'order.VALUE', 'chat.VALUE', 'tWork.ELEMENT', 'STATUS.STATUS_ID', 'STATUS.RESPONSIBLE_ID'])
            ->registerRuntimeField((new Reference('STATUS', Order::class, Join::on('this.order.VALUE','ref.ID')))->configureJoinType(Join::TYPE_LEFT))
            ->where('ID', $this->arResult['order'])
            ->where('CREATED_BY', $this->arResult['currentUserId'])
            ->where('STATUS.STATUS_ID', 'F')
            ->where('STATUS.RESPONSIBLE_ID', '!=', null)
            ->where('rev.VALUE', null);
        
        $this->order = $this->query->fetchObject();

        if(empty($this->order)) {
            $this->arResult['error']['noedit'] = 'Вы не можете оставить отзыв';
        } else {
            $this->prepareOrder();
        }
    }

    private function addReview()
    {
        $user = UserTable::getList([
            'select' => ['ID', 'NAME', 'LAST_NAME', 'LOGIN'],
            'filter' => ['ID' => $this->arResult['customer']],
            'cache' => Data::getCache(),
        ])->fetchObject();

        $userName = Academy::getUserFullName(null, $user);
        $userUrl = "/experts/{$user->getLogin()}/";

        $saveReview = $this->review
            ->setActive(true)
            ->setName($userName)
            ->set('ur', $this->arResult['answer'])
            ->set('uo', $this->arResult['customer'])
            ->set('score', $this->arResult['score'])
            ->setPreviewText($this->order->getTwork()?->getElement()?->getName())
            ->setDetailText($this->sanitizer->SanitizeHtml($this->parser->convertText($this->arResult['description'])))
            ->save();

        if($saveReview->isSuccess()) {
            try {
                $this->order
                    ->set('rev', $saveReview->getId())
                    ->save();

                Messages::reviewAddSuccess([
                    'user' => $this->arResult['customer'],
                    'chatId' => $this->arResult['chat'],
                    'urlExpert' => $userUrl,
                    'expertName' => $userName,
                ]);

                Messages::reviewAddAnswer([
                    'user' => $this->arResult['answer'],
                    'url' => "/lk/order/{$this->arResult['code']}/",
                    'orderId' => $this->arResult['order']
                ]);
                
                PullEvent::add([$this->arResult['customer'], $this->arResult['answer']], [
                    'module_id' => 'sale',
                    'command' => 'langOrderUpdate',
                    'params' => [
                        'id' => $this->arResult['order'],
                        'serviceId' => $this->arResult['id'],
                        'updateField' => 'all',
                    ]
                ]);
                $this->arResult['addSuccess'] = 'y';
            } catch(Exception $e) {
                $saveReview->delete();
            }
        } else {
            $this->arResult['error']['ordersave'] = 'Не удалось сохранить отзыв. Попробуйте еще раз';
        }
    }

    public function executeComponent()
    {
        $this->getParser();
        $this->getSanitizer();
        $this->getData();

        if(empty($this->arResult['error']) || array_key_exists("textlength", $this->arResult['error'])) {
            $this->getOrder();

            if($this->isAdd() && empty($this->arResult['error'])) {
                $this->addReview();
            }
        }

        $this->includeComponentTemplate();
    }

}