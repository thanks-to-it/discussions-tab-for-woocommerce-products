const tabOpener = {
	init: function () {
		jQuery('document').ready(function() {
			tabOpener.activateDiscussionsTab();
		});
		jQuery('body').on('alg_dtwp_comments_loaded', function () {
			tabOpener.activateDiscussionsTab();
		});
		window.addEventListener('hashchange', tabOpener.activateDiscussionsTab);
	},
	activateDiscussionsTab: function () {
		let hash = window.location.hash;
		if ( hash.toLowerCase().indexOf( alg_dtwp.commentLink + '-' ) >= 0 || hash === '#' + alg_dtwp.tabID || hash === '#tab-' + alg_dtwp.tabID ) {
			let alg_dtwp_tab = alg_dtwp.tabID;
			let discussionsTabA = jQuery('#tab-title-' + alg_dtwp_tab + ' a');
			if (discussionsTabA.length) {
				discussionsTabA.trigger('click');
			}
		}
	},
}
module.exports = tabOpener;