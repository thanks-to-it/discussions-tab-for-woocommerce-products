(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_ajax-tab_js"],{

/***/ "./src/js/modules/ajax-tab.js":
/*!************************************!*\
  !*** ./src/js/modules/ajax-tab.js ***!
  \************************************/
/***/ ((module) => {

var tabContentLoader = {
  tabID: alg_dtwp.tabID,
  tab: null,
  contentCalled: false,
  ajaxurl: alg_dtwp.ajaxurl,
  postID: alg_dtwp.postID,
  check: function check() {
    var tab = jQuery('#tab-title-' + tabContentLoader.tabID);
    tabContentLoader.tab = tab;
    if (tab.length && jQuery('#tab-title-' + tabContentLoader.tabID).attr('class').indexOf('active') !== -1) {
      tab.addClass('alg-dtwp-loading-tab');
      tabContentLoader.contentCalled = true;
      tabContentLoader.loadTabContent();
    }
  },
  loadTabContent: function loadTabContent() {
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
        jQuery("body").trigger({
          type: "alg_dtwp_comments_loaded"
        });
      }, 150);
    });
  }
};
var ajaxTab = {
  init: function init() {
    jQuery('body').on('click', '#tab-title-' + alg_dtwp.tabID, function (e) {
      var time = null;
      if (time) {
        clearTimeout(time);
      }
      time = setTimeout(function () {
        if (!tabContentLoader.contentCalled) {
          tabContentLoader.check();
        }
      }, 150);
    });
  }
};
module.exports = ajaxTab;

/***/ })

}]);
//# sourceMappingURL=src_js_modules_ajax-tab_js.js.map