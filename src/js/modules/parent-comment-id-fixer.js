const parentCommentIDFixer = {
	init: function () {
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
}
module.exports = parentCommentIDFixer;