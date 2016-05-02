jQuery(document).ready(function($) {

	$('.optional-settings-toggle').click(function(e){
		e.preventDefault();
		$('.optional-settings').slideToggle(600, function(){
			if ($(this).is(':visible')) {
				$('.optional-settings-toggle').text('Hide Optional Settings');
			} else {
				$('.optional-settings-toggle').text('Show Optional Settings');
			}
		});
	});

	customContentCheck($('input:radio[name=_display-content]:checked').val());
	changeCategoryDD($('#mdwm-pages-metabox .post-type-dd').val());
	changeDisplayExcerpt($('#_display-excerpt').attr('checked'));
	changeMoreLink($('#_more-link').attr('checked'));
	
	$('input:radio[name=_display-content]').click(function() {
		customContentCheck($(this).val());
	});
	
	$('#mdwm-pages-metabox .post-type-dd').change(function() {
		changeCategoryDD($(this).val());
	});
	
	$('#mdwm-pages-metabox .post-type-tax').change(function() {
		var value=$(this).val();
		$('#mdwm-pages-metabox #_post-category').val($(this).val());
		
		$('#'+$(this).attr('id')+' option').each(function() {
			if ($(this).val()==value) {
				$('#mdwm-pages-metabox #_post-taxonomy').val($(this).attr('data-type-tax'));
			}
		});
	});
	
	$('input:checkbox[name=_display-excerpt]').change(function() {
		changeDisplayExcerpt(this.checked);
	});

	$('input:checkbox[name=_more-link]').change(function() {
		changeMoreLink(this.checked);
	});

});

//$=jQuery.noConflict();

function customContentCheck(value) {
	if (value=='custom') {
		jQuery('#mdwm-pages-metabox .custom-content').show();
	} else {
		jQuery('#mdwm-pages-metabox .custom-content').hide();	
	}	
}

function changeCategoryDD(value) {
	var prefix='post-type-tax-';
	jQuery('#mdwm-pages-metabox .post-type-tax').each(function() {
		jQuery(this).hide();
	});
	jQuery('#mdwm-pages-metabox #'+prefix+value).show();
}

function changeDisplayExcerpt(isChecked) {
	if (isChecked) {
		jQuery('#mdwm-pages-metabox .display-excerpt').show();
	} else {
		jQuery('#mdwm-pages-metabox .display-excerpt').hide();	
	}
}

function changeMoreLink(isChecked) {
	if (isChecked) {
		jQuery('#mdwm-pages-metabox .more-link').show();
	} else {
		jQuery('#mdwm-pages-metabox .more-link').hide();	
	}
}
