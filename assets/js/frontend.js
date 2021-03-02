/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/modules/ajax-tab.js":
/*!************************************!*\
  !*** ./src/js/modules/ajax-tab.js ***!
  \************************************/
/***/ ((module) => {

var tabContentLoader = {
  tabID: alg_dtwp.tabID,
  tab: null,
  contentCalled: false,
  ajaxurl: alg_dtwp.ajaxurl,
  postID: alg_dtwp.postID,
  check: function check() {
    var tab = jQuery('#tab-title-' + tabContentLoader.tabID);
    tabContentLoader.tab = tab;

    if (tab.length && jQuery('#tab-title-' + tabContentLoader.tabID).hasClass('active')) {
      tab.addClass('alg-dtwp-loading-tab');
      tabContentLoader.contentCalled = true;
      tabContentLoader.loadTabContent();
    }
  },
  loadTabContent: function loadTabContent() {
    var data = {
      action: 'alg_dtwp_get_tab_content',
      post_id: tabContentLoader.postID
    };
    jQuery.post(tabContentLoader.ajaxurl, data, function (response) {
      if (response.success) {
        jQuery("#tab-" + tabContentLoader.tabID).html(response.data.content);
      }

      tabContentLoader.tab.addClass('alg-dtwp-loaded');
      setTimeout(function () {
        tabContentLoader.tab.removeClass('alg-dtwp-loaded');
        tabContentLoader.tab.removeClass('alg-dtwp-loading-tab');
        jQuery("body").trigger({
          type: "alg_dtwp_comments_loaded"
        });
      }, 150);
    });
  }
};
var scroller = {
  scrollByAnchor: function scrollByAnchor() {
    var currentURL = window.location.href;
    var target = jQuery('a[href*="' + currentURL + '"]');

    if (window.location.hash.length && target.length) {
      var element = target.closest('li')[0];
      var offset = 130;
      var topPos = element.getBoundingClientRect().top + window.pageYOffset - offset;
      window.scrollTo({
        top: topPos,
        behavior: 'smooth'
      });
    }
  }
};
var ajaxTab = {
  init: function init() {
    jQuery('body').on('click', '.wc-tabs li a, ul.tabs li a', function (e) {
      var time = null;

      if (time) {
        clearTimeout(time);
      }

      time = setTimeout(function () {
        if (!tabContentLoader.contentCalled) {
          tabContentLoader.check();
        }
      }, 150);
    });
    jQuery('body').on('alg_dtwp_comments_loaded', function () {
      scroller.scrollByAnchor();
    });
  }
};
module.exports = ajaxTab;

/***/ }),

/***/ "./src/js/modules/cancel-btn-fixer.js":
/*!********************************************!*\
  !*** ./src/js/modules/cancel-btn-fixer.js ***!
  \********************************************/
/***/ ((module) => {

var cancelBtnFixer = {
  init: function init() {
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

/***/ }),

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

/***/ }),

/***/ "./src/js/modules/labels-manager.js":
/*!******************************************!*\
  !*** ./src/js/modules/labels-manager.js ***!
  \******************************************/
/***/ ((module) => {

var alg_dtwp_labels = {
  labels: alg_dtwp.possibleCommentTags,
  tips: alg_dtwp.tips,
  icons: alg_dtwp.icons,
  possible_wrappers: ['.comment-text', '.comment-body'],
  init: function init() {
    this.add_label();
  },
  add_label: function add_label() {
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
var labelsManager = {
  init: function init() {
    jQuery(document).ready(function ($) {
      alg_dtwp_labels.init();
      $('body').on('alg_dtwp_comments_loaded', function () {
        alg_dtwp_labels.init();
      });
    });
  }
};
module.exports = labelsManager;

/***/ }),

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

/***/ }),

/***/ "./src/js/modules/wp-editor.js":
/*!*************************************!*\
  !*** ./src/js/modules/wp-editor.js ***!
  \*************************************/
