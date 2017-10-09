<?php global $advancedMetaboxes; ?>

<div class="gallery-wrap">
	<div id="advanced-cms-gallery"><?php echo $advancedMetaboxes->get_gallery_images($value); ?></div>
	<input class="gallery-uploader button" name="<?php echo $atts['id']; ?>_button" id="<?php echo $atts['id']; ?>_button" value="Edit Gallery" />
	<input class="gallery-remove button" name="<?php echo $atts['id']; ?>_button" id="<?php echo $atts['id']; ?>_button" value="Remove Gallery" />
	<input class="gallery-ids" type="hidden" name="<?php echo $atts['id']; ?>" value="<?php echo $advancedMetaboxes->get_gallery_image_ids($value); ?>" />
</div>