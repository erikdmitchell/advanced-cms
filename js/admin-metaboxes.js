jQuery(document).ready(function($) {

	$('.custom-metabox #mb_id').change(function() {
		if ($(this).metaboxIDcheck($(this),$(this).val(),wp_metabx_options)) {
			$('.custom-metabox #submit').prop('disabled',false);
		} else {
			$('.custom-metabox #submit').prop('disabled',true);
		}
	});

});