<?php
/**
 * Version: 1.0.0
 * Author: erikdmitchell
**/

class mdw_Meta_Box {

	private $nonce = 'wp_upm_media_nonce'; // Represents the nonce value used to save the post media //
	private $umb_version='1.1.7';
	
	protected $fields=array();

	/**
	 * constructs our function, setups our scripts and styles, attaches meta box to wp actions
	 * @param array $config
	 							post_types
	 							id
	 							title
	 							prefix
	**/
	function __construct($config=array()) {
		$ran_string=substr(substr("abcdefghijklmnopqrstuvwxyz",mt_rand(0,25),1).substr(md5(time()),1),0,5);
	
		$default_config=array(
			'id' => 'mdwmb_'.$ran_string,
			'title' => 'Default Meta Box',
			'prefix' => '_mdwmb',
			'post_types' => 'post,page'
		);

		$config=array_merge($default_config,$config);
	
		if (!is_array($config['post_types'])) :
			$config['post_types']=explode(",",$config['post_types']);
		endif;

		$config=$this->check_config_prefix($config); // makes sure our prefix starts with '_'
		
		$this->config=$config; // set out config
		
		// load our extra classes and whatnot
		$this->autoload_class('mdwmb_Functions');
		
		// include any files needed
		require_once(plugin_dir_path(__FILE__).'mdwmb-image-video.php');

		add_action('admin_enqueue_scripts',array($this,'register_admin_scripts_styles'));
		add_action('wp_enqueue_scripts',array($this,'register_scripts_styles'));
		add_action('save_post',array($this,'save_custom_meta_data'));
		add_action('add_meta_boxes',array($this,'mdwmb_add_meta_box'));
	}

	function register_admin_scripts_styles() {
		wp_enqueue_style('mdwmb-admin-css',plugins_url('/css/admin.css',__FILE__));
	}
	
	function register_scripts_styles() {
		wp_enqueue_style('custom-video-js_css',plugins_url('/css/custom-video-js.css',__FILE__));
		
		wp_enqueue_script('video-js_js','//vjs.zencdn.net/4.2/video.js',array(),'4.2', true);
		wp_enqueue_style('video-js_css','//vjs.zencdn.net/4.2/video-js.css',array(),'4.2');
	}
	
	/**
	 * makes sure our prefix starts with '_'
	 * @param array $config
	 * returns array $config
	**/
	function check_config_prefix($config) {
		if (substr($config['prefix'],0,1)!='_')
			$config['prefix']='_'.$config['prefix'];
	
		return $config;
	}

	/**
	 * autoloads our helper classes and functions
	 * @param string $class_name - the name of the field/class to include
	**/
	private function autoload_class($filename) {
		require_once(plugin_dir_path(__FILE__).$filename.'.php');
		
		return new $filename;
	}
	
	/**
	 * creates the actual metabox itself using the id and title from the config file and attaches it to the post type
	 * callback: generate_meta_box_fields
	**/
	function mdwmb_add_meta_box() {
		foreach ($this->config['post_types'] as $post_type) :
	    add_meta_box(
	    	$this->config['id'],
	      __($this->config['title'],'Upload_Meta_Box'),
	      array($this,'generate_meta_box_fields'),
	      $post_type,
	      'normal',
	      'high'
	    );
    endforeach;
	}
	
	/**
	 * cycles through the fields (set in add_field)
	 * calls the generate_field() function
	**/
	function generate_meta_box_fields($post) {
		$html=null;
		
		wp_enqueue_script('umb-admin',plugins_url('/js/metabox-media-uploader.js',__FILE__),array('jquery'),$this->umb_version);
		
		wp_nonce_field(plugin_basename( __FILE__ ),$this->nonce);

		$html.='<div class="umb-meta-box">';
			foreach ($this->fields as $field) :
				$html.='<div class="meta-row">';
					$html.='<label for="'.$field['id'].'">'.$field['label'].'</label>';
					$html.=$this->generate_field($field);
				$html.='</div>';
			endforeach;
		$html.='</div>';
		
		echo $html;	
	}

