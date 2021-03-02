const alg_dtwp_labels = {
	labels: alg_dtwp.possibleCommentTags,
	tips: alg_dtwp.tips,
	icons: alg_dtwp.icons,
	possible_wrappers: ['.comment-text', '.comment-body'],
	init: function () {
		this.add_label();
	},
	add_label: function () {
		alg_dtwp_labels.labels.forEach(function (label) {
			alg_dtwp_labels.possible_wrappers.some(function (wrapper) {
				var the_wrapper = jQuery('.' + label).find(wrapper + ":first");
				if (the_wrapper.length) {
					the_wrapper.each(function () {
						var labels = jQuery(this).find('.alg-dtwp-labels');
						if (!labels.length) {
							jQuery(this).append('<div class="alg-dtwp-labels"></div>');
							labels = jQuery(this).find('.alg-dtwp-labels');
						}
						labels.append('<div class="alg-dtwp-label ' + label + '-label"></div>');
						var this_label = labels.find('.alg-dtwp-label.' + label + '-label');
						if (alg_dtwp_labels.icons[label]) {
							this_label.append('<i class="alg-dtwp-fa ' + alg_dtwp_labels.icons[label] + '" aria-hidden="true"></i>');
						}
						if (alg_dtwp_labels.tips[label]) {
							this_label.addClass('has-tip');
							this_label.append('<div class="alg-dtwp-tip">' + alg_dtwp_labels.tips[label] + '</div>');
						}
					});
					return true;
				}
			});
		});
	}
};
const labelsManager = {
	init: function () {
		jQuery(document).ready(function ($) {
			alg_dtwp_labels.init();
			$('body').on('alg_dtwp_comments_loaded', function () {
				alg_dtwp_labels.init();
			});
		});
	}
}
module.exports = labelsManager;