/***/ ((module) => {

var WPEditor = {
  initTinyMCE: function initTinyMCE() {
    wp.editor.remove('discussion');
    wp.editor.initialize('discussion', {
      tinymce: true,
      teeny: true,
      quicktags: true
    });
  },
  init: function init() {
    jQuery('body').on('click', '.wc-tabs li a, ul.tabs li a', function (e) {
      setTimeout(WPEditor.initTinyMCE, 150);
    });
    jQuery(document).ready(function () {
      setTimeout(WPEditor.initTinyMCE, 150);
    });
    jQuery('body').on('alg_dtwp_comments_loaded', WPEditor.initTinyMCE);
    jQuery(document).on('click', '.comment-reply-link,#cancel-comment-reply-link', function (e) {
      setTimeout(WPEditor.initTinyMCE, 150);
    });
    jQuery(document).on('tinymce-editor-setup', WPEditor.setupEditor);
  },
  setupEditor: function setupEditor(event, editor) {
    if (editor.id !== 'discussion') return;
    var toolbarArr = editor.settings.toolbar1.split(',');
    toolbarArr = toolbarArr.filter(function (btn) {
      return ['bullist', 'numlist'].indexOf(btn) === -1;
    });
    editor.settings.toolbar1 = toolbarArr.join();
  }
};
module.exports = WPEditor;

/***/ }),

/***/ "./src/js/modules lazy recursive ^\\.\\/.*$":
/*!********************************************************!*\
  !*** ./src/js/modules/ lazy ^\.\/.*$ namespace object ***!
  \********************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var map = {
	"./ajax-tab": "./src/js/modules/ajax-tab.js",
	"./ajax-tab.js": "./src/js/modules/ajax-tab.js",
	"./cancel-btn-fixer": "./src/js/modules/cancel-btn-fixer.js",
	"./cancel-btn-fixer.js": "./src/js/modules/cancel-btn-fixer.js",
	"./iconpicker-manager": "./src/js/modules/iconpicker-manager.js",
	"./iconpicker-manager.js": "./src/js/modules/iconpicker-manager.js",
	"./labels-manager": "./src/js/modules/labels-manager.js",
	"./labels-manager.js": "./src/js/modules/labels-manager.js",
	"./parent-comment-id-fixer": "./src/js/modules/parent-comment-id-fixer.js",
	"./parent-comment-id-fixer.js": "./src/js/modules/parent-comment-id-fixer.js",
	"./wp-editor": "./src/js/modules/wp-editor.js",
	"./wp-editor.js": "./src/js/modules/wp-editor.js"
};

function webpackAsyncContext(req) {
	return Promise.resolve().then(() => {
		if(!__webpack_require__.o(map, req)) {
			var e = new Error("Cannot find module '" + req + "'");
			e.code = 'MODULE_NOT_FOUND';
			throw e;
		}

		var id = map[req];
		return __webpack_require__.t(id, 7);
	});
}
webpackAsyncContext.keys = () => (Object.keys(map));
webpackAsyncContext.id = "./src/js/modules lazy recursive ^\\.\\/.*$";
module.exports = webpackAsyncContext;

/***/ }),

