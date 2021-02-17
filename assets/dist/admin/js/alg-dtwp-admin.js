jQuery(function ($) {
	var alg_dtwp_admin_iconpicker = {
		init: function () {
			var icon_input = $('.alg-dtwp-icon-picker');
			this.createIconNextToInput(icon_input);
			if(icon_input.length){
				this.callIconPicker(icon_input);
			}
		},
		createIconNextToInput:function(input){
			jQuery('<span style="margin:0px 7px 0 10px" class="input-group-addon"></span>').insertAfter(input);
		},
		callIconPicker:function(element){
			element.iconpicker({
				selectedCustomClass:'alg-dtwp-iconpicker-selected',
				hideOnSelect: true,
				placement: 'bottom'
			});
		}
	};
	alg_dtwp_admin_iconpicker.init();
});