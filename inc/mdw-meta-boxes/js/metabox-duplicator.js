console.log(options);
jQuery(document).ready(function($) {
	$('.dup-meta-box a').click(function(e) {
		e.preventDefault();
		var parentID=$('#'+options.metaboxID).parent().attr('id');
		var boxCounter=0;
		
		$('.'+options.metaboxClass).each(function() {
			boxCounter++;
		});

		// clone metbox, append to parent box, adjust id and id/name of input elements
		var newID='#'+options.metaboxID+'-'+boxCounter;
		var newIDraw=options.metaboxID+'-'+boxCounter;
		
		$('#'+options.metaboxID).clone()
			.insertAfter('#'+parentID)
			.attr('id',newID)
			.find('.meta-row').each(function() {
				var id=$(this).find('input').attr('id');
				var name=$(this).find('input').attr('name');

				if (typeof id==='undefined') {
					id=$(this).find('textarea').attr('id');
					name=$(this).find('textarea').attr('name');

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
			});
			
		// do ajax stuff //
		var data={ 
			action:'dup-box' ,
			id:newIDraw,
			title:options.metaboxTitle,
			prefix:options.metaboxPrefix,
			post_types:options.metaboxPostTypes
		};
		$.post(ajaxurl,data, function(response) {
			console.log('ajax resp');
	  	console.log(response);
	 	});
	 	
	});
});