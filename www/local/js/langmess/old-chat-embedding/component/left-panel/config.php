<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'js' => 'dist/left-panel.bundle.js',
	'rel' => [
		'main.polyfill.core',
		// 'langmess.old-chat-embedding.component.recent-list',
		'langmess.component.recent',
		'langmess.old-chat-embedding.component.search',
		'im.old-chat-embedding.const',
		'im.const',
		'im.lib.utils',
		'main.core.events',
		'ui.design-tokens',
		'ui.vue',
		'ui.vue.vuex',
	],
	'skip_core' => true,
];