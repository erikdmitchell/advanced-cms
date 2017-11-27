<?php

class PickleCMS_Admin_Component_Metaboxes extends PickleCMS_Admin_Component {

    public $config='';

	public function __construct() {   	
		add_action('admin_enqueue_scripts', array($this, 'scripts_styles'));
		add_action('admin_init', array($this, 'add_metaboxes_to_global')); // check
		add_action('admin_init', array($this, 'update'));
		

		
		add_action('wp_ajax_pickle_cms_get_metabox', array($this, 'ajax_get')); // check
		add_action('wp_ajax_pickle_cms_delete_metabox', array($this, 'ajax_delete')); // check


		add_action('save_post', array($this, 'save_metabox_data')); // check
		add_action('add_meta_boxes', array($this, 'add_meta_boxes')); // check


		$this->slug='metaboxes';
		$this->name='Metaboxes';
		$this->items=$this->get_option('pickle_cms_metaboxes', array());
		$this->version='0.1.0';
		
		// do not delete!
    	parent::__construct();	
	}
	
	/**
	 * scripts_styles function.
	 * 
	 * @access public
	 * @param mixed $hook
	 * @return void
	 */
	public function scripts_styles($hook) {
		wp_enqueue_script('pickle-cms-admin-metaboxes', PICKLE_CMS_ADMIN_URL.'js/metaboxes.js', array('jquery'), $this->version, true);
		wp_enqueue_script('metabox-id-check-script', PICKLE_CMS_ADMIN_URL.'js/jquery.metabox-id-check.js', array('jquery'), '0.1.0');
		wp_enqueue_script('jquery-maskedinput-script', PICKLE_CMS_URL.'/js/jquery.maskedinput.min.js', array('jquery'), '1.3.1', true);
		wp_enqueue_script('jq-validator-script', PICKLE_CMS_URL.'/js/jquery.validator.js', array('jquery'), '1.0.0', true); // this may be global admin
		
		wp_enqueue_style('pickle-cms-metabox-style', PICKLE_CMS_ADMIN_URL.'css/metaboxes.css', '', $this->version);	
	}

	/**
	 * update function.
	 * 
	 * @access public
	 * @return void
	 */
	public function update() {   	
		if (!isset($_POST['pickle_cms_admin']) || !wp_verify_nonce($_POST['pickle_cms_admin'], 'update_metaboxes'))
			return false;

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

		update_option('pickle_cms_metaboxes', $metaboxes);

		$url=pickle_cms_get_admin_link(array(
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
print_r($_POST);
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
	
	public function add_metaboxes_to_global() {
		global $wp_meta_boxes;

		// cycle through metaboxes //
		if (!$this->config || empty($this->config))
			return false;

		foreach ($this->config as $metabox) :
			$callback=array();

			// grab mb fields (callback) //
			foreach ($metabox['fields'] as $field) :
				if (isset($field['field_id']))
					$callback[]=$field['field_id'];
			endforeach;

			// setup for each post type //
			foreach ($metabox['post_types'] as $post_type) :
				$arr=array(
					$metabox['mb_id'] => array(
						'id' => $metabox['mb_id'],
						'title' => $metabox['title'],
						'callback' => $callback,
						'args' => ''
					)
				);
				$wp_meta_boxes[$post_type]['normal']['high']=$arr;
			endforeach;
		endforeach;
	}	

	public function add_meta_boxes() {
    	global $post;
    	
    	$post_type=get_post_type($post);
    	
		if (empty($this->items))
			return false;

        foreach ($this->items as $key => $item) :      
            if ($this->is_metabox_in_post_type($item, $post_type)) :
				add_meta_box(
					$item['mb_id'],
					__($item['title'], 'Upload_Meta_Box'),
					array($this, 'generate_metabox_fields'),
					$post_type,
					'normal',
					'high',
					array(
						'config_key' => $key,
						'meta_box_id' => $item['mb_id'],
						'post_id' => $post->ID
					)
				);            
            endif;
        endforeach;
	}
	
	function generate_metabox_fields($post, $metabox) {
		$html=null;
		$row_counter=1;
		$fields=$this->get_metabox_fields($metabox['id']);
print_r($fields);
print_r($this->items);
		$html.='<div class="pickle-cms-meta-box">';

			$html.=wp_nonce_field('update_metabox', 'pickle_cms_metabox', true, false);
			

	
						foreach ($fields as $field) :	
							$classes=array('meta-row', $field['id'], 'type-'.$field['field_type']);
							$field['value']=get_post_meta($post->ID, $field['id'], true);
					
							$html.='<div id="meta-row-'.$row_counter.'" class="'.implode(' ', $classes).'" data-input-id="'.$field['id'].'" data-field-type="'.$field['field_type'].'" data-field-order="">';
								$html.='<label for="'.$field['id'].'">'.$field['title'].'</label>';
		
								$html.='<div class="fields-wrap">';
									$html.=pickle_cms_fields()->fields[$field['field_type']]->create_field($field);
									
									if (isset($field['description']))
										$html.='<p class="description">'.$field['description'].'</p>';
								$html.='</div>';
		
							$html.='</div>';
							$row_counter++;																
						endforeach;


			$html.='<input type="hidden" id="pickle-cms-metabox-id" name="pickle-cms-metabox-id" value="'.$metabox['args']['meta_box_id'].'" />';
			$html.='<input type="hidden" id="pickle-cms-config-key" name="pickle-cms-config-key" value="'.$metabox['args']['config_key'].'" />';
			$html.='<input type="hidden" id="pickle-cms-post-id" name="pickle-cms-post-id" value="'.$metabox['args']['post_id'].'" />';
		$html.='</div>';

		echo $html;
	}
	
	public function get_metabox_fields($id='') {
    	foreach ($this->items as $item) :
    	    if ($item['mb_id'] == $id) :
    	        if (isset($item['fields'])) :
        	        return $item['fields'];
                else:
                    return '';
                endif;
    	    endif;
    	endforeach;
    	
    	return;
	}	

	public function save_metabox_data($post_id) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;

		// if our nonce isn't there, or we can't verify it, bail
		if (!isset($_POST['pickle_cms_metabox']) || !wp_verify_nonce($_POST['pickle_cms_metabox'], 'update_metabox')) 
			return;

		// if our current user can't edit this post, bail
		if (!current_user_can('edit_post',$post_id)) 
			return;
		
		$custom_values=array();
	
		foreach ($this->registered_fields as $key) :
			if (isset($_POST[$key])) :
				$custom_values[$key]=$_POST[$key];
			endif;
		endforeach;
		
		foreach ($custom_values as $meta_key => $meta_value) :
			update_post_meta($post_id, $meta_key, $meta_value);
		endforeach;
	}

    protected function is_metabox_in_post_type($metabox='', $post_type='') {
        if (empty($metabox) || empty($post_type))
            return false;
            
        if (in_array($post_type, $metabox['post_types']))
            return true;
            
        return false;            
    }

}