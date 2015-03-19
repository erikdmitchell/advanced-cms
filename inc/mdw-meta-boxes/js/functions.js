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

	$('.mdw-cms-datepicker').datepicker({
		'dateFormat' : 'mm/dd/yy'
	});

	$('.phone').mask('(999) 999-9999'); // masked input //

});
