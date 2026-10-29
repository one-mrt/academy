export default {
	input: 'src/index.ts',
	output: 'dist/entity-selector.bundle.js',
	namespace: 'LG.EntitySelector',
	adjustConfigPhp: false,
	browserslist: true,
	transformClasses: [
		'Dialog',
		'TagSelector',
		'BaseHeader',
		'DefaultHeader',
		'BaseFooter',
		'DefaultFooter',
		'BaseStub',
		'DefaultStub',
	],
};
