/**
 * Discussions Tab for WooCommerce Products - Frontend JS
 *
 * @version 1.2.8
 * @since   1.2.6
 * @author  WPFactory
 */
// Dynamic modules
__webpack_public_path__ = alg_dtwp.plugin_url + "/assets/";
let modules = alg_dtwp.modulesToLoad;
if (modules && modules.length) {
	modules.forEach(function (module) {
		import(
			/* webpackMode: "lazy"*/
			`./modules/${module}`)
			.then(function (component) {
				component.init();
			});
	});
}

// Static modules
const staticModules = [
	'cancel-btn-fixer',
	'parent-comment-id-fixer',
	'scroller'
];
staticModules.forEach(function (module_name) {
	import(
		/* webpackMode: "lazy"*/
		`./modules/${module_name}`)
		.then(function (component) {
			component.init();
		});
});