jQuery(document).ready(function($) {

/*
	$('.colorPicker').colpick({
		layout:'hex',
		color:$('.colorPicker').val(),
		onSubmit:function(hsb,hex,rgb,el) {
			$(el).val('#'+hex);
			$(el).colpickHide();
		}
	});
*/

	//$('.timepicker').timepicker();

	/**
	 * date picker
	 */
/*
	$('.advanced-cms-datepicker').datepicker({
		dateFormat : advancedCMSjs.datepicker.format,
		showButtonPanel: true,
		changeMonth: true,
		changeYear: true
	});
*/

	// format and set our date properly //
/*
	if (advancedCMSjs.datepicker.value!='') {
		var dateFormatted=$.datepicker.formatDate(advancedCMSjs.datepicker.format, new Date(advancedCMSjs.datepicker.value));

		$('#'+advancedCMSjs.datepicker.id).val(dateFormatted);
	}
*/

	//$('.phone').mask('(999) 999-9999'); // masked input //

	// setup media uploader //
	//$('.type-gallery').advancedCMScustomMediaUploader();

});
