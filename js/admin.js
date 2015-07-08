jQuery(document).ready(function($) {

	var $formWrap=$('#mdw-cms-form-wrap');
	var $ajaxLoader=$('#ajax-loader');
	var $adminNotices=$('#mdw-cms-admin-notices');

	/**
	 * edit/delete link click
	 */
	$('.mdw-cms-edit-delete-list > span > a').live('click',function(e) {
		e.preventDefault();

		$ajaxLoader.show();

		var data={
			'action' : $(this).data('action'),
			'tab_url' : $(this).data('tab-url'),
			'item_type' : $(this).data('item-type'),
			'slug' : $(this).data('slug'),
			'page_action' : $(this).data('page-action'),
			'id' : $(this).data('id')
		};
//console.log(data);

		$.post(ajaxurl,data,function(response) {
			var results=$.parseJSON(response);
			$formWrap.html('').html(results.content); // clear and push content to our wrapper
//console.log(results);

			if (data.page_action=='delete') {
				$('#'+data.item_type+'-list-'+data.id).remove();
			}

			$adminNotices.html('').html(results.notice); // clear and post notice

			$ajaxLoader.hide();
		});
	});

});