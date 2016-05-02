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
	 * edit link click
	 */
/*
	$('.mdw-cms-edit-delete-list > span.edit > a').live('click',function(e) {
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

		$.post(ajaxurl,data,function(response) {
			var results=$.parseJSON(response);

			$formWrap.html('').html(results.content); // clear and push content to our wrapper
			$adminNotices.html('').html(results.notice); // clear and post notice
			$ajaxLoader.hide();
		});
	});
*/

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
			var data=$.parseJSON(response);

			setupDialogBox(data);
		});
	});
	/**
	 * submit button (cpt for now)
	 */
/*
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
*/

});

function setupDialogBox(data) {
	var dialogBoxID='dialog-confirm';
	var $dialogBox='';
	var delete_data={
		'action' : 'mdw_cms_delete_post_type',
		'name' : data.name
	}

	// generate dialog box //
	$dialogBox+='<div id="'+dialogBoxID+'" title="'+data.label+'">';
		$dialogBox+='<p>This will delete the '+data.label+' ('+data.name+') custom post type. Are you sure?</p>';
	$dialogBox+='</div>';

	jQuery('body').append($dialogBox); // append box

	// launch dialog box //
	jQuery('#'+dialogBoxID).dialog({
		resizable: false,
		modal: true,
		buttons: {
			"Delete": function() {
				jQuery.post(ajaxurl, delete_data, function(response) {
					if (response) {
						location.reload();
					}
				});
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
				jQuery('#'+dialogBoxID).remove();
			}
		}
	});

}