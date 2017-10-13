<?php

class PickleCMS_Admin_Columns extends PickleCMS_Admin {

	public function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'scripts_styles'));

		add_action('admin_init', array($this, 'update_admin_columns'));

		$this->options['admin_columns']=$this->get_option('pickle_cms_admin_columns', array());
	}

	public function scripts_styles($hook) {
		wp_enqueue_script('pickle-cms-admin-columns-script', PICKLE_CMS_ADMIN_URL.'js/admin-columns.js', array('jquery'), '0.1.0', true);	
	}
	
	public function update_admin_columns() {
		if (!isset($_POST['pickle_cms_admin']) || !wp_verify_nonce($_POST['pickle_cms_admin'], 'update_columns'))
			return;

		if (!isset($_POST['post_type']) || $_POST['post_type']=='0')
			return;
		
		if (!isset($_POST['metabox_taxonomy']) || $_POST['metabox_taxonomy']=='0')
			return;

		$admin_columns=get_option('pickle_cms_admin_columns');

		$arr=array(
			'post_type' => $_POST['post_type'],
			'metabox_taxonomy' => $_POST['metabox_taxonomy'],
		);

		if ($_POST['admin_column_id']!=-1) :
			$admin_columns[$_POST['admin_column_id']]=$arr;
		else :
			$admin_columns[]=$arr;
		endif;

		if (get_option('pickle_cms_admin_columns'))
			$option_exists=true;

		$this->options['admin_columns']=$admin_columns; // set var

		$update=update_option('pickle_cms_admin_columns', $admin_columns);

		if ($update) :
			$update=true;
		elseif ($option_exists) :
			$update=true;
		else :
			$update=false;
		endif;

		$url=$this->admin_url(array(
			'tab' => 'columns',
			'action' => 'update',
			//'id' => $data['name'],
			'updated' => $update,
			'edit' => 'columns'
		));

		wp_redirect($url);
		exit;
	}

}

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

function pickle_cms_setup_admin_columns_args() {
	global $pickle_cms_admin;

	$default_args=array(
		'base_url' => admin_url('tools.php?page=pickle-cms&tab=columns'),
		'btn_text' => 'Create',
		'post_type' => '',
		'metabox_taxonomy' => '',
		'id' => -1,
		'header' => 'Add New Admin Column',
	);

	// edit custom post type //
	if (isset($_GET['post_type']) && $_GET['post_type']) :
		foreach ($pickle_cms_admin->options['admin_columns'] as $key => $column) :
			if ($column['post_type']==$_GET['post_type'] && $column['metabox_taxonomy']==$_GET['metabox_taxonomy']) :
				$args=$column;
				$args['header']='Edit Admin Column';
				$args['btn_text']='Update';
				$args['id']=$key;
			endif;
		endforeach;
	endif;

	$args=pickle_cms_parse_args($args, $default_args);

	return $args;
}
?>