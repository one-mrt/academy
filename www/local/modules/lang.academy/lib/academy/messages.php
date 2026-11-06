<?
namespace Lang\Academy;

// use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
// use Bitrix\Main\UserTable;
// use Bitrix\Sale\PaySystem;
// use Bitrix\Catalog\Model\Price;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
// use Bitrix\Main\Engine\CurrentUser;
// use Bitrix\Main\Engine\Response\Redirect;
// use Bitrix\Sale\Internals\StatusLangTable;
// use Bitrix\Pull\Event as PullEvent;

use Bitrix\Main\SiteTable;

Loc::loadMessages(__FILE__);

Loader::includeModule('im');

class Messages {

    private static function getSite(): string
    {
        $serverName = "";
        $site = SiteTable::getList([
            'select' => ['SERVER_NAME'],
            'filter' => ['LID' => SITE_ID],
            'cache' => Data::getCache()
        ])->fetchObject();

        if(!empty($site)) {
            $serverName = "https://{$site->getServerName()}";
        }

        return $serverName;
    }

    /**
     * Публикация статуса в чат заказа
     * 
     * @param $param = [
     *      'user' => id пользователя
     *      'chatId' => id чата
     *      'orderId' => id заказа
     *      'urlOrder' => ссылка на публичный заказ
     * ]
     */
    public static function statusPublished(array $param)
    {
        $serverName = self::getSite();
        $message = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "Заказ [url={$serverName}{$param['urlOrder']}]№{$param['orderId']}[/url] опубликован! Теперь его могут видеть все пользователи."
        ];

