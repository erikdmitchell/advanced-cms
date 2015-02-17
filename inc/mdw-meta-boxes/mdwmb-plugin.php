<?php
/**
 * Version: 1.2.1
 * Author: erikdmitchell
**/

class MDWMetaboxes {

	private $nonce = 'wp_upm_media_nonce'; // Represents the nonce value used to save the post media //
	private $version='1.2.2';
	private $option_name='mdw_meta_box_duped_boxes';

	protected $options=array();
	protected $post_types=array();

	public $fields=array();

	/**
	 * constructs our function, setups our scripts and styles, attaches meta box to wp actions
	 */
	function __construct() {
		$config=get_option('mdw_cms_metaboxes');

		$this->fields=array(
			'checkbox' => array(
				'repeatable' => 1,
				'options' => 0,
			),
			'colorpicker' => array(
				'repeatable' => 0,
				'options' => 0,
			),
			'date' => array(
				'repeatable' => 0,
				'options' => 0,
			),
			'email' => array(
				'repeatable' => 1,
				'options' => 0,
			),
			'media' => array(
				'repeatable' => 0,
				'options' => 0,
			),
			'phone' => array(
				'repeatable' => 1,
				'options' => 0,
			),
			'radio' => array(
				'repeatable' => 1,
				'options' => 0,
			),
			'select' => array(
				'repeatable' => 0,
				'options' => 1,
			),
			'text' => array(
				'repeatable' => 1,
				'options' => 0,
			),
			'textarea' => array(
				'repeatable' => 1,
				'options' => 0,
			),
			'timepicker' => array(
				'repeatable' => 0,
				'options' => 0,
			),
			'url'	 => array(
				'repeatable' => 1,
				'options' => 0,
			),
			'wysiwyg' => array(
				'repeatable' => 0,
				'options' => 0,
			)
		);
		$this->config=$this->setup_config($config); // set our config

/*
echo '<pre>';
print_r($this->config);
echo '</pre>';
*/


		// load our extra classes and whatnot
		//$this->autoload_class('mdwmb_Functions'); -- GUI

		// include any files needed
		//require_once(plugin_dir_path(__FILE__).'mdwmb-image-video.php'); -- GUI

		add_action('admin_enqueue_scripts',array($this,'register_admin_scripts_styles'));
		add_action('wp_enqueue_scripts',array($this,'register_scripts_styles'));
		add_action('save_post',array($this,'save_custom_meta_data'));
		add_action('add_meta_boxes',array($this,'mdwmb_add_meta_box'));
		//add_action('wp_ajax_dup-box',array($this,'duplicate_meta_box'));
		//add_action('wp_ajax_remove-box',array($this,'remove_duplicate_meta_box'));

		add_action('wp_ajax_duplicate_metabox_field',array($this,'ajax_duplicate_metabox_field'));
		add_action('wp_ajax_remove_duplicate_metabox_field',array($this,'ajax_remove_duplicate_metabox_field'));
	}

