<?php
class MDWCMSgui {

	public $options=array();
	public $version='2.1.8';

	public function __construct() {
		add_action('admin_menu',array($this,'build_admin_menu'));
		add_action('admin_enqueue_scripts',array($this,'scripts_styles'));
		add_action('admin_init','MDWCMSlegacy::setup_legacy_updater');
		add_action('admin_init', array($this, 'update_post_types'));
		add_action('admin_notices','MDWCMSlegacy::legacy_admin_notices');

		//$this->update_mdw_cms_settings();
		$this->check_version();
		$this->cleanup_old_options();

		$this->options['metaboxes']=get_option('mdw_cms_metaboxes');
		$this->options['post_types']=get_option('mdw_cms_post_types');
		$this->options['taxonomies']=get_option('mdw_cms_taxonomies');
	}

	/**
	 * build_admin_menu function.
	 *
	 * @access public
	 * @return void
	 */
	public function build_admin_menu() {
		add_management_page('MDW CMS','MDW CMS','manage_options','mdw-cms', array($this, 'mdw_cms_page'));
	}

	/**
	 * scripts_styles function.
	 *
	 * @access public
	 * @param mixed $hook
	 * @return void
	 */
	public function scripts_styles($hook) {
		$disable_bootstrap=false;

		wp_enqueue_style('mdw-cms-gui-style',plugins_url('/css/admin.css',__FILE__));

		wp_register_script('mdw-cms-admin-metaboxes-script',plugins_url('/js/admin-metaboxes.js',__FILE__),array('metabox-id-check-script'));

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('mdw-cms-gui-mb-script',plugins_url('/js/mb.js',__FILE__),array('jquery'),'1.0.0',true);
		wp_enqueue_script('namecheck-script',plugins_url('/js/jquery.namecheck.js',__FILE__),array('jquery'));
		wp_enqueue_script('metabox-id-check-script',plugins_url('/js/jquery.metabox-id-check.js',__FILE__),array('jquery'));
		wp_enqueue_script('mdw-cms-admin-custom-post-types-script',plugins_url('/js/admin-custom-post-types.js',__FILE__),array('namecheck-script'));
		wp_enqueue_script('mdw-cms-admin-custom-taxonomies-script',plugins_url('/js/admin-custom-taxonomies.js',__FILE__),array('namecheck-script'));


		if (isset($this->options['options']) && is_array($this->options['options']))
			extract($this->options['options']);

		$post_types=get_post_types();
		$types=array();
		foreach ($post_types as $post_type) :
			$types[]=$post_type;
		endforeach;

		$taxonomy_options=array(
			'reservedPostTypes' => $types
		);

		wp_localize_script('mdw-cms-admin-custom-taxonomies-script','wp_options',$taxonomy_options);

		if (isset($this->options['metaboxes'])) :
			$metaboxes=$this->options['metaboxes'];
		else :
			$metaboxes=array();
		endif;

		$mb_arr=array();

		if ($metaboxes && !empty($metaboxes)) :
			foreach ($metaboxes as $metabox) :
				$mb_arr[]=$metabox['mb_id'];
			endforeach;
		endif;

		$metabox_options=array(
			'reserved' => $mb_arr
		);

		wp_localize_script('mdw-cms-admin-metaboxes-script','wp_metabx_options',$metabox_options);

		wp_enqueue_script('mdw-cms-admin-metaboxes-script');
	}

	/**
	 * check_version function.
	 *
	 * @access protected
	 * @return void
	 */
	protected function check_version() {
		$stored_version=get_option('mdw_cms_version');

		if ($stored_version!=$this->version || !$stored_version)
			update_option('mdw_cms_version', $this->version);
	}

