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
	$('.advanced-cms-post-types .post-type-list td .dashicons-trash').on('click', function(e) {
		e.preventDefault();

		var data={
			'action' : 'advanced_cms_get_post_type',
			'slug' : $(this).data('slug')
		};
console.log(data);
		$.post(ajaxurl, data, function(response) {
			var response_data=$.parseJSON(response);
			var data={
				'label' : response_data.label,
				'id' : response_data.name
			};
			var delete_data={
				'action' : 'advanced_cms_delete_post_type',
				'name' : response_data.name
			}
			setupDialogBox(data, delete_data, 'custom post type');
		});
	});

	/**
	 * dashicon click
	 */
	$('.custom-post-types .advanced-cms-dashicon-grid .dashicons').click(function(e) {
		e.preventDefault();

		var iconClass=$(this).data('icon');

		// replace icon image //
		$('.custom-post-types .selected-icon > span').removeClass();
		$('.custom-post-types .selected-icon > span').addClass('dashicons '+iconClass);

		// replace hidden input //
		$('.custom-post-types #selected-dashicon').val(iconClass);
	});

});