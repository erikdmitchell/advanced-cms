/**
 * version 1.0.2
 *
 * duplicates a single metabox field
 *
 * @since 1.1.8
 */
jQuery(document).ready(function($) {

	$(document).on('click','.ajaxmb-field-btn.duplicate',function() {
		var metaRow=$(this).parent().attr('id');
		var cloneID=metaRow;

		if ($(this).parent().data('parent-clone')) {
			cloneID=$(this).parent().data('parent-clone');
		}

		add_field(cloneID,metaRow);
	});

	$(document).on('click','.ajaxmb-field-btn.delete',function() {
		var id=$(this).parent().attr('id');
		var $this=$('#'+id);
		var $metabox=$this.parent();
		$('#'+id).remove();

		// run ajax to remove from options //
		var data={
			'action' : 'remove_duplicate_metabox_field',
			'metabox_id' : $metabox.find('#mdw-cms-metabox-id').val(),
			'config_key' : $metabox.find('#mdw-cms-config-key').val(),
			'post_id' : $metabox.find('#mdw-cms-post-id').val(),
			'field_type' : $this.data('field-type'),
			'field_label' : $this.find('label').text(),
			'field_id' : $this.find('input').attr('id')
		};

		$.post(ajaxurl, data, function(response) {
			//console.log(response);
		});
	});

});

/**
 * adds a new field to the specified meta box
 * clones the specified div
 * after cloning, it modifies the id and the class
**/
function add_field(cloneID,appendID) {
	$=jQuery.noConflict();

	var newID=0;

	$('.clone').each(function(i) {
		newID++;
	});

	var newFullID=cloneID+'-'+newID;
	//newID=check_clone_id(newID,mbID,cloneClass); // not used v1.1.8
	var $clonedElement=$('#'+cloneID).clone();
	var inputName=$clonedElement.find('input').attr('name');
	var inputID=$clonedElement.find('input').attr('id');
	var newInputID=inputID+'-'+newID;

	$clonedElement.attr('id',newFullID); // change id of row
	$clonedElement.find('input').attr('name',inputName+'-'+newID); // change input name
	$clonedElement.find('input').attr('id',newInputID); // change input id
	$clonedElement.addClass('clone'); // add classes
	$clonedElement.attr('data-parent-clone',cloneID); // add data parent
	$clonedElement.insertAfter('#'+appendID); // insert into form
	$('<button type="button" class="ajaxmb-field-btn delete">Delete Field</button>').insertAfter('#'+newInputID); // add delete btn

	var $metabox=$clonedElement.parent();

	// run ajax to add to options //
	var data={
		'action' : 'duplicate_metabox_field',
		'metabox_id' : $metabox.find('#mdw-cms-metabox-id').val(),
		'config_key' : $metabox.find('#mdw-cms-config-key').val(),
		'post_id' : $metabox.find('#mdw-cms-post-id').val(),
		'field_type' : $clonedElement.data('field-type'),
		'field_label' : $clonedElement.find('label').text(),
		'field_id' : $clonedElement.find('input').attr('id')
	};

	$.post(ajaxurl, data, function(response) {
		//console.log(response);
	});

}

function check_clone_id(cloneID,id,_class) {
	var newCloneID=cloneID;

	$(id+' '+_class).each(function() {
		var id=$(this).attr('id');
		if (newCloneID==id) {
			var idArr=id.split('-');
			var idNum=parseInt(idArr[idArr.length-1]);
			idArr[idArr.length-1]=idNum+1;
			newCloneID=idArr.join('-');
		}
	});

	return newCloneID;
}