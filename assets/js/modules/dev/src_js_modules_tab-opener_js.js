(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_tab-opener_js"],{

/***/ "./src/js/modules/tab-opener.js":
/*!**************************************!*\
  !*** ./src/js/modules/tab-opener.js ***!
  \**************************************/
/***/ ((module) => {

var tabOpener = {
  init: function init() {
    jQuery('document').ready(function () {
      tabOpener.activateDiscussionsTab();
    });
    jQuery('body').on('alg_dtwp_comments_loaded', function () {
      tabOpener.activateDiscussionsTab();
    });
    window.addEventListener('hashchange', tabOpener.activateDiscussionsTab);
  },
  activateDiscussionsTab: function activateDiscussionsTab() {
    var hash = window.location.hash;
    if (hash.toLowerCase().indexOf(alg_dtwp.commentLink + '-') >= 0 || hash === '#' + alg_dtwp.tabID || hash === '#tab-' + alg_dtwp.tabID) {
      var alg_dtwp_tab = alg_dtwp.tabID;
      var discussionsTabA = jQuery('#tab-title-' + alg_dtwp_tab + ' a');
      if (discussionsTabA.length) {
        discussionsTabA.trigger('click');
      }
    }
  }
};
module.exports = tabOpener;

/***/ })

}]);
//# sourceMappingURL=src_js_modules_tab-opener_js.js.map