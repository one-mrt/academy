<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'js' => [
		'./dist/recent.bundle.js',
	],
	'css' => [
		'./dist/recent.bundle.css',
	],
	'rel' => [
		'main.polyfill.core',
		'langmess.application.core',
		'langmess.component.recent',
		'ui.vue',
	],
	'skip_core' => true,
];