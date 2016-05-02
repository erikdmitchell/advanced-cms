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