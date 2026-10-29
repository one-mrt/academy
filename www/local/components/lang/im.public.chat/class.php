<?php

class PublicChatComponent extends \CBitrixComponent
{
	public function executeComponent()
	{
		if (!\Bitrix\Main\Loader::includeModule('im')) {
			return false;
		}

		$this->arResult['items'] = array();

		//если чат есть, соберем его сообщения, последние
		$chatId = \Bitrix\Main\Config\Option::get('main', 'my_public_chat');
		if ($chatId) {
			$res = \Bitrix\Im\Model\MessageTable::getList(array(
															'select' => array('*', 'AUTHOR_LOGIN' => 'AUTHOR.LOGIN'),
															'filter' => array('CHAT_ID' => $chatId, '>AUTHOR_ID' => 0),
															'limit' => 5,
															'order' => array('DATE_CREATE' => 'DESC')));
			while ($row = $res->fetch()) {
				$this->arResult['items'][] = $row;
			}
			$this->arResult['items'] = array_reverse($this->arResult['items']);
		}


		$this->includeComponentTemplate();
	}
}
