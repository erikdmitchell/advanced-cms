<select name="<?php echo $atts['id']; ?>" id="<?php echo $atts['id']; ?>">
	<option>Select One</option>
	<?php if (isset($atts['options']) && is_array($atts['options'])) : ?>
		<?php foreach ($atts['options'] as $option) : ?>
			<option value="<?php echo $option['value']; ?>" <?php selected($value, $option['value']); ?>><?php echo $option['name']; ?></option>
		<?php endforeach; ?>
	<?php endif; ?>
</select>