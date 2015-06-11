jQuery(document).ready(function($) {

	$('.colorPicker').colpick({
		layout:'hex',
		color:$('.colorPicker').val(),
		onSubmit:function(hsb,hex,rgb,el) {
			$(el).val('#'+hex);
			$(el).colpickHide();
		}
	});

	$('.timepicker').timepicker();

	/**
	 * date picker
	 */
	$('.mdw-cms-datepicker').datepicker({
		dateFormat : wp_options.dateFormat,
		showButtonPanel: true,
		changeMonth: true,
		changeYear: true
	});

	$('.phone').mask('(999) 999-9999'); // masked input //

	// setup media uploader //
	$('.type-gallery').mdwCMScustomMediaUploader();

});
