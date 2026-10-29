<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	'NAME' => 'Публичный чат',
	'DESCRIPTION' => 'Простейший публичный чат',
	'CACHE_PATH' => 'Y',
	'PATH' => array(
		'ID' => 'communication',
		'CHILD' => array(
			'ID' => 'im',
			'NAME' => 'Чат'
		)
	),
);