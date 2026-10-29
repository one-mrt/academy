<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

return [
	'js' => 'dist/sidebar.js',
	'rel' => [
		'main.polyfill.core',
		'langmess.component.recent',
		'langmess.old-chat-embedding.component.search',
		'im.old-chat-embedding.const',
		'main.core.events',
	],
	'skip_core' => true,
];