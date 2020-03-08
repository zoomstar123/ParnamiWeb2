/* Plugin Settings */

jQuery(document).ready(function($) {
	
	$('.disable-gutenberg-reset-options').on('click', function(e) {
		e.preventDefault();
		$('.plugin-modal-dialog').dialog('destroy');
		var link = this;
		var button_names = {}
		button_names[alert_reset_options_true]  = function() { window.location = link.href; }
		button_names[alert_reset_options_false] = function() { $(this).dialog('close'); }
		$('<div class="plugin-modal-dialog">'+ alert_reset_options_message +'</div>').dialog({
			title: alert_reset_options_title,
			buttons: button_names,
			modal: true,
			width: 350
		});
	});
	
});

jQuery(document).ready(function($){
	
	var disable_g7g_el = 'table.form-table input[name="disable_gutenberg_options[disable-all]"]';
	var disable_g7g_go = $(disable_g7g_el +':checked').val();
	
	var disable_g7g_title = $('.wrap h2').slice(1,5);
	var disable_g7g_text  = $('.wrap p').not('.g7g-display').not('.submit');
	var disable_g7g_table = $('.wrap table').slice(1,5);
	
	if (disable_g7g_go) {
		disable_g7g_title.hide();
		disable_g7g_text.hide();
		disable_g7g_table.hide();
	}
	
	$(disable_g7g_el).bind('change',function(){
		if ($(this).val()) {
			disable_g7g_title.toggle(0);
			disable_g7g_text.toggle(0);
			disable_g7g_table.toggle(0);
		} else {
			disable_g7g_title.hide();
			disable_g7g_text.hide();
			disable_g7g_table.hide();
		}
	});
	
});