	/**
	 * mdw_cms_page function.
	 *
	 * our primary admin page, utlaizes tabs for internal navigation
	 *
	 * @access public
	 * @return void
	 */
	public function mdw_cms_page() {
		$tabs=array(
			'cms-main' => 'Main',
			'mdw-cms-cpt' => 'Custom Post Types',
			'mdw-cms-metaboxes' => 'Metaboxes',
			'mdw-cms-tax' => 'Custom Taxonomies'
		);

		if (isset( $_GET[ 'tab' ] )) :
			$active_tab=$_GET[ 'tab' ];
		else :
			$active_tab='cms-main';
		endif;
		?>

		<div class="wrap mdw-cms-wrap">

			<h1>MDW CMS</h1>

			<h2 class="nav-tab-wrapper">
				<?php
				foreach ($tabs as $tab => $name) :
					if ($active_tab==$tab) :
						$class='nav-tab-active';
					else :
						$class=null;
					endif;
					?>
					<a href="?page=mdw-cms&tab=<?php echo $tab; ?>" class="nav-tab <?php echo $class; ?>"><?php echo $name; ?></a>
				<?php endforeach; ?>
			</h2>

			<?php
			switch ($active_tab) :
				case 'cms-main':
					$html.=mdw_cms_get_template('main');
					break;
				case 'mdw-cms-cpt':
					echo mdw_cms_get_template('custom-post-types');
					break;
				case 'mdw-cms-metaboxes':
					echo mdw_cms_get_template('metaboxes');
					break;
				case 'mdw-cms-tax':
					echo mdw_cms_get_template('custom-taxonomies');
					break;
				default:
					echo mdw_cms_get_template('main');
					break;
			endswitch;
			?>

		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * build_field_rows function.
	 *
	 * @access public
	 * @param string $field_id (default: '')
	 * @param string $field (default: '')
	 * @param int $order (default: 0)
	 * @param string $classes (default: '')
	 * @return void
	 */
	function build_field_rows($field_id='', $field='', $order=0, $classes='') {
		// prep vars to pass //
		$attributes=array();
		$attributes['field_id']=$field_id;
		$attributes['field']=$field;
		$attributes['order']=$order;
		$attributes['classes']=$classes;

		echo mdw_cms_get_template('metabox-field-rows', $attributes);
	}

	/**
	 * update_options function.
	 *
	 * @access public
	 * @param mixed $options
	 * @return void
	 */
	public function update_options($options) {
		if (!$options['update'])
			return false;

		$new_options=$options;
		unset($new_options['update']); // a temp var passed, remove it

		update_option('mdw_cms_options', $new_options);

		$this->options=get_option('mdw_cms_options');
	}

	/**
	 * runs all of our update and edit functions
	 * called in __construct and run before everything so that our options are updated before page load
	 */
	function update_mdw_cms_settings() {
		$post_types=get_option('mdw_cms_post_types');
		$metaboxes=get_option('mdw_cms_metaboxes');
		$taxonomies=get_option('mdw_cms_taxonomies');

		// add metabox //
		if (isset($_POST['update-metabox']) && $_POST['update-metabox']=='Create') :
			if ($this->update_metaboxes($_POST)) :
				$this->admin_notices('updated','Metabox has been created.');
				// redirect ?? //
				if (!function_exists('wp_get_current_user')) :
					include(ABSPATH . "wp-includes/pluggable.php");
				endif;

				wp_redirect(admin_url('tools.php?page=mdw-cms&tab=mdw-cms-metaboxes&edit=mb&mb_id='.$_POST['mb_id']));
				exit;
			else :
				$this->admin_notices('error','There was an issue creating the metabox.');
			endif;
		endif;

		// update/edit metabox //
		if (isset($_POST['update-metabox']) && $_POST['update-metabox']=='Update') :
			if ($this->update_metaboxes($_POST)) :
				$this->admin_notices('updated','Metabox has been updated.');
			else :
				$this->admin_notices('error','There was an issue updating the metabox.');
			endif;
		endif;

		// remove metabox //
		if (isset($_GET['delete']) && $_GET['delete']=='mb') :
			foreach ($metaboxes as $key => $mb) :
				if ($mb['mb_id']==$_GET['mb_id']) :
					unset($metaboxes[$key]);
					$this->admin_notices('updated','Metabox has been removed.');
				endif;
			endforeach;

			$metaboxes=array_values($metaboxes);

			update_option('mdw_cms_metaboxes',$metaboxes);
		endif;

		// create custom taxonomy //
		if (isset($_POST['add-tax']) && $_POST['add-tax']=='Create') :
			if ($this->update_taxonomies($_POST)) :
				$this->admin_notices('updated','Taxonomy has been created.');
			else :
				$this->admin_notices('error','There was an issue creating the taxonomy.');
			endif;
		endif;

		// update/edit taxonomy //
		if (isset($_POST['add-tax']) && $_POST['add-tax']=='Update') :
			if ($this->update_taxonomies($_POST)) :
				$this->admin_notices('updated','Taxonomy has been updated.');
			else :
				$this->admin_notices('error','There was an issue updating the taxonomy.');
			endif;
		endif;

		// remove taxonomy //
		if (isset($_GET['delete']) && $_GET['delete']=='tax') :
			foreach ($taxonomies as $key => $tax) :
				if ($tax['name']==$_GET['slug']) :
					unset($taxonomies[$key]);
					$this->admin_notices('updated','Taxonomy has been deleted.');
				endif;
			endforeach;

			$taxonomies=array_values($taxonomies);

			update_option('mdw_cms_taxonomies',$taxonomies);
		endif;
	}

	/**
	 * update_metaboxes function.
	 *
	 * updates our metabox settings and its fields
	 *
	 * @access public
	 * @static
	 * @param array $data (default: array())
	 * @return void
	 */
	public static function update_metaboxes($data=array()) {
		global $MDWMetaboxes;

		$metaboxes=get_option('mdw_cms_metaboxes');
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
				if (!$field['field_type']) :
					unset($data['fields'][$key]);
				else :
					$data['fields'][$key]['field_id']=$MDWMetaboxes->generate_field_id($prefix,$field['field_label']); // add id
					// remove empty options fields //
					if (isset($field['options'])) :
						unset($data['fields'][$key]['options']['default']);
						$data['fields'][$key]['options']=array_values($data['fields'][$key]['options']);
					endif;
				endif;
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

		return update_option('mdw_cms_metaboxes',$metaboxes);
	}

	/**
	 * update_taxonomies function.
	 *
	 * @access public
	 * @param array $data (default: array())
	 * @return void
	 */
	function update_taxonomies($data=array()) {
		$option_exists=false;
		$taxonomies=get_option('mdw_cms_taxonomies');

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

		if (get_option('mdw_cms_taxonomies'))
			$option_exists=true;

		$update=update_option('mdw_cms_taxonomies',$taxonomies);

		if ($update) :
			return true;
		elseif ($option_exists) :
			return true;
		else :
			return false;
		endif;
	}

	/**
	 * get_post_types_list function.
	 *
	 * @access public
	 * @param bool $selected_pt (default: false)
	 * @param string $output (default: 'checkbox')
	 * @return void
	 */
	public function get_post_types_list($selected_pt=false, $output='checkbox') {
		$html=null;
		$args=array(
			'public' => true
		);
		$post_types_arr=get_post_types($args);

		$html.='<tr class="post-type-list-admin">';
			$html.='<th scope="row">';
				$html.='<label for="post_type">Post Type</label>';
			$html.='</th>';

			$html.='<td class="post-types-cbs">';
				$counter=0;
				foreach ($post_types_arr as $type) :
					if ($counter==0) :
						$class='first';
					else :
						$class='';
					endif;

					if ($selected_pt && in_array($type,$selected_pt)) :
						$checked='checked=checked';
					else :
						$checked=null;
					endif;

					$html.='<input type="checkbox" name="post_types[]" value="'.$type.'" '.$checked.'>'.$type.'<br />';

					$counter++;
				endforeach;
			$html.='</td>';
		$html.='</tr>';

		return $html;
	}

	public function update_post_types() {
		if (!isset($_POST['mdw_cms_admin']) || !wp_verify_nonce($_POST['mdw_cms_admin'], 'update_cpts'))
			return false;

		$data=$_POST;
		$post_types=get_option('mdw_cms_post_types');
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
			'hierarchical' => $data['hierarchical'],
			'page_attributes' => $data['page_attributes'],
			'comments' => $data['comments']
		);

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
		if ($post_types_s==serialize($post_types))
			return true;

		$this->options['post_types']=$post_types; // set var

		return update_option('mdw_cms_post_types', $post_types);

/*
		// remove custom post type //
		if (isset($_GET['delete']) && $_GET['delete']=='cpt') :
			foreach ($post_types as $key => $cpt) :
				if ($cpt['name']==$_GET['slug']) :
					unset($post_types[$key]);
					$this->admin_notices('updated','Post type has been deleted.');
				endif;
			endforeach;

			$post_types=array_values($post_types);

			update_option('mdw_cms_post_types',$post_types);
		endif;
*/
	}

