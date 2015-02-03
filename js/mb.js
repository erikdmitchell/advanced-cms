$j=jQuery.noConflict();

$j(document).ready(function() {
	
	// display field data on load //
	$j('.fields-wrapper').each(function() {
		var ddValue=$j(this).find('.field_type').val();
		
		$j(this).find('.type').each(function() {
			if ($j(this).data('field-type')==ddValue) {
				$j(this).show();							
			} else {
				$j(this).hide();	
			}	
		});	
	});
	
	// display field data on change //
	$j('.add-fields .field_type').live('change',function() {
		var ddValue=$j(this).val();
	
		$j(this).parent().parent().find('.type').each(function() {
			if ($j(this).data('field-type')==ddValue) {
				$j(this).show();							
			} else {
				$j(this).hide();	
			}		
		});
	});
	
	// adds a new field to our metabox //
	$j('#add-field-btn').live('click', function() {
		var newID=0;
		var lastFieldID='';
		var $clonedElement=$j('#fields-wrapper-default').clone();
		
		$j('.fields-wrapper').each(function(i) {
			newID=i+1;
			lastFieldID=$j(this).attr('id');
		});		
		
		// clean up and configure our cloned element (classes, ids, etc)
		var cloneID='fields-wrapper-'+newID;
		
		$clonedElement.removeClass('default');
		$clonedElement.attr('id',cloneID);

		$clonedElement.insertAfter('#'+lastFieldID);
		
		$j('#'+cloneID+' .name-item').each(function() {
			var attrName=$j(this).attr('name');
			var attrNewName=attrName.replace('default',newID);
			$j(this).attr('name',attrNewName);
		});
		
		$j('#'+cloneID+' .remove-field').attr('data-id','fields-wrapper-'+newID); // set our button to remove field
	});
	
	// remove a metabox field //
	$j('.button.remove-field').live('click',function(e) {
		var elemID=$j(this).data('id');		
		$j('#'+elemID).remove();
	});

	// adds a new option field to our metabox element //
	$j('#add-option-field-btn').on('click', function() {
		var newID=0;
		var lastFieldID='';
		var wrapperID=$j(this).parent().parent().attr('id');
		var $clonedElement=$j('#option-row-default').clone();
		
		$j('#'+wrapperID+' .option-row').each(function(i) {
			newID=i+1;
			lastFieldID=$j(this).attr('id');
		});	
		
		// clean up and configure our cloned element (classes, ids, etc)
		var cloneID='option-row-'+newID;
		
		$clonedElement.removeClass('default');
		$clonedElement.attr('id',cloneID);

		$clonedElement.insertAfter('#'+lastFieldID);

		//$j('#'+wrapperID).append($clonedElement);
		
		$j('#'+cloneID+' .options-item').each(function() {
			var attrName=$j(this).attr('name');
			var attrNewName=attrName.replace('default',newID);
			$j(this).attr('name',attrNewName);
		});
	});
	
	// handles our checkbox issue for forms //
/*
	$j('input[type="checkbox"]').on('change', function(e){
		if ($j(this).prop('checked')) {
			$j(this).val(1);
		} else {
			$j(this).val(0);	
			$j(this).removeAttr('checked');
		}
	});
*/
	
});

/**
 * our sortable function for our metabox fields
 */	
/*
jQuery(function($) {
	$( ".sortable" ).sortable({
		start: function (event,ui) {
			var start_pos = ui.item.index();
			ui.item.data('start_pos', start_pos);	
		},
		change: function (event,ui) {
			var start_pos = ui.item.data('start_pos');
			var index = ui.placeholder.index();
			if (start_pos < index) {
				$('#sortable li:nth-child(' + index + ')').addClass('highlights');
			} else {
				$('#sortable li:eq(' + (index + 1) + ')').addClass('highlights');
			}		
		},
		update: function (event,ui) {
			$('#sortable li').removeClass('highlights');
			// potential db update //
			var place=1;
			var points=[60,50,45,40,35,30,28,26,24,22,20,18,16,14,12,10,9,8,7,6,5,4,3,2,1];
			var pointsCounter=0;
			var start_post=ui.item.data('start_pos');
			var end_pos = ui.item.index();

			// sort places & points //
			ui.item.parent().find('li').each(function() {
				if ($(this).hasClass('header')) {
					// skip header row //
				} else {
					$(this).find('span.place input').val(place);
					$(this).find('span.points input').val(points[pointsCounter]);
					
					place++;
					pointsCounter++;
				}
			});

			// sort points //
						
		}
	});
	
  $( ".sortable" ).disableSelection();

});
*/

jQuery(function($) {
	$( ".sortable-div" ).sortable({});
  $( ".sortable" ).disableSelection();
});