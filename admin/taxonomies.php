<?php
class adminTax {

	public function __construct() {
		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));
		add_action('init',array($this,'add_page'));
	}

	public function admin_scripts_styles($hook) {
		wp_register_script('mdw-cms-admin-custom-taxonomies-script',plugins_url('/js/admin-custom-taxonomies.js',__FILE__),array('namecheck-script'));

		$post_types=get_post_types();
		$types=array();
		foreach ($post_types as $post_type) :
			$types[]=$post_type;
		endforeach;

		$taxonomy_options=array(
			'reservedPostTypes' => $types
		);

		wp_localize_script('mdw-cms-admin-custom-taxonomies-script','wp_options',$taxonomy_options);

		wp_enqueue_script('mdw-cms-admin-custom-taxonomies-script');
	}

	public function add_page() {
		mdw_cms_add_admin_page(array(
			'id' => 'taxonomies',
			'name' => 'Taxonomies',
			'function' => array($this,'admin_page'),
			'order' => 2
		));
	}

	public function admin_page() {
		mdw_cms_load_admin_page('taxonomies');
	}
	/*
	protected function update_options() {
		$notices=null;

		// create custom taxonomy //
		if (isset($_POST['add-tax']) && $_POST['add-tax']=='Create') :
			if ($this->update_taxonomies($_POST)) :
				$notices='<div class="updated">Taxonomy has been created.</div>';
			else :
				$notices='<div class="error">There was an issue creating the taxonomy.</div>';
			endif;
		endif;

		// update taxonomy //
		if (isset($_POST['add-tax']) && $_POST['add-tax']=='Update') :
			if ($this->update_taxonomies($_POST)) :
				$notices='<div class="updated">Taxonomy has been updated.</div>';
			else :
				$notices='<div class="error">There was an issue updating the taxonomy.</div>';
			endif;
		endif;

		// remove taxonomy //
		if (isset($_GET['delete']) && $_GET['delete']=='tax') :
			foreach ($this->options as $key => $tax) :
				if ($tax['name']==$_GET['slug']) :
					unset($this->options[$key]);
					$notices='<div class="updated">Taxonomy has been removed.</div>';
				endif;
			endforeach;

			$taxonomies=array_values($this->options);

			update_option('mdw_cms_taxonomies',$taxonomies);
		endif;

		$this->options=get_option($this->wp_option); // reload our options

		return $notices;
	}
	*/
	/**
	 * update_taxonomies function.
	 *
	 * @access public
	 * @param array $data (default: array())
	 * @return void
	 */
	/*
	public function update_taxonomies($data=array()) {
		$option_exists=false;
		$taxonomies=get_option($this->wp_option);

		if (!isset($data['name']) || $data['name']=='')
			return false;

		if (!isset($data['post_types']))
			$data['post_types']=array(
				'post'
			);

		$arr=array(
			'name' => $data['name'],
			'object_type' => $data['post_types'],
			'args' => array(
				'hierarchical' => true,
				'label' => $data['label'],
				'query_var' => true,
				'rewrite' => true
			)
		);

		if ($data['tax-id']!=-1) :
			$taxonomies[$data['tax-id']]=$arr;
		else :
			if (!empty($taxonomies)) :
				foreach ($taxonomies as $tax) :
					if ($tax['name']==$data['name'])
						return false;
				endforeach;
			endif;
			$taxonomies[]=$arr;
		endif;

		if (get_option($this->wp_option))
			$option_exists=true;

		$update=update_option($this->wp_option,$taxonomies);

		if ($update || $option_exists) :
			return true;
		else :
			return false;
		endif;
	}
	*/

}

new adminTax();


function mdw_cms_taxonomies_submit_button($id=-1) {
	if ($id!=-1) :
		$html='<input type="button" name="add-tax" id="submit" class="button button-primary submit-button" value="Update">';
	else :
		$html='<input type="button" name="add-tax" id="submit" class="button button-primary submit-button" value="Create">';
	endif;

	echo $html;
}

function mdw_cms_get_taxonomy_id($id=-1) {
	if (isset($_GET['edit']) && $_GET['edit']=='tax')
		$id=$_GET['id'];

	return apply_filters('mdw_cms_admin_taxonomy_id',$id);
}

function mdw_cms_setup_taxonomy_page_values() {
	global $mdw_cms_options;

	$id=mdw_cms_get_taxonomy_id();
	$default_args=array(
		'id' => $id,
		'name' => null,
		'object_type' => array(),
		'args' => array(
			'hierarchical' => 1,
			'label' => null,
			'query_var' => 1,
			'rewrite' => 1,
			'show_ui' => 1,
			'show_admin_col' => 1,
		),
	);
	$tax_args=array();

	// load tax args if we have one //
	if ($id!=-1 && isset($mdw_cms_options['taxonomies'][$id]))
		$tax_args=$mdw_cms_options['taxonomies'][$id];

	$args=wp_parse_args($tax_args,$default_args);

	return $args;
}

function mdw_cms_get_existing_taxonomies() {
	global $mdw_cms_options;

	if (isset($mdw_cms_options['taxonomies']) && !empty($mdw_cms_options['taxonomies'])) :
		foreach ($mdw_cms_options['taxonomies'] as $key => $tax) :
			echo '<div class="tax-row mdw-cms-edit-delete-list">';
				echo '<span class="tax">'.$tax['args']['label'].'</span><span class="edit">[<a href="'.mdw_cms_tab_url('taxonomies',array('edit' => 'tax', 'slug' => $tax['name'], 'id' => $key),false).'">Edit</a>]</span><span class="delete">[<a href="'.mdw_cms_tab_url('taxonomies',array('delete' => 'tax', 'slug' => $tax['name']),false).'">Delete</a>]</span>';
			echo '</div>';
		endforeach;
	endif;
}
?>