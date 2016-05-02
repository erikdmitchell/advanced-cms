jQuery(document).ready(function($) {

	/**
	 * delete link click
	 */
	$('.mdw-cms-custom-post-types-list .cpt-row .delete a').live('click',function(e) {
		e.preventDefault();

		var data={
			'action' : 'mdw_cms_get_post_type',
			'slug' : $(this).parents('.cpt-row').data('slug')
		};

		$.post(ajaxurl, data, function(response) {
			var data=$.parseJSON(response);

			setupDialogBox(data);
		});
	});

});

function setupDialogBox(data) {
	var dialogBoxID='dialog-confirm';
	var $dialogBox='';
	var delete_data={
		'action' : 'mdw_cms_delete_post_type',
		'name' : data.name
	}

	// generate dialog box //
	$dialogBox+='<div id="'+dialogBoxID+'" title="'+data.label+'">';
		$dialogBox+='<p>This will delete the '+data.label+' ('+data.name+') custom post type. Are you sure?</p>';
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