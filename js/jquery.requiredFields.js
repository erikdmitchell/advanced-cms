/**
 * validates our required fields
 *
 * @since 2.1.8
 */
(function($) {

	$.fn.requiredFields=function(options) {
		var settings=$.extend({
			errorField : $('#mdw-cms-name-error'),
			btnID : 'submit'
		}, options);
		var $form=$(this);
		var totalInputs=0;
		var validInputs=0;

		var init=function() {
			onloadCheck();
			liveCheck();
		};

		var liveCheck=function() {
			$form.on('change', function() {
				validInputs=0;

				$form.find('input.required').each(function() {

					if ($(this).val()!='') {
						validInputs++;
					}
				});

				buttonCheck();
			});
		};

		var onloadCheck=function() {
			validInputs=0;

			// make sure there's a value in the input //
			$form.find('input.required').each(function() {
				if ($(this).val()!='') {
					validInputs++;
				}

				totalInputs++;
			});

			buttonCheck();
		};

		var buttonCheck=function() {
			// see how many valid inputs we have and disable our buttons //
			if (validInputs!=totalInputs) {
				$form.find('#'+settings.btnID).prop('disabled', true);
			} else {
				$form.find('#'+settings.btnID).prop('disabled', false);
			}
		};

		init();

		return;
	};

}(jQuery));