<?php
class MDWCMSgui {

	protected $options=array();
	protected $admin_notices_output=array();
	protected $base_url=null;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		include_once('admin/main.php');
		include_once('admin/post-types.php');
		//include_once('admin/taxonomies.php');
		//include_once('admin/metaboxes.php');

		//$this->update_mdw_cms_settings(); // will be removed soon

		//$this->options=$this->setup_default_options();

		//$this->options['metaboxes']=get_option('mdw_cms_metaboxes');

		$this->base_url=admin_url('tools.php?page=mdw-cms');

		add_action('admin_menu',array($this,'build_admin_menu'));
		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));

		add_action('admin_init','MDWCMSlegacy::setup_legacy_updater');
		add_action('admin_notices','MDWCMSlegacy::legacy_admin_notices');

		add_filter('admin_body_class',array($this,'add_classes_to_body'));
	}

	/**
	 * build_admin_menu function.
	 *
	 * @access public
	 * @return void
	 */
	public function build_admin_menu() {
		add_management_page('MDW CMS','MDW CMS','administrator','mdw-cms',array($this,'mdw_cms_page'));
	}

	/**
	 * admin_scripts_styles function.
	 *
	 * @access public
	 * @param mixed $hook
	 * @return void
	 */
	public function admin_scripts_styles($hook) {
		$disable_bootstrap=false;

		wp_enqueue_style('mdw-cms-gui-style',plugins_url('/css/admin.css',__FILE__));

		//wp_register_script('mdw-cms-admin-metaboxes-script',plugins_url('/js/admin-metaboxes.js',__FILE__),array('metabox-id-check-script'));

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('mdw-cms-gui-mb-script',plugins_url('/js/mb.js',__FILE__),array('jquery'),'1.0.0',true);
		wp_enqueue_script('namecheck-script',plugins_url('/js/jquery.namecheck.js',__FILE__),array('jquery'));
/*
		wp_enqueue_script('metabox-id-check-script',plugins_url('/js/jquery.metabox-id-check.js',__FILE__),array('jquery'));

		$metaboxes=$this->options['metaboxes'];
		$mb_arr=array();

		// existing metaboxes //
		foreach ($metaboxes as $metabox) :
			$mb_arr[]=$metabox['mb_id'];
		endforeach;

		// taxonomies //
		$taxonomies=get_taxonomies();
		foreach ($taxonomies as $taxonomy) :
			$mb_arr[]=$taxonomy;
		endforeach;

		// manual additions //
		$mb_arr[]='postimage';
		$mb_arr[]='excerpt';
		$mb_arr[]='commentstatus';
		$mb_arr[]='slug';
		$mb_arr[]='author';

		$metabox_options=array(
			'reserved' => $mb_arr
		);

		wp_localize_script('mdw-cms-admin-metaboxes-script','wp_metabx_options',$metabox_options);

		wp_enqueue_script('mdw-cms-admin-metaboxes-script');
*/
	}

	/**
	 * add_classes_to_body function.
	 *
	 * @access public
	 * @param mixed $classes
	 * @return void
	 */
	public function add_classes_to_body($classes) {
		$classes.=' mdw-cms-admin';

		return $classes;
	}

	/**
	 * mdw_cms_page function.
	 *
	 * our primary admin page, utlaizes tabs for internal navigation
	 *
	 * @access public
	 * @return void
	 */
	function mdw_cms_page() {
		global $mdw_cms_admin_pages,$mdw_cms_admin_page_hooks,$mdw_cms_options;

//print_r($mdw_cms_admin_pages);
echo '<pre>';
print_r($mdw_cms_options);
echo '</pre>';

		$notice=null;
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'main';

		// sort tabs by order parameter //
		usort($mdw_cms_admin_pages, function ($a, $b) {
			if (function_exists('bccomp')) :
				return bccomp($a['order'], $b['order']);
			else :
				return strcmp($a['order'], $b['order']);
			endif;
		});

		if (isset($_POST['notice'])) // check this
			$notice=urldecode($_POST['notice']);

		echo '<div class="mdw-cms-wrap">';

			echo '<h2>MDW CMS</h2>';

			echo '<h2 class="nav-tab-wrapper">';
				foreach ($mdw_cms_admin_pages as $tab) :
					if ($active_tab==$tab['id']) :
						$classes='nav-tab-active';
					else :
						$classes=null;
					endif;

					echo '<a href="'.$this->base_url.'&tab='.$tab['id'].'" class="nav-tab '.$classes.'">'.$tab['name'].'</a>';
				endforeach;
			echo '</h2>';

			echo '<div id="mdw-cms-admin-notices">'.$notice.'</div>';
			echo '<div id="mdw-cms-form-wrap">';

				foreach ($mdw_cms_admin_page_hooks['hooks'] as $hookname => $active) :
					$hookname_arr=explode('-',$hookname);
					$id=array_pop($hookname_arr);
//echo "$active_tab | $id<br>";
					if ($active_tab==$id) :
						do_action($hookname,$this);
					endif;
				endforeach;

			echo '</div><!-- #mdw-cms-form-wrap -->';

			echo '<div id="ajax-loader"><div id="ajax-image"></div></div>';

		echo '</div><!-- /.wrap -->';
	}

	/**
	 * runs all of our update and edit functions
	 * called in __construct and run before everything so that our options are updated before page load
	 */
/*
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
	}
*/

	/**
	 * admin_notices function.
	 *
	 * @access public
	 * @param string $class (default: 'error')
	 * @param string $message (default: '')
	 * @return void
	 */
	function admin_notices($class='error',$message='') {
		$this->admin_notices_output[]='<div class="'.$class.'"><p>'.$message.'</p></div>';
	}


}

new MDWCMSgui();
?>