	/**
	 * generates the input box of each meta field
	 * uses a switch case to determine which field to output (default is text)
	 * @param array $args (set in the add_field() function via the add_field() function)
	**/
	function generate_field($args) {
		global $post;

		$html=null;
		$values=get_post_custom($post->ID);
		
		if (isset($values[$args['id']][0])) :
			$value=$values[$args['id']][0];
		else :
			$value=null;
		endif;

		switch ($args['type']) :
			case 'text' :
				$html.='<input type="text" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'checkbox':
				$html.='<input type="checkbox" name="'.$args['id'].'" id="'.$args['id'].'" '.checked($value,'on',false).' />';
				break;
			case 'textarea':
				$html.='<textarea class="textarea" name="'.$args['id'].'" id="'.$args['id'].'">'.$value.'</textarea>';
				break;
			case 'wysiwyg':
				$settings=array(
					'media_buttons' => false,
					'textarea_rows' => 10,
					'quicktags' => false
				);
				//$html.=wp_editor($value,$args['id'],$settings);
				$html.=mdwmb_Functions::mdwm_wp_editor($value,$args['id'],$settings);
				break;
			case 'media':
				$html.='<input id="'.$args['id'].'" class="uploader-input regular-text" type="text" name="'.$args['id'].'" value="'.$value.'" />';
				$html.='<input class="uploader button" name="'.$args['id'].'_button" id="'.$args['id'].'_button" value="Upload" />';
				$html.='<input type="hidden" name="_name" value="'.$args['id'].'" />';
				
				$attr=array(
					'src' => $value,
					/* 'class' => 'umb-media-thumb', */
				);
	
				if ($value) :
					$html.='<div class="umb-media-thumb">';
						$html.=get_the_post_thumbnail($post->ID,'thumbnail',$attr);
						$html.='<a class="remove" data-type-img-id="'.$args['id'].'" href="#">Remove</a>';
					$html.='</div>';
				endif;
				
				break;
			default:
				$html.='<input type="text" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
		endswitch;
		
		return $html;
	}
	
	/**
	 * a public function that allows the user to add a field to the meta box
	 * @param array $args 
	 							id (field id) REQUIRED
	 							type (type of input field) 
	 							label (for field)
	 							value (of field)
	**/
	public function add_field($args) {
		$new_field=array('id' => '', 'type' => 'text', 'label' => 'Text Box', 'value' => '');
		$new_field=array_merge($new_field,$args);
		$new_field['id']=$this->config['prefix'].'_'.$new_field['id'];
		$this->fields[$new_field['id']]=$new_field;	
	}
	
	/**
	 * saves our meta field data
	**/
	public function save_custom_meta_data($post_id) {
		// Bail if we're doing an auto save  
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
	
		// if our nonce isn't there, or we can't verify it, bail
		if (!isset($_POST[$this->nonce]) || !wp_verify_nonce($_POST[$this->nonce],plugin_basename(__FILE__))) return;

		// if our current user can't edit this post, bail  
		if (!current_user_can('edit_post',$post_id)) return;

		foreach ($this->fields as $field) :
			$data = "";
			
			if (isset($_POST[$field['id']])):
				$data=$_POST[$field['id']]; // submitted value //
			endif;

			// fix notices on unchecked check boxes //
			//if (get_post_meta($post_id, $field['id']) == "") :
			//	add_post_meta($post_id, $field['id'], $data, true);
			//elseif ($data != get_post_meta($post_id, $field['id'], true)) :
			if ($data=="") :
				delete_post_meta($post_id, $field['id'], get_post_meta($post_id, $field['id'], true));
			else :
				update_post_meta($post_id, $field['id'], $data);
			endif;			
		endforeach;			
	}

} // end class

/**
 * this loads our load plugin first so that the meta boxes can be used throughout the site
**/
/*
add_action('plugins_loaded','load_plugin_first');
function load_plugin_first() {
	$path = str_replace( WP_PLUGIN_DIR . '/', '', __FILE__ );
	if ( $plugins = get_option( 'active_plugins' ) ) {
		if ( $key = array_search( $path, $plugins ) ) {
			array_splice( $plugins, $key, 1 );
			array_unshift( $plugins, $path );
			update_option( 'active_plugins', $plugins );
		}
	}	
}
*/