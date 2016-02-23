<?php
global $mdw_cms_admin_pages,$mdw_cms_admin_page_hooks,$mdw_cms_options,$mdw_cms_wp_option_name,$mdw_cms_version,$mdw_cms_admin_url;

$mdw_cms_admin_pages=array();
$mdw_cms_admin_page_hooks=array();
$mdw_cms_wp_option_name='mdw_cms_options';
$mdw_cms_version='2.2.0';
$mdw_cms_options=get_option($mdw_cms_wp_option_name,array());
$mdw_cms_admin_url=admin_url('tools.php?page=mdw-cms');

// update cms version //
$stored_mdw_cms_version=get_option('mdw_cms_version','1.1.1');

if ($stored_mdw_cms_version<$mdw_cms_version) :
	mdw_cms_options_legacy_update();
	update_option('mdw_cms_version',$mdw_cms_version);
endif;


/**
 * mdw_cms_add_admin_page function.
 *
 * @access public
 * @param array $args (default: array())
 * @return void
 */
function mdw_cms_add_admin_page($args=array()) {
	global $mdw_cms_admin_pages,$mdw_cms_admin_page_hooks;

	$default_args=array(
		'id' => 'mdw-cms-default',
		'name' => 'Default',
		'function' => null,
		'order' => 99,
		'options' => array(),
	);
	$args=wp_parse_args($args,$default_args);

	$mdw_cms_admin_pages[$args['id']]=$args; // add to our global pages

	// setup hooks, generate name, add action and add to global var //
	$hookname='mdw_cms_admin_page-'.$args['id'];

	add_action($hookname,$args['function']);

	$mdw_cms_admin_page_hooks['hooks'][$hookname]=true;

	// add our options //
	mdw_cms_add_options($args['id'],$args['options']);
}

/**
 * mdw_cms_add_options function.
 *
 * @access public
 * @param bool $id (default: false)
 * @param array $options (default: array())
 * @return void
 */
function mdw_cms_add_options($id=false,$options=array()) {
	global $mdw_cms_options,$mdw_cms_wp_option_name;

	if (!$id || empty($options))
		return false;

	$mdw_cms_options=get_option($mdw_cms_wp_option_name); // get stored options

	// if we have option, replace recursively, else add //
	if (isset($mdw_cms_options[$id])) :
		array_replace_recursive($mdw_cms_options[$id],$options);
	else :
		$mdw_cms_options[$id]=$options;
	endif;
}

function mdw_cms_update_options() {
	global $mdw_cms_options;

}


/*
	function update_options() {
		$options=array();

		if (get_option($this->option_name)) :
			$options=array_replace_recursive($this->default_options,get_option($this->option_name)); // merge stored with default options
		else :
			$options=$this->default_options;
		endif;

		$options=array_replace_recursive($options,$_POST['theme_options']); // merger post (updated) options with previous options

		$updated=update_option($this->option_name,$options);

		$this->options=$this->setup_options(); // kind of like a refresh now the db is updated

		return $updated;
	}
*/

/*
	function update_options($options) {
		if (!$options['update'])
			return false;

		$new_options=$options;
		unset($new_options['update']); // a temp var passed, remove it

		update_option($this->wp_option,$new_options);

		return get_option($this->wp_option);
	}
*/

/**
 * mdw_cms_options_legacy_update function.
 *
 * @access public
 * @return void
 */
function mdw_cms_options_legacy_update() {
	global $mdw_cms_wp_option_name;

	$options=array();
	$post_types=get_option('mdw_cms_post_types');
	$taxonomies=get_option('mdw_cms_taxonomies');
	$metaboxes=get_option('mdw_cms_metaboxes');

	if ($post_types) :
		$options['post_types']=$post_types;
		delete_option('mdw_cms_post_types');
	endif;

	if ($taxonomies) :
		$options['taxonomies']=$taxonomies;
		delete_option('mdw_cms_taxonomies');
	endif;

	if ($metaboxes) :
		$options['metaboxes']=$metaboxes;
		delete_option('mdw_cms_metaboxes');
	endif;

	update_option($mdw_cms_wp_option_name,$options);
}

/**
 * mdw_cms_load_admin_page function.
 *
 * @access public
 * @param bool $page_name (default: false)
 * @param mixed $attributes (default: null)
 * @return void
 */
function mdw_cms_load_admin_page($page_name=false,$attributes=null) {
	if (!$page_name)
		return false;

	ob_start();

	do_action('mdw_cms_before_load_admin_page'.$page_name);

	include_once('pages/'.$page_name.'.php');

	do_action('mdw_cms_after_load_admin_page'.$page_name);

	$html=ob_get_contents();

	ob_end_clean();

	echo $html;
}

function mdw_cms_tab_url($tab_id='') {
	global $mdw_cms_admin_pages,$mdw_cms_admin_url;

	$tab_url=null;

	foreach ($mdw_cms_admin_pages as $page) :
		if ($tab_id==$page['id'])
			$tab_url=$mdw_cms_admin_url.'&tab='.$tab_id;
	endforeach;

	echo $tab_url;
}

/**
 * get_post_types_list function.
 *
 * @access public
 * @param bool $selected_pt (default: false)
 * @param string $output (default: 'checkbox')
 * @return void
 */
function get_post_types_list($selected_pt=false,$output='checkbox') {
	$html=null;
	$args=array(
		'public' => true
	);
	$post_types_arr=get_post_types($args);

	$label_class='col-md-3';
	$input_class='col-md-3';

	$html.='<div class="form-row row post-type-list-admin">';
		$html.='<label for="post_type" class="'.$label_class.'">Post Type</label>';
		$html.='<div class="post-types-cbs '.$input_class.'">';
			$counter=0;
			foreach ($post_types_arr as $type) :
				if ($counter==0) :
					$class='first';
				else :
					$class='';
				endif;

				if ($selected_pt && in_array($type,$selected_pt)) :
					$checked='checked=checked';
				else :
					$checked=null;
				endif;


				$html.='<div class="col-md-12">';
					$html.='<input type="checkbox" name="post_types[]" value="'.$type.'" '.$checked.'>'.$type.'<br />';
				$html.='</div>';

				$counter++;
			endforeach;
		$html.='</div>';
	$html.='</div>';

	return $html;
}
?>