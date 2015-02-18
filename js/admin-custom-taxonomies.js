jQuery(document).ready(function($) {
	$('.custom-taxonomies #name').change(function() {
		if ($(this).nameCheck($(this),$(this).val(),wp_options)) {
			$('.custom-taxonomies #submit').prop('disabled',false);
		} else {
			$('.custom-taxonomies #submit').prop('disabled',true);
		}
	});
});