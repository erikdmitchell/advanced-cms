<?php global $advancedMetaboxes, $post; ?>

<input id="<?php echo $atts['id']; ?>" class="uploader-input regular-text" type="text" name="<?php echo $atts['id']; ?>" value="<?php echo $value; ?>" />
<input class="uploader button" name="<?php echo $atts['id']; ?>_button" id="<?php echo $atts['id']; ?>_button" value="Upload" />
<input type="hidden" name="_name" value="<?php echo $atts['id']; ?>" />

<?php if (!$atts['description_visible']) : ?>
	<div class="description field_description"><?php echo $atts['description']; ?></div>
<?php endif; ?>

<?php if ($value) : ?>
	<?php 
	$attr=array('src' => $value);
	$thumbnail=get_the_post_thumbnail($post->ID, 'thumbnail', $attr);
	$attachment_id=$advancedMetaboxes->get_attachment_id_from_url($value);
	?>
	
	<div class="advanced-cms-meta-box-thumb">
		<?php if (get_post_mime_type($attachment_id)!='image') : ?>
			<?php echo wp_get_attachment_image($attachment_id,'thumbnail',true); ?>
		<?php else : ?>
			<?php echo $thumbnail; ?>
		<?php endif; ?>
		<p><a class="remove" data-type-img-id="<?php echo $atts['id']; ?>" href="#">Remove</a></p>
	</div>
<?php endif; ?>