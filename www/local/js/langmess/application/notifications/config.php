<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/notifications.bundle.css',
	'js' => 'dist/notifications.bundle.js',
	'rel' => [
		'main.polyfill.core',
		'langmess.application.core',
		'langmess.component.notifications',
		'im.provider.pull',
		'ui.vue',
	],
	'skip_core' => true,
];