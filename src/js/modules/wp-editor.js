const WPEditor={
	initTinyMCE:function(){
		wp.editor.remove('discussion');
		wp.editor.initialize('discussion', {
			tinymce: true,
			teeny: true,
			quicktags: true,
		});
	},
	init:function(){
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
		jQuery( document ).on( 'tinymce-editor-setup', WPEditor.setupEditor);
	},
	setupEditor:function(event, editor){
		if(editor.id !== 'discussion') return;
		let toolbarArr = editor.settings.toolbar1.split(',');
		toolbarArr = toolbarArr.filter(btn => ['bullist', 'numlist'].indexOf(btn) === -1);
		editor.settings.toolbar1 = toolbarArr.join();
		editor.settings.content_style='body{font-size:15px;font-family:Source Sans Pro,HelveticaNeue-Light,Helvetica Neue Light,Helvetica Neue,Helvetica,Arial,Lucida Grande,sans-serif;}';
	}
}
module.exports = WPEditor;