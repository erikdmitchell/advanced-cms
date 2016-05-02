/**
 * validates our custom post type name
 *
 * @since 2.1.8
 */
(function($) {
	$.fn.nameCheck=function($this,value,options) {
		var settings=$.extend({
			maxLength : 20,
			reservedPostTypes : ['post','page','attachment','revision','nav_menu_item'],
			errorField : $('#mdw-cms-name-error')
		}, options);

		settings.errorField.text(''); // clear errors

		// strip spaces and set to lowercase //
		value=value.replace(/\s+/g, '').toLowerCase();

		$this.val(value); // set value to clean one

		// check if name is reserved //
		if ($.inArray(value,settings.reservedPostTypes)!=-1) {
			settings.errorField.text('Can not use a reserved post type.');
			return false;
		}

		// check length //
		if (value.length>settings.maxLength) {
			settings.errorField.text('Name length is too long.');
			return false;
		}

		settings.errorField.text(''); // clear errors

		return true;
	};
}(jQuery));