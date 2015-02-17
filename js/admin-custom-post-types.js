jQuery(document).ready(function($) {
	$('.custom-post-types #name').change(function() {
		if ($(this).checkPostType($(this),$(this).val())) {
			$('.custom-post-types #submit').prop('disabled',false);
		} else {
			$('.custom-post-types #submit').prop('disabled',true);
		}
	});
});

/**
 * validates our custom post type name
 * paramaters: 20 chars max, no capital letters or spaces and reserved post types.
 *
 * @since 1.1.6
 */
(function($) {
	$.fn.checkPostType=function($this,value,options) {
		var settings=$.extend({
			maxLength : 20,
			reservedPostTypes : ['post','page','attachment','revision','nav_menu_item']
		}, options);

		// strip spaces and set to lowercase //
		value=value.replace(/\s+/g, '').toLowerCase();

		$this.val(value); // set value to clean one

		// check if name is reserved //
		if ($.inArray(value,settings.reservedPostTypes)!=-1) {
			return false;
		}

		// check length //
		if (value.length>settings.maxLength) {
			return false;
		}

		return true;
	};
}(jQuery));