/**
 * validates our metabox id
 *
 * @since 2.1.9.1
 */
(function($) {
	$.fn.metaboxIDcheck=function($this, value, options) {
		var data={
			'action' : 'mdw_cms_reserved_names',
			'type' : 'metabox'
		};

		$.post(ajaxurl, data, function(reserved_names) {
			var settings=$.extend({
				maxLength : 20,
				reserved : reserved_names,
				errorField : $('#mdw-cms-name-error')
			}, options);

			clearErrors(settings.errorField); // clear errors

			$this.val(cleanValue(value)); // clean value and set

			// check if name is reserved
			if (isReserved(value, settings.reserved)) {
				settings.errorField.text('Name is already used.');

				return false;
			}

			// check length //
			if (tooLong(value, settings.maxLength)) {
				settings.errorField.text('Name length is too long.');

				return false;
			}

			clearErrors(settings.errorField); // clear errors

			return true;
		});

		return;
	};
}(jQuery));

/**
 * clearErrors function.
 *
 * @access public
 * @param mixed $field
 * @return void
 */
function clearErrors($field) {
	$field.text('');
}

/**
 * cleanValue function.
 *
 * @access public
 * @param mixed value
 * @return void
 */
function cleanValue(value) {
	value=value.replace(/\s+/g, '').toLowerCase(); // strip spaces and set to lowercase

	return value;
}

/**
 * isReserved function.
 *
 * @access public
 * @param mixed value
 * @param mixed reserved
 * @return void
 */
function isReserved(value, reserved) {
	if (reserved.indexOf(value) != -1) {
		return true;
	}

	return false;
}

/**
 * tooLong function.
 *
 * @access public
 * @param mixed value
 * @param mixed maxLength
 * @return void
 */
function tooLong(value, maxLength) {
	if (value.length > maxLength) {
		return true;
	}

	return false;
}