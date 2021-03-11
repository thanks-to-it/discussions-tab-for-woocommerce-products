(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_iconpicker-manager_js"],{

/***/ "./src/js/modules/iconpicker-manager.js":
/*!**********************************************!*\
  !*** ./src/js/modules/iconpicker-manager.js ***!
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
      jQuery('<span style="cursor:pointer;position:relative;top:2px;margin:0px 7px 0 10px;vertical-align: middle" class="input-group-addon"></span>').insertAfter(input);
    },
    callIconPicker: function callIconPicker(element) {
      element.iconpicker({
        selectedCustomClass: 'alg-dtwp-iconpicker-selected',
        hideOnSelect: true,
        placement: 'bottom',
        templates: {
          iconpickerItem: '<a role="button" href="javascript://" class="iconpicker-item"><i></i></a>'
        }
      });
    }
  };
  alg_dtwp_admin_iconpicker.init();
});

/***/ })

}]);
//# sourceMappingURL=src_js_modules_iconpicker-manager_js.js.map