	function register_admin_scripts_styles() {
		global $post;

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('colpick-js',plugins_url('/js/colpick.js',__FILE__));
		wp_enqueue_script('jq-timepicker',plugins_url('/js/jquery.ui.timepicker.js',__FILE__));
		wp_enqueue_script('jquery-maskedinput-script',plugins_url('/js/jquery.maskedinput.min.js',__FILE__),array('jquery'),'1.3.1',true);
		//wp_enqueue_script('metabox-duplicator',plugins_url('/js/metabox-duplicator.js',__FILE__),array('jquery'),'0.1.0',true);
		wp_enqueue_script('metabox-remover',plugins_url('/js/metabox-remover.js',__FILE__),array('jquery'),'0.1.0',true);
		wp_enqueue_script('metabox-datepicker-script',plugins_url('/js/metabox-datepicker.js',__FILE__),array('jquery-ui-datepicker'),'1.0.0',true);
		wp_enqueue_script('metabox-maskedinput-script',plugins_url('/js/metabox-maskedinput.js',__FILE__),array('jquery-maskedinput-script'),'1.0.0',true);
		wp_enqueue_script('jq-validator-script',plugins_url('/js/jquery.validator.js',__FILE__),array('jquery'),'1.0.0',true);
		wp_enqueue_script('mdw-cms-js',plugins_url('/js/functions.js',__FILE__),array('jquery'));
		wp_enqueue_script('duplicate-metabox-fields',plugins_url('js/duplicate-metabox-fields.js',__FILE__),array('jquery'),'1.0.2');

		if (isset($post->ID)) :
			$post_id=$post->ID;
		else :
			$post_id=null;
		endif;

		$options=array();

		$options['postID']=$post_id;

		if (!empty($this->config)) :
			foreach ($this->config as $config) :
				//if ($config['duplicate']) :
					$options[]=array(
						'metaboxID' => $config['mb_id'],
						'metaboxClass' => $config['mb_id'].'-meta-box',
						'metaboxTitle' => $config['title'],
						'metaboxPrefix' => $config['prefix'],
						'metaboxPostTypes' => $config['post_types'],
					);
				//endif;
			endforeach;
		endif;

		//wp_localize_script('metabox-duplicator','options',$options);
		//wp_localize_script('metabox-remover','options',get_option($this->option_name));

		wp_enqueue_style('mdwmb-admin-css',plugins_url('/css/admin.css',__FILE__));
		wp_enqueue_style('jquery-ui-style','//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css',array(),'1.10.4');
		wp_enqueue_style('colpick-css',plugins_url('/css/colpick.css',__FILE__));
		wp_enqueue_style('jq-timepicker-style',plugins_url('/css/jquery.ui.timepicker.css',__FILE__));
		//wp_enqueue_style('aja-meta-boxes-css',plugins_url('css/ajax-meta-boxes.css',__FILE__),array(),'1.0.0','all');
	}

	/**
	 *
	 */
	function register_scripts_styles() {
		wp_enqueue_style('custom-video-js_css',plugins_url('/css/custom-video-js.css',__FILE__));

		wp_enqueue_script('video-js_js','//vjs.zencdn.net/4.2/video.js',array(),'4.2', true);
		wp_enqueue_style('video-js_css','//vjs.zencdn.net/4.2/video-js.css',array(),'4.2');
	}

	/**
	 * check_config_prefix function.
	 *
	 * makes sure our prefix starts with '_'
	 *
	 * @access public
	 * @param bool $config (default: false)
	 * @param bool $prefix (default: false)
	 * @return $prefix
	 */
	function check_config_prefix($prefix=false) {
		if (!$prefix)
			return false;

		if (substr($prefix,0,1)!='_')
			$prefix='_'.$prefix;

		return $prefix;
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

		//$this->build_duplicated_boxes($post->ID); // must do here b/c we need the post id
		if (empty($this->config))
			return false;

		foreach ($this->config as $key => $config) :
			$config_id=$config['mb_id']; // for use in our classes function

/*
			if (isset($config['removable'])) :
				$removable=$config['removable'];
			else :
				$removable=false;
			endif;
*/

			foreach ($config['post_types'] as $post_type) :
		    add_meta_box(
		    	$config['mb_id'],
		      __($config['title'],'Upload_Meta_Box'),
		      array($this,'generate_meta_box_fields'),
		      $post_type,
		      'normal',
		      'high',
		      array(
		      	'config_key' => $key,
						//'duplicate' => $config['duplicate'],
						'meta_box_id' => $config['mb_id'],
						//'removable' => $removable,
						'post_id' => $post->ID
		      )
		    );

		    //if ($config['duplicate'])
			    //add_filter('postbox_classes_'.$post_type.'_'.$config['id'],array($this,'add_meta_box_classes'));

	    endforeach;
    endforeach;
	}

	/**
	 * adds classes to our meta box
	**/
/*
	function add_meta_box_classes($classes=array()) {
		global $config_id;

		$classes[]='dupable';
		$classes[]=$config_id.'-meta-box';

		return $classes;
	}
*/

