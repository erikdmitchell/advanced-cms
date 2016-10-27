jQuery(document).ready(function($) {

	$('form.custom-taxonomies').requiredFields();

	/**
	 * metabox id check
	 */
	$('.custom-taxonomies #name').change(function() {
		if ($(this).taxonomyIDcheck($(this), $(this).val())) {
			$('.custom-taxonomies #submit').prop('disabled',false);
		} else {
			$('.custom-taxonomies #submit').prop('disabled',true);
		}
	});

	/**
	 * delete link click
	 */
	$('.mdw-cms-taxonomies .taxonomies-list td a .dashicons-trash').on('click', function(e) {
		e.preventDefault();

		var data={
			'action' : 'mdw_cms_get_taxonomy',
			'name' : $(this).data('name')
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