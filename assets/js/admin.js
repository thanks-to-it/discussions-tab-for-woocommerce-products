/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/modules/iconpicker-manager.js":
/*!**********************************************!*\
  !*** ./src/js/modules/iconpicker-manager.js ***!
  \**********************************************/
/***/ (() => {

/**
 * Discussions Tab for WooCommerce Products - Iconpicker manager
 *
 * @author  WPFactory
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

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other entry modules.
(() => {
/*!*************************!*\
  !*** ./src/js/admin.js ***!
  \*************************/
/**
 * Discussions Tab for WooCommerce Products - Admin JS
 *
 * @version 1.2.6
 * @since   1.2.6
 * @author  WPFactory
 */
var iconpicker = __webpack_require__(/*! ./modules/iconpicker-manager */ "./src/js/modules/iconpicker-manager.js");
})();

// This entry needs to be wrapped in an IIFE because it needs to be isolated against other entry modules.
(() => {
/*!*****************************!*\
  !*** ./src/scss/admin.scss ***!
  \*****************************/
// extracted by mini-css-extract-plugin
})();

/******/ })()
;
//# sourceMappingURL=admin.js.map