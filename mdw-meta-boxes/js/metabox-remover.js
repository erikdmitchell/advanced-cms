jQuery(document).ready(function($) {
	$('.remove-meta-box a').click(function(e) {
		e.preventDefault();

		var optionKey='-1'; // can't be 0 or false //
		var metaID=$(this).data('meta-id');
		var postID=$(this).data('post-id');

		for (var i in options) {
			if (options[i].post_id==postID) {
				optionKey=i;
			}		
		}

		if (optionKey=='-1') {
			return false;
		}	
		
		// do ajax stuff //
		var data={ 
			action:'remove-box' ,
			postID:postID,
			metaID:metaID,
			optionKey:optionKey
		};

		$.post(ajaxurl,data, function(response) {
			//console.log('ajax resp');
	  	//console.log(response);
	  	// reload page //
	 	});
	 	
	});
});