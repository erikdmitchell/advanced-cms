<?php
class MDWCMSPostTypes {

	protected $tab_url=null;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));
		add_action('init',array($this,'add_page'));
		add_action('init',array($this,'create_post_types'));
		add_action('wp_ajax_confirm_delete_cpt',array($this,'ajax_confirm_delete_cpt'));
		add_action('wp_ajax_delete_cpt',array($this,'ajax_delete_cpt'));
		add_action('wp_ajax_update_cpt',array($this,'ajax_update_cpt'));
	}

	/**
	 * admin_scripts_styles function.
	 *
	 * @access public
	 * @param mixed $hook
	 * @return void
	 */
	public function admin_scripts_styles($hook) {
		wp_enqueue_script('thickbox');
		wp_enqueue_script('mdw-cms-admin-custom-post-types-script',plugins_url('/js/post-types.js',__FILE__),array('namecheck-script'));

		wp_enqueue_style('thickbox');
	}

	/**
	 * add_page function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_page() {
		mdw_cms_add_admin_page(array(
			'id' => 'post_types',
			'name' => 'Post Types',
			'function' => array($this,'admin_page'),
			'order' => 1
		));
	}

	/**
	 * admin_page function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_page() {
		mdw_cms_load_admin_page('post-types');
	}

	/**
	 * create_post_types function.
	 *
	 * @access public
	 * @return void
	 */
	public function create_post_types() {
		global $mdw_cms_options;

		// check we have post types //
		if (!isset($mdw_cms_options['post_types']) || empty($mdw_cms_options['post_types']))
			return false;

		// spit out our post types //
		foreach ($mdw_cms_options['post_types'] as $post_type) :
			$default_args=array(
				'supports' => array(),
				'taxonomies' => 'post_tag',
				'title' => false,
				'thumbnail' => false,
				'editor' => false,
				'revisions' => false,
				'page_attributes' => false,
				'hierarchical' => false,
			);
			$args=wp_parse_args($post_type,$default_args);

			extract($args);

			// check for custom 'args' //
			if ($title)
				$supports[]='title';

			if ($thumbnail)
				$supports[]='thumbnail';

			if ($editor)
				$supports[]='editor';

			if ($revisions)
				$supports[]='revisions';

			if ($page_attributes)
				$supports[]='page-attributes';

			register_post_type($post_type['name'],
				array(
					'labels' => array(
						'name' => _x($label,$label,$name),
						'singular_name' => _x($singular_label,$name),
						'add_new' => _x('Add New',$name),
						'add_new_item' => __('Add New '.$singular_label),
						'edit_item' => __('Edit '.$singular_label),
						'new_item' => __('New '.$singular_label),
						'all_items' => __('All '.$label),
						'view_item' => __('View '.$singular_label),
						'search_items' => __('Search '.$label),
						'not_found' =>  __('No '.$label.' found'),
						'not_found_in_trash' => __('No '.$label.' found in Trash'),
						'parent_item_colon' => '',
						'menu_name' => $label
					),
					'public' => true,
					'has_archive' => false,
					'show_in_menu' => true,
					'menu_position'=> 5,
					'supports' => $supports,
					'taxonomies' => array($taxonomies),
					'hierarchical' => $hierarchical
				)
			);

		endforeach;
	}

	/**
	 * ajax_delete_cpt function.
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_delete_cpt() {
		$html=null;

		if (isset($_GET['action']) && isset($_GET['id']) && isset($_GET['slug']) && $_GET['id']!=-1 && $_GET['action']=='delete_cpt') :
			$html.='<p>This will delete the '.$_GET['slug'].' post type. Are you sure?</p>';
			$html.='<input id="mdw_cms_delete_cpt_submit" class="button button-primary button-large" value="Delete" data-id='.$_GET['id'].'></input>';
		else :
			$html.='Error';
		endif;

    $html.='<a id="mdw_cms_delete_cpt_cancel" class="button button-large" href="#">Cancel</a>';

		echo $html;

		wp_die();
	}

	/**
	 * ajax_confirm_delete_cpt function.
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_confirm_delete_cpt() {
		global $mdw_cms_options;

		if (isset($_POST['id']) && isset($mdw_cms_options['post_types'][$_POST['id']])) :
			unset($mdw_cms_options['post_types'][$_POST['id']]);
			$mdw_cms_options['post_types']=array_values($mdw_cms_options['post_types']);

			mdw_cms_update_options();
		endif;

		wp_die();
	}

	/**
	 * ajax_update_cpt function.
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_update_cpt() {
		global $mdw_cms_options;

		$response=array();

		extract($_POST);

		if ($page_action=='add') : // NEED TO CHECK
			if ($slug=$this->add_custom_post_type($form_data)) :
				$response['notice']='<div class="updated">Post type "'.$slug.'" has been created.</div>';
			else :
				$response['notice']='<div class="error">There was an issue creating the post type "'.$slug.'</div>';
			endif;
		elseif ($page_action=='update') :
			$slug=mdw_cms_get_post_type_name($cpt_id);

			if ($this->update_custom_post_types()) :
				$response['notice']='<div class="updated">Post type "'.$slug.'" has been updated.</div>';
			else :
				$response['notice']='<div class="error">There was an issue updating the post type "'.$slug.'</div>';
			endif;
		endif;

		echo json_encode($response);

		wp_die();
	}

	protected function add_custom_post_type($data=array()) {
		$form_data_final=array();

		// make it a cleaner arra with a name => value setup //
		foreach ($data as $input) :
			$form_data_final[$input['name']]=$input['value'];
		endforeach;

		if ($this->update_custom_post_types($form_data_final))
			return $form_data_final['name']; // we may need to get the id

		return false;
	}

	/**
	 * update_custom_post_types function.
	 *
	 * @access protected
	 * @param array $data (default: array())
	 * @return void
	 */
	protected function update_custom_post_types($data=array()) {
		global $mdw_cms_options;

		$org_post_types_s=serialize($mdw_cms_options['post_types']);
		$post_types=$mdw_cms_options['post_types'];

		// get $_POST if not directly passed //
		if (empty($data) && isset($_POST['form_data'])) :
			foreach ($_POST['form_data'] as $arrays) :
				$data[$arrays['name']]=$arrays['value'];
			endforeach;
		endif;

		// check we have a name //
		if (!isset($data['name']) || $data['name']=='')
			return false;

			// build array for storing //
		$arr=array(
			'name' => $data['name'],
			'label' => $data['label'],
			'singular_label' => $data['singular_label'],
			'description' => $data['description'],
			'title' => $data['title'],
			'thumbnail' => $data['thumbnail'],
			'editor' => $data['editor'],
			'revisions' => $data['revisions'],
			'excerpt' => $data['excerpt'],
			'hierarchical' => $data['hierarchical'],
			'page_attributes' => $data['page_attributes']
		);

		if ($data['cpt-id']!=-1) :
			// if we change the name, clean up the db //
			if ($data['name']!=$data['cpt-prev-name']) :
				$this->update_cpt_name($data['cpt-prev-name'],$data['name']);
			endif;

			$post_types[$data['cpt-id']]=$arr;
		else :
			if (!empty($post_types)) :
				// we do a name check and return false if already found //
				foreach ($post_types as $cpt) :
					if ($cpt['name']==$data['name'])
						return false;
				endforeach;
			endif;
			$post_types[]=$arr;
		endif;

		// we are simply updating the same info -- force true //
		if ($org_post_types_s==serialize($post_types))
			return true;

		// update global var, then store via function //
		$mdw_cms_options['post_types']=$post_types;
		mdw_cms_update_options();

		return true;
	}

	/**
	 * update_cpt_name function.
	 *
	 * @access protected
	 * @param bool $old (default: false)
	 * @param bool $new (default: false)
	 * @return void
	 */
	protected function update_cpt_name($old=false,$new=false) {
		global $wpdb;

		if (!$old || !$new)
			return false;

		$field='post_type';

		$sql="
			UPDATE ".$wpdb->prefix."posts
			SET $field = REPLACE($field,'$old','$new')
			WHERE $field LIKE '%$old%'
		";

		$wpdb->get_results($sql);

		return true;
	}

}

