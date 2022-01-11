const subscription = {
	ajaxurl: alg_dtwp.ajaxurl,
	postID: alg_dtwp.postID,
	security: alg_dtwp.subscription_nonce,
	init: function () {
		jQuery('body').on('change', '#dtwp_subscribe_via_email', function (e) {
			var data = {
				action: 'alg_dtwp_toggle_subscription',
				security: subscription.security,
				post_id: subscription.postID,
				subscribed: this.checked
			};
			jQuery.post(subscription.ajaxurl, data, function (response) {

			});
		});
	}
};
module.exports = subscription;