	/**
	 * cleanup_old_options function.
	 *
	 * @access protected
	 * @return void
	 */
	protected function cleanup_old_options() {
		$version_check='2.1.8';
		$version_cleaned=get_option('mdw_cms_options_clean_up', false);

		if ($this->version<='2.1.8' && !$version_cleaned) :
			$options=get_option('mdw_cms_options', array());
			$metabox_options=get_option('mdw_cms_metaboxes', array());
			$post_type_options=get_option('mdw_cms_post_types', array());
			$taxonomies_options=get_option('mdw_cms_taxonomies', array());

			// check we have metaboxes to migrate //
			if (isset($options['metaboxes']) && !empty($options['metaboxes'])) :
				foreach ($options['metaboxes'] as $key => $metabox) :
					$flag=0;

					// see if we have a match and merge //
					foreach ($metabox_options as $mb_key => $mb) :
						if ($metabox['mb_id']==$mb['mb_id']) :
							$metabox_options[$mb_key]=mdw_cms_parse_args($metabox, $mb); //merge
							$flag=1; // set flag
						endif;
					endforeach;

					// check flag and add if not set //
					if (!$flag)
						$metabox_options[]=$metabox;

					unset($options['metaboxes'][$key]); // remove old option
				endforeach;

				// update in db //
				update_option('mdw_cms_options', $options);
				update_option('mdw_cms_metaboxes', $metabox_options);
			endif;

			// check we have post types to migrate //
			if (isset($options['post_types']) && !empty($options['post_types'])) :
				foreach ($options['post_types'] as $key => $post_type) :
					$flag=0;

					// see if we have a match and merge //
					foreach ($post_type_options as $pt_key => $pt) :
						if ($post_type['name']==$pt['name']) :
							$post_type_options[$pt_key]=mdw_cms_parse_args($post_type, $pt); //merge
							$flag=1; // set flag
						endif;
					endforeach;

					// check flag and add if not set //
					if (!$flag)
						$post_type_options[]=$post_type;

					unset($options['post_types'][$key]); // remove old option
				endforeach;

				// update in db //
				update_option('mdw_cms_options', $options);
				update_option('mdw_cms_post_types', $post_type_options);
			endif;

			// check we have taxonomies to migrate //
			if (isset($options['taxonomies']) && !empty($options['taxonomies'])) :
				foreach ($options['taxonomies'] as $key => $tax) :
					$flag=0;

					// see if we have a match and merge //
					foreach ($taxonomies_options as $t_key => $t) :
						if ($tax['name']==$t['name']) :
							$taxonomies_options[$t_key]=mdw_cms_parse_args($tax, $t); //merge
							$flag=1; // set flag
						endif;
					endforeach;

					// check flag and add if not set //
					if (!$flag)
						$taxonomies_options[]=$tax;

					unset($options['taxonomies'][$key]); // remove old option
				endforeach;

				// update in db //
				update_option('mdw_cms_options', $options);
				update_option('mdw_cms_taxonomies', $taxonomies_options);
			endif;

			update_option('mdw_cms_options_clean_up', true); // no need to run again
		endif;
	}

}

$mdw_cms_admin=new MDWCMSgui();
?>
