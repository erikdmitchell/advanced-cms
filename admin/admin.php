<?php
global $pickle_cms_admin;

/**
 * PickleCMSAdmin class.
 */
class PickleCMSAdmin {

	public $options=array();

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action('admin_menu', array($this, 'build_admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'scripts_styles'));
		add_action('admin_init', array($this, 'update_post_types'));
		add_action('admin_init', array($this, 'update_metaboxes'));
		add_action('admin_init', array($this, 'update_taxonomies'));
		add_action('admin_notices', array($this, 'admin_notices'));
		
		add_action('wp_ajax_pickle_cms_get_metabox', array($this, 'ajax_get_metabox'));
		add_action('wp_ajax_pickle_cms_delete_metabox', array($this, 'ajax_delete_metabox'));
		add_action('wp_ajax_pickle_cms_get_post_type', array($this, 'ajax_get_post_type'));
		add_action('wp_ajax_pickle_cms_delete_post_type', array($this, 'ajax_delete_post_type'));
		add_action('wp_ajax_pickle_cms_get_taxonomy', array($this, 'ajax_get_taxonomy'));
		add_action('wp_ajax_pickle_cms_delete_taxonomy', array($this, 'ajax_delete_taxonomy'));
		add_action('wp_ajax_pickle_cms_reserved_names', array($this, 'ajax_reserved_names'));
		add_action('wp_ajax_pickle_cms_blank_metabox_field', array($this, 'ajax_blank_metabox_field'));

		$this->options['metaboxes']=$this->get_option('pickle_cms_metaboxes', array());
		$this->options['post_types']=$this->get_option('pickle_cms_post_types', array());
		$this->options['taxonomies']=$this->get_option('pickle_cms_taxonomies', array());
		$this->options['columns']=$this->get_option('pickle_cms_admin_columns', array());
	}
	
	/**
	 * get_option function.
	 * 
	 * @access protected
	 * @param string $name (default: '')
	 * @param string $default (default: '')
	 * @return void
	 */
	protected function get_option($name='', $default='') {
		$option=get_option($name, $default);
		
		if ($option=='')
			$option=$default;

		return $option;
	}

	/**
	 * build_admin_menu function.
	 *
	 * @access public
	 * @return void
	 */
	public function build_admin_menu() {
		add_menu_page('Pickle CMS', 'Pickle CMS', 'manage_options', 'pickle-cms', array($this, 'admin_page'), 'dashicons-layout');
	}

	/**
	 * scripts_styles function.
	 *
	 * @access public
	 * @param mixed $hook
	 * @return void
	 */
	public function scripts_styles($hook) {
		global $wp_scripts;

		$ui = $wp_scripts->query('jquery-ui-core');

		wp_register_script('pickle-cms-admin-metaboxes', PICKLE_CMS_ADMIN_URL.'js/metaboxes.js', array('jquery'), '0.2.0');

		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('namecheck-script', PICKLE_CMS_URL.'js/jquery.namecheck.js', array('jquery'), '0.1.0');
		wp_enqueue_script('metabox-id-check-script', PICKLE_CMS_URL.'js/jquery.metabox-id-check.js', array('jquery'), '0.1.0');
		wp_enqueue_script('taxonomy-id-check-script', PICKLE_CMS_URL.'js/jquery.taxonomy-id-check.js', array('jquery'), '0.1.0');
		wp_enqueue_script('requiredFields-script', PICKLE_CMS_ADMIN_URL.'js/jquery.requiredFields.js', array('jquery'), '0.1.0');
		wp_enqueue_script('pickle-cms-admin-functions', PICKLE_CMS_ADMIN_URL.'js/functions.js', array('jquery'), '0.1.0');
		wp_enqueue_script('pickle-cms-admin-post-types', PICKLE_CMS_ADMIN_URL.'js/post-types.js', array('jquery-ui-dialog'), '0.1.0');
		wp_enqueue_script('pickle-cms-admin-taxonomies', PICKLE_CMS_ADMIN_URL.'js/taxonomies.js', array('jquery'), '0.1.0');
		wp_enqueue_script('pickle-cms-fields-script', PICKLE_CMS_ADMIN_URL.'js/fields.js', array('jquery'), '0.1.0', true);
		wp_enqueue_script('pickle-cms-admin-columns-script', PICKLE_CMS_ADMIN_URL.'js/admin-columns.js', array('jquery'), '0.1.0', true);
		
		wp_enqueue_script('pickle-cms-admin-metaboxes');

		wp_enqueue_style('jquery-ui-smoothness', "https://ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css");
		wp_enqueue_style('pickle-cms-admin-style', PICKLE_CMS_ADMIN_URL.'css/admin.css');
		wp_enqueue_style('pickle-cms-metabox-style', PICKLE_CMS_ADMIN_URL.'css/metaboxes.css');		
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
				case 'columns':
					$type='Admin Columns';
					break;
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
			'taxonomies' => 'Taxonomies',
			'columns' => 'Admin Columns',
		);

		if (isset( $_GET[ 'tab' ] ))
			$active_tab=$_GET['tab'];
		?>

		<div class="wrap pickle-cms-wrap">

			<h1>Pickle CMS</h1>

			<h2 class="nav-tab-wrapper">
				<?php
				foreach ($tabs as $tab => $name) :
					if ($active_tab==$tab) :
						$class='nav-tab-active';
					else :
						$class=null;
					endif;
					?>
					<a href="?page=pickle-cms&tab=<?php echo $tab; ?>" class="nav-tab <?php echo $class; ?>"><?php echo $name; ?></a>
				<?php endforeach; ?>
			</h2>

			<?php
			switch ($active_tab) :
				case 'cms-main':
					echo pickle_cms_get_admin_page('main');
					break;
				case 'columns':
					if (isset($_GET['action']) && $_GET['action']=='update') :
						echo pickle_cms_get_admin_page('single-admin-column');
					else :
						echo pickle_cms_get_admin_page('admin-columns');
					endif;
					break;
				case 'post-types':
					if (isset($_GET['action']) && $_GET['action']=='update') :
						echo pickle_cms_get_admin_page('single-post-type');
					else :
						echo pickle_cms_get_admin_page('post-types');
					endif;
					break;
				case 'metaboxes':
					if (isset($_GET['action']) && $_GET['action']=='update') :
						echo pickle_cms_get_admin_page('single-metabox');
					else :
						echo pickle_cms_get_admin_page('metaboxes');
					endif;
					break;
				case 'taxonomies':
					if (isset($_GET['action']) && $_GET['action']=='update') :
						echo pickle_cms_get_admin_page('single-taxonomy');
					else :
						echo pickle_cms_get_admin_page('taxonomies');
					endif;
					break;
				default:
					echo pickle_cms_get_admin_page('main');
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
/*
	function build_field_rows($field_id='', $field='', $order=0, $classes='') {
		// prep vars to pass //
		//$field['field_id']=$field_id;
		//$field['order']=$order;
		//$field['classes']=$classes;
print_r($field);
		echo pickle_cms_get_admin_page('metabox-field-rows', $field);
	}
*/

	/**
	 * update_metaboxes function.
	 *
	 * @access public
	 * @return void
	 */
	public function update_metaboxes() {
		if (!isset($_POST['pickle_cms_admin']) || !wp_verify_nonce($_POST['pickle_cms_admin'], 'update_metaboxes'))
			return false;

		global $pickleMetaboxes;
echo '<pre>';
echo "update metaboxes<br>";
		$data=$_POST;
		$metaboxes=get_option('pickle_cms_metaboxes');
		$edit_key=-1;
//print_r($_POST);
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
/*
echo "mb<br>";		
print_r($metaboxes);	
echo '</pre>';		
exit;
*/
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

	/**
	 * update_taxonomies function.
	 *
	 * @access public
	 * @return void
	 */
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

			switch ($output) :
				case 'dropdown' :
					$selected_pt=implode('', $selected_pt);
					$html.=$this->post_types_dropdown($post_types_arr, $selected_pt);
					break;
				default:
					$html.=$this->post_types_checkbox($post_types_arr, $selected_pt);
			endswitch;


		$html.='</tr>';

		return $html;
	}
	
	/**
	 * post_types_checkbox function.
	 * 
	 * @access protected
	 * @param string $post_types (default: '')
	 * @param string $selected (default: '')
	 * @return void
	 */
	protected function post_types_checkbox($post_types='', $selected='') {
		$html=null;
		
		$html.='<td class="post-types-cbs">';
			$counter=0;

			foreach ($post_types as $type) :
				if ($counter==0) :
					$class='first';
				else :
					$class='';
				endif;

				if ($selected && in_array($type, (array) $selected)) :
					$checked='checked=checked';
				else :
					$checked=null;
				endif;

				$html.='<input type="checkbox" name="post_types[]" value="'.$type.'" '.$checked.'>'.$type.'<br />';

				$counter++;
			endforeach;
		$html.='</td>';		

		return $html;
	}

	protected function post_types_dropdown($post_types='', $selected='') {
		$html=null;
		
		$html.='<td class="post-types-dropdown">';
			$html.='<select name="post_type">';

				$html.='<option value="0">Select One</option>';

				foreach ($post_types as $type) :
					$html.='<option value="'.$type.'" '.selected($selected, $type, false).'>'.$type.'</option>';
				endforeach;
			
			$html.='</select>';
		$html.='</td>';		

		return $html;
	}

	/**
	 * update_post_types function.
	 *
	 * @access public
	 * @return void
	 */
	public function update_post_types() {
		if (!isset($_POST['pickle_cms_admin']) || !wp_verify_nonce($_POST['pickle_cms_admin'], 'update_cpts'))
			return false;

		$data=$_POST;
		$post_types=get_option('pickle_cms_post_types');
		$post_types_s=serialize($post_types);

		if (!isset($data['name']) || $data['name']=='')
			return false;

		$arr=array(
			'name' => $data['name'],
			'label' => $data['label'],
			'singular_label' => $data['singular_label'],
			'description' => $data['description'],
			'supports' => array(
				'title' => $data['supports']['title'],
				'thumbnail' => $data['supports']['thumbnail'],
				'editor' => $data['supports']['editor'],
				'revisions' => $data['supports']['revisions'],
				'page_attributes' => $data['supports']['page_attributes'],
				'excerpt' => $data['supports']['excerpt'],
				'comments' => $data['supports']['comments'],
			),
			'hierarchical' => $data['hierarchical'],
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

		$update=update_option('pickle_cms_post_types', $post_types);

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
	 * pickle_cms_delete_post_type function.
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

		update_option('pickle_cms_post_types', $post_types); // update option

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

		update_option('pickle_cms_metaboxes', $metaboxes); // update option

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

		update_option('pickle_cms_taxonomies', $taxonomies); // update option

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
			'page' => 'pickle-cms'
		);
		$args=wp_parse_args($args, $default_args);
		$admin_url=add_query_arg($args, admin_url('/tools.php'));

		return $admin_url;
	}
	
	public function ajax_blank_metabox_field() {
print_r($_POST);

		wp_die();		
	}

}

$pickle_cms_admin=new PickleCMSAdmin();
?>
