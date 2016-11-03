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
		dateFormat : wp_options.datepicker.format,
		showButtonPanel: true,
		changeMonth: true,
		changeYear: true
	});

	// format and set our date properly //
	var dateFormatted=$.datepicker.formatDate(wp_options.datepicker.format, new Date(wp_options.datepicker.value));
	$('#'+wp_options.datepicker.id).val(dateFormatted);

	$('.phone').mask('(999) 999-9999'); // masked input //

	// setup media uploader //
	$('.type-gallery').mdwCMScustomMediaUploader();

});
