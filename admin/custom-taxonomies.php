<?php
class adminTax {

	public $wp_option='mdw_cms_taxonomies';
	public $options=array();

	protected $tab_url='';

	function __construct() {
		$this->tab_url=admin_url('tools.php?page=mdw-cms&tab=tax');

		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));

		add_filter('mdw_cms_options_tabs',array($this,'setup_tab'));
		add_filter('mdw_cms_default_options',array($this,'add_options'));
	}

	public function admin_scripts_styles($hook) {
		wp_register_script('mdw-cms-admin-custom-taxonomies-script',plugins_url('/js/admin-custom-taxonomies.js',__FILE__),array('namecheck-script'));

		$post_types=get_post_types();
		$types=array();
		foreach ($post_types as $post_type) :
			$types[]=$post_type;
		endforeach;

		$taxonomy_options=array(
			'reservedPostTypes' => $types
		);

		wp_localize_script('mdw-cms-admin-custom-taxonomies-script','wp_options',$taxonomy_options);

		wp_enqueue_script('mdw-cms-admin-custom-taxonomies-script');
	}

	public function setup_tab($tabs) {
		$tabs['tax']=array(
			'name' => 'Taxonomies',
			'function' => array($this,'admin_page'),
			'order' => 2
		);
		return $tabs;
	}

	public function add_options($options) {
		$this->options=get_option($this->wp_option);
		$options['taxonomies']=$this->options;

		return $options;
	}

	public function admin_page() {
		$btn_text='Create';
		$name=null;
		$label=null;
		$object_type=null;
		$hierarchical=1;
		$show_ui=1;
		$show_admin_col=1;
		$id=-1;
		$notices=null;

		$label_class='col-md-3';
		$input_class='col-md-3';
		$description_class='col-md-6';
		$description_ext_class='col-md-9 col-md-offset-3';
		$error_class='col-md-12';
		$existing_label_class='col-md-5';
		$edit_class='col-md-2';
		$delete_class='col-md-2';

		// edit custom taxonomy //
		if (isset($_GET['edit']) && $_GET['edit']=='tax') :
			foreach ($this->options as $key => $tax) :
				if ($tax['name']==$_GET['slug']) :
					extract($this->options[$key]);
					$label=$args['label'];
					$id=$key;
				endif;
			endforeach;
		endif;

		// create custom taxonomy //
		if (isset($_POST['add-tax']) && $_POST['add-tax']=='Create') :
			if ($this->update_taxonomies($_POST)) :
				$notices='<div class="updated">Taxonomy has been created.</div>';
			else :
				$notices='<div class="error">There was an issue creating the taxonomy.</div>';
			endif;
		endif;

		// update taxonomy //
		if (isset($_POST['add-tax']) && $_POST['add-tax']=='Update') :
			if ($this->update_taxonomies($_POST)) :
				$notices='<div class="updated">Taxonomy has been updated.</div>';
			else :
				$notices='<div class="error">There was an issue updating the taxonomy.</div>';
			endif;
		endif;

		// remove taxonomy //
		if (isset($_GET['delete']) && $_GET['delete']=='tax') :
			foreach ($this->options as $key => $tax) :
				if ($tax['name']==$_GET['slug']) :
					unset($this->options[$key]);
					$notices='<div class="updated">Taxonomy has been removed.</div>';
				endif;
			endforeach;

			$taxonomies=array_values($this->options);

			update_option('mdw_cms_taxonomies',$taxonomies);
		endif;

		$this->options=get_option($this->wp_option); // reload our options

		if ($id!=-1)
			$btn_text='Update';

		$html=null;

		$html.='<div class="taxonomy-notices">'.$notices.'</div>';

		$html.='<div class="row">';

			$html.='<form class="custom-taxonomies col-md-8" method="post">';
				$html.='<h3>Add New Custom Taxonomy</h3>';
				$html.='<div class="form-row row">';
					$html.='<label for="name" class="required '.$label_class.'">Name</label>';
					$html.='<div class="input '.$input_class.'">';
						$html.='<input type="text" name="name" id="name" value="'.$name.'" />';
					$html.='</div>';
					$html.='<span class="description '.$description_class.'">(e.g. brands)</span>';
					$html.='<div id="mdw-cms-name-error" class="'.$error_class.'"></div>';
					$html.='<div class="description-ext '.$description_ext_class.'">Max 20 characters, can not contain capital letters or spaces. Cannot be the same name as a (custom) post type.</div>';
				$html.='</div>';

				$html.='<div class="form-row row">';
					$html.='<label for="label" class="'.$label_class.'">Label</label>';
					$html.='<div class="input '.$input_class.'">';
						$html.='<input type="text" name="label" id="label" value="'.$label.'" />';
					$html.='</div>';
					$html.='<span class="description '.$description_class.'">(e.g. Brands)</span>';
				$html.='</div>';

				$html.=get_post_types_list($object_type);

				$html.='<p class="submit"><input type="submit" name="add-tax" id="submit" class="button button-primary" value="'.$btn_text.'"></p>';
				$html.='<input type="hidden" name="tax-id" id="tax-id" value='.$id.' />';
			$html.='</form>';

			$html.='<div class="custom-taxonomies-list col-md-4">';
				$html.='<h3>Custom Taxonomies</h3>';

				if ($this->options) :
					foreach ($this->options as $tax) :
						$html.='<div class="tax-row row">';
							$html.='<span class="tax '.$existing_label_class.'">'.$tax['args']['label'].'</span><span class="edit '.$edit_class.'">[<a href="'.$this->tab_url.'&edit=tax&slug='.$tax['name'].'">Edit</a>]</span><span class="delete '.$delete_class.'">[<a href="'.$this->tab_url.'&delete=tax&slug='.$tax['name'].'">Delete</a>]</span>';
						$html.='</div>';
					endforeach;
				endif;

			$html.='</div><!-- .custom-taxonomies-list -->';

		$html.='</div><!-- .row -->';

		echo $html;
	}

	/**
	 * update_taxonomies function.
	 *
	 * @access public
	 * @param array $data (default: array())
	 * @return void
	 */
	public function update_taxonomies($data=array()) {
		$option_exists=false;
		$taxonomies=get_option($this->wp_option);

		if (!isset($data['name']) || $data['name']=='')
			return false;

		if (!isset($data['post_types']))
			$data['post_types']=array(
				'post'
			);

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

		if (get_option($this->wp_option))
			$option_exists=true;

		$update=update_option($this->wp_option,$taxonomies);

		if ($update) :
			return true;
		elseif ($option_exists) :
			return true;
		else :
			return false;
		endif;
	}

}

new adminTax();
?>