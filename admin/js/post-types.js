jQuery(document).ready(function($) {

	var $formWrap=$('#mdw-cms-form-wrap');
	var $ajaxLoader=$('#ajax-loader');
	var $adminNotices=$('#post-type-admin-notices');

	/**
	 * attach name check to cpt
	 */
	$('.custom-post-types #name').change(function() {
		if ($(this).nameCheck($(this),$(this).val())) {
			$('.custom-post-types #submit').prop('disabled',false);
		} else {
			$('.custom-post-types #submit').prop('disabled',true);
		}
	});

	/**
	 * delete link click
	 */
	$('.mdw-cms-edit-delete-list > span.delete > a').live('click',function(e) {
		e.preventDefault();

		var tb_show_url=ajaxurl+'?action=delete_cpt&id='+$(this).data('id')+'&slug='+$(this).data('slug');
		tb_show('',tb_show_url);
	});

	/**
	 * delete cpt confirm
	 */
	$('#mdw_cms_delete_cpt_submit').live('click',function(e) {
		e.preventDefault();

		var data={
			'action': 'confirm_delete_cpt',
			'id' : $(this).data('id')
		};

		$.post(ajaxurl,data,function(response) {
			location.reload();
		});
	});

	/**
	 * cancel delete cpt
	 */
	$('#mdw_cms_delete_cpt_cancel').live('click',function(e) {
		e.preventDefault();

		tb_remove();
	});

	/**
	 * submit button (cpt for now)
	 */
	$('.submit-button').live('click',function(e) {
		e.preventDefault();

		$ajaxLoader.show();

		var type=$(this).data('type');
		var $form=$(this).parents('form');

		var data={
			'action' : $(this).data('action'),
			'tab_url' : $(this).data('tab-url'),
			'form_data' : $form.serializeArray(),
			'page_action' : $(this).data('page-action'),
			'item_type' : $(this).data('item-type'),
			'cpt_id' : $('#cpt-id').val()
		};

		$.post(ajaxurl,data,function(response) {
			var results=$.parseJSON(response);

			$adminNotices.html('').html(results.notice); // clear and post notice

			$ajaxLoader.hide();
		});
	});

});