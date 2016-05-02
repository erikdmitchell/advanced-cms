function setupDialogBox(data, delete_data, description) {
	var dialogBoxID='dialog-confirm';
	var $dialogBox='';

	// generate dialog box //
	$dialogBox+='<div id="'+dialogBoxID+'" title="'+data.label+'">';
		$dialogBox+='<p>This will delete the '+data.label+' ('+data.id+') '+description+'. Are you sure?</p>';
	$dialogBox+='</div>';

	jQuery('body').append($dialogBox); // append box

	// launch dialog box //
	jQuery('#'+dialogBoxID).dialog({
		resizable: false,
		modal: true,
		buttons: {
			"Delete": function() {
				jQuery.post(ajaxurl, delete_data, function(response) {
					if (response) {
						location.reload();
					}
				});
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
				jQuery('#'+dialogBoxID).remove();
			}
		}
	});
}