/**
 * custom media uploader
 *
 * Version: 0.1.1
 * @since 2.1.5
 */
(function($) {

	$.fn.mdwCMScustomMediaUploader=function(options) {
		var opts=$.extend({
			$button : $('.mdw-cms-meta-box .gallery-uploader'),
			$removeButton : $('.mdw-cms-meta-box .gallery-remove'),
			$gallery : $('#mdw-cms-gallery'),
			$idsInput : false,
			ajaxAction : 'mdw_cms_gallery_update'
		}, options);

		var file_frame;

		opts.$button.on('click', function( event ) {
		  event.preventDefault();

			// we have a default class setup, so if no $idsInput is passed, we use the default
			if (typeof opts.$idsInput==='undefined' || !opts.$idsInput) {
				opts.$idsInput=$(this).parent().find('.gallery-ids');
			}

		  // If the media frame already exists, reopen it.
		  if ( file_frame ) {
		    file_frame.open();
		    return;
		  }

		  // Create the media frame.
		  file_frame = wp.media.frames.file_frame = wp.media({
			  //id
			  frame: 'post',
			  state: 'gallery-edit',
		    editing: true,
		    multiple: true  // Set to true to allow multiple files to be selected
		  });

			// fill frame with images //
			var selection = selection();
			file_frame = wp.media({
			    //id:         'my-frame',
			    frame:      'post',
			    state:      'gallery-edit',
			    //title:      wp.media.view.l10n.editGalleryTitle,
			    editing:    true,
			    multiple:   true,
			    selection:  selection
			});

		  // When an image/gallery is selected, run a callback.
		  file_frame.on( 'update', function() {
		    var controller=file_frame.states.get('gallery-edit');
		    var library=controller.get('library');
		    var ids=library.pluck('id');

				opts.$gallery.val(ids);

				var data={
					'action':opts.ajaxAction,
					'ids':ids
				}

		    $.post(ajaxurl, data, function (response) {
					var images=$.parseJSON(response);
					opts.$gallery.html(images);
					opts.$idsInput.val(ids);

				});
		  });

		  // Finally, open the modal
		  file_frame.open();

		  // Gets initial gallery-edit images. Function modified from wp.media.gallery.edit in wp-includes/js/media-editor.js.source.html
			function selection() {
//console.log(wp.media.view.settings.mdw_cms_gallery.shortcode);
		    var shortcode = wp.shortcode.next( 'gallery', wp.media.view.settings.mdw_cms_gallery.shortcode ); // potential variable
		    var defaultPostId = wp.media.gallery.defaults.id;
		    var attachments;
		    var selection;

		    // Bail if we didn't match the shortcode or all of the content.
		    if ( ! shortcode )
		        return;

		    // Ignore the rest of the match object.
		    shortcode = shortcode.shortcode;

		    if ( _.isUndefined( shortcode.get('id') ) && ! _.isUndefined( defaultPostId ) )
		        shortcode.set( 'id', defaultPostId );

		    attachments = wp.media.gallery.attachments( shortcode );
		    selection = new wp.media.model.Selection( attachments.models, {
		        props:    attachments.props.toJSON(),
		        multiple: true
		    });

		    selection.gallery = attachments.gallery;

		    // Fetch the query's attachments, and then break ties from the
		    // query to allow for sorting.
		    selection.more().done( function() {
		        // Break ties with the query.
		        selection.props.set({ query: false });
		        selection.unmirror();
		        selection.props.unset('orderby');
		    });

		    return selection;
			} // function [selection]

		});

		/**
		 * reomve a gallery
		 */
		opts.$removeButton.on('click',function(event) {
			event.preventDefault();

			// we have a default class setup, so if no $idsInput is passed, we use the default
			if (typeof opts.$idsInput==='undefined' || !opts.$idsInput) {
				opts.$idsInput=$(this).parent().find('.gallery-ids');
			}

			opts.$gallery.html('');
			opts.$idsInput.val('');

			return false;
		});

	};

})(jQuery);