(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_iconpicker_manager_js"],{

/***/ "./src/js/modules/iconpicker_manager.js":
/*!**********************************************!*\
  !*** ./src/js/modules/iconpicker_manager.js ***!
  \**********************************************/
/***/ (() => {

/**
 * Discussions Tab for WooCommerce Products - Iconpicker manager
 *
 * @author  Thanks to IT
 */
jQuery(function ($) {
  var alg_dtwp_admin_iconpicker = {
    init: function init() {
      var icon_input = $('.alg-dtwp-icon-picker');
      this.createIconNextToInput(icon_input);

      if (icon_input.length) {
        this.callIconPicker(icon_input);
      }
    },
    createIconNextToInput: function createIconNextToInput(input) {
      jQuery('<span style="margin:0px 7px 0 10px" class="input-group-addon"></span>').insertAfter(input);
    },
    callIconPicker: function callIconPicker(element) {
      element.iconpicker({
        selectedCustomClass: 'alg-dtwp-iconpicker-selected',
        hideOnSelect: true,
        placement: 'bottom'
      });
    }
  };
  alg_dtwp_admin_iconpicker.init();
});

/***/ })

}]);
//# sourceMappingURL=src_js_modules_iconpicker_manager_js.js.map