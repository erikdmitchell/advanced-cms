jQuery(document).ready(function($) {

	var $formWrap=$('#mdw-cms-form-wrap');
	var $ajaxLoader=$('#ajax-loader');
	var $adminNotices=$('#mdw-cms-admin-notices');

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
			'item_type' : $(this).data('item-type')
		};

		$.post(ajaxurl,data,function(response) {
			var results=$.parseJSON(response);

			if (data.page_action=='add') {
				var form='';
				// build fake form to add to page reload
				form+='<form action="'+data.tab_url+'" method="post">';
					form+='<input type="hidden" name="create-cpt" value="1">';
					form+='<input type="hidden" name="id" value="'+results.id+'">';
					form+='<input type="hidden" name="notice" value="'+results.notice+'">';
				form+='</form>';
				$(form).appendTo('body').submit();
			} else {
				$formWrap.html('').html(results.content); // clear and push content to our wrapper
				$adminNotices.html('').html(results.notice); // clear and post notice
			}

			$ajaxLoader.hide();
		});
	});

});