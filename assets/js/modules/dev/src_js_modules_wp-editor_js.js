(self["webpackChunk"] = self["webpackChunk"] || []).push([["src_js_modules_wp-editor_js"],{

/***/ "./src/js/modules/wp-editor.js":
/*!*************************************!*\
  !*** ./src/js/modules/wp-editor.js ***!
  \*************************************/
/***/ ((module) => {

var WPEditor = {
  initTinyMCE: function initTinyMCE() {
    wp.editor.remove('discussion');
    wp.editor.initialize('discussion', {
      tinymce: {
        wpautop: true //toolbar1: 'code',
        //toolbar1: 'bold italic link codesample',
        //plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
        //toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
        //toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'

      },
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
    editor.settings.toolbar1 = toolbarArr.join(); //editor.settings.wpautop=true;
    //editor.settings.autop=true;
    //editor.settings.forced_root_block = '';
    //editor.settings.force_br_newlines = true;
    //editor.settings.force_p_newlines = false;

    /*editor.settings.remove_linebreaks=true;
    editor.settings.convert_newlines_to_brs=true;
    editor.settings.remove_redundant_brs=true;*/

    editor.settings.content_style = 'body{font-size:15px;font-family:Source Sans Pro,HelveticaNeue-Light,Helvetica Neue Light,Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif;}';
  }
};
module.exports = WPEditor;

/***/ })

}]);
//# sourceMappingURL=src_js_modules_wp-editor_js.js.map