jQuery(document).ready(function($) {

	// select post type //
	$('.pickle-cms-admin-column [name="post_type"]').on('change', function(e) {
		e.preventDefault();
		
		var data={
			'action' : 'pickle_cms_admin_col_change_post_type',
			'post_type' : $(this).val()
		};

		$.post(ajaxurl, data, function(response) {
			$('#select-post-type').html(response);		
		});
	});

    // delete column (pops up confirm dialog) //
	$('.pickle-cms-admin-columns .admin-column-list td a .dashicons-trash').on('click',function(e) {
		e.preventDefault();

		var data={
			'action' : 'pickle_cms_get_column',
			'post_type' : $(this).data('postType'),
			'taxonomy' : $(this).data('taxonomy')			
		};

		$.post(ajaxurl, data, function(response) {
			var response_data=$.parseJSON(response);

			var data={
				'label' : response_data.metabox_taxonomy,
				'id' : response_data.post_type
			};
			var delete_data={
				'action' : 'pickle_cms_delete_column',
				'post_type' : response_data.post_type,
				'taxonomy': response_data.metabox_taxonomy
			}
		
			setupDialogBox(data, delete_data, 'admin column');
		});
	});

});