new MDWCMSPostTypes();

/**
 * mdw_cms_post_types_submit_button function.
 *
 * @access public
 * @param float $id (default: -1)
 * @return void
 */
function mdw_cms_post_types_submit_button($id=-1) {
	if ($id!=-1) :
		$html='<input type="button" name="add-cpt" id="submit" class="button button-primary submit-button" value="Update" data-type="cpt" data-tab-url="'.admin_url('tools.php?page=mdw-cms&tab=post_types').'" data-page-action="update" data-action="update_cpt" data-item-type="cpt">';
	else :
		$html='<input type="button" name="add-cpt" id="submit" class="button button-primary submit-button" value="Create" disabled data-type="cpt" data-tab-url="'.admin_url('tools.php?page=mdw-cms&tab=post_types').'" data-page-action="add" data-action="update_cpt" data-item-type="cpt">';
	endif;

	echo $html;
}

/**
 * mdw_cms_get_post_type_id function.
 *
 * @access public
 * @param float $id (default: -1)
 * @return void
 */
function mdw_cms_get_post_type_id($id=-1) {
	if (isset($_GET['id']) && isset($_GET['action']))
		$id=$_GET['id'];

	return apply_filters('mdw_cms_admin_post_type_id',$id);
}

/**
 * mdw_cms_setup_post_type_page_values function.
 *
 * @access public
 * @return void
 */
function mdw_cms_setup_post_type_page_values() {
	global $mdw_cms_options;

	$id=mdw_cms_get_post_type_id();
	$default_args=array(
		'id' => $id,
		'name' => null,
		'label' => null,
		'singular_label' => null,
		'description' => null,
		'title' => 1,
		'thumbnail' => 1,
		'editor' => 1,
		'revisions' => 1,
		'excerpt' => 0,
		'hierarchical' => 0,
		'page_attributes' => 0
	);
	$post_type_args=array();

	// load cpt args if we have one //
	if ($id!=-1 && isset($mdw_cms_options['post_types'][$id]))
		$post_type_args=$mdw_cms_options['post_types'][$id];

	$args=wp_parse_args($post_type_args,$default_args);

	return $args;
}

/**
 * mdw_cms_existing_post_types function.
 *
 * @access public
 * @return void
 */
function mdw_cms_existing_post_types() {
	global $mdw_cms_options;

	if (isset($mdw_cms_options['post_types'])) :
		foreach ($mdw_cms_options['post_types'] as $key => $cpt) :
			echo '<div id="cpt-list-'.$key.'" class="cpt-row row mdw-cms-edit-delete-list">';
				echo '<span class="cpt">'.$cpt['label'].'</span>';
				echo '<span class="edit">[<a href="'.mdw_cms_tab_url('post_types',array('action' => 'edit', 'slug' => $cpt['name'], 'id' => $key),false).'">Edit</a>]</span>';
				echo '<span class="delete">[<a href="" data-slug="'.$cpt['name'].'" data-id="'.$key.'>">Delete</a>]</span>';
			echo '</div>';
		endforeach;
	else :
		echo 'No post types yet.';
	endif;
}

function mdw_cms_get_post_type_name($id=-1) {
	global $mdw_cms_options;

	if (isset($mdw_cms_options['post_types'][$id]))
		return $mdw_cms_options['post_types'][$id]['name'];

	return false;
}
?>