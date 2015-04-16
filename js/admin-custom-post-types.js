jQuery(document).ready(function($) {

	$('.custom-post-types #name').change(function() {
		if ($(this).nameCheck($(this),$(this).val())) {
			$('.custom-post-types #submit').prop('disabled',false);
		} else {
			$('.custom-post-types #submit').prop('disabled',true);
		}
	});

});