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

        var comment_id = $(this).parent().parent().attr('id');
        var comment_id_arr = comment_id.split("-");
        var parent_post_id = comment_id_arr[comment_id_arr.length - 1];
        respond_wrapper.find("#comment_parent").val(parent_post_id);
      });
    });
  }
};
module.exports = parentCommentIDFixer;

/***/ })

}]);
//# sourceMappingURL=src_js_modules_parent-comment-id-fixer_js.js.map