	/**
	 * cycles through the fields (set in add_field)
	 * calls the generate_field() function
	**/
	function generate_meta_box_fields($post,$metabox) {
		$html=null;
		$this->fields=null; // this needs to be adjusted for legacy v 1.1.8
		$row_counter=1;

		wp_enqueue_script('umb-admin',plugins_url('/js/metabox-media-uploader.js',__FILE__),array('jquery'));

		wp_nonce_field(plugin_basename( __FILE__ ),$this->nonce);

		// because our legacy and current setups can be different, we need this function to do our post fields //
		$this->add_post_fields($this,$metabox,$post->ID);

		$html.='<div class="mdw-cms-meta-box umb-meta-box">';

			foreach ($this->config as $config) :

				if ($metabox['args']['meta_box_id']==$config['mb_id']) :

					if (!empty($config['fields'])) :
						$this->add_fields_array($config['fields'],$config['mb_id']);
					endif;

				endif;

			endforeach;

			// output all of our fields //
			if (isset($this->fields)) :

				// sort fields by order //
				usort($this->fields, function ($a, $b) {
					return strcmp($a['order'], $b['order']);
				});

				foreach ($this->fields as $field) :
					$classes=$field['id'].' type-'.$field['type'];

					if ($field['duplicate'])
						$classes.=' clone';

					$html.='<div id="meta-row-'.$row_counter.'" class="meta-row '.$classes.'" data-input-id="'.$field['id'].'" data-field-type="'.$field['type'].'" data-field-order="'.$field['order'].'">';
						$html.='<label for="'.$field['id'].'">'.$field['label'].'</label>';
						$html.=$this->generate_field($field);

						if ($field['duplicate'])
							$html.='<button type="button" class="ajaxmb-field-btn delete">Delete Field</button>'; // add delete btn

					$html.='</div>';
					$row_counter++;
				endforeach;
			endif;

/*
			if ($metabox['args']['duplicate'])
				$html.='<div class="dup-meta-box"><a href="#" data-meta-id="'.$metabox['args']['meta_box_id'].'">Duplicate Box</a></div>';

			if ($metabox['args']['removable'])
				$html.='<div class="remove-meta-box"><a href="#" data-meta-id="'.$metabox['args']['meta_box_id'].'" data-post-id="'.$post->ID.'">Remove Box</a></div>';
*/
			$html.='<input type="hidden" id="mdw-cms-metabox-id" name="mdw-cms-metabox-id" value="'.$metabox['args']['meta_box_id'].'" />';
			$html.='<input type="hidden" id="mdw-cms-config-key" name="mdw-cms-config-key" value="'.$metabox['args']['config_key'].'" />';
			$html.='<input type="hidden" id="mdw-cms-post-id" name="mdw-cms-post-id" value="'.$metabox['args']['post_id'].'" />';
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
		$classes='field-input';

		if (isset($values[$args['id']][0])) :
			$value=$values[$args['id']][0];
		else :
			$value=null;
		endif;

		switch ($args['type']) :
			case 'checkbox':
				$html.='<input type="checkbox" class="'.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" '.checked($value,'on',false).' />';
				break;
			case 'colorpicker' :
				$html.='<input type="text" class="colorPicker" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'date':
				$html.='<input type="text" class="mdw-cms-datepicker" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'date-time':
				//$html.='<input type="text" class="datepicker" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				//$html.='<input type="text" class="timepicker" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'email' :
				$html.='<input type="text" class="email validator '.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
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
			case 'phone':
				$html.='<input type="text" class="phone '.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'radio':
				//$html.='<input type="radio" class="'.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" '.checked($value,'on',false).' /> '.$value;
				break;
			case 'select' :
				$html.='<select name="'.$args['id'].'" id="'.$args['id'].'">';
					$html.='<option>Select One</option>';
					if (isset($args['options']) && is_array($args['options'])) :
						foreach ($args['options'] as $option) :
							$html.='<option value="'.$option['value'].'">'.$option['name'].'</option>';
						endforeach;
					endif;
				$html.='</select>';
				break;
			case 'text' :
				$html.='<input type="text" class="'.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'textarea':
				$html.='<textarea class="textarea '.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'">'.$value.'</textarea>';
				break;
			case 'timepicker' :
				$html.='<input type="text" class="timepicker" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'url':
				$html.='<input type="text" class="url validator '.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
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
			default:
				$html.='<input type="text" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
		endswitch;

		if ($args['repeatable'])
			$html.='<button type="button" class="ajaxmb-field-btn duplicate">Duplicate Field</button>';

		return $html;
	}

