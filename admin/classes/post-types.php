<?php
class PickleCMS_Admin_Component_Post_Types extends PickleCMS_Admin_Component {
	
	public function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'scripts_styles'));
		add_action('admin_init', array($this, 'update_post_types'));
		
		add_action('wp_ajax_pickle_cms_get_post_type', array($this, 'ajax_get_post_type'));
		add_action('wp_ajax_pickle_cms_delete_post_type', array($this, 'ajax_delete_post_type'));
		
		$this->slug='post-types';
		$this->name='Post Types';
		$this->items=$this->get_option('pickle_cms_post_types', array());
		
		// do not delete!
    	parent::__construct();		
	}

	public function scripts_styles($hook) {
		wp_enqueue_script('pickle-cms-admin-post-types', PICKLE_CMS_ADMIN_URL.'js/post-types.js', array('jquery-ui-dialog'), '0.1.0');
	}

	public function update() {
		if (!isset($_POST['pickle_cms_admin']) || !wp_verify_nonce($_POST['pickle_cms_admin'], 'update_cpts'))
			return false;

		$data=$_POST;
		$post_types=get_option('pickle_cms_post_types');
		$post_types_s=serialize($post_types);

		if (!isset($data['name']) || $data['name']=='')
			return false;

		$arr=array(
			'name' => $data['name'],
			'label' => $data['label'],
			'singular_label' => $data['singular_label'],
			'description' => $data['description'],
			'supports' => array(
				'title' => $data['supports']['title'],
				'thumbnail' => $data['supports']['thumbnail'],
				'editor' => $data['supports']['editor'],
				'revisions' => $data['supports']['revisions'],
				'page_attributes' => $data['supports']['page_attributes'],
				'excerpt' => $data['supports']['excerpt'],
				'comments' => $data['supports']['comments'],
			),
			'hierarchical' => $data['hierarchical'],
			'icon' => $data['icon'],
		);
		$url=$this->admin_url(array(
			'tab' => 'post-types',
			'action' => 'update',
			'edit' => 'cpt',
			'slug' => $data['name'],
			'updated' => 1
		));		
		
		if ($data['cpt-id']!=-1) :
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
		if ($post_types_s==serialize($post_types)) :
			wp_redirect($url);
			exit();
		endif;

		$this->options['post_types']=$post_types; // set var

		$update=update_option('pickle_cms_post_types', $post_types);

		wp_redirect($url);
		exit();
	}

	public function get() {
		if (!isset($_POST['slug']))
			return false;

		// find matching post type //
		foreach ($this->options['post_types'] as $post_type) :
			if ($post_type['name']==$_POST['slug']) :
				echo json_encode($post_type);
				break;
			endif;
		endforeach;

		wp_die();
	}

	public function ajax_delete() {
		if (!isset($_POST['name']))
			return false;

		if ($this->delete_post_type($_POST['name']))
			return true;

		return false;

		wp_die();
	}

	public function delete($name='') {
		$post_types=array();

		// build clean array //
		foreach ($this->options['post_types'] as $key => $post_type) :
			if ($post_type['name']!=$name)
				$post_types[]=$post_type;
		endforeach;

		$this->options['post_types']=$post_types; // set var

		update_option('pickle_cms_post_types', $post_types); // update option

		return false;
	}
	
	public function default_args() {
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

}
?>