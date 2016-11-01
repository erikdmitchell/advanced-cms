<?php global $MDWMetaboxes; ?>

<div class="gallery-wrap">
	<div id="mdw-cms-gallery"><?php echo $MDWMetaboxes->get_gallery_images($value); ?></div>
	<input class="gallery-uploader button" name="<?php echo $atts['id']; ?>_button" id="<?php echo $atts['id']; ?>_button" value="Edit Gallery" />
	<input class="gallery-remove button" name="<?php echo $atts['id']; ?>_button" id="<?php echo $atts['id']; ?>_button" value="Remove Gallery" />
	<input class="gallery-ids" type="hidden" name="<?php echo $atts['id']; ?>" value="<?php echo $MDWMetaboxes->get_gallery_image_ids($value); ?>" />
</div>

<div class="gallery-settings">
	<label for="disable-bootstrap">Disable Bootstrap</label><input type="checkbox" id="disable-bootstrap" name="disable-bootstrap" value="" />
	<div class="description">
		If your theme has bootstrap, we recommend disabling our gallery bootstrap due to potential conflicts. We do not utilize, nor include all
		bootstrap styles and functionality, just what's need for the gallery.
	</div>
</div>