const scroller = {
	init: function () {
		jQuery('body').on('alg_dtwp_comments_loaded', function () {
			setTimeout(scroller.scrollByAnchor, 150);
		});
		jQuery('document').ready(function(){
			setTimeout(scroller.scrollByAnchor, 150);
		});
		window.onhashchange = function () {
			scroller.scrollByAnchor();
		};
		scroller.activateDiscussionsTab();
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
	scrollByAnchor: function () {
		let currentURL = window.location.href;
		let target = jQuery('a[href*="' + currentURL + '"]');
		if (window.location.hash.length && target.length) {
			const element = target.closest('.comment')[0];
			const offset = 145;
			var comments = document.querySelectorAll('.comment.' + alg_dtwp.commentTypeID);
			comments.forEach(function (item) {
				item.classList.remove('alg-dtwp-anchor-source');
			});
			const topPos = element.getBoundingClientRect().top + window.pageYOffset - offset;
			window.scrollTo({
				top: topPos,
				behavior: 'smooth'
			});
			setTimeout(function () {
				element.classList.add('alg-dtwp-anchor-source');
			}, 500);
		}
	}
}
module.exports = scroller;