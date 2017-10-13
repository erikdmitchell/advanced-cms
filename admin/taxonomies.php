<?php

class PickleCMS_Admin_Taxonomies extends PickleCMS_Admin {

	public function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'scripts_styles'));

		add_action('admin_init', array($this, 'update_taxonomies'));

		add_action('wp_ajax_pickle_cms_get_taxonomy', array($this, 'ajax_get_taxonomy'));
		add_action('wp_ajax_pickle_cms_delete_taxonomy', array($this, 'ajax_delete_taxonomy'));

		$this->options['taxonomies']=$this->get_option('pickle_cms_taxonomies', array());
	}

	public function scripts_styles($hook) {
		wp_enqueue_script('taxonomy-id-check-script', PICKLE_CMS_URL.'js/jquery.taxonomy-id-check.js', array('jquery'), '0.1.0');
		
		wp_enqueue_script('pickle-cms-admin-taxonomies', PICKLE_CMS_ADMIN_URL.'js/taxonomies.js', array('jquery'), '0.1.0');
	}

	public function update_taxonomies() {
		if (!isset($_POST['pickle_cms_admin']) || !wp_verify_nonce($_POST['pickle_cms_admin'], 'update_taxonomies'))
			return false;

		$data=$_POST;
		$option_exists=false;
		$taxonomies=get_option('pickle_cms_taxonomies');

		if (!isset($data['name']) || $data['name']=='')
			return false;

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

		if (get_option('pickle_cms_taxonomies'))
			$option_exists=true;

		$this->options['taxonomies']=$taxonomies; // set var

		$update=update_option('pickle_cms_taxonomies',$taxonomies);

		if ($update) :
			$update=true;
		elseif ($option_exists) :
			$update=true;
		else :
			$update=false;
		endif;

		$url=$this->admin_url(array(
			'tab' => 'taxonomies',
			'action' => 'update',
			'id' => $data['name'],
			'updated' => $update,
			'edit' => 'tax'
		));

		wp_redirect($url);
		exit();
	}

	public function ajax_get_taxonomy() {
		if (!isset($_POST['name']))
			return false;

		// find matching post type //
		foreach ($this->options['taxonomies'] as $taxonomy) :
			if ($taxonomy['name']==$_POST['name']) :
				echo json_encode($taxonomy);
				break;
			endif;
		endforeach;

		wp_die();
	}

	public function ajax_delete_taxonomy() {
		if (!isset($_POST['id']))
			return;

		if ($this->delete_taxonomy($_POST['id']))
			return true;

		return;

		wp_die();
	}

	public function delete_taxonomy($name='') {
		$taxonomies=array();

		// build clean array //
		foreach ($this->options['taxonomies'] as $key => $taxonomy) :
			if ($taxonomy['name']!=$name)
				$taxonomies[]=$taxonomy;
		endforeach;

		$this->options['taxonomies']=$taxonomies; // set var

		update_option('pickle_cms_taxonomies', $taxonomies); // update option

		return false;
	}

}
?>