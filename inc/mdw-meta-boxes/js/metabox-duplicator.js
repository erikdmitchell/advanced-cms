//console.log(options);
jQuery(document).ready(function($) {
	$('.dup-meta-box a').click(function(e) {
		e.preventDefault();

		var metaID=$(this).data('meta-id');
		
		for (var i in options) {
			if (options[i].metaboxID==metaID) {
				var optionsKey=options[i];
			}		
		}
		
		var parentID=$('#'+optionsKey.metaboxID).parent().attr('id');
		var boxCounter=0;

		
		$('.'+optionsKey.metaboxClass).each(function() {
			boxCounter++;
		});

		// clone metbox, append to parent box, adjust id and id/name of input elements
		var newID='#'+optionsKey.metaboxID+'-'+boxCounter;
		var newIDraw=optionsKey.metaboxID+'-'+boxCounter;
		var fields=new Array();
		var id, name, type, label, strippedName;
		var fieldsObj={};
		
		$('#'+optionsKey.metaboxID).clone()
			.insertAfter('#'+parentID)
			.attr('id',newID)
			.find('.meta-row').each(function() {
				id=$(this).find('input').attr('id');
				name=$(this).find('input').attr('name');
				type='text';
				label=$(this).find('label').text();

				if (typeof id==='undefined') {
					id=$(this).find('textarea').attr('id');
					name=$(this).find('textarea').attr('name');
					type='textarea';

					$(this).find('textarea').prop({
						'id' : id+'-'+boxCounter,
						'name' : name+'-'+boxCounter				
					});
				}	else {
					$(this).find('input').prop({
						'id' : id+'-'+boxCounter,
						'name' : name+'-'+boxCounter					
					});
				}
				strippedName=name.replace(optionsKey.metaboxPrefix+'_',''); // strip _
				strippedName=strippedName+'-'+boxCounter; // add -ID

				// build our field objects //
				fieldsObj[strippedName]={
					type : type,
					label : label
				};			
			});

		// do ajax stuff //
		var data={ 
			action:'dup-box' ,
			postID:options.postID,
			//nonce:options.nonce,
			id:newIDraw,
			title:optionsKey.metaboxTitle,
			prefix:optionsKey.metaboxPrefix,
			post_types:optionsKey.metaboxPostTypes,
			fields:fieldsObj
		};
		//data[options.nonce]='mdw-cms/inc/mdw-meta-boxes/mdwmb-plugin.php'; // append nonce to object

		$.post(ajaxurl,data, function(response) {
			console.log('ajax resp');
	  	console.log(response);
	  	// reload page //
	 	});
	 	
	});
});