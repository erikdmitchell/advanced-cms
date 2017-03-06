<?php
global $advanced_cms_admin;

/**
 * AdvancedCMSAdmin class.
 */
class AdvancedCMSAdmin {

	public $options=array();

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action('admin_menu', array($this, 'build_admin_menu'));
		add_action('admin_enqueue_scripts',array($this,'scripts_styles'));
		add_action('admin_init', array($this, 'update_post_types'));
		add_action('admin_init', array($this, 'update_metaboxes'));
		add_action('admin_init', array($this, 'update_taxonomies'));
		add_action('admin_notices', array($this, 'admin_notices'));
		
		add_action('wp_ajax_advanced_cms_get_metabox', array($this, 'ajax_get_metabox'));
		add_action('wp_ajax_advanced_cms_delete_metabox', array($this, 'ajax_delete_metabox'));
		add_action('wp_ajax_advanced_cms_get_post_type', array($this, 'ajax_get_post_type'));
		add_action('wp_ajax_advanced_cms_delete_post_type', array($this, 'ajax_delete_post_type'));
		add_action('wp_ajax_advanced_cms_get_taxonomy', array($this, 'ajax_get_taxonomy'));
		add_action('wp_ajax_advanced_cms_delete_taxonomy', array($this, 'ajax_delete_taxonomy'));
		add_action('wp_ajax_advanced_cms_reserved_names', array($this, 'ajax_reserved_names'));

