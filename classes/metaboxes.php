<?php
global $pickle_metaboxes;

/**
 * PickleCMSMetaboxes class.
 */
class PickleCMSMetaboxes {

	public $config;

	/**
	 * constructs our function, setups our scripts and styles, attaches meta box to wp actions
	 */
	function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts_styles'));
		add_action('save_post', array($this, 'save_custom_meta_data'));
		add_action('add_meta_boxes', array($this, 'add_meta_boxes'));

		add_action('wp_ajax_duplicate_metabox_field', array($this, 'ajax_duplicate_metabox_field'));
		add_action('wp_ajax_remove_duplicate_metabox_field' ,array($this, 'ajax_remove_duplicate_metabox_field'));

		add_action('admin_init', array($this, 'add_metaboxes_to_global'));
				
		add_action('plugins_loaded', array($this, 'setup_config'));
	}
	
	/**
	 * register_admin_scripts_styles function.
	 *
	 * @access public
	 * @param mixed $hook
	 * @return void
	 */
	public function register_admin_scripts_styles($hook) {
		wp_enqueue_script('jquery-maskedinput-script', PICKLE_CMS_URL.'/js/jquery.maskedinput.min.js', array('jquery'), '1.3.1', true);
		wp_enqueue_script('jq-validator-script', PICKLE_CMS_URL.'/js/jquery.validator.js', array('jquery'), '1.0.0', true);
	}

	/**
	 * check_config_prefix function.
	 * 
	 * @access protected
	 * @param bool $prefix (default: false)
	 * @return void
	 */
	protected function check_config_prefix($prefix=false) {
		if (!$prefix)
			return false;

		if (substr($prefix,0,1)!='_')
			$prefix='_'.$prefix;

		return $prefix;
	}

	public function add_meta_boxes() {
		global $config_id, $post;

		if (empty($this->config))
			return false;

		foreach ($this->config as $key => $config) :
			$config_id=$config['mb_id']; // for use in our classes function

			foreach ($config['post_types'] as $post_type) :
				add_meta_box(
					$config['mb_id'],
					__($config['title'], 'Upload_Meta_Box'),
					array($this, 'generate_meta_box_fields'),
					$post_type,
					apply_filters("pickle_cms_add_metabox_context_{$config['mb_id']}", 'normal'), // normal, pickle, side
					apply_filters("pickle_cms_add_metabox_priority_{$config['mb_id']}", 'high'), // high, core, default, low (prority)
					array(
						'config_key' => $key,
						'meta_box_id' => $config['mb_id'],
						'post_id' => $post->ID
					)
				);
			endforeach;
		endforeach;
	}

	function generate_meta_box_fields($post, $metabox) {
		$html=null;
		$row_counter=1;

		wp_enqueue_script('pickle-cms-metabox-media-uploader', PICKLE_CMS_URL.'/js/metabox-media-uploader.js', array('jquery'));

		wp_nonce_field(plugin_basename( __FILE__ ), $this->nonce);

		$html.='<div class="pickle-cms-meta-box">';

			foreach ($this->config as $config) :

				if ($metabox['args']['meta_box_id']==$config['mb_id']) :

					if (!empty($config['fields'])) :
						// order ?/? //
				/*		
				usort($this->fields, function ($a, $b) {
					if (function_exists('bccomp')) :
						return bccomp($a['order'], $b['order']);
					else :
						return strcmp($a['order'], $b['order']);
					endif;
				});
				*/		
						foreach ($config['fields'] as $field) :	
							$classes=array('meta-row', $field['id'], 'type-'.$field['field_type']);
							$field['value']=get_post_meta($post->ID, $field['name'], true);

echo '<pre>';
print_r($field);
echo '</pre>';	
					
							$html.='<div id="meta-row-'.$row_counter.'" class="'.implode(' ', $classes).'" data-input-id="'.$field['id'].'" data-field-type="'.$field['field_type'].'" data-field-order="'.$field['order'].'">';
								$html.='<label for="'.$field['id'].'">'.$field['title'].'</label>';
		
								$html.='<div class="fields-wrap">';
									$html.=apply_filters('create_field_'.$field['field_type'], $field);
									
									if (isset($field['description']))
										$html.='<p class="description">'.$field['description'].'</p>';
								$html.='</div>';
		
							$html.='</div>';
							$row_counter++;																
						endforeach;
					endif;

				endif;

			endforeach;

			$html.='<input type="hidden" id="pickle-cms-metabox-id" name="pickle-cms-metabox-id" value="'.$metabox['args']['meta_box_id'].'" />';
			$html.='<input type="hidden" id="pickle-cms-config-key" name="pickle-cms-config-key" value="'.$metabox['args']['config_key'].'" />';
			$html.='<input type="hidden" id="pickle-cms-post-id" name="pickle-cms-post-id" value="'.$metabox['args']['post_id'].'" />';
		$html.='</div>';

		echo $html;
	}

	public function generate_field_id($prefix=false, $label=false, $field_id=false) {
		$id=null;

		if (!$prefix || !$label)
			return false;

		$prefix=$this->check_config_prefix($prefix);

		if (empty($label)) :
			$id=$prefix.'_'.$field_id;
		else :
			$id=$prefix.'_'.strtolower($this->clean_special_chars($label));
		endif;

		return $id;
	}

	public function save_custom_meta_data($post_id) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our nonce isn't there, or we can't verify it, bail
		if (!isset($_POST[$this->nonce]) || !wp_verify_nonce($_POST[$this->nonce],plugin_basename(__FILE__))) return;

		// if our current user can't edit this post, bail
		if (!current_user_can('edit_post',$post_id)) return;
		
		$custom_values=array();
	
		foreach ($this->registered_fields as $key) :
			if (isset($_POST[$key]))
				$custom_values[$key]=$_POST[$key];
		endforeach;
		
		foreach ($custom_values as $meta_key => $meta_value) :
			update_post_meta($post_id, $meta_key, $meta_value);
		endforeach;
	}

	/**
	 * setup_config function.
	 * 
	 * @access public
	 * @return void
	 */
	public function setup_config() {
		do_action('picklecms_register_field');

		$config=get_option('pickle_cms_metaboxes');
			
		$ran_string=substr(substr("abcdefghijklmnopqrstuvwxyz",mt_rand(0,25),1).substr(md5(time()),1),0,5);
		$default_config=array(
			'mb_id' => 'picklemb_'.$ran_string,
			'title' => 'Default Meta Box',
			'prefix' => '_picklemb',
			'post_types' => 'post, page',
			'fields' => array(),
		);

		if (empty($config))
			return false;

		// setup our metaboxes //
		foreach ($config as $key => $arr) :
			$_config=pickle_cms_parse_args($arr, $default_config);

			if (!is_array($_config['post_types'])) :
				$_config['post_types']=explode(',', $_config['post_types']);
			endif;

			$_config['prefix']=$this->check_config_prefix($_config['prefix']); // makes sure our prefix starts with '_'

			$config[$key]=$_config;
		endforeach;
	
		$this->config=$config;

		$this->registered_fields=$this->registered_fields();

		return;
	}

	/**
	 * registered_fields function.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function registered_fields() {
		$registered_fields=array();
		
		foreach ($this->config as $mb) :
			foreach ($mb['fields'] as $field) :
				$registered_fields[]=$field['field_id'];
			endforeach;
		endforeach;
		
		return $registered_fields;
	}

	public function clean_special_chars($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

		return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}

	/**
	 * add_metaboxes_to_global function.
	 * 
	 * @access public
	 * @return void
	 */
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
	
} // end class

$pickle_metaboxes = new PickleCMSMetaboxes();
?>