	/**
	 * a public function that allows the user to add a field to the meta box
	 * @param array $args
	 							id (field id)
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
					if (isset($config['id']) && $config['id']==$meta_id) :
						$prefix=$config['prefix'];
					elseif (isset($config['mb_id']) && $config['mb_id']==$meta_id) :
						$prefix=$config['prefix'];
					endif;
				endforeach;
			endif;
		endif;

		$new_field=array('id' => '', 'type' => 'text', 'label' => 'Text Box', 'value' => '', 'order' => 0);
		$new_field=array_merge($new_field,$args);

		if (is_numeric($new_field['id']))
			$new_field['id']=$this->generate_field_id($prefix,$new_field['label'],$new_field['id']);

		$this->fields[$new_field['id']]=$new_field;
	}

	/**
	 * generate_field_id function.
	 *
	 * @access public
	 * @param bool $prefix (default: false)
	 * @param bool $label (default: false)
	 * @param bool $field_id (default: false)
	 * @return $id
	 */
	function generate_field_id($prefix=false,$label=false,$field_id=false) {
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

	/**
	 * a variation of the add_fields function
	 * this allows us to generate our fields with a passed array
	 * added 1.1.8
	**/
	function add_fields_array($arr,$meta_id) {
		foreach ($arr as $id => $values) :
			$options=false;
			$repeatable=0;
			$order=0;

			if (isset($values['options']))
				$options=$values['options'];

			if (isset($values['repeatable']))
				$repeatable=1;

			if (isset($values['order']))
				$order=$values['order'];

			$args=array(
				'id' => $id,
				'type' => $values['field_type'],
				'label' => $values['field_label'],
				'order' => $order,
				'options' => $options,
				'repeatable' => $repeatable,
				'duplicate' => 0
			);

			$this->add_field($args,$meta_id);
		endforeach;
	}

	/**
	 * add_post_fields function.
	 *
	 * @access public
	 * @param bool $arr (default: false)
	 * @param bool $metabox (default: false)
	 * @param bool $post_id (default: false)
	 * @return void
	 */
	function add_post_fields($arr=false,$metabox=false,$post_id=false) {
		if (!$arr || empty($arr) || !$metabox || !$post_id)
			return false;

		foreach ($this->config as $config) :
			if ($metabox['args']['meta_box_id']==$config['mb_id']) :
				if (isset($config['post_fields']) && !empty($config['post_fields'])) :
					foreach ($config['post_fields'] as $post_field) :
						if ($post_field['post_id']==$post_id) :
							$args=array(
								'id' => $post_field['field_id'],
								'type' => $post_field['field_type'],
								'label' => $post_field['field_label'],
								'order' => $post_field['order'],
								'options' => 0,
								'repeatable' => 0,
								'duplicate' => 1
							);
							$this->add_field($args,$post_field['metabox_id']);
						endif;
					endforeach;
				endif;
			endif;
		endforeach;
	}

	/**
	 * save_custom_meta_data function.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @return void
	 */
	public function save_custom_meta_data($post_id) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our nonce isn't there, or we can't verify it, bail
		if (!isset($_POST[$this->nonce]) || !wp_verify_nonce($_POST[$this->nonce],plugin_basename(__FILE__))) return;

		// if our current user can't edit this post, bail
		if (!current_user_can('edit_post',$post_id)) return;

		//$this->build_duplicated_boxes($post_id); // must do here again b/c this action is added before we have all the info

		// cycle through config fields and find matches //
		foreach ($this->config as $config) :
			$data=null;
			$prefix=$config['prefix'];

			foreach ($config['fields'] as $id => $field) :
				$field_id=$prefix.'_'.$id;

				if (isset($field['field_id']))
					$field_id=$field['field_id'];

				if (isset($_POST[$field_id])):
					$data=$_POST[$field_id]; // submitted value //
				endif;

				// fix notices on unchecked check boxes //
				//if (get_post_meta($post_id, $field['id']) == "") :
				//	add_post_meta($post_id, $field['id'], $data, true);
				//elseif ($data != get_post_meta($post_id, $field['id'], true)) :

				if ($data=='') :
					delete_post_meta($post_id, $field_id, get_post_meta($post_id, $field_id, true));
				else :
					update_post_meta($post_id, $field_id, $data);
				endif;

			endforeach;

			// if there's specific post fields, look for those //
			if (isset($config['post_fields']) && !empty($config['post_fields'])) :
				foreach ($config['post_fields'] as $post_field) :
					if ($post_field['post_id']==$post_id) :
						$field_id=$post_field['field_id'];

						if (isset($_POST[$field_id])):
							$data=$_POST[$field_id]; // submitted value //
						endif;

						if ($data=='') :
							delete_post_meta($post_id, $field_id, get_post_meta($post_id, $field_id, true));
						else :
							update_post_meta($post_id, $field_id, $data);
						endif;

					endif;
				endforeach;
			endif;

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
	 * setup our config with defaults and adjusments
	**/
	function setup_config($configs=array()) {
		$ran_string=substr(substr("abcdefghijklmnopqrstuvwxyz",mt_rand(0,25),1).substr(md5(time()),1),0,5);
		$default_config=array(
			'mb_id' => 'mdwmb_'.$ran_string,
			'title' => 'Default Meta Box',
			'prefix' => '_mdwmb',
			'post_types' => 'post,page',
			//'duplicate' => 0,
			//'fields' => array(), // for legacy support (pre 1.1.8)
			'post_fields' => array()
		);

		if (empty($configs))
			return false;

		foreach ($configs as $key => $config) :
			$config=array_merge($default_config,$config);

			if (!is_array($config['post_types'])) :
				$config['post_types']=explode(",",$config['post_types']);
			endif;

			$config['prefix']=$this->check_config_prefix($config['prefix']); // makes sure our prefix starts with '_'

			$configs[$key]=$config;
		endforeach;

		return $configs;
	}

/*
	function build_duplicated_boxes($post_id=false) {
		if (!$post_id)
			return false;

		$option_arr=get_option($this->option_name);

		if (!count($option_arr) || !$option_arr)
			return false;

		foreach ($option_arr as $option) :
			if ($option['post_id']==$post_id) :
				$option['removable']=true; // allows us to have a remove button
				array_push($this->config,$option);
			endif;
		endforeach;

		return;
	}
*/

	/**
	 * clean_special_chars function.
	 *
	 * removes all special chars from a string
	 *
	 * @access public
	 * @param mixed $string
	 * @return $string
	 */
	function clean_special_chars($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

		return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}

	/**
	 * ajax_duplicate_metabox_field function.
	 *
	 * adds specific fields to indvidual posts/pages via our duplicate field button
	 * because we use a config array, when the field is duplicated via js, it needs to be added
	 * however, the dup fields apply to individual posts only, so we have an array for them
	 *
	 * @access public
	 * @return void
	 */
	function ajax_duplicate_metabox_field() {
// todo: check empty
		$arr=array(
			'post_id' => $_POST['post_id'],
			'config_key' => $_POST['config_key'], // may not be needed
			'metabox_id' => $_POST['metabox_id'],
			'field_type' => $_POST['field_type'],
			'field_label' => $_POST['field_label'],
			'options' => array(),
			'field_id' => $_POST['field_id'],
			'order' => $_POST['order']
		);

		// check for dups and replace if found //
		$dup_flag=false;
		if (isset($this->config[$_POST['config_key']]['post_fields']) && !empty($this->config[$_POST['config_key']]['post_fields'])) :
			foreach ($this->config[$_POST['config_key']]['post_fields'] as $key => $post_field) :
				if ($post_field['field_id']==$arr['field_id']) :
					$this->config[$_POST['config_key']]['post_fields'][$key]=$arr;
					$dup_flag=true;
				endif;
			endforeach;
		endif;

		// insert if no dup found //
		if (!$dup_flag)
			$this->config[$_POST['config_key']]['post_fields'][]=$arr;

		if (update_option('mdw_cms_metaboxes',$this->config)) :
			echo true;
		else :
			echo false;
		endif;

		wp_die();
	}

	function ajax_remove_duplicate_metabox_field() {
// todo: check empty
		foreach ($this->config[$_POST['config_key']]['post_fields'] as $key => $post_field) :
			if ($post_field['field_id']==$_POST['field_id'])
				unset($this->config[$_POST['config_key']]['post_fields'][$key]);
		endforeach;

		$this->config[$_POST['config_key']]['post_fields']=array_values($this->config[$_POST['config_key']]['post_fields']);

		if (update_option('mdw_cms_metaboxes',$this->config)) :
			echo 'option updated';
		else :
			echo 'option failed to update (may be due to no fields being reomved)';
		endif;

		wp_die();
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
$MDWMetaboxes = new MDWMetaboxes();
?>
