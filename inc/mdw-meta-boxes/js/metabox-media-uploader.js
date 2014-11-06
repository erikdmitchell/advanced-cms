jQuery(document).ready(function($){
  var _custom_media = true,
      _orig_send_attachment = wp.media.editor.send.attachment;

  $('.uploader.button').click(function(e) {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    var button = $(this);
    var id = button.attr('id').replace('_button', '');
    _custom_media = true;
    wp.media.editor.send.attachment = function(props, attachment){
      if ( _custom_media ) {
        $("#"+id).val(attachment.url);
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      };
    }

    wp.media.editor.open(button);
    return false;
  });

  $('.add_media').on('click', function(){
    _custom_media = false;
  });
  
  $('.umb-media-thumb a.remove').click(function(e) {
  	e.preventDefault();
  	var imgID=$(this).attr('data-type-img-id');
  	
  	$('#'+imgID).val('');
  	$('.umb-media-thumb img').hide();
  	$(this).hide();
  });
});		
