<?php

class PickleCMS_Admin {

	public $options=array();

	public function __construct() {
		add_action('admin_menu', array($this, 'build_admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'scripts_styles'));
		add_action('admin_notices', array($this, 'admin_notices'));

		add_action('wp_ajax_pickle_cms_reserved_names', array($this, 'ajax_reserved_names'));
	}

	protected function get_option($name='', $default='') {
		$option=get_option($name, $default);
		
		if ($option=='')
			$option=$default;

		return $option;
	}

	public function build_admin_menu() {
		add_menu_page('Pickle CMS', 'Pickle CMS', 'manage_options', 'pickle-cms', array($this, 'admin_page'), 'dashicons-layout');
	}

	public function scripts_styles($hook) {
		global $wp_scripts;

		$ui = $wp_scripts->query('jquery-ui-core');
				
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('namecheck-script', PICKLE_CMS_URL.'js/jquery.namecheck.js', array('jquery'), '0.1.0');

		wp_enqueue_script('requiredFields-script', PICKLE_CMS_ADMIN_URL.'js/jquery.requiredFields.js', array('jquery'), '0.1.0');
		wp_enqueue_script('pickle-cms-admin-functions', PICKLE_CMS_ADMIN_URL.'js/functions.js', array('jquery'), '0.1.0');

		wp_enqueue_style('jquery-ui-smoothness', "https://ajax.googleapis.com/ajax/libs/jqueryui/{$ui->ver}/themes/smoothness/jquery-ui.min.css");
		wp_enqueue_style('pickle-cms-admin-style', PICKLE_CMS_ADMIN_URL.'css/admin.css');
	}

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

	protected function admin_url($args='') {
		$default_args=array(
			'page' => 'pickle-cms'
		);
		$args=wp_parse_args($args, $default_args);
		$admin_url=add_query_arg($args, admin_url('/tools.php'));

		return $admin_url;
	}
	
}
?>
