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

});