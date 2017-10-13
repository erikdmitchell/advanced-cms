<?php

class PickleCMS_Admin_Component_Metaboxes extends PickleCMS_Admin_Component {

	public function __construct() {
		add_action('admin_init', array($this, 'update'));
		
		add_action('wp_ajax_pickle_cms_get_metabox', array($this, 'ajax_get'));
		add_action('wp_ajax_pickle_cms_delete_metabox', array($this, 'ajax_delete'));

		$this->slug='metaboxes';
		$this->name='Metaboxes';
		$this->items=$this->get_option('pickle_cms_metaboxes', array());
		
		// do not delete!
    	parent::__construct();	
	}
	
	public function scripts_styles($hook) {
		global $wp_scripts;

		$ui = $wp_scripts->query('jquery-ui-core');

		wp_register_script('pickle-cms-admin-metaboxes', PICKLE_CMS_ADMIN_URL.'js/metaboxes.js', array('jquery'), '0.2.0');

		wp_enqueue_script('metabox-id-check-script', PICKLE_CMS_URL.'js/jquery.metabox-id-check.js', array('jquery'), '0.1.0');
		
		wp_enqueue_script('pickle-cms-admin-metaboxes');

		wp_enqueue_style('pickle-cms-metabox-style', PICKLE_CMS_ADMIN_URL.'css/metaboxes.css');		
	}

	public function update() {
		if (!isset($_POST['pickle_cms_admin']) || !wp_verify_nonce($_POST['pickle_cms_admin'], 'update_metaboxes'))
			return false;

		global $pickleMetaboxes;

		$data=$_POST;
		$metaboxes=get_option('pickle_cms_metaboxes');
		$edit_key=-1;

		if (!isset($data['mb_id']) || $data['mb_id']=='')
			return false;

		// check for prefix //
		if (empty($data['prefix'])) :
			$prefix='_'.$data['mb_id'];
		else :
			$prefix=$data['prefix'];
		endif;

		if (empty($data['post_types']))
			$data['post_types'][]='post';

		$arr=array(
			'mb_id' => $data['mb_id'],
			'title' => $data['title'],
			'prefix' => $prefix,
			'post_types' => $data['post_types'],
		);

		// clean fields, if any //
		if (isset($data['fields'])) :
			foreach ($data['fields'] as $key => $field) :

				if (empty($field['field_type']) || empty(trim($field['title'])))
					unset($data['fields'][$key]);

			endforeach;
		endif;

		if (isset($data['fields']))
			$arr['fields']=array_values($data['fields']);

		if (!empty($metaboxes)) :
			foreach ($metaboxes as $key => $mb) :
				if ($mb['mb_id']==$data['mb_id']) :
					if (isset($data['update-metabox']) && $data['update-metabox']=='Update') :
						$edit_key=$key;
						if (isset($arr['post_fields'])) :
							$arr['post_fields']=$mb['post_fields'];
						endif;
					else :
						return false;
					endif;
				endif;
			endforeach;
		endif;

		if ($edit_key!=-1) :
			$metaboxes[$edit_key]=$arr;
		else :
			$metaboxes[]=$arr;
		endif;

		$this->options['metaboxes']=$metaboxes; // set var

		update_option('pickle_cms_metaboxes', $metaboxes);

		$url=$this->admin_url(array(
			'tab' => 'metaboxes',
			'action' => 'update',
			'edit' => 'mb',
			'id' => $data['mb_id'],
			'updated' => 1
		));

		wp_redirect($url);
		exit();

		return;
	}

	public function ajax_get() {
		if (!isset($_POST['id']))
			return false;

		// find matching post type //
		foreach ($this->options['metaboxes'] as $metabox) :
			if ($metabox['mb_id']==$_POST['id']) :
				echo json_encode($metabox);
				break;
			endif;
		endforeach;

		wp_die();
	}

	public function ajax_delete() {

		if (!isset($_POST['id']))
			return;

		if ($this->delete_metabox($_POST['id']))
			return true;

		return;

		wp_die();
	}

	public function delete_metabox($id='') {
		$metaboxes=array();

		// build clean array //
		foreach ($this->options['metaboxes'] as $key => $metabox) :
			if ($metabox['mb_id']!=$id)
				$metaboxes[]=$metabox;
		endforeach;

		$this->options['metaboxes']=$metaboxes; // set var

		update_option('pickle_cms_metaboxes', $metaboxes); // update option

		return false;
	}

	public function get_wp_metabox_slugs() {
		global $wp_meta_boxes;

		$meta_box_slugs=array();

		foreach ($wp_meta_boxes as $screen) :
			foreach ($screen as $context) :
				foreach ($context as $priority) :
					foreach ($priority as $slug => $metabox) :
						$meta_box_slugs[]=$slug;
					endforeach;
				endforeach;
			endforeach;
		endforeach;

		return $meta_box_slugs;
	}

	public function setup() {
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
			foreach (picklecms()->admin->components['metaboxes']->items as $metabox) :
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

}
?>