<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'js' => [
		'./dist/core.bundle.js',
	],
	'rel' => [
		'main.polyfill.core',
		'langmess.application.launch',
		'im.controller',
	],
	'skip_core' => true,
];