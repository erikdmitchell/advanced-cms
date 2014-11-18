jQuery(document).ready(function($) {

	$(document).on('click','.ajaxmb-field-btn.duplicate',function() {	// used v1.1.8
		var metaRow=$(this).parent().attr('id');
		var cloneID=metaRow;
		
		if ($(this).parent().data('parent-clone')) {
			cloneID=$(this).parent().data('parent-clone');
		}

		add_field(cloneID,metaRow);	
	});

	$(document).on('click','.ajaxmb-field-btn.delete',function() {
		var id=$(this).parent().attr('id');
		$('#'+id).remove();
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