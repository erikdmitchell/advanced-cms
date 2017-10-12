<?php

function ajax_pickle_cms_admin_col_change_post_type() {
	echo pickle_cms_metabox_taxonomy_dropdown($_POST['post_type']);
		
	wp_die();
}
add_action('wp_ajax_pickle_cms_admin_col_change_post_type', 'ajax_pickle_cms_admin_col_change_post_type');

function pickle_cms_metabox_taxonomy_dropdown($post_type='', $selected='') {
	$html='';
	$metabox_fields=array();
	$taxonomies=pickle_cms_get_taxonomies($post_type);
	$metaboxes=pickle_cms_get_metaboxes($post_type);
	
	foreach ($metaboxes as $metabox) :
		$metabox_fields=array_merge($metabox_fields, pickle_cms_get_metabox_fields($metabox['mb_id']));
	endforeach;	
	
	$html.='<select name="metabox_taxonomy">';
		$html.='<option value="0">Select One</option>';
		
		if (!empty($taxonomies)) :
			$html.='<option value="0">Taxonomy:</option>';
			
			foreach ($taxonomies as $taxonomy) :
				$html.='<option value="'.$taxonomy['name'].'" '.selected($selected, $taxonomy['name'], false).'>&nbsp;&nbsp;'.$taxonomy['args']['label'].'</option>';
			endforeach;
		endif;

		if (!empty($metabox_fields)) :
			$html.='<option value="0">Metabox Fields:</option>';
			
			foreach ($metabox_fields as $metabox_field) :
				$html.='<option value="'.$metabox_field['id'].'" '.selected($selected, $metabox_field['id'], false).'>&nbsp;&nbsp;'.$metabox_field['title'].'</option>';
			endforeach;
		endif;
	
	$html.='</select>';	

	return $html;
}

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