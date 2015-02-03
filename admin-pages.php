<?php
class MDWCMSgui {

	protected $options=array();

	function __construct() {
		add_action('admin_menu',array($this,'build_admin_menu'));
		add_action('admin_enqueue_scripts',array($this,'scripts_styles'));
		add_action('admin_notices',array($this,'admin_notices')); // may not be needed

		add_action('admin_init','MDWCMSlegacy::setup_legacy_updater');
		add_action('admin_notices','MDWCMSlegacy::legacy_admin_notices');

		//update_option('mdw_cms_version','1.1.1');

		$this->update_mdw_cms_settings();

		$this->options['version']=get_option('mdw_cms_version');
		$this->options['metaboxes']=get_option('mdw_cms_metaboxes');
		$this->options['post_types']=get_option('mdw_cms_post_types');
	}

	function build_admin_menu() {
		add_management_page('MDW CMS','MDW CMS','administrator','mdw-cms',array($this,'mdw_cms_page'));
	}

	function scripts_styles() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('mdw-cms-gui-mb-script',plugins_url('/js/mb.js',__FILE__),array('jquery'));

		wp_enqueue_style('mdw-cms-gui-style',plugins_url('/css/admin.css',__FILE__));
	}

	function mdw_cms_page() {
		$html=null;
		$tabs=array(
			'cms-main' => 'Main',
			'mdw-cms-cpt' => 'Custom Post Types',
			'mdw-cms-metaboxes' => 'Metaboxes'
		);
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'cms-main';

		$html.='<div class="wrap">';
			$html.='<h2>MDW CMS</h2>';

			$html.='<h2 class="nav-tab-wrapper">';
				foreach ($tabs as $tab => $name) :
					if ($active_tab==$tab) :
						$class='nav-tab-active';
					else :
						$class=null;
					endif;

					$html.='<a href="?page=mdw-cms&tab='.$tab.'" class="nav-tab '.$class.'">'.$name.'</a>';
				endforeach;
			$html.='</h2>';

			switch ($active_tab) :
				case 'cms-main':
					$html.=$this->default_admin_page();
					break;
				case 'mdw-cms-cpt':
					$html.=$this->cpt_admin_page();
					break;
				case 'mdw-cms-metaboxes':
					$html.=$this->metaboxes_admin_page();
					break;
				default:
					$html.=$this->default_admin_page();
					break;
			endswitch;

		$html.='</div><!-- /.wrap -->';

		echo $html;
	}

	/**
	 *
	 */
	function default_admin_page() {
		$html=null;

		$html.='<h3>CMS</h3>';

		$html.='<div class="mdw-cms-default">';
			$html.='CONTENT NEEDS TO GO HERE';

			$html.=MDWCMSlegacy::get_legacy_page();
		$html.='</div>';

		return $html;
	}

	/**
	 *
	 */
	function cpt_admin_page() {
		$base_url=admin_url('tools.php?page=mdw-cms&tab=mdw-cms-cpt');
		$btn_text='Create';
		$name=null;
		$label=null;
		$singular_label=null;
		$description=null;
		$title=1;
		$thumbnail=1;
		$editor=1;
		$revisions=1;
		$hierarchical=0;
		$id=-1;

		// edit custom post type //
		if (isset($_GET['edit']) && $_GET['edit']=='cpt') :
			foreach ($this->options['post_types'] as $key => $cpt) :
				if ($cpt['name']==$_GET['slug']) :
					extract($this->options['post_types'][$key]);
					$id=$key;
				endif;
			endforeach;
		endif;

		if ($id!=-1)
			$btn_text='Update';

		$html=null;

		$html.='<form class="custom-post-types" method="post">';
			$html.='<h3>Add New Custom Post Type</h3>';
			$html.='<div class="form-row">';
				$html.='<label for="name" class="required">Post Type Name</label>';
				$html.='<input type="text" name="name" id="name" value="'.$name.'" />';
				$html.='<span class="description">(e.g. movie)</span>';
				$html.='<div class="description-ext">Max 20 characters, can not contain capital letters or spaces. Reserved post types: post, page, attachment, revision, nav_menu_item.</div>';
			$html.='</div>';

			$html.='<div class="form-row">';
				$html.='<label for="label">Label</label>';
				$html.='<input type="text" name="label" id="label" value="'.$label.'" />';
				$html.='<span class="description">(e.g. Movies)</span>';
			$html.='</div>';

			$html.='<div class="form-row">';
				$html.='<label for="singular_label">Singular Label</label>';
				$html.='<input type="text" name="singular_label" id="singular_label" value="'.$singular_label.'" />';
				$html.='<span class="description">(e.g. Movie)</span>';
			$html.='</div>';

			$html.='<div class="form-row">';
				$html.='<label for="description">Description</label>';
				$html.='<textarea name="description" id="description" rows="4" cols="40">'.$description.'</textarea>';
				//$html.='<span class="description">description</span>';
			$html.='</div>';

			$html.='<div class="advanced-options">';
				$html.='<div class="form-row">';
					$html.='<label for="title">Title</label>';
					$html.='<select name="title" id="title">';
						$html.='<option value="1" '.selected($title,1,false).'>True</option>';
						$html.='<option value="0" '.selected($title,0,false).'>False</option>';
					$html.='</select>';
					$html.='<span class="description">(default True)</span>';
				$html.='</div>';
				$html.='<div class="form-row">';
					$html.='<label for="thumbnail">Thumbnail</label>';
					$html.='<select name="thumbnail" id="thumbnaill">';
						$html.='<option value="1" '.selected($thumbnail,1,false).'>True</option>';
						$html.='<option value="0" '.selected($thumbnail,0,false).'>False</option>';
					$html.='</select>';
					$html.='<span class="description">(default True)</span>';
				$html.='</div>';
				$html.='<div class="form-row">';
					$html.='<label for="editor">Editor</label>';
					$html.='<select name="editor" id="editor">';
						$html.='<option value="1" '.selected($editor,1,false).'>True</option>';
						$html.='<option value="0" '.selected($editor,0,false).'>False</option>';
					$html.='</select>';
					$html.='<span class="description">(default True)</span>';
				$html.='</div>';
				$html.='<div class="form-row">';
					$html.='<label for="revisions">Revisions</label>';
					$html.='<select name="revisions" id="revisions">';
						$html.='<option value="1" '.selected($revisions,1,false).'>True</option>';
						$html.='<option value="0" '.selected($revisions,0,false).'>False</option>';
					$html.='</select>';
					$html.='<span class="description">(default True)</span>';
				$html.='</div>';
				$html.='<div class="form-row">';
					$html.='<label for="hierarchical">Hierarchical</label>';
					$html.='<select name="hierarchical" id="hierarchical">';
						$html.='<option value="1" '.selected($hierarchical,1,false).'>True</option>';
						$html.='<option value="0" '.selected($hierarchical,0,false).'>False</option>';
					$html.='</select>';
					$html.='<span class="description">(default True)</span>';
				$html.='</div>';
			$html.='</div>';
			$html.='<p class="submit"><input type="submit" name="add-cpt" id="submit" class="button button-primary" value="'.$btn_text.'"></p>';
			$html.='<input type="hidden" name="cpt-id" id="cpt-id" value='.$id.' />';
		$html.='</form>';

		$html.='<div class="custom-post-types-list">';
			$html.='<h3>Custom Post Types</h3>';

			if ($this->options['post_types']) :
				foreach ($this->options['post_types'] as $cpt) :
					$html.='<div class="cpt-row">';
						$html.=$cpt['label'].'<span class="edit">[<a href="'.$base_url.'&edit=cpt&slug='.$cpt['name'].'">Edit</a>]</span><span class="delete">[<a href="'.$base_url.'&delete=cpt&slug='.$cpt['name'].'">Delete</a>]</span>';
					$html.='</div>';
				endforeach;
			endif;

		$html.='</div>';

		return $html;
	}

	/**
	 *
	 */
	function metaboxes_admin_page() {
		global $MDWMetaboxes;

		$base_url=admin_url('tools.php?page=mdw-cms&tab=mdw-cms-metaboxes');
		$btn_text='Create';
		$html=null;
		$mb_id=null;
		$title=null;
		$prefix=null;
		$post_type=null;
		$edit_class='';
		$fields=false;

		$args=array(
			'public' => true,
			//'_builtin' => false
		);
		$post_types_arr=get_post_types($args);

		// edit //
		if (isset($_GET['edit']) && $_GET['edit']=='mb') :
			foreach ($this->options['metaboxes'] as $key => $mb) :
				if ($mb['mb_id']==$_GET['mb_id']) :
					extract($this->options['metaboxes'][$key]);
					$edit_class='visible';
					$btn_text='Update';
				endif;
			endforeach;
		endif;

		$html.='<h3>Metaboxes</h3>';

		$html.='<form class="custom-metabox" method="post">';
			$html.='<h3>Add Metabox</h3>';
			$html.='<div class="form-row">';
				$html.='<label for="mb_id" class="required">Metabox ID</label>';
				$html.='<input type="text" name="mb_id" id="mb_id" value="'.$mb_id.'" />';
				$html.='<span class="description">(e.g. movie_details)</span>';
				//$html.='<div class="description-ext">Max 20 characters, can not contain capital letters or spaces. Reserved post types: post, page, attachment, revision, nav_menu_item.</div>';
			$html.='</div>';

			$html.='<div class="form-row">';
				$html.='<label for="title">Title</label>';
				$html.='<input type="text" name="title" id="title" value="'.$title.'" />';
				$html.='<span class="description">(e.g. Movie Details)</span>';
			$html.='</div>';

			$html.='<div class="form-row">';
				$html.='<label for="prefix">Prefix</label>';
				$html.='<input type="text" name="prefix" id="prefix" value="'.$prefix.'" />';
				$html.='<span class="description">(e.g. movies)</span>';
			$html.='</div>';

			$html.='<div class="form-row">';
				$html.='<label for="post_type">Post Type</label>';
				$counter=0;
				foreach ($post_types_arr as $type) :
					if ($counter==0) :
						$class='first';
					else :
						$class='';
					endif;

					if (isset($post_types) && in_array($type,$post_types)) :
						$checked='checked=checked';
					else :
						$checked=null;
					endif;

					$html.='<input class="post-type-cb '.$class.'" type="checkbox" name="post_types[]" value="'.$type.'" '.$checked.'>'.$type.'<br />';
					$counter++;
				endforeach;
			$html.='</div>';

			$html.='<h3>Metabox Fields</h3>';
			$html.='<div class="add-fields sortable-div '.$edit_class.'">';
				if ($fields) :
					foreach ($fields as $field_id => $field) :
						$html.=$this->build_field_rows($field_id,$field);
					endforeach;
				endif;

				$html.=$this->build_field_rows('default',null,'default'); // add default field //
			$html.='</div><!-- .add-fields -->';

			$html.='<p class="submit">';
				$html.='<input type="submit" name="update-metabox" id="submit" class="button button-primary" value="'.$btn_text.'">';
				$html.='<input type="button" name="add-field" id="add-field-btn" class="button button-primary add-field" value="Add Field">';
			$html.='</p>';
		$html.='</form>';

		$html.='<div class="custom-metabox-list">';
			$html.='<h3>Custom Metaboxes</h3>';

			if ($this->options['metaboxes']) :
				foreach ($this->options['metaboxes'] as $mb) :
					$html.='<div class="metabox-row">';
						$html.=$mb['title'].'<span class="edit">[<a href="'.$base_url.'&edit=mb&mb_id='.$mb['mb_id'].'">Edit</a>]</span><span class="delete">[<a href="'.$base_url.'&delete=mb&mb_id='.$mb['mb_id'].'">Delete</a>]</span>';
					$html.='</div>';
				endforeach;
			endif;

		$html.='</div>';

		return $html;
	}

	/**
	 *
	 */
	function build_field_rows($field_id,$field,$classes='') {
		global $MDWMetaboxes;

		$html=null;

		if (isset($field['repeatable']) && $field['repeatable']) :
			$repeatable_checked='checked="checked"';
		else :
			$repeatable_checked=null;
		endif;

		$html.='<div class="sortable fields-wrapper '.$classes.'" id="fields-wrapper-'.$field_id.'">';
			$html.='<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
			$html.='<div class="form-row">';
				$html.='<label for="field_type">Field Type</label>';
				$html.='<select class="field_type name-item" name="fields['.$field_id.'][field_type]">';
					$html.='<option value=0>Select One</option>';
					foreach ($MDWMetaboxes->fields as $field_type => $setup) :
						$html.='<option value="'.$field_type.'" '.selected($field['field_type'],$field_type,false).'>'.$field_type.'</option>';
					endforeach;
				$html.='</select>';
			$html.='</div>';

			$html.='<div class="field-options" id="">';
				$html.='<div class="field">';
					$html.='<label for="field_label">Label</label>';
					$html.='<input type="text" name="fields['.$field_id.'][field_label]" class="field_label name-item" value="'.$field['field_label'].'" />';
				$html.='</div>';

				foreach ($MDWMetaboxes->fields as $field_type => $setup) :
					$html.='<div class="type" data-field-type="'.$field_type.'">';
						if ($setup['repeatable']) :
							$html.='<div class="field repeatable">';
								$html.='<label for="repeatable">Repeatable</label>';
								$html.='<input type="checkbox" name="fields['.$field_id.'][repeatable]" value="1" class="repeatable-box name-item" '.$repeatable_checked.' />';
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

								$html.='<div class="add-option-field"><input type="button" name="add-option-field" id="add-option-field-btn" class="button button-primary" value="Add Option"></div>';
							$html.='</div>';
						endif;
					$html.='</div>';
				endforeach;
				$html.='<input type="button" name="remove-field" id="remove-field-btn" class="button button-primary remove-field" data-id="fields-wrapper-'.$field_id.'" value="Remove Field">';
			$html.='</div><!-- .field-options -->';
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

		// create custom post type //
		if (isset($_POST['add-cpt']) && $_POST['add-cpt']=='Create') :
			if ($this->update_custom_post_types($_POST)) :
				$this->admin_notices('updated','Post type has been created.');
			else :
				$this->admin_notices('error','There was an issue creating the post type.');
			endif;
		endif;

		// update/edit custom post type //
		if (isset($_POST['add-cpt']) && $_POST['add-cpt']=='Update') :
			if ($this->update_custom_post_types($_POST)) :
				$this->admin_notices('updated','Post type has been updated.');
			else :
				$this->admin_notices('error','There was an issue updating the post type.');
			endif;
		endif;

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

		// add metabox //
		if (isset($_POST['update-metabox']) && $_POST['update-metabox']=='Create') :
			if ($this->update_metaboxes($_POST)) :
				$this->admin_notices('updated','Metabox has been created.');
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
	 *
	 */
	public static function update_custom_post_types($data=array()) {
		$post_types=get_option('mdw_cms_post_types');

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
			'hierarchical' => $data['hierarchical']
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

		return update_option('mdw_cms_post_types',$post_types);
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
		$metaboxes=get_option('mdw_cms_metaboxes');
		$edit_key=-1;

		if (!isset($data['mb_id']) || $data['mb_id']=='')
			return false;

		$arr=array(
			'mb_id' => $data['mb_id'],
			'title' => $data['title'],
			'prefix' => $data['prefix'],
			'post_types' => $data['post_types'],
		);

		// clean fields, if any //
		if (isset($data['fields'])) :
			foreach ($data['fields'] as $key => $field) :
				if (!$field['field_type']) :
					unset($data['fields'][$key]);
				else :
					// remove empty options fields //
					if (isset($field['options'])) :
						unset($data['fields'][$key]['options']['default']);
						$data['fields'][$key]['options']=array_values($data['fields'][$key]['options']);
					endif;
				endif;
			endforeach;
		endif;

		$arr['fields']=array_values($data['fields']);

		if (!empty($metaboxes)) :
			foreach ($metaboxes as $key => $mb) :
				if ($mb['mb_id']==$data['mb_id']) :
					if (isset($data['update-metabox']) && $data['update-metabox']=='Update') :
						$edit_key=$key;
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
	 *
	 */
	function admin_notices($class='error',$message='') {
		echo '<div class="'.$class.'"><p>'.$message.'</p></div>';
	}
}

new MDWCMSgui();
?>
