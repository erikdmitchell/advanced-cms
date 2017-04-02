<?php
function acms_change_field_type() {
	global $advancedMetaboxes;
	
	$options=apply_filters('load_field_options_'.$_POST['field'], array());

	echo $options;

	wp_die();
}
add_action('wp_ajax_metabox_change_field_type', 'acms_change_field_type');
?>