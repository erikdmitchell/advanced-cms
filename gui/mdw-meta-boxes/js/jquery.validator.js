jQuery(document).ready(function($) {
	// add a place for our errors //
	$('.validator').each(function (i) {
		$(this).after('<span class="jq-validator"></span>');
	});
	
	$('.url').change(function() {
		validateURL($(this).val(),$(this));
	});

	$('.email').change(function() {
		validateEMail($(this).val(),$(this));
	});
});

function validateURL(url,$this) {
	if (/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(url)) {
  	this.next('.jq-validator').removeClass('error').text(''); // valid
	} else {
  	$this.next('.jq-validator').addClass('error').text('Invalid URL');	
	}
}

function validateEMail(email,$this) {
	if (/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email)) {
  	$this.next('.jq-validator').removeClass('error').text(''); // valid
	} else {
  	$this.next('.jq-validator').addClass('error').text('Invalid Email');	
	}
}