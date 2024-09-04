(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_parent-comment-id-fixer_js"],{

/***/ "./src/js/modules/parent-comment-id-fixer.js":
/*!***************************************************!*\
  !*** ./src/js/modules/parent-comment-id-fixer.js ***!
  \***************************************************/
/***/ ((module) => {

var parentCommentIDFixer = {
  init: function init() {
    jQuery(document).ready(function ($) {
      var respond_wrapper = null;
      $(document).on('click', '.comment-reply-link', function (e) {
        respond_wrapper = $('#' + alg_dtwp.respondID);
        if (!respond_wrapper.length) {
          e.preventDefault();
          return;
        }
        var comment_id = $(this).data('commentid');
        respond_wrapper.find("#comment_parent").val(comment_id);
      });
    });
  }
};
module.exports = parentCommentIDFixer;

/***/ })

}]);
//# sourceMappingURL=src_js_modules_parent-comment-id-fixer_js.js.map