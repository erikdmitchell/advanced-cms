var $j=jQuery;//.noConflict();

var widgetID=0; // sets our global variable //

/**
 * attached to live b/c WP Widgets are AJAX based once they're initiated
 * using some predefined attributes, this swaps out the 'Category' drop down based on the 'Post Type' selected.
 * it also sets the value of our 'Category' drop down if one exists
**/
$j('.post-type-dd').live('change',function() {
	widgetID=$j(this).attr('data-type-widget-id');
	var postTypeID='post-type-tax-'+jQuery(this).val();
	
	$j('.post-type-tax').each(function() {	
		$j(this).hide();
		if (postTypeID==jQuery(this).attr('id')) {
			$j(this).show();
			// set taxonomy //
			if (getTaxonomy(postTypeID)) {
				setTaxonomy(getTaxonomy(postTypeID));
			} else {
				setTaxonomy(null);			
			}
		}
		
		if (postTypeID=='post-type-tax-post') {			
			$j('#'+widgetID+' .for-post-only').show();
			$j('#'+widgetID+' .for-page-only').hide();		
		} else {
			$j('#'+widgetID+' .for-post-only').hide();
			$j('#'+widgetID+' .for-page-only').show();	
		}
		
	});
});

/**
 * changes our hidden input value when the 'Category' drop down changes
**/
$j('.post-type-tax').live('change',function() {
	widgetID=$j(this).attr('data-type-widget-id');
	var postTypeID=$j(this).attr('id');
//console.log('post type tax change');
	$j('#widget-'+widgetID+'-post-category').val($j(this).val());		
	
	if (getTaxonomy(postTypeID)) {
		setTaxonomy(getTaxonomy(postTypeID));
	} else {
		setTaxonomy(null);			
	}
});

/**
 * sets the 'Category' value
**/
function setTaxonomy(value) {
	var taxFieldID='widget-'+widgetID+'-post-taxonomy';
//console.log('setTaxonomy');
	$j('#'+taxFieldID).val(value);
}

/**
 * if there's a selected value of the 'Category' dropdwon it returns is, else returns false
**/
function getTaxonomy(id) {
	var selectedTaxonomy=$j('#'+widgetID+' #'+id).find('option:selected').attr('data-type-tax');
//console.log('getTaxonomy');
	if (typeof selectedTaxonomy==='undefined') {
		return false;
	} else {
		return selectedTaxonomy;
	}
	
	return false;
}

/**
 * our show hide functions for some of our checkboxes
**/
$j('.more-link-checkbox').change(function() {
	var widgetID=$j(this).parent().parent().attr('id');
	if ($j(this).is(':checked')) {
		$j('#'+widgetID+' p.more-link').show();	
	} else {
		$j('#'+widgetID+' p.more-link').hide();	
	}
});

$j('.disp-excerpt-checkbox').change(function() {
	var widgetID=$j(this).parent().parent().attr('id');
	if ($j(this).is(':checked')) {
		$j('#'+widgetID+' p.disp-excerpt').show();	
	} else {
		$j('#'+widgetID+' p.disp-excerpt').hide();	
	}
});

$j(document).ready(function() {
	// for each widget, run function //
	$j('#upper-home-widget .widget').each(function(i) {
		updateWidget($j(this).attr('id'));
	});	
});


/**
 * hook into the ajax function when a widget save is complete
 * cycle thorugh the junk and get our widget id
**/
$j(document).ajaxComplete(function(event,XMLHttpRequest,ajaxOptions) {
	if (typeof ajaxOptions.data === 'undefined') {
		return false;
	}
	
	var pairs=ajaxOptions.data.split('&');
	var i;
	var split;
	
	for (i in pairs) {
		if (typeof pairs[i]==='string') {
			split=pairs[i].split('=');
			if (split[0]=='widget-id') {
				widgetID=split[1];
			}	
		}
	}
	
	updateWidget(widgetID);
	
});

/**
 * a 'document(ready)' type function four our widgets
**/
function updateWidget(id) {
	if ($j('#'+id+' .more-link-checkbox').is(':checked')) {
		$j('#'+id+' p.more-link').show();	
	}

	if ($j('#'+id+' .disp-excerpt-checkbox').is(':checked')) {
		$j('#'+id+' p.disp-excerpt').show();	
	}
	
	if ($j('#'+id+' .post-type-dd').val()=='post') {
		$j('#'+id+' p.for-post-only').show();	
	}
}
