jQuery(document).ready(function($) {

	$('form.custom-taxonomies').requiredFields();

	/**
	 * delete link click
	 */
	$('.custom-taxonomies-list .tax-row .delete a').live('click',function(e) {
		e.preventDefault();

		var data={
			'action' : 'mdw_cms_get_taxonomy',
			'name' : $(this).parents('.tax-row').data('name')
		};

		$.post(ajaxurl, data, function(response) {
			var response_data=$.parseJSON(response);
			var data={
				'label' : response_data.args.label,
				'id' : response_data.name
			};
			var delete_data={
				'action' : 'mdw_cms_delete_taxonomy',
				'id' : response_data.name
			}

			setupDialogBox(data, delete_data, 'metabox');
		});
	});

});