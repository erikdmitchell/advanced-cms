<?php
class adminCPT {

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
		wp_enqueue_script('mdw-cms-admin-custom-post-types-script',plugins_url('/js/post-types.js',__FILE__),array('namecheck-script'));
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
	 * ajax_update_cpt function.
	 *
	 * edit/delete custom post type
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_update_cpt() {
		global $mdw_cms_options;

		$post_types=$mdw_cms_options['post_types'];
		$response=array();

		extract($_POST);
		if ($page_action=='edit') :
			add_filter('mdw_cms_admin_post_type_id',function($_id) { return $id; });
		elseif ($page_action=='delete') : // remove post type and update option //
			unset($post_types[$id]);
			$post_types=array_values($post_types);
			update_option($this->wp_option,$post_types);

			$response=true; // reload page //
		elseif ($page_action=='add') :
			$form_data_final=array();

			foreach ($form_data as $input) :
				$form_data_final[$input['name']]=$input['value'];
			endforeach;

			if ($this->update_custom_post_types($form_data_final)) :
				$post_types=get_option($this->wp_option);
				foreach ($post_types as $key => $post_type) :
					if ($post_type['name']==$form_data_final['name']) :
						$id=$key;
						$slug=$post_type['name'];
					endif;
				endforeach;

				// b/c we do a page reload, this hides some vars so that we can load with the edit screen up //
				$response['id']=$id;
				$response['notice']=urlencode('<div class="updated">Post type "'.$slug.'" has been created.</div>');
			else :
				$response['content']=$this->admin_page_core();
				$response['notice']='<div class="error">There was an issue creating the post type "'.$slug.'</div>';
			endif;
		elseif ($page_action=='update') :
			$form_data_final=array();

			foreach ($form_data as $input) :
				$form_data_final[$input['name']]=$input['value'];
			endforeach;

			$id=$form_data_final['cpt-id'];
			$slug=$form_data_final['name'];

			if ($this->update_custom_post_types($form_data_final)) :
				$response['content']=$this->admin_page_core($id);
				$response['notice']='<div class="updated">Post type "'.$slug.'" has been updated.</div>';
			else :
				$response['content']=$this->admin_page_core($id);
				$response['notice']='<div class="error">There was an issue updating the post type "'.$slug.'</div>';
			endif;
		endif;

		echo json_encode($response);

		wp_die();
	}

	/**
	 * update_custom_post_types function.
	 *
	 * @access protected
	 * @param array $data (default: array())
	 * @return void
	 */
	protected function update_custom_post_types($data=array()) {
		$post_types=get_option($this->wp_option);
		$post_types_s=serialize($post_types);

		if (!isset($data['name']) || $data['name']=='')
			return false;

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
				self::update_cpt_name($data['cpt-prev-name'],$data['name']);
			endif;

			$post_types[$data['cpt-id']]=$arr;
		else :
			if (!empty($post_types)) :
				foreach ($post_types as $cpt) :
					if ($cpt['name']==$data['name'])
						return false;
				endforeach;
			endif;
			$post_types[]=$arr;
		endif;

		// we are simply updating the same info -- force true //
		if ($post_types_s==serialize($post_types))
			return true;

		return update_option($this->wp_option,$post_types);
	}

	/**
	 * update_cpt_name function.
	 *
	 * @access protected
	 * @static
	 * @param bool $old (default: false)
	 * @param bool $new (default: false)
	 * @return void
	 */
	protected static function update_cpt_name($old=false,$new=false) {
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

new adminCPT();

function mdw_cms_post_types_submit_button($id=-1) {
	if ($id!=-1) :
		$html='<input type="button" name="add-cpt" id="submit" class="button button-primary submit-button" value="Update" data-type="cpt" data-tab-url="'.admin_url('tools.php?page=mdw-cms&tab=post_types').'" data-page-action="update" data-action="update_cpt" data-item-type="cpt">';
	else :
		$html='<input type="button" name="add-cpt" id="submit" class="button button-primary submit-button" value="Create" disabled data-type="cpt" data-tab-url="'.admin_url('tools.php?page=mdw-cms&tab=post_types').'" data-page-action="add" data-action="update_cpt" data-item-type="cpt">';
	endif;

	echo $html;
}

function mdw_cms_get_post_type_id($id=-1) {
	if (isset($_GET['id']) && isset($_GET['action']))
		$id=$_GET['id'];

	return apply_filters('mdw_cms_admin_post_type_id',$id);
}
?>