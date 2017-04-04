jQuery(document).ready(function($) {

	$('form.custom-metabox').requiredFields();

	/**
	 * metabox id check
	 */
	$('.custom-metabox #mb_id').change(function() {
		if ($(this).metaboxIDcheck($(this), $(this).val())) {
			$('.custom-post-types #submit').prop('disabled',false);
		} else {
			$('.custom-post-types #submit').prop('disabled',true);
		}
	});

	/**
	 * delete metabox link click
	 */
	$('.advanced-cms-metaboxes .metaboxes-list td a .dashicons-trash').on('click',function(e) {
		e.preventDefault();

		var data={
			'action' : 'advanced_cms_get_metabox',
			'id' : $(this).data('id')
		};

		$.post(ajaxurl, data, function(response) {
			var response_data=$.parseJSON(response);
			var data={
				'label' : response_data.title,
				'id' : response_data.mb_id
			};
			var delete_data={
				'action' : 'advanced_cms_delete_metabox',
				'id' : response_data.mb_id
			}
			setupDialogBox(data, delete_data, 'metabox');
		});
	});

	/**
	 * display field data (options) on change
	 */
	$('.custom-metabox').on('change', '.add-fields .field-type', function(e) {	
		e.preventDefault();
	
		var elID=$(this).parents('.advanced-cms-fields-wrapper').attr('id');
		var data={
			'action' : 'metabox_change_field_type',
			'field' : $(this).val()
		};
	
		$.post(ajaxurl, data, function(response) {
			
			$('#' + elID).find('.field-options').html(''); // clear out options
			$('#' + elID).find('.field-options').html(response); // add new options	
		});
	});

	/**
	 * adds a new field to our metabox
	 */
	$('#add-field-btn').on('click', function(e) {	
		e.preventDefault();
		
		$(this).duplicateMetaboxField();
	});

	// remove a metabox field //
	$('.button.remove-field').live('click',function(e) {
console.log('remove mb field');		
		var elemID=$(this).data('id');
		
		$('#'+elemID).remove();
	});

	// adds a new option field to our metabox element TEST //
	$('.add-option-field-btn').on('click', function() {
		var wrapperID=$(this).parent().parent().attr('id');
		var $lastElement=$('#'+wrapperID+' .option-row:last');
		var $clonedElement=$lastElement.clone();
		var lastElementID=$lastElement.attr('id').replace('option-row-', '');
		var newID=parseInt(lastElementID) + 1;

		// clean up and configure our cloned element (classes, ids, etc)
		var cloneID='option-row-'+newID;

		$clonedElement.attr('id', cloneID);
		$clonedElement.insertAfter('#'+wrapperID+' #option-row-' + lastElementID);

		// change names //
		$clonedElement.find('.options-item').each(function() {
			var attrName=$(this).attr('name');
			var attrNewName=attrName.replace('[options][' + lastElementID + ']', '[options][' + newID + ']');

			$(this).attr('name', attrNewName);
			$(this).val(''); // clear values
		});


	});

});

/**
 * our sortable function for our metabox fields
 */
 /*
jQuery(function($) {
	$( ".sortable-div" ).sortable({
		start: function (event,ui) {
			var start_pos = ui.item.index();
			ui.item.data('start_pos', start_pos);
		},
		change: function (event,ui) {
			var start_pos = ui.item.data('start_pos');
			var index = ui.placeholder.index();
		},
		update: function (event,ui) {
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
*/

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
		var $fieldsWrapper=$('.advanced-cms-fields-wrapper');

		$fieldsWrapper.each(function() {
			lastFieldID=$(this).attr('id');
			lastFieldArr=lastFieldID.split('-');
			lastFieldIDNum=lastFieldArr.pop();
			newID=parseInt(lastFieldIDNum)+1;
		});

		var $clonedElement=$('#'+lastFieldID).clone(); // clone element

		// clean up and configure our cloned element (classes, ids, etc)
		var cloneID='advanced-cms-fields-wrapper-'+newID;

		$clonedElement.removeClass('default');
		$clonedElement.attr('id', cloneID);
		$clonedElement.insertAfter('#'+lastFieldID);

		// replace lastFieldIDNum with our new attribute name via new id //
		$('#'+cloneID+' .name-item').each(function() {
			var attrName=$(this).attr('name');
			var attrNewName=attrName.replace(lastFieldIDNum,newID);
			$(this).attr('name',attrNewName);
		});

		// clear all input values (reset) - except butons //
		$('#advanced-cms-fields-wrapper-'+newID).find('input').each(function() {
			if ($(this).attr('type')!='button') {
				$(this).val('');
			}
		});

		// clear drop down //
		$('#advanced-cms-fields-wrapper-'+newID).find('select').each(function() {
			$(this).val(0);
		});

		// hides any custom fields in our item cloned from //
		$('#advanced-cms-fields-wrapper-'+newID).find('.field-options').html('');

		var lastFieldOrder=parseInt($('#'+lastFieldID+' .order').val()); // last field order (as int) //

		$('#'+cloneID+' .order').val(lastFieldOrder+1); // set new field order //
		$('#'+cloneID+' .remove-field').attr('data-id','advanced-cms-fields-wrapper-'+newID); // set our button to remove field //
	};

}(jQuery));