<?php
class adminCPT {

	public $wp_option='mdw_cms_post_types';
	public $options=array();

	protected $tab_url=null;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	function __construct() {
		$this->tab_url=admin_url('tools.php?page=mdw-cms&tab=cpt');

		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));
		add_action('wp_ajax_update_cpt',array($this,'ajax_update_cpt'));

		add_filter('mdw_cms_options_tabs',array($this,'setup_tab'));
		add_filter('mdw_cms_default_options',array($this,'add_options'));
	}

	/**
	 * admin_scripts_styles function.
	 *
	 * @access public
	 * @param mixed $hook
	 * @return void
	 */
	public function admin_scripts_styles($hook) {
		wp_enqueue_script('mdw-cms-admin-custom-post-types-script',plugins_url('/js/admin-custom-post-types.js',__FILE__),array('namecheck-script'));
	}

	/**
	 * setup_tab function.
	 *
	 * @access public
	 * @param mixed $tabs
	 * @return void
	 */
	public function setup_tab($tabs) {
		$tabs['cpt']=array(
			'name' => 'Custom Post Types',
			'function' => array($this,'admin_page'),
			'order' => 10
		);
		return $tabs;
	}

	/**
	 * add_options function.
	 *
	 * @access public
	 * @param mixed $options
	 * @return void
	 */
	public function add_options($options) {
		$this->options=get_option($this->wp_option);
		$options['post_types']=$this->options;

		return $options;
	}

	/**
	 * admin_page function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_page() {
		echo $this->admin_page_core();
	}

	/**
	 * admin_page_core function.
	 *
	 * @access protected
	 * @param float $id (default: -1)
	 * @return void
	 */
	protected function admin_page_core($id=-1) {
		$this->options=get_option($this->wp_option); // update our options

		$name=null;
		$label=null;
		$singular_label=null;
		$description=null;
		$title=1;
		$thumbnail=1;
		$editor=1;
		$revisions=1;
		$excerpt=0;
		$hierarchical=0;
		$page_attributes=0;
		$btn_disabled='disabled';

		$label_class='col-md-3';
		$input_class='col-md-3';
		$description_class='col-md-6';
		$description_ext_class='col-md-9 col-md-offset-3';
		$error_class='col-md-12';
		$select_class='col-md-3';
		$existing_label_class='col-md-5';
		$edit_class='col-md-2';
		$delete_class='col-md-2';

		// when a cpt is created is runs a fake form so the page refreshes properly //
		if (isset($_POST['create-cpt']) && $_POST['create-cpt'])
			$id=$_POST['id'];

		// load cpt if we have one //
		if ($id!=-1) :
			extract($this->options[$id]);

			$btn_disabled=null;
		endif;

		$html=null;

		$html.='<div class="row">';

			$html.='<form class="custom-post-types col-md-8" method="post">';
				$html.='<h3>Add New Custom Post Type</h3>';
				$html.='<div class="form-row row">';
					$html.='<label for="name" class="required '.$label_class.'">Post Type Name</label>';
					$html.='<div class="input '.$input_class.'">';
						$html.='<input type="text" name="name" id="name" value="'.$name.'" />';
					$html.='</div>';
					$html.='<span class="description '.$description_class.'">(e.g. movie)</span>';
					$html.='<div id="mdw-cms-name-error" class="'.$error_class.'"></div>';
					$html.='<div class="description-ext '.$description_ext_class.'">Max 20 characters, can not contain capital letters or spaces. Reserved post types: post, page, attachment, revision, nav_menu_item.</div>';
				$html.='</div>';

				$html.='<div class="form-row row">';
					$html.='<label for="label" class="'.$label_class.'">Label</label>';
					$html.='<div class="input '.$input_class.'">';
						$html.='<input type="text" name="label" id="label" value="'.$label.'" />';
					$html.='</div>';
					$html.='<span class="description '.$description_class.'">(e.g. Movies)</span>';
				$html.='</div>';

				$html.='<div class="form-row row">';
					$html.='<label for="singular_label" class="'.$label_class.'">Singular Label</label>';
					$html.='<div class="input '.$input_class.'">';
						$html.='<input type="text" name="singular_label" id="singular_label" value="'.$singular_label.'" />';
					$html.='</div>';
					$html.='<span class="description '.$description_class.'">(e.g. Movie)</span>';
				$html.='</div>';

				$html.='<div class="form-row row">';
					$html.='<label for="description" class="'.$label_class.'">Description</label>';
					$html.='<textarea name="description" id="description" rows="4" cols="40">'.$description.'</textarea>';
				$html.='</div>';

				$html.='<div class="advanced-options">';
					$html.='<div class="form-row row">';
						$html.='<label for="title" class="'.$label_class.'">Title</label>';
						$html.='<div class="'.$select_class.'">';
							$html.='<select name="title" id="title">';
								$html.='<option value="1" '.selected($title,1,false).'>True</option>';
								$html.='<option value="0" '.selected($title,0,false).'>False</option>';
							$html.='</select>';
						$html.='</div>';
						$html.='<span class="description '.$description_class.'">(default True)</span>';
					$html.='</div>';
					$html.='<div class="form-row row">';
						$html.='<label for="thumbnail" class="'.$label_class.'">Thumbnail</label>';
						$html.='<div class="'.$select_class.'">';
							$html.='<select name="thumbnail" id="thumbnaill">';
								$html.='<option value="1" '.selected($thumbnail,1,false).'>True</option>';
								$html.='<option value="0" '.selected($thumbnail,0,false).'>False</option>';
							$html.='</select>';
						$html.='</div>';
						$html.='<span class="description '.$description_class.'">(default True)</span>';
					$html.='</div>';
					$html.='<div class="form-row row">';
						$html.='<label for="editor" class="'.$label_class.'">Editor</label>';
						$html.='<div class="'.$select_class.'">';
							$html.='<select name="editor" id="editor" >';
								$html.='<option value="1" '.selected($editor,1,false).'>True</option>';
								$html.='<option value="0" '.selected($editor,0,false).'>False</option>';
							$html.='</select>';
						$html.='</div>';
						$html.='<span class="description '.$description_class.'">(default True)</span>';
					$html.='</div>';
					$html.='<div class="form-row row">';
						$html.='<label for="revisions" class="'.$label_class.'">Revisions</label>';
						$html.='<div class="'.$select_class.'">';
							$html.='<select name="revisions" id="revisions">';
								$html.='<option value="1" '.selected($revisions,1,false).'>True</option>';
								$html.='<option value="0" '.selected($revisions,0,false).'>False</option>';
							$html.='</select>';
						$html.='</div>';
						$html.='<span class="description '.$description_class.'">(default True)</span>';
					$html.='</div>';
					$html.='<div class="form-row row">';
						$html.='<label for="revisions" class="'.$label_class.'">Excerpt</label>';
						$html.='<div class="'.$select_class.'">';
							$html.='<select name="excerpt" id="_excerpt">';
								$html.='<option value="1" '.selected($excerpt,1,false).'>True</option>';
								$html.='<option value="0" '.selected($excerpt,0,false).'>False</option>';
							$html.='</select>';
						$html.='</div>';
						$html.='<span class="description '.$description_class.'">(default True)</span>';
					$html.='</div>';
					$html.='<div class="form-row row">';
						$html.='<label for="hierarchical" class="'.$label_class.'">Hierarchical</label>';
						$html.='<div class="'.$select_class.'">';
							$html.='<select name="hierarchical" id="hierarchical">';
								$html.='<option value="1" '.selected($hierarchical,1,false).'>True</option>';
								$html.='<option value="0" '.selected($hierarchical,0,false).'>False</option>';
							$html.='</select>';
						$html.='</div>';
						$html.='<span class="description '.$description_class.'">(default False)</span>';
						$html.='<div class="description-ext '.$description_ext_class.'">Whether the post type is hierarchical (e.g. page). Allows Parent to be specified. Note: "page-attributes" must be set to true to show the parent select box.</div>';
					$html.='</div>';
					$html.='<div class="form-row row">';
						$html.='<label for="page_attributes" class="'.$label_class.'">Page Attributes</label>';
						$html.='<div class="'.$select_class.'">';
							$html.='<select name="page_attributes" id="page_attributes">';
								$html.='<option value="1" '.selected($page_attributes,1,false).'>True</option>';
								$html.='<option value="0" '.selected($page_attributes,0,false).'>False</option>';
							$html.='</select>';
						$html.='</div>';
						$html.='<span class="description '.$description_class.'">(default False)</span>';
					$html.='</div>';
				$html.='</div>';

				$html.='<p class="submit">';
					if ($id!=-1) :
						$html.='<input type="button" name="add-cpt" id="submit" class="button button-primary submit-button" value="Update" '.$btn_disabled.' data-type="cpt" data-tab-url="'.$this->tab_url.'" data-page-action="update" data-action="update_cpt" data-item-type="cpt">';
					else :
						$html.='<input type="button" name="add-cpt" id="submit" class="button button-primary submit-button" value="Create" '.$btn_disabled.' data-type="cpt" data-tab-url="'.$this->tab_url.'" data-page-action="add" data-action="update_cpt" data-item-type="cpt">';
					endif;
				$html.='</p>';

				$html.='<input type="hidden" name="cpt-id" id="cpt-id" value='.$id.' />';
				$html.='<input type="hidden" name="cpt-prev-name" id="cpt-prev-name" value='.$name.' />';
				//$html.='<input type="hidden" name="return-url" id="return-url" value='.$base_url.'&edit=cpt&slug='.$cpt['name'].' />';
			$html.='</form>';

			$html.='<div class="custom-post-types-list col-md-4">';
				$html.='<h3>Custom Post Types</h3>';

				if ($this->options) :
					foreach ($this->options as $key => $cpt) :
						$html.='<div id="cpt-list-'.$key.'" class="cpt-row row mdw-cms-edit-delete-list">';
							$html.='<span class="cpt '.$existing_label_class.'">'.$cpt['label'].'</span>';
							$html.='<span class="edit '.$edit_class.'">[<a href="" data-tab-url="'.$this->tab_url.'" data-item-type="cpt" data-slug="'.$cpt['name'].'" data-page-action="edit" data-action="update_cpt" data-id="'.$key.'" data-title="Custom Post Type">Edit</a>]</span>';
							$html.='<span class="delete '.$delete_class.'">[<a href="" data-tab-url="'.$this->tab_url.'" data-item-type="cpt" data-slug="'.$cpt['name'].'" data-page-action="delete" data-action="update_cpt" data-id="'.$key.'" data-title="Custom Post Type">Delete</a>]</span>';
						$html.='</div>';
					endforeach;
				endif;

			$html.='</div>';

		$html.='</div><!-- .row -->';


		return $html;
	}

	/**
	 * ajax_update_cpt function.
	 *
	 * edit/delete custom post type
	 *
	 * @access public
	 * @return void
	 */
	public function ajax_update_cpt() {
		$post_types=$this->options;
		$response=array();

		extract($_POST);

		if ($page_action=='edit') :
			$response['content']=$this->admin_page_core($id);
			$response['notice']=null;
		elseif ($page_action=='delete') : // remove post type and update option //
			unset($post_types[$id]);
			$post_types=array_values($post_types);
			update_option($this->wp_option,$post_types);

			$response=true; // reload page //
		elseif ($page_action=='add') :
			$form_data_final=array();

			foreach ($form_data as $input) :
				$form_data_final[$input['name']]=$input['value'];
			endforeach;

			if ($this->update_custom_post_types($form_data_final)) :
				$post_types=get_option($this->wp_option);
				foreach ($post_types as $key => $post_type) :
					if ($post_type['name']==$form_data_final['name']) :
						$id=$key;
						$slug=$post_type['name'];
					endif;
				endforeach;

				// b/c we do a page reload, this hides some vars so that we can load with the edit screen up //
				$response['id']=$id;
				$response['notice']=urlencode('<div class="updated">Post type "'.$slug.'" has been created.</div>');
			else :
				$response['content']=$this->admin_page_core();
				$response['notice']='<div class="error">There was an issue creating the post type "'.$slug.'</div>';
			endif;
		elseif ($page_action=='update') :
			$form_data_final=array();

			foreach ($form_data as $input) :
				$form_data_final[$input['name']]=$input['value'];
			endforeach;

			$id=$form_data_final['cpt-id'];
			$slug=$form_data_final['name'];

			if ($this->update_custom_post_types($form_data_final)) :
				$response['content']=$this->admin_page_core($id);
				$response['notice']='<div class="updated">Post type "'.$slug.'" has been updated.</div>';
			else :
				$response['content']=$this->admin_page_core($id);
				$response['notice']='<div class="error">There was an issue updating the post type "'.$slug.'</div>';
			endif;
		endif;

		echo json_encode($response);

		wp_die();
	}

	/**
	 * update_custom_post_types function.
	 *
	 * @access protected
	 * @param array $data (default: array())
	 * @return void
	 */
	protected function update_custom_post_types($data=array()) {
		$post_types=get_option($this->wp_option);
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
			'excerpt' => $data['excerpt'],
			'hierarchical' => $data['hierarchical'],
			'page_attributes' => $data['page_attributes']
		);

		if ($data['cpt-id']!=-1) :
			// if we change the name, clean up the db //
			if ($data['name']!=$data['cpt-prev-name']) :
				self::update_cpt_name($data['cpt-prev-name'],$data['name']);
			endif;

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

		return update_option($this->wp_option,$post_types);
	}

}

new adminCPT();
?>