        return \CIMChat::AddSystemMessage($message);
    }

    public static function statusDefault(array $param)
    {
        $serverName = self::getSite();
        $message = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "[url={$serverName}{$param['urlExpert']}]{$param['expertName']}[/url] изменил(а) статус заказа на [b]«{$param['status']}»[/b]"
        ];

        return \CIMChat::AddSystemMessage($message);
    }

    public static function statusDispute(array $param)
    {
        $serverName = self::getSite();
        $message = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "[url={$serverName}{$param['urlExpert']}]{$param['expertName']}[/url] инициировал(а) [b]«{$param['status']}»[/b]"
        ];

        return \CIMChat::AddSystemMessage($message);
    }

    public static function statusDefaultManager(array $param)
    {
        $param = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "Статус заказа изменился на [b]«{$param['status']}»[/b]"
        ];

        return \CIMChat::AddSystemMessage($param);
    }

    public static function addOrderCustomer(array $param)
    {
        $serverName = self::getSite();
        $param = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "[url={$serverName}{$param['urlExpert']}]{$param['expertName']}[/url] создал(а) заказ [url={$param['urlOrder']}]№{$param['orderId']}[/url]"
        ];

        return \CIMChat::AddSystemMessage($param);
    }

    public static function statusDraft(array $param)
    {
        $serverName = self::getSite();
        $param = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "Заказ [url={$serverName}{$param['urlOrder']}]№{$param['orderId']}[/url] находится в статусе [b]«Черновик»[/b] и не виден другим пользователям. В этом статусе вы можете его редактировать. Чтобы заказ стал доступен другим пользователям, измените статус на [b]«Открыт»[/b]."
        ];

        return \CIMChat::AddSystemMessage($param);
    }

    public static function statusChange(array $param)
    {
        $param = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "Статус заказа изменился на [b]«{$param['status']}»[/b]"
        ];

        return \CIMChat::AddSystemMessage($param);
    }

    public static function payOrder(array $param)
    {
        $param = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "[color=#008000][b]Заказ оплачен![/b][/color]"
        ];

        return \CIMChat::AddSystemMessage($param);
    }

    public static function editPriceOrder(array $param)
    {
        $param = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "Цена заказа изменилась, текущая цена [b]{$param['sum']} ₽[/b]"
        ];

        return \CIMChat::AddSystemMessage($param);
    }

    public static function editFileOrder(array $param)
    {
        $serverName = self::getSite();
        $param = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "[url={$serverName}{$param['url']}]{$param['userName']}[/url] загрузил [b]«Файлы»[/b]"
        ];

        return \CIMChat::AddSystemMessage($param);
    }

    public static function editFileResultOrder(array $param)
    {
        $serverName = self::getSite();
        $param = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "[url={$serverName}{$param['url']}]{$param['userName']}[/url] загрузил [b]«Результаты»[/b]"
        ];

        return \CIMChat::AddSystemMessage($param);
    }

    public static function selectedUserAnswerOrder(array $param)
    {
        $serverName = self::getSite();
        $param = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "[url={$serverName}{$param['url']}]{$param['userName']}[/url] выбран(а) исполнителем"
        ];

        return \CIMChat::AddSystemMessage($param);
    }

    public static function statusNewPrivateOrder(array $param)
    {
        $serverName = self::getSite();
        $param = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "Вы сделали личный заказ [url={$serverName}{$param['urlOrder']}]№{$param['orderId']}[/url]. Предложение отправлено [url={$serverName}{$param['urlExpert']}]{$param['userName']}[/url]."
        ];

        return \CIMChat::AddSystemMessage($param);
    }
    
    public static function cancelPrivateChatOrder(array $param)
    {
        $serverName = self::getSite();
        $param = [
            "USER_ID" => $param['fromUser'],
            'CHAT_ID' => $param['chatId'],
            "MESSAGE" => "[user={$param['toUser']}]{$param['userName']}[/user] отказался брать заказ [url={$serverName}{$param['url']}]№{$param['orderId']}[/url]. Вы можете опубликовать заказ или отменить его.",
        ];

        return \CIMChat::AddSystemMessage($param);
    }

    public static function acceptOfferOrder(array $param)
    {
        $serverName = self::getSite();
        $param = [
            "TO_USER_ID" => $param['toUser'], // Кому
            "FROM_USER_ID" => $param['fromUser'], // Кто
            "NOTIFY_TYPE" => IM_NOTIFY_FROM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|accept|{$param['orderId']}|{$param['fromUser']}|{$param['toUser']}",
            "NOTIFY_MESSAGE" => "По заказу [url={$serverName}{$param['url']}]№{$param['orderId']}[/url] ваше предложение принято. Заказ перемещен в раздел «Мои заказы» личного кабинета.",
        ];

        return \CIMNotify::Add($param);
    }

    public static function selectedOfferOrder(array $param)
    {
        $serverName = self::getSite();
        $param = [
            "TO_USER_ID" => $param['fromUser'],
            "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|system|offer|selected|{$param['orderId']}",
            "NOTIFY_MESSAGE" => "Исполнитель по заказу [url={$serverName}{$param['url']}]№{$param['orderId']}[/url] выбран",
        ];

        return \CIMNotify::Add($param);
    }

    public static function warningSelectedOfferUser(array $param)
    {
        $serverName = self::getSite();
        $param = [
            "TO_USER_ID" => $param['fromUser'],
            "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|system|offer|noselected|{$param['orderId']}",
            "NOTIFY_MESSAGE" => "[b]Внимание:[/b] Исполнитель по заказу [url={$serverName}{$param['url']}]№{$param['orderId']}[/url] уже выбран",
        ];

        return \CIMNotify::Add($param);
    }

    public static function confirmAnswerOrder(array $param)
    {
        $serverName = self::getSite();
        $message = [
            "TO_USER_ID" => $param['toUser'], // Кому
            "FROM_USER_ID" => $param['fromUser'], // Кто
            "NOTIFY_TYPE" => IM_NOTIFY_CONFIRM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|confirm|{$param['orderId']}|{$param['fromUser']}|{$param['toUser']}|{$param['offerId']}|{$param['serviceId']}",
            "NOTIFY_MESSAGE" => "Вы подтверждаете, что готовы выполнить заказ [url={$serverName}{$param['url']}]№{$param['orderId']}[/url]",
            // "NOTIFY_MESSAGE_OUT" => "",
            "NOTIFY_BUTTONS" => [
                ['TITLE' => 'Подтверждаю', 'VALUE' => 'Y', 'TYPE' => 'accept'],
                ['TITLE' => 'Отказываюсь', 'VALUE' => 'N', 'TYPE' => 'cancel']
            ]
        ];

        return \CIMNotify::Add($message);
    }

    /**
     * Отказ исполнителя брать заказа
     */
    public static function cancelConfirmAnswer(array $param)
    {
        $serverName = self::getSite();
        $message = [
            "TO_USER_ID" => $param['fromUser'], // Кому
            "FROM_USER_ID" => $param['toUser'], // Кто
            "NOTIFY_TYPE" => IM_NOTIFY_FROM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|confirmcancel|{$param['orderId']}|{$param['fromUser']}|{$param['toUser']}",
            "NOTIFY_MESSAGE" => "[user={$param['toUser']}]{$param['userName']}[/user] отказался брать заказ [url={$serverName}{$param['url']}]№{$param['orderId']}[/url]",
            // "NOTIFY_MESSAGE_OUT" => "",
        ];
        
        return \CIMNotify::Add($message);
    }

    /**
     * Публикация в чат отказ исполнителя брать заказа
     */
    public static function cancelConfirmChatOrder(array $param)
    {
        $serverName = self::getSite();
        $message = [
            "USER_ID" => $param['fromUser'],
            'CHAT_ID' => $param['chatId'],
            "MESSAGE" => "[user={$param['toUser']}]{$param['userName']}[/user] отказался брать заказ [url={$serverName}{$param['url']}]№{$param['orderId']}[/url]",
        ];

        return \CIMChat::AddSystemMessage($message);
    }

    public static function cancelAnswerOrder(array $param)
    {
        $serverName = self::getSite();
        $message = [
            "TO_USER_ID" => $param['toUser'],  // Кому
            "FROM_USER_ID" => $param['fromUser'],  // Кто
            "NOTIFY_TYPE" => IM_NOTIFY_FROM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|cancel|{$param['orderId']}|{$param['fromUser']}|{$param['toUser']}|{$param['offerId']}|{$param['serviceId']}",
            "NOTIFY_MESSAGE" => "По заказу [url={$serverName}{$param['url']}]№{$param['orderId']}[/url] предложение отклонено. Вы можете сделать новое",
            // "NOTIFY_MESSAGE_OUT" => "",
        ];

        return \CIMNotify::Add($message);
    }

    public static function cancelAnswerOrderAll(array $param)
    {
        $serverName = self::getSite();
        $message = [
            "TO_USER_ID" => $param['toUser'],  // Кому
            "FROM_USER_ID" => $param['fromUser'],  // Кто
            "NOTIFY_TYPE" => IM_NOTIFY_FROM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|cancel|{$param['orderId']}|{$param['fromUser']}|{$param['toUser']}|{$param['offerId']}|{$param['serviceId']}",
            "NOTIFY_MESSAGE" => "По заказу [url={$serverName}{$param['url']}]№{$param['orderId']}[/url] предложение отклонено. Выбран другой исполнитель.",
            // "NOTIFY_MESSAGE_OUT" => "",
        ];

        return \CIMNotify::Add($message);
    }

    public static function cancelPrivateAnswerOrder(array $param)
    {
        $serverName = self::getSite();
        $message = [
            "TO_USER_ID" => $param['fromUser'], // Кому
            "FROM_USER_ID" => $param['toUser'], // Кто
            "NOTIFY_TYPE" => IM_NOTIFY_FROM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|privatecancel|{$param['orderId']}|{$param['fromUser']}|{$param['toUser']}",
            "NOTIFY_MESSAGE" => "[user={$param['toUser']}]{$param['userName']}[/user] отказался брать заказ [url={$serverName}{$param['url']}]№{$param['orderId']}[/url]",
            // "NOTIFY_MESSAGE_OUT" => "",
        ];

        return \CIMNotify::Add($message);
    }

    public static function addNewPrivateAnswerOrder(array $param)
    {
        $serverName = self::getSite();
        $message = [
            "TO_USER_ID" => $param['toUser'], // Кому
            "FROM_USER_ID" => $param['fromUser'], // Кто
            "NOTIFY_TYPE" => IM_NOTIFY_CONFIRM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|private|{$param['orderId']}|{$param['fromUser']}|{$param['toUser']}|0|{$param['productId']}",
            "NOTIFY_MESSAGE" => "Получен личный заказ [url={$serverName}{$param['url']}]№{$param['orderId']}[/url]. Вы можете ознакомьтся и сделать предложение или отказаться.",
            "NOTIFY_BUTTONS" => [
                [
                    'TITLE' => 'Подробнее',
                    'VALUE' => 'link',
                    'TYPE' => 'accept',
                    'LINK' => $param['url']
                ],
                ['TITLE' => 'Отказаться', 'VALUE' => 'N', 'TYPE' => 'cancel']
            ]
        ];

        return \CIMNotify::Add($message);
    }

    public static function cancelWithdrawAnswer(array $param)
    {
        $serverName = self::getSite();
        $message = [
            "TO_USER_ID" => $param['toUser'],
            "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|system|offer|cancel|{$param['orderId']}",
            "NOTIFY_MESSAGE" => "Предложение по заказу [url={$serverName}{$param['url']}]№{$param['orderId']}[/url] отозвано",
        ];

        return \CIMNotify::Add($message);
    }

    public static function foundsCreditedAccountAnswer(array $param)
    {
        $serverName = self::getSite();
        $message = [
            "TO_USER_ID" => $param['user'],
            "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|system|credit|account|{$param['orderId']}|{$param['sum']}",
            "NOTIFY_MESSAGE" => "[color=#008000][b]Послупление средств на счет:[/b][/color] На счет поступило [b]{$param['sum']} ₽[/b] за выполненный заказ [url={$serverName}{$param['url']}]№{$param['orderId']}[/url].",
        ];

        return \CIMNotify::Add($message);
    }

    public static function reviewAddSuccess(array $param)
    {
        $serverName = self::getSite();
        $message = [
            'USER_ID' => $param['user'],
            'CHAT_ID' => $param['chatId'],
            'MESSAGE' => "[url={$serverName}{$param['urlExpert']}]{$param['expertName']}[/url], спасибо за ваш отзыв!"
        ];

        return \CIMChat::AddSystemMessage($message);
    }

    // Уведомить исполнителя что ему оставлен отзыв
    public static function reviewAddAnswer(array $param)
    {
        $serverName = self::getSite();
        $message = [
            "TO_USER_ID" => $param['user'],
            "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|system|review|{$param['orderId']}",
            "NOTIFY_MESSAGE" => "Вам оставили отзыв по заказу [url={$serverName}{$param['url']}]№{$param['orderId']}[/url]",
        ];

        return \CIMNotify::Add($message);
    }

    public static function statusDefaultNotifyManager(array $param)
    {
        $serverName = self::getSite();
        $param = [
            "TO_USER_ID" => $param['toUser'],
            "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|system|status|{$param['orderId']}|{$param['status']}",
            'NOTIFY_MESSAGE' => "Статус заказа [url={$serverName}{$param['orderUrl']}]№{$param['orderId']}[/url] изменился на [b]«{$param['status']}»[/b]"
        ];

        return \CIMNotify::Add($param);
    }

    public static function statusPayNotifyCustomer(array $param)
    {
        $serverName = self::getSite();
        $param = [
            "TO_USER_ID" => $param['toUser'],
            "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => "order|system|status|pay|{$param['orderId']}|{$param['status']}",
            'NOTIFY_MESSAGE' => "Статус заказа [url={$serverName}{$param['orderUrl']}]№{$param['orderId']}[/url] изменился на [color=#c90536][b]«{$param['status']}»[/b][/color]. Пора оплатить заказ!"
        ];

        return \CIMNotify::Add($param);
    }

    public static function addNewOfferOrder(array $param)
    {
        $serverName = self::getSite();
        $link = "{$serverName}{$param['orderUrl']}";

        $message = [
            "TO_USER_ID" => $param['toUser'], // Кому
            "FROM_USER_ID" => $param['fromUser'], // Кто
            "NOTIFY_TYPE" => IM_NOTIFY_CONFIRM,
            "NOTIFY_MODULE" => "im",
            "NOTIFY_TAG" => $param['tag'],
            "NOTIFY_MESSAGE" => "По вашему заказу [url={$link}]№{$param['orderId']}[/url] поступило предложение на сумму [b]{$param['sum']}[/b]",
            "NOTIFY_BUTTONS" => [
                ['TITLE' => 'Принять', 'VALUE' => 'Y', 'TYPE' => 'accept'],
                ['TITLE' => 'Отказаться', 'VALUE' => 'N', 'TYPE' => 'cancel']
            ]
        ];

        return \CIMNotify::Add($message);
    }

    // public static function reviewAddCustomer(array $param)
    // {
    //     \CIMNotify::Add([
    //         "TO_USER_ID" => $param['user'],
    //         "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
    //         "NOTIFY_MODULE" => "im",
    //         "NOTIFY_TAG" => "IM_CONFIG_NOTICE",
    //         "NOTIFY_MESSAGE" => "Пожалуйста оставьте отзыв",
    //         //"[color=#008000][b]Послупление средств на счет:[/b][/color] На счет поступило [b]{$param['sum']} ₽[/b] за выполненный заказ [url={$param['url']}]№{$param['orderId']}[/url].",
    //     ]);
    // }
}