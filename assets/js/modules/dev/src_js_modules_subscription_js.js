(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_subscription_js"],{

/***/ "./src/js/modules/subscription.js":
/*!****************************************!*\
  !*** ./src/js/modules/subscription.js ***!
  \****************************************/
/***/ ((module) => {

var subscription = {
  ajaxurl: alg_dtwp.ajaxurl,
  postID: alg_dtwp.postID,
  security: alg_dtwp.subscription_nonce,
  init: function init() {
    jQuery('body').on('change', '#dtwp_subscribe_via_email', function (e) {
      var data = {
        action: 'alg_dtwp_toggle_subscription',
        security: subscription.security,
        post_id: subscription.postID,
        subscribed: this.checked
      };
      jQuery.post(subscription.ajaxurl, data, function (response) {});
    });
  }
};
module.exports = subscription;

/***/ })

}]);
//# sourceMappingURL=src_js_modules_subscription_js.js.map