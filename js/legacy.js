jQuery(document).ready(function($) {

	$('#run-upgrade').click(function(e) {
		e.preventDefault();
		$(this).prop('disabled',true);

		var data={
			'action': 'run_legacy_upgrade',
			'file': options.file
		}

		$.post(ajaxurl, data, function(response) {
			var result=$.parseJSON(response);

			$('#mdwcms-updater-notices').append(result.notices);
			$('#clear-files').prop('disabled',false);
		});
	});

	$('#clear-files').click(function(e) {
		e.preventDefault();
		$(this).prop('disabled',true);

		$('<div></div>').appendTo('body')
    .html('<div>Your are about to delete your older MDW CMS config file. This cannot be undone. Are you sure you want to do this?</div>')
    .dialog({
        modal: true,
        title: 'Delete config file.',
        zIndex: 10000,
        autoOpen: true,
        width: '300px',
        resizable: false,
        buttons: {
            Yes: function () {

							var data={
								'action': 'legacy_upgrade_remove_file',
								'file': options.file
							}

							$.post(ajaxurl, data, function(response) {
								var result=$.parseJSON(response);

								$('#mdwcms-updater-notices').html(result.notices);
								$('#clear-files').prop('disabled',true);
							});

                $(this).dialog("close");
            },
            No: function () {
		            $('#clear-files').prop('disabled',false);
                $(this).dialog("close");
            }
        },
        close: function (event, ui) {
		        $('#clear-files').prop('disabled',false);
            $(this).remove();
        }
    });
	});

});