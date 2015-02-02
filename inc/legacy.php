<?php
if (!class_exists('MDW_CPT')) :

	class MDW_CPT {

		function __construct() { }

		/**
		 * adds post types (slug) to our post_types array
		 * @param string/array $args - the slug name of the post type(s)
		 */
		public function add_post_types($args) {
			$Words=new Inflector();
			$post_types=array();
			$clean_post_types=array();
			$mdw_cms_post_types=get_option('mdw_cms_post_types');
			$post_types=array();

			if (is_numeric(key($args))) :
				foreach ($args as $type) :
					$post_types[$type]=array();
				endforeach;
			else :
				$post_types=$args;
			endif;

			// clean and setup for migration //
			foreach ($post_types as $name => $post_type) :
				$title=0;
				$thumbail=0;
				$editor=0;
				$revisions=0;
				$hierarchical=0;
				$word_type='plural';

				if (isset($args['word_type']))
					$word_type=$args['word_type'];

				// format our post type by forcing it to lowercase and replacing spaces with hyphens //
				$post_type_name=strtolower($name);
				$post_type_name=str_replace(' ','-',$post_type_name);

				$post_type_name_mod=str_replace('-',' ',$post_type_name);

				// WILL NEED TO REDO SPACES FOR FORMAL
				if ($word_type=='plural') :
					$post_type_plural=$post_type_name;
					$post_type_formal=$Words->singularize(ucwords($post_type_name_mod));
					$post_type_formal_plural=ucwords($post_type_plural);
				else :
					$post_type_plural=$Words->pluralize($post_type_name);
					$post_type_formal=ucwords($post_type_name_mod);
					$post_type_formal_plural=ucwords($post_type_plural);
				endif;

				if (in_array('title',$post_type['supports']))
					$title=1;

				if (in_array('thumbnail',$post_type['supports']))
					$thumbail=1;

				if (in_array('editor',$post_type['supports']))
					$editor=1;

				if (in_array('revisions',$post_type['supports']))
					$revisions=1;

				if (isset($post_type['hierarchical']) && $post_type['hierarchical'])
					$hierarchical=1;

				$clean_post_types[]=array(
			    'name' => $name,
			    'label' => $post_type_formal,
			    'singular_label' => $post_type_formal_plural,
			    'description' => '',
			    'title' => $title,
			    'thumbnail' => $thumbail,
			    'editor' => $editor,
			    'revisions' => $revisions,
			    'hierarchical' => $hierarchical,
			    'add-cpt' => 'Create',
			    'cpt-id' => -1,
				);
			endforeach;

			foreach ($clean_post_types as $pt) :
				$flag=false;

				if (!empty($mdw_cms_post_types)) :
					foreach ($mdw_cms_post_types as $mdw_pt) :
						if ($mdw_pt['name']==$pt['name'])
							$flag=true;
					endforeach;
				endif;

				if (!$flag) :
					MDWCMSlegacy::$admin_notices=array(); // reset to "clear" before our new results
					MDWCMSlegacy::$admin_notices[]=array(
						'class' => 'updated',
						'message' => 'MDW CMS Updated custom post types.'
					);
					//MDWCMSgui::update_custom_post_types($pt);
				endif;
			endforeach;
		}

	}

	$mdw_custom_post_types=new MDW_CPT();

endif;