		$this->options['metaboxes']=get_option('advanced_cms_metaboxes');
		$this->options['post_types']=get_option('advanced_cms_post_types', array());
		$this->options['taxonomies']=get_option('advanced_cms_taxonomies', array());
	}

	/**
	 * build_admin_menu function.
	 *
	 * @access public
	 * @return void
	 */
	public function build_admin_menu() {
		add_menu_page('Advanced CMS', 'Advanced CMS', 'manage_options', 'advanced-cms', array($this, 'admin_page'), 'dashicons-layout');
	}

	/**
	 * scripts_styles function.
	 *
	 * @access public
	 * @param mixed $hook
	 * @return void
	 */
	public function scripts_styles($hook) {
		global $advancedMetaboxes;

		wp_register_script('advanced-cms-admin-metaboxes', plugins_url('/admin/js/metaboxes.js', __FILE__), array('jquery'), '0.2.0');

		// localize scripts //
		$metaboxes_arr=array(
			'fields' => $advancedMetaboxes->fields,
		);

		wp_localize_script('advanced-cms-admin-metaboxes', 'metaboxData', $metaboxes_arr);

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('namecheck-script',plugins_url('/js/jquery.namecheck.js',__FILE__), array('jquery'), '0.1.0');
		wp_enqueue_script('metabox-id-check-script',plugins_url('/js/jquery.metabox-id-check.js',__FILE__), array('jquery'), '0.1.0');
		wp_enqueue_script('taxonomy-id-check-script',plugins_url('/js/jquery.taxonomy-id-check.js',__FILE__), array('jquery'), '0.1.0');
		wp_enqueue_script('requiredFields-script',plugins_url('/js/jquery.requiredFields.js',__FILE__), array('jquery'), '0.1.0');
		wp_enqueue_script('advanced-cms-admin-functions', plugins_url('/admin/js/functions.js', __FILE__), array('jquery'), '0.1.0');
		wp_enqueue_script('advanced-cms-admin-post-types', plugins_url('/admin/js/post-types.js', __FILE__), array('jquery'), '0.1.0');
		wp_enqueue_script('advanced-cms-admin-taxonomies', plugins_url('/admin/js/taxonomies.js', __FILE__), array('jquery'), '0.1.0');
		wp_enqueue_script('advanced-cms-admin-metaboxes');

		wp_enqueue_style('advanced-cms-admin-style',plugins_url('/admin/css/admin.css',__FILE__));
		wp_enqueue_style('advanced-cms-admin-style', plugins_url('/admin/css/metaboxes.css', __FILE__));
	}

	/**
	 * admin_notices function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_notices() {
		if (isset($_GET['edit'])) :
			switch ($_GET['edit']) :
				case 'cpt' :
					$type='Post Type';
					break;
				case 'mb' :
					$type='Metabox';
					break;
				case 'tax':
					$type='Taxonomy';
					break;
				default :
					$type='';
			endswitch;
		endif;

		if (isset($_GET['updated'])) :
			if ($_GET['updated']) :
				echo '<div class="notice notice-success is-dismissible"><p>'.$type.' was updated</p></div>';
			else :
				echo '<div class="notice notice-error is-dismissible"><p>'.$type.' was not updated</p></div>';
			endif;
		endif;
	}

	/**
	 * admin_page function.
	 *
	 * our primary admin page, utlaizes tabs for internal navigation
	 *
	 * @access public
	 * @return void
	 */
	public function admin_page() {
		$active_tab='cms-main';
		$tabs=array(
			'cms-main' => 'Main',
			'post-types' => 'Post Types',
			'metaboxes' => 'Metaboxes',
			'taxonomies' => 'Taxonomies'
		);

		if (isset( $_GET[ 'tab' ] ))
			$active_tab=$_GET['tab'];
		?>

		<div class="wrap advanced-cms-wrap">

			<h1>Advanced CMS</h1>

			<h2 class="nav-tab-wrapper">
				<?php
				foreach ($tabs as $tab => $name) :
					if ($active_tab==$tab) :
						$class='nav-tab-active';
					else :
						$class=null;
					endif;
					?>
					<a href="?page=advanced-cms&tab=<?php echo $tab; ?>" class="nav-tab <?php echo $class; ?>"><?php echo $name; ?></a>
				<?php endforeach; ?>
			</h2>

			<?php
			switch ($active_tab) :
				case 'cms-main':
					if (isset($_GET['documentation']) && !empty($_GET['documentation'])) :
						echo advanced_cms_get_doc_template($_GET['documentation']);
					else :
						echo advanced_cms_get_admin_page('main');
					endif;
					break;
				case 'post-types':
					if (isset($_GET['action']) && $_GET['action']=='update') :
						echo advanced_cms_get_admin_page('single-post-type');
					else :
						echo advanced_cms_get_admin_page('post-types');
					endif;
					break;
				case 'metaboxes':
					if (isset($_GET['action']) && $_GET['action']=='update') :
						echo advanced_cms_get_admin_page('single-metabox');
					else :
						echo advanced_cms_get_admin_page('metaboxes');
					endif;
					break;
				case 'taxonomies':
					if (isset($_GET['action']) && $_GET['action']=='update') :
						echo advanced_cms_get_admin_page('single-taxonomy');
					else :
						echo advanced_cms_get_admin_page('taxonomies');
					endif;
					break;
				default:
					echo advanced_cms_get_admin_page('main');
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
		$field['field_id']=$field_id;
		$field['order']=$order;
		$field['classes']=$classes;

		echo advanced_cms_get_admin_page('metabox-field-rows', $field);
	}

	/**
	 * update_metaboxes function.
	 *
	 * @access public
	 * @return void
	 */
	public function update_metaboxes() {
		if (!isset($_POST['advanced_cms_admin']) || !wp_verify_nonce($_POST['advanced_cms_admin'], 'update_metaboxes'))
			return false;

		global $advancedMetaboxes;

		$data=$_POST;
		$metaboxes=get_option('advanced_cms_metaboxes');
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
					$data['fields'][$key]['field_id']=$advancedMetaboxes->generate_field_id($prefix, $field['field_label']); // add id

					// remove empty options fields //
					if (isset($field['options'])) :

						foreach ($field['options'] as $_key => $option) :
							if (empty($option['name']) || empty($option['value']) || $option['name']=='') :
								unset($field['options'][$_key]);
							endif;
						endforeach;

						$data['fields'][$key]['options']=$field['options'];
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

		$this->options['metaboxes']=$metaboxes; // set var

		update_option('advanced_cms_metaboxes', $metaboxes);

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

	/**
	 * update_taxonomies function.
	 *
	 * @access public
	 * @return void
	 */
	public function update_taxonomies() {
		if (!isset($_POST['advanced_cms_admin']) || !wp_verify_nonce($_POST['advanced_cms_admin'], 'update_taxonomies'))
			return false;

		$data=$_POST;
		$option_exists=false;
		$taxonomies=get_option('advanced_cms_taxonomies');

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

		if (get_option('advanced_cms_taxonomies'))
			$option_exists=true;

		$this->options['taxonomies']=$taxonomies; // set var

		$update=update_option('advanced_cms_taxonomies',$taxonomies);

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
		$selected_pt=(array) $selected_pt;

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

					if ($selected_pt && in_array($type, (array) $selected_pt)) :
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

	/**
	 * update_post_types function.
	 *
	 * @access public
	 * @return void
	 */
	public function update_post_types() {
		if (!isset($_POST['advanced_cms_admin']) || !wp_verify_nonce($_POST['advanced_cms_admin'], 'update_cpts'))
			return false;

		$data=$_POST;
		$post_types=get_option('advanced_cms_post_types');
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
			'excerpt' => $data['excerpt'],
			'comments' => $data['comments'],
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

		$update=update_option('advanced_cms_post_types', $post_types);

		wp_redirect($url);
		exit();
	}

	/**
	 * ajax_get_post_type function.
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_get_post_type() {
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

	/**
	 * ajax_delete_post_type function.
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_delete_post_type() {
		if (!isset($_POST['name']))
			return false;

		if ($this->delete_post_type($_POST['name']))
			return true;

		return false;

		wp_die();
	}

	/**
	 * advanced_cms_delete_post_type function.
	 *
	 * @access public
	 * @param string $name (default: '')
	 * @return void
	 */
	public function delete_post_type($name='') {
		$post_types=array();

		// build clean array //
		foreach ($this->options['post_types'] as $key => $post_type) :
			if ($post_type['name']!=$name)
				$post_types[]=$post_type;
		endforeach;

		$this->options['post_types']=$post_types; // set var

		update_option('advanced_cms_post_types', $post_types); // update option

		return false;
	}

	/**
	 * ajax_get_metabox function.
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_get_metabox() {
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

	/**
	 * ajax_delete_metabox function.
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_delete_metabox() {

		if (!isset($_POST['id']))
			return;

		if ($this->delete_metabox($_POST['id']))
			return true;

		return;

		wp_die();
	}

	/**
	 * delete_metabox function.
	 *
	 * @access public
	 * @param string $id (default: '')
	 * @return void
	 */
	public function delete_metabox($id='') {
		$metaboxes=array();

		// build clean array //
		foreach ($this->options['metaboxes'] as $key => $metabox) :
			if ($metabox['mb_id']!=$id)
				$metaboxes[]=$metabox;
		endforeach;

		$this->options['metaboxes']=$metaboxes; // set var

		update_option('advanced_cms_metaboxes', $metaboxes); // update option

		return false;
	}

	/**
	 * ajax_get_taxonomy function.
	 *
	 * @access public
	 * @return void
	 */
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

		update_option('advanced_cms_taxonomies', $taxonomies); // update option

		return false;
	}

	/**
	 * ajax_reserved_names function.
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_reserved_names() {
		if (empty($_POST['type']))
			return;

		$reserved_names=array();
		$types=$_POST['type'];

		if (!is_array($types))
			$types=explode(',', $types);

		foreach ($types as $type) :
			switch ($type) :
				case 'post' :
					$post_types=get_post_types(array(), 'names');
					foreach ($post_types as $post_type) :
						$reserved_names[]=$post_type;
					endforeach;
					break;
				case 'metabox' :
					$metaboxes=$this->get_wp_metabox_slugs();
					foreach ($metaboxes as $metabox) :
						$reserved_names[]=$metabox;
					endforeach;
					break;
				case 'taxonomy' :
					$taxonomies=get_taxonomies();
					foreach ($taxonomies as $taxonomy) :
						$reserved_names[]=$taxonomy;
					endforeach;
					break;
			endswitch;
		endforeach;

		echo json_encode($reserved_names);

		wp_die();
	}

	/**
	 * get_wp_metabox_slugs function.
	 *
	 * @access public
	 * @return void
	 */
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

	/**
	 * admin_url function.
	 *
	 * @access protected
	 * @param string $args (default: '')
	 * @return void
	 */
	protected function admin_url($args='') {
		$default_args=array(
			'page' => 'advanced-cms'
		);
		$args=wp_parse_args($args, $default_args);
		$admin_url=add_query_arg($args, admin_url('/tools.php'));

		return $admin_url;
	}

}

$advanced_cms_admin=new AdvancedCMSAdmin();
?>
