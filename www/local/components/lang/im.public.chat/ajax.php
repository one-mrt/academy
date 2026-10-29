<?php
/*
 * Отправка сообщения в публичный чат.
 */
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Im\Chat;
use Bitrix\Main\Loader;

Loader::includeModule('im');

$context = \Bitrix\Main\Application::getInstance()->getContext();
$request = $context->getRequest();

if ($request->get('message') && \Bitrix\Main\Loader::includeModule('im')) {
	$chat = new \CIMChat(0);//ноль не случаен
	//если самого чата нет, создаем его
	$chatId = \Bitrix\Main\Config\Option::get('main', 'my_public_chat');
	if (!$chatId) {
		$chatId = $chat->add(array(
			'USERS' => array($GLOBALS['USER']->getId()),
			'TITLE' => 'Публичный чат',
			'MESSAGE' => 'Чат открыт!',
			'TYPE' => Chat::TYPE_OPEN
		));
		\Bitrix\Main\Config\Option::set('main', 'my_public_chat', $chatId);
	}
	//добавляем юзера в чат
	$chat->AddUser($chatId, $GLOBALS['USER']->getId());
	//добавляем сообщение
	$chat->AddMessage(array(
		'FROM_USER_ID' => $GLOBALS['USER']->getId(),
		'TO_CHAT_ID' => $chatId,
		'MESSAGE' => $request->get('message'),
	));
}










