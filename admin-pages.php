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
		include_once('admin/functions.php');

		include_once('admin/default.php');
		include_once('admin/custom-post-types.php');
		include_once('admin/custom-taxonomies.php');

		$this->update_mdw_cms_settings(); // will be removed soon

		$this->options=$this->setup_default_options();

		$this->options['metaboxes']=get_option('mdw_cms_metaboxes');

		$this->base_url=admin_url('tools.php?page=mdw-cms');

		add_action('admin_menu',array($this,'build_admin_menu'));
		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));
		//add_action('admin_notices',array($this,'admin_notices')); // may not be needed
		//add_filter('mdw_cms_admin_notices',array($this,'admin_notices'));
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

		wp_register_script('mdw-cms-admin-metaboxes-script',plugins_url('/js/admin-metaboxes.js',__FILE__),array('metabox-id-check-script'));

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('mdw-cms-gui-mb-script',plugins_url('/js/mb.js',__FILE__),array('jquery'),'1.0.0',true);
		wp_enqueue_script('namecheck-script',plugins_url('/js/jquery.namecheck.js',__FILE__),array('jquery'));
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
		$options_tabs=$this->add_options_tab(apply_filters('mdw_cms_options_tabs',array()));
		$notice=null;

		if (empty($options_tabs))
			return false;

		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'default';
		usort($options_tabs['tabs'], function ($a, $b) {
			if (function_exists('bccomp')) :
				return bccomp($a['order'], $b['order']);
			else :
				return strcmp($a['order'], $b['order']);
			endif;
		});

		if (isset($_POST['notice']))
			$notice=urldecode($_POST['notice']);

		echo '<div class="mdw-cms-wrap">';

			echo '<h2>MDW CMS</h2>';

			echo '<h2 class="nav-tab-wrapper">';
				foreach ($options_tabs['tabs'] as $tab) :
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

				foreach ($options_tabs['hooks'] as $tag => $active) :
					$tag_arr=explode('-',$tag);
					$id=array_pop($tag_arr);

					if ($active_tab==$id) :
						do_action($tag,$this);
					endif;
				endforeach;

			echo '</div><!-- #mdw-cms-form-wrap -->';

			echo '<div id="ajax-loader"><div id="ajax-image"></div></div>';

		echo '</div><!-- /.wrap -->';
	}

	/**
	 * add_options_tab function.
	 *
	 * @access public
	 * @param array $tabs (default: array())
	 * @return void
	 */
	function add_options_tab($tabs=array()) {
		$tabs_full=array();

		if (empty($tabs))
			return false;

		foreach ($tabs as $tab_id => $tab) :
			// setup our action hooks //
			$hookname='mdw_theme_options_tab-'.$tab_id;

			add_action($hookname,$tab['function']);

			$tabs_full['hooks'][$hookname]=true;

			// setup our tabs //
			$tabs_full['tabs'][]=array(
				'id' => $tab_id,
				'name' => $tab['name'],
				'order' => $tab['order']
			);
		endforeach;

		return $tabs_full;
	}

/*
	function setup_options() {
		$options=array();
		$this->default_options=$this->setup_default_options();

		if (get_option($this->option_name)) :
	 		$options=array_replace_recursive($this->default_options,get_option($this->option_name));

	 		// apply a clean to our options in case there's old values stored in db //
	 		$default_options_keys=array();
	 		foreach ($this->default_options as $key => $value) :
	 			$default_options_keys[]=$key;
	 		endforeach;

	 		foreach ($options as $key => $option) :
	 			if (!in_array($key,$default_options_keys))
	 				unset($options[$key]);
	 		endforeach;

	 	else :
	 		$options=$this->default_options;
	 	endif;

		return $options;
	}
*/

	function setup_default_options() {
		$options=array();
		$options=apply_filters('mdw_cms_default_options',$options);

		return $options;
	}

