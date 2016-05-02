jQuery(document).ready(function($) {

	$('form.custom-post-types').requiredFields();

	/**
	 * name check
	 */
	$('.custom-post-types #name').change(function() {
		if ($(this).nameCheck($(this), $(this).val())) {
			$('.custom-post-types #submit').prop('disabled',false);
		} else {
			$('.custom-post-types #submit').prop('disabled',true);
		}
	});

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
			var response_data=$.parseJSON(response);
			var data={
				'label' : response_data.label,
				'id' : response_data.name
			};
			var delete_data={
				'action' : 'mdw_cms_delete_post_type',
				'name' : response_data.name
			}
			setupDialogBox(data, delete_data, 'custom post type');
		});
	});

});