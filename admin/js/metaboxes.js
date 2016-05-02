jQuery(document).ready(function($) {

	/**
	 * delete link click
	 */
	$('.custom-metabox-list .metabox-row .delete a').live('click',function(e) {
		e.preventDefault();

		var data={
			'action' : 'mdw_cms_get_metabox',
			'id' : $(this).parents('.metabox-row').data('id')
		};

		$.post(ajaxurl, data, function(response) {
			var response_data=$.parseJSON(response);
			var data={
				'label' : response_data.title,
				'id' : response_data.mb_id
			};
			var delete_data={
				'action' : 'mdw_cms_delete_metabox',
				'id' : response_data.mb_id
			}
			setupDialogBox(data, delete_data, 'metabox');
		});
	});

});