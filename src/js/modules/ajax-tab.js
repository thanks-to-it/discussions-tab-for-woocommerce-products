let tabContentLoader = {
	tabID: alg_dtwp.tabID,
	tab: null,
	contentCalled: false,
	ajaxurl: alg_dtwp.ajaxurl,
	postID: alg_dtwp.postID,
	check: function () {
		let tab = jQuery('#tab-title-' + tabContentLoader.tabID);
		tabContentLoader.tab = tab;
		if (tab.length && jQuery('#tab-title-' + tabContentLoader.tabID).hasClass('active')) {
			tab.addClass('alg-dtwp-loading-tab');
			tabContentLoader.contentCalled = true;
			tabContentLoader.loadTabContent();
		}
	},
	loadTabContent: function () {
		var data = {
			action: 'alg_dtwp_get_tab_content',
			post_id: tabContentLoader.postID
		};
		jQuery.post(tabContentLoader.ajaxurl, data, function (response) {
			if (response.success) {
				jQuery("#tab-" + tabContentLoader.tabID).html(response.data.content);
			}
			tabContentLoader.tab.addClass('alg-dtwp-loaded');
			setTimeout(function () {
				tabContentLoader.tab.removeClass('alg-dtwp-loaded');
				tabContentLoader.tab.removeClass('alg-dtwp-loading-tab');
				jQuery("body").trigger({type: "alg_dtwp_comments_loaded"});
			}, 150);
		});
	}
};
let scroller = {
	scrollByAnchor: function () {
		let currentURL = window.location.href;
		var target = jQuery('a[href*="' + currentURL + '"]');
		if (window.location.hash.length && target.length) {
			const element = target.closest('li')[0];
			const offset = 130;
			const topPos = element.getBoundingClientRect().top + window.pageYOffset - offset;
			window.scrollTo({
				top: topPos,
				behavior: 'smooth'
			});
		}
	}
}
const ajaxTab = {
	init:function(){
		jQuery('body').on('click', '.wc-tabs li a, ul.tabs li a', function (e) {
			let time = null;
			if (time) {
				clearTimeout(time);
			}
			time = setTimeout(function () {
				if (!tabContentLoader.contentCalled) {
					tabContentLoader.check();
				}
			}, 150);
		});
		jQuery('body').on('alg_dtwp_comments_loaded',function(){
			scroller.scrollByAnchor();
		});
	}
};

module.exports = ajaxTab;