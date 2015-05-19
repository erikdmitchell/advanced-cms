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
	$j('#add-field-btn').on('click', function() {
		$j(this).duplicateMetaboxField();
	});

	// remove a metabox field //
	$j('.button.remove-field').live('click',function(e) {
		var elemID=$j(this).data('id');
		$j('#'+elemID).remove();
	});

	// adds a new option field to our metabox element //
	$j('.add-option-field-btn').on('click', function() {
		var newID=0;
		var lastFieldID='';
		var wrapperID=$j(this).parent().parent().attr('id');
		var $clonedElement=$j('#'+wrapperID+' #option-row-default').clone();

		$j('#'+wrapperID+' .option-row').each(function(i) {
			newID=i+1;
			lastFieldID=$j(this).attr('id');
		});

		// clean up and configure our cloned element (classes, ids, etc)
		var cloneID='option-row-'+newID;

		$clonedElement.removeClass('default');
		$clonedElement.attr('id',cloneID);
		$clonedElement.insertAfter('#'+wrapperID+' #'+lastFieldID);

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
jQuery(function($) {
	$( ".sortable-div" ).sortable({
		start: function (event,ui) {
			var start_pos = ui.item.index();
			ui.item.data('start_pos', start_pos);
		},
		change: function (event,ui) {
			var start_pos = ui.item.data('start_pos');
			var index = ui.placeholder.index();
/*
			if (start_pos < index) {
				$('#sortable li:nth-child(' + index + ')').addClass('highlights');
			} else {
				$('#sortable li:eq(' + (index + 1) + ')').addClass('highlights');
			}
*/
		},
		update: function (event,ui) {
			//$('#sortable li').removeClass('highlights');

			// update information //
			var order=1;
			var start_post=ui.item.data('start_pos');
			var end_pos = ui.item.index();

			ui.item.parent().find('.sortable').each(function() {
				$(this).find('.order').val(order);
				order++;
			});

			// sort points //

		}
	});

  $( ".sortable" ).disableSelection();

});

jQuery(function($) {
	//$( ".sortable-div" ).sortable({});
  //$( ".sortable" ).disableSelection();
});


/**
 * add new metabox field
 * @version: 1.0.0
 * @since
 */
(function($) {

	$.fn.duplicateMetaboxField=function(callback) {

		var newID=0;
		var lastFieldID;
		var lastFieldArr;
		var lastFieldIDNum=0;
		var $fieldsWrapper=$('.fields-wrapper');

		$fieldsWrapper.each(function() {
			lastFieldID=$(this).attr('id');
			lastFieldArr=lastFieldID.split('-');
			lastFieldIDNum=lastFieldArr.pop();
			newID=parseInt(lastFieldIDNum)+1;
		});



console.log('last id: '+lastFieldID);
console.log('last num: '+lastFieldIDNum);
console.log('new id: '+newID);


		var $clonedElement=$('#'+lastFieldID).clone(); // clone element

		// clean up and configure our cloned element (classes, ids, etc)
		var cloneID='fields-wrapper-'+newID;

		$clonedElement.removeClass('default');
		$clonedElement.attr('id',cloneID);
		$clonedElement.insertAfter('#'+lastFieldID);

		// get the id number of the last element in arr for our find and replace //
		//var lastFieldArr=options.lastFieldID.split('-');
		//var lastFieldIDNum=lastFieldArr.pop();

		// replace lastFieldIDNum with our new attribute name via new id //
		$('#'+cloneID+' .name-item').each(function() {
			var attrName=$(this).attr('name');
			var attrNewName=attrName.replace(lastFieldIDNum,newID);
			$(this).attr('name',attrNewName);
		});

		// clear all input values (reset) - except butons //
		$('#fields-wrapper-'+newID).find('input').each(function() {
			if ($(this).attr('type')!='button') {
				$(this).val('');
			}
		});

		// clear drop down //
		$('#fields-wrapper-'+newID).find('select').each(function() {
			$(this).val(0);
		});

		var lastFieldOrder=parseInt($('#'+lastFieldID+' .order').val()); // last field order (as int) //

		$('#'+cloneID+' .order').val(lastFieldOrder+1); // set new field order //
		$('#'+cloneID+' .remove-field').attr('data-id','fields-wrapper-'+newID); // set our button to remove field //
	};

}(jQuery));