const cancelBtnFixer = {
	init:function(){
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