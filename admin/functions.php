<?php
/**
 * pickle_cms_get_admin_page function.
 * 
 * @access public
 * @param bool $template_name (default: false)
 * @param mixed $attributes (default: null)
 * @return void
 */
function pickle_cms_get_admin_page($template_name=false, $attributes=null) {
	if (!$attributes )
		$attributes = array();

	if (!$template_name)
		return false;

	include(PICKLE_CMS_PATH.'admin/pages/'.$template_name.'.php');

	$html=ob_get_contents();

	if (ob_get_length()) ob_end_clean();

	return $html;
}

/**
 * is_pickle_cms_admin_page function.
 *
 * @access public
 * @return void
 */
function is_pickle_cms_admin_page() {
	if (isset($_GET['page']) && $_GET['page']=='pickle-cms')
		return true;

	return false;
}

/**
 * get_pickle_cms_admin_tab function.
 *
 * @access public
 * @return void
 */
function get_pickle_cms_admin_tab() {
	if (isset($_GET['page']) && $_GET['page']=='pickle-cms' && isset($_GET['tab']))
		return $_GET['tab'];

	return false;
}

function pickle_cms_setup_taxonomy_args() {
	global $pickle_cms_admin;

	$default_args=array(
		'btn_text' => 'Create',
		'name' => null,
		'label' => null,
		'object_type' => null,
		'hierarchical' => 1,
		'show_ui' => 1,
		'show_admin_col' => 1,
		'id' => -1,
		'header' => 'Add New Taxonomy',
	);

	// edit custom taxonomy //
	if (isset($_GET['id']) && $_GET['id']) :
		foreach ($pickle_cms_admin->options['taxonomies'] as $key => $taxonomy) :
			if ($taxonomy['name']==$_GET['id']) :
				$args=$taxonomy['args'];
				$args['name']=$taxonomy['name'];
				$args['object_type']=$taxonomy['object_type'];
				$args['id']=$key;
				$args['btn_text']='Update';
				$args['header']='Update Taxonomy';
			endif;
		endforeach;
	endif;

	$args=pickle_cms_parse_args($args, $default_args);

	return $args;
}

function pickle_cms_setup_metabox_args() {
	global $pickle_cms_admin;

	$default_args=array(
		'base_url' => admin_url('tools.php?page=pickle-cms&tab=metaboxes'),
		'btn_text' => 'Create',
		'mb_id' => '',
		'title' => '',
		'prefix' => '',
		'post_types' => '',
		'edit_class_v' => '',
		'fields' => array(),
		'header' => 'Add New Metabox',
	);

	// edit //
	if (isset($_GET['id']) && $_GET['id']) :
		foreach ($pickle_cms_admin->options['metaboxes'] as $metabox) :
			if ($metabox['mb_id']==$_GET['id']) :
				$args=$metabox;
				$args['header']='Edit Metabox';
				$args['btn_text']='Update';
			endif;
		endforeach;
	endif;

	$args=pickle_cms_parse_args($args, $default_args);

	return $args;
}

function pickle_cms_setup_post_type_args() {
	global $pickle_cms_admin;

	$default_args=array(
		'base_url' => admin_url('tools.php?page=pickle-cms&tab=post-types'),
		'btn_text' => 'Create',
		'name' => '',
		'label' => '',
		'singular_label' => '',
		'description' => '',
		'supports' => array(
			'title' => 1,
			'thumbnail' => 1,
			'editor' => 1,
			'revisions' => 1,
			'page_attributes' => 0,
			'excerpt' => 0,
			'comments' => 0,
		),
		'hierarchical' => 0,
		'id' => -1,
		'header' => 'Add New Custom Post Type',
		'icon' => 'dashicons-admin-post',
		'error_class' => '',
	);

	// edit custom post type //
	if (isset($_GET['slug']) && $_GET['slug']) :
		foreach ($pickle_cms_admin->options['post_types'] as $key => $post_type) :
			if ($post_type['name']==$_GET['slug']) :
				$args=$post_type;
				$args['header']='Edit Post Type';
				$args['btn_text']='Update';
				$args['id']=$key;
			endif;
		endforeach;
	endif;

	$args=pickle_cms_parse_args($args, $default_args);

	return $args;
}

function pickle_cms_setup_admin_columns_args() {
	global $pickle_cms_admin;

	$default_args=array(
		'base_url' => admin_url('tools.php?page=pickle-cms&tab=columns'),
		'btn_text' => 'Create',
		'name' => '',
		'label' => '',
		'singular_label' => '',
		'description' => '',
		'supports' => array(
			'title' => 1,
			'thumbnail' => 1,
			'editor' => 1,
			'revisions' => 1,
			'page_attributes' => 0,
			'excerpt' => 0,
			'comments' => 0,
		),
		'hierarchical' => 0,
		'id' => -1,
		'header' => 'Add New Admin Column',
		'icon' => 'dashicons-admin-post',
		'error_class' => '',
	);

	// edit custom post type //
	if (isset($_GET['slug']) && $_GET['slug']) :
		foreach ($pickle_cms_admin->options['post_types'] as $key => $post_type) :
			if ($post_type['name']==$_GET['slug']) :
				$args=$post_type;
				$args['header']='Edit Post Type';
				$args['btn_text']='Update';
				$args['id']=$key;
			endif;
		endforeach;
	endif;

	$args=pickle_cms_parse_args($args, $default_args);

	return $args;
}

function pickle_cms_get_admin_link($args='') {
	$default_args=array(
		'page' => 'pickle-cms',
	);
	$args=wp_parse_args($args, $default_args);

	$url=add_query_arg($args, admin_url('tools.php'));

	return $url;
}

function pickle_cms_admin_link($args='') {
	echo pickle_cms_get_admin_link($args);
}
?>