(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_scroller_js"],{

/***/ "./src/js/modules/scroller.js":
/*!************************************!*\
  !*** ./src/js/modules/scroller.js ***!
  \************************************/
/***/ ((module) => {

var scroller = {
  init: function init() {
    jQuery('body').on('alg_dtwp_comments_loaded', function () {
      setTimeout(scroller.scrollByAnchor, 200);
    });
    jQuery('document').ready(function () {
      setTimeout(scroller.scrollByAnchor, 200);
    });

    window.onhashchange = function () {
      scroller.scrollByAnchor();
    };

    jQuery('document').ready(function () {
      scroller.activateDiscussionsTab();
    });
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
  },
  scrollByAnchor: function scrollByAnchor() {
    var currentURL = window.location.href;
    var target = jQuery('a[href*="' + currentURL + '"]');

    if (window.location.hash.length && target.length) {
      var element = target.closest('.comment')[0];
      var offset = 145;
      var comments = document.querySelectorAll('.comment.' + alg_dtwp.commentTypeID);
      comments.forEach(function (item) {
        item.classList.remove('alg-dtwp-anchor-source');
      });
      var topPos = element.getBoundingClientRect().top + window.pageYOffset - offset;
      window.scrollTo({
        top: topPos,
        behavior: 'smooth'
      });
      setTimeout(function () {
        element.classList.add('alg-dtwp-anchor-source');
      }, 500);
    }
  }
};
module.exports = scroller;

/***/ })

}]);
//# sourceMappingURL=src_js_modules_scroller_js.js.map