/*
	function update_options() {
		$options=array();

		if (get_option($this->option_name)) :
			$options=array_replace_recursive($this->default_options,get_option($this->option_name)); // merge stored with default options
		else :
			$options=$this->default_options;
		endif;

		$options=array_replace_recursive($options,$_POST['theme_options']); // merger post (updated) options with previous options

		$updated=update_option($this->option_name,$options);

		$this->options=$this->setup_options(); // kind of like a refresh now the db is updated

		return $updated;
	}
*/


	/**
	 * metaboxes_admin_page function.
	 *
	 * @access public
	 * @return void
	 */
	function metaboxes_admin_page() {
		global $MDWMetaboxes;

		$base_url=admin_url('tools.php?page=mdw-cms&tab=mdw-cms-metaboxes');
		$btn_text='Create';
		$html=null;
		$mb_id=null;
		$title=null;
		$prefix=null;
		$post_types=null;
		$edit_class_v='';
		$fields=false;
		$field_counter=0;
		$field_id=0;

		$label_class='col-md-3';
		$input_class='col-md-3';
		$description_class='col-md-6';
		$select_class='col-md-3';
		$existing_label_class='col-md-5';
		$edit_class='col-md-2';
		$delete_class='col-md-2';

		// edit //
		if (isset($_GET['edit']) && $_GET['edit']=='mb') :
			foreach ($this->options['metaboxes'] as $key => $mb) :
				if ($mb['mb_id']==$_GET['mb_id']) :
					extract($this->options['metaboxes'][$key]);
					$edit_class_v='visible';
					$btn_text='Update';
				endif;
			endforeach;
		endif;

		$html.='<div class="row">';

			$html.='<form class="custom-metabox col-md-8" method="post">';
				$html.='<h3>Add Metabox</h3>';
				$html.='<div class="form-row row">';
					$html.='<label for="mb_id" class="required '.$label_class.'">Metabox ID</label>';
					$html.='<div class="input '.$input_class.'">';
						$html.='<input type="text" name="mb_id" id="mb_id" class="" value="'.$mb_id.'" />';
					$html.='</div>';
					$html.='<span class="description '.$description_class.'">(e.g. movie_details)</span>';
					$html.='<div class="mdw-cms-name-error col-md-6 col-md-offset-3"></div>';
				$html.='</div>';

				$html.='<div class="form-row row">';
					$html.='<label for="title" class="'.$label_class.'">Title</label>';
					$html.='<div class="input '.$input_class.'">';
						$html.='<input type="text" name="title" id="title" class="" value="'.$title.'" />';
					$html.='</div>';
					$html.='<span class="description '.$description_class.'">(e.g. Movie Details)</span>';
				$html.='</div>';

				$html.='<div class="form-row row">';
					$html.='<label for="prefix" class="'.$label_class.'">Prefix</label>';
					$html.='<div class="input '.$input_class.'">';
						$html.='<input type="text" name="prefix" id="prefix" class="" value="'.$prefix.'" />';
					$html.='</div>';
					$html.='<span class="description '.$description_class.'">(e.g. movies)</span>';
				$html.='</div>';

				$html.=$this->get_post_types_list($post_types);

				$html.='<div class="add-fields sortable-div '.$edit_class_v.'">';

					$html.='<h3>Metabox Fields</h3>';

					if ($fields) :
						foreach ($fields as $field_id => $field) :
							$html.=$this->build_field_rows($field_id,$field,$field_counter);
							$field_counter++;
						endforeach;
					endif;

					// 0 is default ie no fields exist //
					if ($field_counter==0)
						$html.=$this->build_field_rows($field_id,null,$field_counter); // add 'default' field //

				$html.='</div><!-- .add-fields -->';
				$html.='<p class="submit">';
					$html.='<input type="submit" name="update-metabox" id="submit" class="button button-primary" value="'.$btn_text.'">';
					$html.='<input type="button" name="add-field" id="add-field-btn" class="button button-primary add-field" value="Add Field">';
				$html.='</p>';

				// add hidden fields for edit //
				if (isset($_GET['edit']) && $_GET['edit']=='mb') :
					$html.='<input type="hidden" name="edit_mb_id" value="'.$_GET['mb_id'].'" />';
				endif;
			$html.='</form>';

			$html.='<div class="custom-metabox-list col-md-4">';
				$html.='<h3>Custom Metaboxes</h3>';

				if ($this->options['metaboxes']) :
					foreach ($this->options['metaboxes'] as $mb) :
						$html.='<div class="metabox-row row">';
							$html.='<span class="mb '.$existing_label_class.'">'.$mb['title'].'</span><span class="edit '.$edit_class.'">[<a href="'.$base_url.'&edit=mb&mb_id='.$mb['mb_id'].'">Edit</a>]</span><span class="delete '.$delete_class.'">[<a href="'.$base_url.'&delete=mb&mb_id='.$mb['mb_id'].'">Delete</a>]</span>';
						$html.='</div>';
					endforeach;
				endif;

			$html.='</div>';

		$html.='</div><!-- .row -->';

		return $html;
	}

	/**
	 * custom_taxonomies_admin_page function.
	 *
	 * @access public
	 * @return void
	 */


	/**
	 *
	 */
	function build_field_rows($field_id,$field,$order=0,$classes='') {
		global $MDWMetaboxes;

		$html=null;
		$field_description=null;
		$prefix=null;

		$label_class='col-md-3';
		$input_class='col-md-3';
		$description_class='col-md-6';
		//$description_ext_class='col-md-9 col-md-offset-3';
		//$error_class='col-md-12';
		$select_class='col-md-3';
		//$existing_label_class='col-md-5';
		//$edit_class='col-md-2';
		//$delete_class='col-md-2';

		if (isset($_GET['edit']) && $_GET['edit']=='mb') :
			foreach ($this->options['metaboxes'] as $key => $mb) :
				if ($mb['mb_id']==$_GET['mb_id']) :
					extract($this->options['metaboxes'][$key]);
				endif;
			endforeach;
		endif;

		if (isset($field['repeatable']) && $field['repeatable']) :
			$repeatable_checked='checked="checked"';
		else :
			$repeatable_checked=null;
		endif;

		if (isset($field['format']['value'])) :
			$format=$field['format']['value'];
		else :
			$format=null;
		endif;

		if (isset($field['field_description']) && !empty($field['field_description']))
			$field_description=$field['field_description'];

		$html.='<div class="row sortable fields-wrapper '.$classes.'" id="fields-wrapper-'.$field_id.'">';
			$html.='<div class="fields-wrapper-border">';
				$html.='<div class="col-md-1">';
					$html.='<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
				$html.='</div>';

				$html.='<div class="col-md-11">';
					$html.='<div class="row">';
						$html.='<div class="col-md-3 field-type-label">';
							$html.='<label for="field_type">Field Type</label>';
						$html.='</div>';
						$html.='<div class="col-md-9">';
							$html.='<select class="field_type name-item" name="fields['.$field_id.'][field_type]">';
								$html.='<option value=0>Select One</option>';
								foreach ($MDWMetaboxes->fields as $field_type => $setup) :
									$html.='<option value="'.$field_type.'" '.selected($field['field_type'],$field_type,false).'>'.$field_type.'</option>';
								endforeach;
							$html.='</select>';
						$html.='</div>';
					$html.='</div><!-- .row -->';
				$html.='</div>';

				$html.='<div class="field-label col-md-11 col-md-offset-1">';
					$html.='<div class="row">';
						$html.='<div class="col-md-3 field-label-label">';
							$html.='<label for="field_label">Label</label>';
						$html.='</div>';
						$html.='<div class="col-md-9 label-input">';
							$html.='<input type="text" name="fields['.$field_id.'][field_label]" class="field_label name-item" value="'.$field['field_label'].'" />';
						$html.='</div>';
					$html.='</div>';
				$html.='</div>';

				$html.='<div class="field-options col-md-11 col-md-offset-1" id="">';
					foreach ($MDWMetaboxes->fields as $field_type => $setup) :
						$html.='<div class="type" data-field-type="'.$field_type.'">';
							if ($setup['repeatable']) :
								$html.='<div class="field repeatable row">';
									$html.='<div class="col-md-3 field-repeatable-label">';
										$html.='<label for="repeatable">Repeatable</label>';
									$html.='</div>';
									$html.='<div class="col-md-9 field-repeatable-check">';
										$html.='<input type="checkbox" name="fields['.$field_id.'][repeatable]" value="1" class="repeatable-box name-item" '.$repeatable_checked.' />';
									$html.='</div>';
								$html.='</div>';
							endif;

							if ($setup['options']) :
								$html.='<div class="field options" id="field-options-'.$field_id.'">';
									$html.='<label for="options">Options</label>';
									// get options //
									if (isset($field['options']) && !empty($field['options'])) :
										foreach ($field['options'] as $key => $option) :
											$html.='<div class="option-row" id="option-row-'.$key.'">';
												$html.='<label for="options-default-name">Name</label>';
												$html.='<input type="text" name="fields['.$field_id.'][options]['.$key.'][name]" class="options-item name" value="'.$option['name'].'" />';
												$html.='<label for="options-default-value">Value</label>';
												$html.='<input type="text" name="fields['.$field_id.'][options]['.$key.'][value]" class="options-item value" value="'.$option['value'].'" />';
											$html.='</div><!-- .option-row -->';
										endforeach;
									endif;

									// blank option //
									$html.='<div class="option-row default" id="option-row-default">';
										$html.='<label for="options-default-name">Name</label>';
										$html.='<input type="text" name="fields['.$field_id.'][options][default][name]" class="options-item name" value="" />';
										$html.='<label for="options-default-value">Value</label>';
										$html.='<input type="text" name="fields['.$field_id.'][options][default][value]" class="options-item value" value="" />';
									$html.='</div><!-- .option-row -->';

									$html.='<div class="add-option-field"><input type="button" name="add-option-field" class="add-option-field-btn button button-primary" value="Add Option"></div>';
								$html.='</div>';
							endif;

							if ($setup['format']) :
								$html.='<div class="field format row">';
									$html.='<div class="col-md-3 field-format-label">';
										$html.='<label for="format">Format</label>';
									$html.='</div>';
									$html.='<div class="col-md-9 field-format-check">';
										$html.='<input type="text" name="fields['.$field_id.'][format][value]" class="options-item value" value="'.$format.'" />';
									$html.='</div>';
								$html.='</div>';
							endif;
						$html.='</div>';
					endforeach;
				$html.='</div><!-- .field-options -->';

				$html.='<div class="description col-md-11 col-md-offset-1">';
					$html.='<div class="row">';
						$html.='<div class="col-md-3 file-description-label">';
							$html.='<label for="field_description">Field Description</label>';
						$html.='</div>';
						$html.='<div class="col-md-9 fd">';
							$html.='<input type="text" name="fields['.$field_id.'][field_description]" class="field_description name-item" value="'.$field_description.'" />';
						$html.='</div>';
					$html.='</div>';
				$html.='</div><!-- .description -->';

				$html.='<div class="field-id col-md-11 col-md-offset-1">';
					$html.='<div class="row">';
						$html.='<div class="col-md-3 field-id-label">';
							$html.='<label for="field_id">Field ID</label>';
						$html.='</div>';
						$html.='<div class="col-md-9 field-id-id">';
							$html.='<div class="gen-field-id"><input type="text" readonly="readonly" value="'.$MDWMetaboxes->generate_field_id($prefix,$field['field_label'],$field_id).'" /> <span class="description">(use as meta key)</span></div>';
						$html.='</div>';
					$html.='</div>';
				$html.='</div><!-- .description -->';

				$html.='<div class="remove col-md-11 col-md-offset-1">';
					$html.='<input type="button" name="remove-field" id="remove-field-btn" class="button button-primary remove-field" data-id="fields-wrapper-'.$field_id.'" value="Remove">';
				$html.='</div>';

				$html.='<input type="hidden" name="fields['.$field_id.'][order]" class="order name-item" value="'.$order.'" />';
			$html.='</div>';
		$html.='</div><!-- .fields-wrapper -->';

		return $html;
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

		// check our metaboxes and then updates metabox or modifies it //
		if (!empty($metaboxes)) :
			if (isset($data['update-metabox']) && $data['update-metabox']=='Update') :
				// check if id has changed //
				if (isset($data['edit_mb_id']) && $data['edit_mb_id']!=$data['mb_id']) :
					foreach ($metaboxes as $key => $mb) :
						if ($mb['mb_id']==$data['edit_mb_id']) :
							$edit_key=$key;
							//self::update_metabox_id($data['edit_mb_id'],$data['mb_id']); // run a db update as well as jsut change the id //
						endif;
					endforeach;
				else : // standard edit
					foreach ($metaboxes as $key => $mb) :
						// if the ids match, check that we are updating that one, return false if not (a dup) //
						if ($mb['mb_id']==$data['mb_id']) :
							$edit_key=$key;
							if (isset($arr['post_fields'])) :
								$arr['post_fields']=$mb['post_fields'];
							endif;
						else :
							return false;
						endif;
					endforeach;
				endif;
			endif;
		endif;

		// if we have an edit key, we edit otherwise add //
		if ($edit_key!=-1) :
			$metaboxes[$edit_key]=$arr;
		else :
			$metaboxes[]=$arr;
		endif;

		return update_option('mdw_cms_metaboxes',$metaboxes);
	}

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

	/**
	 * update_metabox_prefix function.
	 *
	 * @access protected
	 * @static
	 * @param bool $old (default: false)
	 * @param bool $new (default: false)
	 * @return void
	 */
	protected static function update_metabox_prefix($old=false,$new=false) {
		global $wpdb;

		if (!$old || !$new)
			return false;

		$field='meta_key';

		$sql="
			UPDATE ".$wpdb->prefix."postmeta
			SET $field = REPLACE($field,'$old','$new')
			WHERE $field LIKE '%$old%'
		";

		$wpdb->get_results($sql);

		return true;
	}

	/**
	 * update_cpt_name function.
	 *
	 * @access protected
	 * @static
	 * @param bool $old (default: false)
	 * @param bool $new (default: false)
	 * @return void
	 */
	protected static function update_cpt_name($old=false,$new=false) {
		global $wpdb;

		if (!$old || !$new)
			return false;

		$field='post_type';

		$sql="
			UPDATE ".$wpdb->prefix."posts
			SET $field = REPLACE($field,'$old','$new')
			WHERE $field LIKE '%$old%'
		";

		$wpdb->get_results($sql);

		return true;
	}

}

new MDWCMSgui();
?>
