<?php
/**
 * Version: 1.1.9
 * Author: erikdmitchell
**/

class mdw_Meta_Box {

	private $nonce = 'wp_upm_media_nonce'; // Represents the nonce value used to save the post media //
	private $umb_version='1.1.9';
	private $option_name='mdw_meta_box_duped_boxes';
	
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
		if (!$this->is_multi($config)) :
			$config=$this->convert_to_multi($config);
		endif;

		$this->config=$this->setup_config($config); // set our config
	
		// load our extra classes and whatnot
		$this->autoload_class('mdwmb_Functions');
	
		// include any files needed
		require_once(plugin_dir_path(__FILE__).'mdwmb-image-video.php');

		add_action('admin_enqueue_scripts',array($this,'register_admin_scripts_styles'));
		add_action('wp_enqueue_scripts',array($this,'register_scripts_styles'));
		add_action('save_post',array($this,'save_custom_meta_data'));
		add_action('add_meta_boxes',array($this,'mdwmb_add_meta_box'));
		add_action('wp_ajax_dup-box',array($this,'duplicate_meta_box'));
		add_action('wp_ajax_remove-box',array($this,'remove_duplicate_meta_box'));
	}

	function register_admin_scripts_styles() {
		global $post;

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-maskedinput-script',plugins_url('/js/jquery.maskedinput.min.js',__FILE__),array('jquery'),'1.3.1',true);
		
		wp_enqueue_script('metabox-duplicator',plugins_url('/js/metabox-duplicator.js',__FILE__),array('jquery'),'0.1.0',true);
		wp_enqueue_script('metabox-remover',plugins_url('/js/metabox-remover.js',__FILE__),array('jquery'),'0.1.0',true);
		wp_enqueue_script('metabox-datepicker-script',plugins_url('/js/metabox-datepicker.js',__FILE__),array('jquery-ui-datepicker'),'1.0.0',true);
		wp_enqueue_script('metabox-maskedinput-script',plugins_url('/js/metabox-maskedinput.js',__FILE__),array('jquery-maskedinput-script'),'1.0.0',true);		
		wp_enqueue_script('jq-validator-script',plugins_url('/js/jquery.validator.js',__FILE__),array('jquery'),'1.0.0',true);
		
		$options=array();
		
		if (isset($post->ID)) :
			$options['postID']=$post->ID;
		else :
			$options['postID']=null;
		endif;

		foreach ($this->config as $config) :
			//if ($config['duplicate']) :	
				$options[]=array(
					'metaboxID' => $config['id'],
					'metaboxClass' => $config['id'].'-meta-box',
					'metaboxTitle' => $config['title'],
					'metaboxPrefix' => $config['prefix'],
					'metaboxPostTypes' => $config['post_types'],
				);
			//endif;
		endforeach;
		wp_localize_script('metabox-duplicator','options',$options);
		
		wp_localize_script('metabox-remover','options',get_option($this->option_name));
		
		wp_enqueue_style('mdwmb-admin-css',plugins_url('/css/admin.css',__FILE__));
		wp_enqueue_style('jquery-ui-style','//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css',array(),'1.10.4');				
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
		global $config_id,$post;

		$this->build_duplicated_boxes($post->ID); // must do here b/c we need the post id

		foreach ($this->config as $key => $config) :
			$config_id=$config['id']; // for use in our classes function
		
			if (isset($config['removable'])) :
				$removable=$config['removable'];
			else :
				$removable=false;
			endif;
			
			foreach ($config['post_types'] as $post_type) :
		    add_meta_box(
		    	$config['id'],
		      __($config['title'],'Upload_Meta_Box'),
		      array($this,'generate_meta_box_fields'),
		      $post_type,
		      'normal',
		      'high',
		      array(
		      	'config_key' => $key,
						'duplicate' => $config['duplicate'],
						'meta_box_id' => $config['id'],
						'removable' => $removable,
		      )
		    );
		    
		    if ($config['duplicate'])
			    add_filter('postbox_classes_'.$post_type.'_'.$config['id'],array($this,'add_meta_box_classes'));
		    
	    endforeach;
    endforeach;
	}
	
	/**
	 * adds classes to our meta box
	**/
	function add_meta_box_classes($classes=array()) {
		global $config_id;
		
		$classes[]='dupable';
		$classes[]=$config_id.'-meta-box';
		
		return $classes;
	}
	
	/**
	 * cycles through the fields (set in add_field)
	 * calls the generate_field() function
	**/
	function generate_meta_box_fields($post,$metabox) {
		$html=null;
		$this->fields=null; // this needs to be adjusted for legacy v 1.1.8

		wp_enqueue_script('umb-admin',plugins_url('/js/metabox-media-uploader.js',__FILE__),array('jquery'),$this->umb_version);
		
		wp_nonce_field(plugin_basename( __FILE__ ),$this->nonce);

		$html.='<div class="umb-meta-box">';

			foreach ($this->config as $config) :
				if ($metabox['args']['meta_box_id']==$config['id']) :
					if (!empty($config['fields']))
						$this->add_fields_array($config['fields'],$config['id']);
				endif;
			endforeach;
		
			foreach ($this->fields as $field) :
				$html.='<div class="meta-row">';
					$html.='<label for="'.$field['id'].'">'.$field['label'].'</label>';
					$html.=$this->generate_field($field);
				$html.='</div>';
			endforeach;
		
			if ($metabox['args']['duplicate'])
				$html.='<div class="dup-meta-box"><a href="#" data-meta-id="'.$metabox['args']['meta_box_id'].'">Duplicate Box</a></div>';

			if ($metabox['args']['removable'])
				$html.='<div class="remove-meta-box"><a href="#" data-meta-id="'.$metabox['args']['meta_box_id'].'" data-post-id="'.$post->ID.'">Remove Box</a></div>';
				
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
			case 'date':
				$html.='<input type="text" class="datepicker" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'phone':
				$html.='<input type="text" class="phone" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;				
			case 'url':
				$html.='<input type="text" class="url validator" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
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
	 * because we allow multiple configs now, we must use legacy support (1 config) and expand to allow for multi configs (pre 1.1.8)
	**/
	public function add_field($args,$meta_id=false) {
		if (count($this->config)==1) :
			$prefix=$this->config[0]['prefix'];
		else :
			if ($meta_id) :
				foreach ($this->config as $config) :
					if ($config['id']==$meta_id)
						$prefix=$config['prefix'];
				endforeach;
			endif;
		endif;
		
		$new_field=array('id' => '', 'type' => 'text', 'label' => 'Text Box', 'value' => '');
		$new_field=array_merge($new_field,$args);
		$new_field['id']=$prefix.'_'.$new_field['id'];
		$this->fields[$new_field['id']]=$new_field;	
	}
	
	/**
	 * a variation of the add_fields function
	 * this allows us to generate our fields with a passed array
	 * added 1.1.8
	**/
	function add_fields_array($arr,$meta_id) {
		foreach ($arr as $id => $values) :
			$args=array(
				'id' => $id,
				'type' => $values['type'],
				'label' => $values['label'],
			);
			$this->add_field($args,$meta_id);
		endforeach;
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
		
		$this->build_duplicated_boxes($post_id); // must do here again b/c this action is added before we have all the info
		
		foreach ($this->config as $config) :
			$data=null;
			$prefix=$config['prefix'];
			
			foreach ($config['fields'] as $id => $field) :
				$field_id=$prefix.'_'.$id;
		
				if (isset($_POST[$field_id])):
					$data=$_POST[$field_id]; // submitted value //
				endif;
	
				// fix notices on unchecked check boxes //
				//if (get_post_meta($post_id, $field['id']) == "") :
				//	add_post_meta($post_id, $field['id'], $data, true);
				//elseif ($data != get_post_meta($post_id, $field['id'], true)) :

				if ($data=="") :
					delete_post_meta($post_id, $field_id, get_post_meta($post_id, $field_id, true));
				else :
					update_post_meta($post_id, $field_id, $data);
				endif;

			endforeach;	

		endforeach;			
	}
	
	function duplicate_meta_box() {
		$this->save_duplicate_meta_box($_POST['postID']);

		exit;
	}

	function remove_duplicate_meta_box() {
		$option=get_option($this->option_name);
		
		// check for option key //
		if (!isset($_POST['optionKey']))
			return false;
		
		$option_to_remove=$option[$_POST['optionKey']];
		
		// do some quick checks to make sure all is ok //
		if ($option_to_remove['post_id']!=$_POST['postID'])
			return false;
			
		if ($option_to_remove['id']!=$_POST['metaID'])
			return false;
		
		// remove post meta //
		foreach ($option_to_remove['fields'] as $id => $field) :
			$meta_key=$option_to_remove['prefix'].'_'.$id;
			if ($meta_key)
				delete_post_meta($_POST['postID'],$meta_key);
		endforeach;		
		
		unset($option[$_POST['optionKey']]); // remove from option
		$option=array_values($option); // reset keys
		
		update_option($this->option_name,$option); // update our option
		
		return true;
				
		exit;
	}

	/**
	 * saves our meta field data when we are duplicating a field
	 * the field needs to be saved so that our class can be updated with the apropriate fields
	 * it's done via ajax, so the users should not see anything
	**/
	public function save_duplicate_meta_box($post_id) {
		// Bail if we're doing an auto save  
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 

		// if our current user can't edit this post, bail  
		if (!current_user_can('edit_post',$post_id)) return;

		$data=null;
		$option=false;
		$option_arr=array();
		$current_option_arr=array();
		$new_option_arr=array();
		$prefix=$_POST['prefix'];
		
		foreach ($_POST['fields'] as $id => $field) :
			$field_id=$prefix.'_'.$id;
			$data='';
			//update_post_meta($post_id,$field_id,$data);
		endforeach;		

		// build our option so that we know we have duped boxes //
		$option=$this->option_name;
		$option_arr=array(
			'post_id' => $_POST['postID'],
			'id' => $_POST['id'],
			'title' => $_POST['title'],
			'prefix' => $_POST['prefix'],
			'post_types' => $_POST['post_types'],
			'duplicate' => 0,
			'fields' => $_POST['fields'],
		);
		
/*
print_r($option_arr);	

		if ($option)
			$current_option_arr=get_option($option);

		if ($current_option_arr) :
			array_push($option_arr,$current_option_arr);
		else :
			$new_option_arr[0]=$option_arr;
			$option_arr=$new_option_arr;
		endif;

print_r($option_arr);	
			
		if ($option)
			update_option($option,$option_arr);
*/
	}
	
	/**
	 * is array multidimensional (check only first level)
	 * takes in array
	 * returns true/false
	**/
	function is_multi($a) {
		if (isset($a[0]))
			return true;

		return false;
	}
	
	/**
	 * converts a 1d array to multi dimensional
	 * takes in arr
	 * returns multi dimen arr
	**/
	function convert_to_multi($arr) {
		$multi_arr=array();

		$multi_arr[]=$arr;
		
		return $multi_arr;
	}
	
	/**
	 * setup our config with defaults and adjusments
	**/
	function setup_config($configs) {
		$ran_string=substr(substr("abcdefghijklmnopqrstuvwxyz",mt_rand(0,25),1).substr(md5(time()),1),0,5);
		$default_config=array(
			'id' => 'mdwmb_'.$ran_string,
			'title' => 'Default Meta Box',
			'prefix' => '_mdwmb',
			'post_types' => 'post,page',
			'duplicate' => 0,
			'fields' => array(), // for legacy support (pre 1.1.8)
		);

		foreach ($configs as $key => $config) :
			$config=array_merge($default_config,$config);

			if (!is_array($config['post_types'])) :
				$config['post_types']=explode(",",$config['post_types']);
			endif;			
			
			$config=$this->check_config_prefix($config); // makes sure our prefix starts with '_'
			
			$configs[$key]=$config;
		endforeach;
		
		return $configs;
	}
	
	function build_duplicated_boxes($post_id=false) {
		if (!$post_id)
			return false;

		$option_arr=get_option($this->option_name);
		
		if (!count($option_arr))
			return false;
			
		foreach ($option_arr as $option) :
			if ($option['post_id']==$post_id) :
				$option['removable']=true; // allows us to have a remove button
				array_push($this->config,$option);
			endif;
		endforeach;				
	
		return;
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
