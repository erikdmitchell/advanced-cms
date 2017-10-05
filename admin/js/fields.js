jQuery(document).ready(function($) {
	
	$('.pickle-cms-admin-page .add-fields').on('change', '.pickle-cms-fields-wrapper .field-title', function(e) {	
		$wrapper=$(this).parents('.pickle-cms-fields-wrapper');
		$fieldID=$wrapper.find($('.field-type.field-id'));
		
		$fieldID.val(stringToSlug($(this).val()));
	});

});

var stringToSlug = function (str) {
	str = str.replace(/^\s+|\s+$/g, ''); // trim
	str = str.toLowerCase();

	// remove accents, swap ñ for n, etc
	var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
	var to   = "aaaaeeeeiiiioooouuuunc------";

	for (var i=0, l=from.length ; i<l ; i++)
	{
		str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
	}

	str = str.replace('.', '-')
		.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
		.replace(/\s+/g, '-') // collapse whitespace and replace by -
		.replace(/-+/g, '-'); // collapse dashes

	return str;
}