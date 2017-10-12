<?php

function ajax_pickle_cms_admin_col_change_post_type() {
	$taxonomies=pickle_cms_get_taxonomies($_POST['post_type']);
	$metaboxes=pickle_cms_get_metaboxes($_POST['post_type']);
	
	foreach ($metaboxes as $metabox) :
		$metabox_fields=array_merge($metabox_fields, pickle_cms_get_metabox_fields($metabox['mb_id']);
	endforeach;
	
	// build out drop down w/ separator //
		
	wp_die();
}
add_action('wp_ajax_pickle_cms_admin_col_change_post_type', 'ajax_pickle_cms_admin_col_change_post_type');

// taxonomies //
function pickle_cms_get_taxonomies($object_type='') {
	global $pickle_cms_admin;
	
	$taxonomies=array();
	$all_taxonomies=$pickle_cms_admin->options['taxonomies'];

	if (empty($object_type)) :
		$taxonomies=$all_taxonomies;
	else :	
		foreach ($all_taxonomies as $taxonomy) :	
			if (in_array($object_type, $taxonomy['object_type'])) :
				$taxonomies[]=$taxonomy;
			endif;
		endforeach;
	endif;
	
	return $taxonomies;
}

// metaboxes //
function pickle_cms_get_metaboxes($object_type='', $metabox_id='') {
	global $pickle_cms_admin;
	
	$fields=array();
	$metaboxes=array();
	$all_metaboxes=$pickle_cms_admin->options['metaboxes'];
	
	// get metaboxes //
	if (empty($metabox_id)) :
		$metaboxes=$all_metaboxes;
	else :
		foreach ($all_metaboxes as $metabox) :
			if ($metabox_id==$metabox['mb_id']) :
				$metaboxes[]=$metabox;
			endif;
		endforeach;	
	endif;
	
	// check object type and remove if not in //
	if (!empty($object_type)) :
		foreach ($metaboxes as $key => $metabox) :
			if (!in_array($object_type, $metabox['post_types'])) :
				unset($metaboxes[$key]);
			endif;
		endforeach;
	endif;
	
	return $metaboxes;
}

function pickle_cms_get_metabox_fields($metabox_id='') {
	global $pickle_cms_admin;

	$fields=array();
	$all_metaboxes=$pickle_cms_admin->options['metaboxes'];
	
	foreach ($all_metaboxes as $metabox) :
		if ($metabox_id==$metabox['mb_id']) :
			return $metabox['fields'];
		endif;
	endforeach;
	
	return $fields;
}
?>