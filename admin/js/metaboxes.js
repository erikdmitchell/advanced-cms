jQuery(document).ready(function($) {

	$('form.custom-metabox').requiredFields();

	$('.custom-metabox #mb_id').change(function() {
		if ($(this).metaboxIDcheck($(this), $(this).val())) {
			$('.custom-post-types #submit').prop('disabled',false);
		} else {
			$('.custom-post-types #submit').prop('disabled',true);
		}
	});

	$('.pickle-cms-metaboxes .metaboxes-list td a .dashicons-trash').on('click',function(e) {
		e.preventDefault();

		var data={
			'action' : 'pickle_cms_get_metabox',
			'id' : $(this).data('id')
		};

		$.post(ajaxurl, data, function(response) {
			var response_data=$.parseJSON(response);
			var data={
				'label' : response_data.title,
				'id' : response_data.mb_id
			};
			var delete_data={
				'action' : 'pickle_cms_delete_metabox',
				'id' : response_data.mb_id
			}
			setupDialogBox(data, delete_data, 'metabox');
		});
	});

	/**
	 * display field data (options) on change
	 // FIELDS //
	 */
	$('.custom-metabox').on('change', '.add-fields .type', function(e) {	
		e.preventDefault();
	
		var elID=$(this).parents('.pickle-cms-fields-wrapper').attr('id');
		var data={
			'action' : 'metabox_change_field_type',
			'field' : $(this).val(),
			'key' : $(this).parents('.pickle-cms-fields-wrapper').data('key'),
		};
	
		$.post(ajaxurl, data, function(response) {
			$('#' + elID).find('.field-options').html(''); // clear out options
			$('#' + elID).find('.field-options').html(response); // add new options	
		});
	});

	// FIELDS //
	$('#add-field-btn').on('click', function(e) {	
		e.preventDefault();
		
		var data={
			'action' : 'pickle_cms_add_meta_box_field'
		};
		
	// we need to check field id //
		$.post(ajaxurl, data, function(response) {			
			$('.add-fields').append(response);		
		});
	});

	// FIELDS //
	$('.custom-metabox').on('click', '.button.remove-field', function(e) {		
		var elemID=$(this).data('id');
		
		$('#'+elemID).remove();
	});

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