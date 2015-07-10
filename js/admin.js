jQuery(document).ready(function($) {

	var $formWrap=$('#mdw-cms-form-wrap');
	var $ajaxLoader=$('#ajax-loader');
	var $adminNotices=$('#mdw-cms-admin-notices');

	/**
	 * edit link click
	 */
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

	/**
	 * delete link click
	 */
	$('.mdw-cms-edit-delete-list > span.delete > a').live('click',function(e) {
		e.preventDefault();

		var data={
			'action' : $(this).data('action'),
			'tab_url' : $(this).data('tab-url'),
			'item_type' : $(this).data('item-type'),
			'slug' : $(this).data('slug'),
			'page_action' : $(this).data('page-action'),
			'id' : $(this).data('id'),
			'title' : $(this).data('title')
		};
		var pageAction=data.page_action.toLowerCase().replace(/^[\u00C0-\u1FFF\u2C00-\uD7FF\w]|\s[\u00C0-\u1FFF\u2C00-\uD7FF\w]/g, function(letter) {
    	return letter.toUpperCase();
		});
		var dialogBoxID='dialog-confirm';
		var $dialogBox='';

		// generate dialog box //
		$dialogBox+='<div id="'+dialogBoxID+'" title="'+pageAction+' '+data.title+'?">';
			$dialogBox+='<p>This will delete the '+data.title+' "'+data.slug+'". Are you sure?</p>';
		$dialogBox+='</div>';

		$('body').append($dialogBox); // append box

		// launch dialog box //
		$('#'+dialogBoxID).dialog({
			resizable: false,
			modal: true,
			buttons: {
				"Delete": function() {
					$ajaxLoader.show();
					$.post(ajaxurl,data,function(response) {
						$ajaxLoader.hide();
						if (response) {
							location.reload();
						}
					});
					$(this).dialog('close');
				},
				Cancel: function() {
					$(this).dialog('close');
					$('#'+dialogBoxID).remove();
				}
			}
		});

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