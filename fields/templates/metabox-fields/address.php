<?php global $advancedMetaboxes; ?>

<div class="address-wrap">
	<div class="line-1">
		<input type="text" class="" name="<?php echo $atts['id']; ?>[line1]" id="<?php echo $atts['id']; ?>_line1" value="<?php echo $value['line1']; ?>" />
	</div>
	<div class="line-2">
		<input type="text" class="" name="<?php echo $atts['id']; ?>[line2]" id="<?php echo $atts['id']; ?>_line2" value="<?php echo $value['line2']; ?>" />
	</div>
	<div class="city">
		<span>City</span><input type="text" class="" name="<?php echo $atts['id']; ?>[city]" id="<?php echo $atts['id']; ?>_city" value="<?php echo $value['city']; ?>" />
	</div>
	<div class="state">
		<span>State/Province</span><input type="text" class="" name="<?php echo $atts['id']; ?>[state]" id="<?php echo $atts['id']; ?>_state" value="<?php echo $value['state']; ?>" />
	</div>
	<div class="zip">
		<span>Postal Code</span><input type="text" class="" name="<?php echo $atts['id']; ?>[zip]" id="<?php echo $atts['id']; ?>_zip" value="<?php echo $value['zip']; ?>" />
	</div>
	<div class="country">
		<span>Country</span> <?php echo $advancedMetaboxes->get_countries_dropdown($atts['id'].'[country]', $value['country']); ?>
	</div>
</div>
