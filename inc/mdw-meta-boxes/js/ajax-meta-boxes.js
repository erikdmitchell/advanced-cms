jQuery(document).ready(function($) {

	$('body').on('click','.ajaxmb-field-btn.add',function() {
		add_field('#ajax_meta_box_id .inside','ajaxmb-field-default-0','.ajaxmb-field');	
	});

	$('body').on('click','.ajaxmb-field-btn.update',function() {
		var id=$(this).parent().attr('id');
		
		var data={
			action:'ajaxmb-field',
			type:'update',
			post_id:$('#ajax_meta_box_id #post-id').val(),
			key:$('#'+id+' #ajaxmb-field-label').val(),
			value:$('#'+id+' #ajaxmb-field-value').val(),
			parent_id:$(this).parent().attr('id')
		};
		$.post(ajaxurl,data,function(response) {
	  	//console.log('response: '+response);
	  	location.reload();
		});	
	});

	$('body').on('click','.ajaxmb-field-btn.delete',function() {
		var id=$(this).parent().attr('id');
		
		var data={
			action:'ajaxmb-field',
			type:'delete',
			post_id:$('#ajax_meta_box_id #post-id').val(),
			key:$('#'+id+' label').text()
		};
		$.post(ajaxurl,data,function(response) {
	  	//console.log('response: '+response);
	  	location.reload();
		});	
	});
	
	var inputText='';

	$('body').on('focus','#ajaxmb-field-label',function() {
		inputText=$(this).val();
		$(this).val('');	
	});

	$('body').on('blur','#ajaxmb-field-label',function() {
		if ($(this).val()=='') {
			$(this).val(inputText);
		}
	});

	$('body').on('focus','.new #ajaxmb-field-value',function() {
		inputText=$(this).val();
		$(this).val('');	
	});

	$('body').on('blur','.new #ajaxmb-field-value',function() {
		if ($(this).val()=='') {
			$(this).val(inputText);
		}
	});
	
});

/**
 * adds a new field to the specified meta box
 * clones the specified div
 * after cloning, it modifies the id and the class
**/
function add_field(mbID,cloneID,cloneClass) {
	$=jQuery.noConflict();
	
	var newID=0;
	
	$(mbID+' '+cloneClass).each(function() {
		var id=$(this).attr('id');
		var idArr=id.split('-');
		var idNum=parseInt(idArr[idArr.length-1]);
		newID=idNum+1;
	});	
	
	newID=cloneID+'-'+newID;
	
	newID=check_clone_id(newID,mbID,cloneClass);
		
	$('#'+cloneID).clone().attr('id',newID).appendTo(mbID);
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