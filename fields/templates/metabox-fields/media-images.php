<?php global $advancedMetaboxes; ?>

<?php $images=$advancedMetaboxes->get_all_media_images(); ?>

<select multiple size="10" name="'.$args['id'].'[]" id="'.$args['id'].'">

	<?php foreach ($images as $image) : ?>
		<?php 
		if (is_array($value_arr) && !empty($value_arr) && in_array($image->ID,$value_arr)) :
			$selected='selected="selected"';
		else :
			$selected=null;
		endif;
		?>
		
		<option value="<?php echo $image->ID; ?>" <?php echo $selected; ?>><?php echo $image->post_title; ?></option>
	<?php endforeach; ?>

</select>