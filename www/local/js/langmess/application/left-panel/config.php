<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/left-panel.bundle.css',
	'js' => 'dist/left-panel.bundle.js',
	'rel' => [
		'main.polyfill.core',
		'langmess.old-chat-embedding.application.core',
		'langmess.old-chat-embedding.component.left-panel',
		'im.const',
		'im.lib.utils',
		'main.core.events',
		'ui.design-tokens',
		'ui.vue',
		'ui.vue.vuex',
	],
	'skip_core' => true,
];