/***/ "./src/js/modules sync recursive ^\\.\\/.*$":
/*!***************************************!*\
  !*** ./src/js/modules/ sync ^\.\/.*$ ***!
  \***************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var map = {
	"./ajax-tab": "./src/js/modules/ajax-tab.js",
	"./ajax-tab.js": "./src/js/modules/ajax-tab.js",
	"./cancel-btn-fixer": "./src/js/modules/cancel-btn-fixer.js",
	"./cancel-btn-fixer.js": "./src/js/modules/cancel-btn-fixer.js",
	"./iconpicker-manager": "./src/js/modules/iconpicker-manager.js",
	"./iconpicker-manager.js": "./src/js/modules/iconpicker-manager.js",
	"./labels-manager": "./src/js/modules/labels-manager.js",
	"./labels-manager.js": "./src/js/modules/labels-manager.js",
	"./parent-comment-id-fixer": "./src/js/modules/parent-comment-id-fixer.js",
	"./parent-comment-id-fixer.js": "./src/js/modules/parent-comment-id-fixer.js",
	"./wp-editor": "./src/js/modules/wp-editor.js",
	"./wp-editor.js": "./src/js/modules/wp-editor.js"
};


function webpackContext(req) {
	var id = webpackContextResolve(req);
	return __webpack_require__(id);
}
function webpackContextResolve(req) {
	if(!__webpack_require__.o(map, req)) {
		var e = new Error("Cannot find module '" + req + "'");
		e.code = 'MODULE_NOT_FOUND';
		throw e;
	}
	return map[req];
}
webpackContext.keys = function webpackContextKeys() {
	return Object.keys(map);
};
webpackContext.resolve = webpackContextResolve;
module.exports = webpackContext;
webpackContext.id = "./src/js/modules sync recursive ^\\.\\/.*$";

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		if(__webpack_module_cache__[moduleId]) {
/******/ 			return __webpack_module_cache__[moduleId].exports;
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
/******/ 	/* webpack/runtime/create fake namespace object */
/******/ 	(() => {
/******/ 		var getProto = Object.getPrototypeOf ? (obj) => (Object.getPrototypeOf(obj)) : (obj) => (obj.__proto__);
/******/ 		var leafPrototypes;
/******/ 		// create a fake namespace object
/******/ 		// mode & 1: value is a module id, require it
/******/ 		// mode & 2: merge all properties of value into the ns
/******/ 		// mode & 4: return value when already ns object
/******/ 		// mode & 16: return value when it's Promise-like
/******/ 		// mode & 8|1: behave like require
/******/ 		__webpack_require__.t = function(value, mode) {
/******/ 			if(mode & 1) value = this(value);
/******/ 			if(mode & 8) return value;
/******/ 			if(typeof value === 'object' && value) {
/******/ 				if((mode & 4) && value.__esModule) return value;
/******/ 				if((mode & 16) && typeof value.then === 'function') return value;
/******/ 			}
/******/ 			var ns = Object.create(null);
/******/ 			__webpack_require__.r(ns);
/******/ 			var def = {};
/******/ 			leafPrototypes = leafPrototypes || [null, getProto({}), getProto([]), getProto(getProto)];
/******/ 			for(var current = mode & 2 && value; typeof current == 'object' && !~leafPrototypes.indexOf(current); current = getProto(current)) {
/******/ 				Object.getOwnPropertyNames(current).forEach(key => def[key] = () => value[key]);
/******/ 			}
/******/ 			def['default'] = () => value;
/******/ 			__webpack_require__.d(ns, def);
/******/ 			return ns;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/ensure chunk */
/******/ 	(() => {
/******/ 		// The chunk loading function for additional chunks
/******/ 		// Since all referenced chunks are already included
/******/ 		// in this file, this function is empty here.
/******/ 		__webpack_require__.e = () => (Promise.resolve());
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/publicPath */
/******/ 	(() => {
/******/ 		var scriptUrl;
/******/ 		if (__webpack_require__.g.importScripts) scriptUrl = __webpack_require__.g.location + "";
/******/ 		var document = __webpack_require__.g.document;
/******/ 		if (!scriptUrl && document) {
/******/ 			if (document.currentScript)
/******/ 				scriptUrl = document.currentScript.src
/******/ 			if (!scriptUrl) {
/******/ 				var scripts = document.getElementsByTagName("script");
/******/ 				if(scripts.length) scriptUrl = scripts[scripts.length - 1].src
/******/ 			}
/******/ 		}
/******/ 		// When supporting browsers where an automatic publicPath is not supported you must specify an output.publicPath manually via configuration
/******/ 		// or pass an empty string ("") and set the __webpack_public_path__ variable from your code to use your own logic.
/******/ 		if (!scriptUrl) throw new Error("Automatic publicPath is not supported in this browser");
/******/ 		scriptUrl = scriptUrl.replace(/#.*$/, "").replace(/\?.*$/, "").replace(/\/[^\/]+$/, "/");
/******/ 		__webpack_require__.p = scriptUrl + "../";
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!****************************!*\
  !*** ./src/js/frontend.js ***!
  \****************************/
/**
 * Discussions Tab for WooCommerce Products - Frontend JS
 *
 * @version 1.2.6
 * @since   1.2.6
 * @author  Thanks to IT
 */
// Dynamic modules (will be loaded through the
__webpack_require__.p = alg_dtwp.plugin_url + "/assets/";
var modules = alg_dtwp.modulesToLoad;

if (modules && modules.length) {
  modules.forEach(function (module) {
    __webpack_require__("./src/js/modules lazy recursive ^\\.\\/.*$")("./".concat(module)).then(function (component) {
      component.init();
    });
  });
} // Static modules


var staticModules = ['cancel-btn-fixer', 'parent-comment-id-fixer', 'labels-manager'];
staticModules.forEach(function (module_name) {
  var module = __webpack_require__("./src/js/modules sync recursive ^\\.\\/.*$")("./" + module_name);

  module.init();
});
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!********************************!*\
  !*** ./src/scss/frontend.scss ***!
  \********************************/
// extracted by mini-css-extract-plugin
})();

/******/ })()
;
//# sourceMappingURL=frontend.js.map