(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_cancel-btn-fixer_js"],{

/***/ "./src/js/modules/cancel-btn-fixer.js":
/*!********************************************!*\
  !*** ./src/js/modules/cancel-btn-fixer.js ***!
  \********************************************/
/***/ ((module) => {

var cancelBtnFixer = {
  init: function init() {
    jQuery(document).ready(function ($) {
      var cancel_btn = null;
      var respond_wrapper = null;
      $(document).on('click', '.comment-reply-link', function (e) {
        respond_wrapper = $('#' + alg_dtwp.respondID);
        cancel_btn = respond_wrapper.find("#cancel-comment-reply-link");
        cancel_btn.show();
        $(document).off('click', '#cancel-comment-reply-link', hide);
        $(document).on('click', '#cancel-comment-reply-link', hide);
      });

      function hide(e) {
        e.preventDefault();
        cancel_btn.hide();
        respond_wrapper.find("#comment_parent").val(0);
        respond_wrapper.remove().insertAfter($('#' + alg_dtwp.respondIDLocation));
      }
    });
  }
};
module.exports = cancelBtnFixer;

/***/ })

}]);
//# sourceMappingURL=src_js_modules_cancel-btn-fixer_js.js.map