if (!class_exists('mdw_Meta_Box')) :

	class mdw_Meta_Box {

		function __construct($config=array()) {
			if (!$this->is_multi($config)) :
				$config=$this->convert_to_multi($config);
			endif;

			$this->config=$this->setup_config($config); // set our config

			$this->convert_metaboxes();
		}

		/**
		 * is array multidimensional (check only first level)
		 * takes in array
		 * returns true/false
		 */
		function is_multi($a) {
			if (isset($a[0]))
				return true;

			return false;
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
		 *
		 */
		function convert_metaboxes() {
			$metaboxes=false;

			foreach ($this->config as $metabox) :
				$fields_arr=array();

				foreach ($metabox['fields'] as $field_slug => $field) :
					$fields_arr[]=array(
						'field_type' => $field['type'],
						'field_label' => $field['label'],
						'options' => array(
							'default' => array(
								'name' => '',
								'value' => ''
							)
						),
					);
				endforeach;

				$new_arr[]=array(
					'mb_id' => $metabox['id'],
					'title' => $metabox['title'],
					'prefix' => $metabox['prefix'],
					'post_types' => $metabox['post_types'],
					'fields' => $fields_arr,
					'update-metabox' => 'Create'
				);
			endforeach;

			foreach ($new_arr as $mb) :
				//$metaboxes=MDWCMSgui::update_metaboxes($mb);

				if ($metaboxes) :
					MDWCMSlegacy::$admin_notices[]=array(
						'class' => 'updated',
						'message' => $mb['title'].' metabox migrated.'
					);
				else :
					MDWCMSlegacy::$admin_notices[]=array(
						'class' => 'error',
						'message' => $mb['title'].' metabox failed to migrate.'
					);
				endif;
			endforeach;
		}

	}

endif;

/**
 * MDWCMSlegacy class.
 */
class MDWCMSlegacy {

	public static $admin_notices=array();
	public static $config_file=false;

	function __construct() {
		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));
		add_action('wp_ajax_run_legacy_upgrade',array($this,'ajax_run_legacy_upgrade'));
		add_action('wp_ajax_legacy_upgrade_remove_file',array($this,'ajax_legacy_upgrade_remove_file'));
	}

	/**
	 * admin_scripts_styles function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_scripts_styles() {
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('mdwcms-legacy-script',plugins_url('/js/legacy.js',dirname(__FILE__)),array('jquery','jquery-ui-dialog'),'1.0.0',true);

		if (self::$config_file) :
			$js_arr=array(
				'file' => self::$config_file
			);

			wp_localize_script('mdwcms-legacy-script','options',$js_arr);
		endif;
	}

	/**
	 * get_legacy_updater function.
	 *
	 * look for a config file in the plugins dir, this is utalized if it exists and is not over written by the plugin
	 * to prevent over writes, we use a wp option now
 	 *
	 * @access public
	 * @return void
	 */
	public static function setup_legacy_updater() {
		//$html=null;
		$plugin_root_path=ABSPATH.'wp-content/plugins/';

/*
		if (get_option('mdw_cms_version')) :
			self::$admin_notices[]=array(
				'class' => 'updated',
				'message' => 'MDW CMS Version '.get_option('mdw_cms_version').' is in use.'
			);
		else :
*/
			if (file_exists($plugin_root_path.'mdw-cms-config.php')) :
				self::$admin_notices[]=array(
					'class' => 'error',
					'message' => 'MDW CMS is currently using a custom config file. Please update to the latest version of the plugin.'
				);

				self::$config_file=$plugin_root_path.'mdw-cms-config.php';
			else :
				self::$admin_notices[]=array(
					'class' => 'error',
					'message' => 'MDW CMS is currently using the default config file. Please update to the latest version of the plugin.'
				);

				self::$config_file=plugin_dir_path(__FILE__).'mdw-cms-config-sample.php';
			endif;
		//endif;

		//return $html;
	}

	/**
	 * legacy_admin_notices function.
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	public static function legacy_admin_notices($return=false) {
		$html=null;

		foreach (self::$admin_notices as $notice) :
			$html.='<div class="'.$notice['class'].'"><p>'.$notice['message'].'</p></div>';
		endforeach;

		if ($return) :
			return $html;
		else :
			echo $html;
		endif;
	}

	public static function get_legacy_page() {
		$html=null;

		if (!self::$config_file)
			return false;

		$html.='<h3>MDW CMS Updater</h3>';

		$html.='<div id="mdwcms-updater-notices"></div>';

		$html.='<p>The new version of the MDW CMS plugin utilizes a graphical user interface (GUI). As a result, an upgrade needs to be run. Click on the button below to complete the process.</p>';
		$html.='<p class="submit">';
			$html.='<input type="button" name="run-upgrade" id="run-upgrade" class="button button-primary" value="Run Upgrade">';
			$html.='<input type="button" name="clear-files" id="clear-files" class="button button-primary" value="Clear Config Files" disabled>';
		$html.='</p>';

		return $html;
	}

	public static function legacy_remove_old_config_file($file) {

	}

	/**
	 * ajax_run_legacy_upgrade function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_run_legacy_upgrade() {
		global $mdw_custom_post_types;

		$return_arr=array();

		if (!isset($_POST['action']) || $_POST['action']!='run_legacy_upgrade')
			return false;

		require_once($_POST['file']);

		$return_arr['notices']=self::legacy_admin_notices(true);

		echo json_encode($return_arr);

		exit;
	}

	function ajax_legacy_upgrade_remove_file() {
		$return_arr=array();

		if (!isset($_POST['action']) || $_POST['action']!='legacy_upgrade_remove_file')
			return false;

		MDWCMSlegacy::$admin_notices=array(); // reset to "clear" before our new results

		//if (unlink($_POST['file'])) :
		if (false) :
			MDWCMSlegacy::$admin_notices[]=array(
				'class' => 'updated',
				'message' => 'Config file removed.'
			);
		else :
			MDWCMSlegacy::$admin_notices[]=array(
				'class' => 'error',
				'message' => 'Failed to remove config file'
			);
		endif;

		$return_arr['notices']=self::legacy_admin_notices(true);

		echo json_encode($return_arr);

		exit;
	}

}
new MDWCMSlegacy();
?>
