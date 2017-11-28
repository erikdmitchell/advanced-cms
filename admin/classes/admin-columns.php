<?php
class PickleCMS_Admin_Component_Admin_Columns extends PickleCMS_Admin_Component {

	public function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'scripts_styles'));
		add_action('admin_init', array($this, 'load_columns'));		
		add_action('admin_init', array($this, 'update_admin_columns'));
		add_action('wp_ajax_pickle_cms_get_column', array($this, 'ajax_get'));
		add_action('wp_ajax_pickle_cms_delete_column', array($this, 'ajax_delete'));

		$this->slug='columns';
		$this->name='Admin Columns';
		$this->items=$this->get_option('pickle_cms_admin_columns', array());
		$this->version='0.1.0';
		
		// do not delete!
    	parent::__construct();	
	}
	
	/**
	 * scripts_styles function.
	 * 
	 * @access public
	 * @param mixed $hook
	 * @return void
	 */
	public function scripts_styles($hook) {
		wp_enqueue_script('pickle-cms-admin-columns-script', PICKLE_CMS_ADMIN_URL.'js/admin-columns.js', array('jquery'), $this->version, true);	
	}
	
	/**
	 * update_admin_columns function.
	 * 
	 * @access public
	 * @return void
	 */
	public function update_admin_columns() {
		if (!isset($_POST['pickle_cms_admin']) || !wp_verify_nonce($_POST['pickle_cms_admin'], 'update_columns'))
			return;

		if (!isset($_POST['post_type']) || $_POST['post_type']=='0')
			return;
		
		if (!isset($_POST['metabox_taxonomy']) || $_POST['metabox_taxonomy']=='0')
			return;

		$admin_columns=get_option('pickle_cms_admin_columns');

		$arr=array(
			'post_type' => $_POST['post_type'],
			'metabox_taxonomy' => $_POST['metabox_taxonomy'],
		);

		if ($_POST['admin_column_id']!=-1) :
			$admin_columns[$_POST['admin_column_id']]=$arr;
		else :
			$admin_columns[]=$arr;
		endif;

		if (get_option('pickle_cms_admin_columns'))
			$option_exists=true;

		$update=update_option('pickle_cms_admin_columns', $admin_columns);

		if ($update) :
			$update=true;
		elseif ($option_exists) :
			$update=true;
		else :
			$update=false;
		endif;

		$url=pickle_cms_get_admin_link(array(
			'tab' => 'columns',
			'action' => 'update',
			'updated' => $update,
			'post_type' => $_POST['post_type'],
			'metabox_taxonomy' => $_POST['metabox_taxonomy'],
		));

		wp_redirect($url);
		exit;
	}

	/**
	 * setup function.
	 * 
	 * @access public
	 * @return void
	 */
	function setup() {
		$default_args=array(
			'base_url' => admin_url('tools.php?page=pickle-cms&tab=columns'),
			'btn_text' => 'Create',
			'post_type' => '',
			'metabox_taxonomy' => '',
			'id' => -1,
			'header' => 'Add New Admin Column',
		);
	
		// edit custom post type //
		if (isset($_GET['post_type']) && $_GET['post_type']) :
			foreach ($this->items as $key => $column) :
				if ($column['post_type']==$_GET['post_type'] && $column['metabox_taxonomy']==$_GET['metabox_taxonomy']) :
					$args=$column;
					$args['header']='Edit Admin Column';
					$args['btn_text']='Update';
					$args['id']=$key;
				endif;
			endforeach;
		endif;
	
		$args=pickle_cms_parse_args($args, $default_args);
	
		return $args;
	}

	/**
	 * ajax_get function.
	 * 
	 * @access public
	 * @return void
	 */
	public function ajax_get() {
		if (!isset($_POST['post_type']) || !isset($_POST['taxonomy']))
			return false;

		// find matching post type //
		foreach ($this->items as $column) :		
			if ($column['post_type']==$_POST['post_type'] && $column['metabox_taxonomy']==$_POST['taxonomy']) :			
				echo json_encode($column);
				break;
			endif;
		endforeach;

		wp_die();
	}

	/**
	 * ajax_delete function.
	 * 
	 * @access public
	 * @return void
	 */
	public function ajax_delete() {	   	
		if (!isset($_POST['post_type']) || !isset($_POST['taxonomy']))
			return;

		if ($this->delete_column($_POST['post_type'], $_POST['taxonomy']))
			return true;

		return;

		wp_die();
	}

	/**
	 * delete_column function.
	 * 
	 * @access protected
	 * @param string $post_type (default: '')
	 * @param string $taxonomy (default: '')
	 * @return void
	 */
	protected function delete_column($post_type='', $taxonomy='') {
		$cols=array();

		// build clean array //
		foreach ($this->items as $col) :		
			if ($col['post_type'] != $post_type || $col['metabox_taxonomy'] != $taxonomy) :
				$cols[]=$col;
            endif;
		endforeach;

		update_option('pickle_cms_admin_columns', $cols); // update option

		return true;
	}
	
	public function load_columns() {
        foreach ($this->items as $item) :
	    	add_filter('manage_edit-'.$item['post_type'].'_columns', array($this, 'custom_admin_columns'));
    		//add_action('manage_'.$item['post_type'].'_posts_custom_column', array($this, 'custom_colun_row'), 10, 2);            
        endforeach;
	}
	
	public function custom_admin_columns($columns) {
		foreach ($this->items as $col) :
			$columns[$col['metabox_taxonomy']]=$this->get_column_label($col['metabox_taxonomy']); // this doth not work
		endforeach;

		return $columns;
	}
	
	/**
	 * get_column_label function.
	 * 
	 * @access protected
	 * @param string $slug (default: '')
	 * @return void
	 */
	protected function get_column_label($slug='') {
    	global $post;
    	
    	$metabox_fields=array();
    	$post_type=get_post_type($post);
        $taxonomies=pickle_cms_get_taxonomies($post_type);
        $metaboxes=pickle_cms_get_metaboxes($post_type);
        $mb_tax_arr=array();
        
    	foreach ($metaboxes as $metabox) :
    		$metabox_fields=array_merge($metabox_fields, pickle_cms_get_metabox_fields($metabox['mb_id']));
    	endforeach;	        
        
        print_r($taxonomies); 
        
        foreach ($taxonomies as $taxonomy) :
            $mb_tax_arr[$taxonomy['name']]=$taxonomy['args']['label'];
        endforeach;
        
        foreach ($metabox_fields as $field) :
            $mb_tax_arr[$field['id']]=$field['title'];
        endforeach;
        
        foreach ($mb_tax_arr as $mb_tax_slug => $label) :
            if ($mb_tax_slug==$slug)
                return $label;
        endforeach;   	
        
        return;
	}
	
	public function custom_colun_row($column_name,$post_id) {
		$custom_fields=get_post_custom($post_id);

		foreach ($this->config['columns'] as $col) :
			if ($col['slug']==$column_name) :
				if (isset($col['type'])) :
				switch ($col['type']) :
					case 'meta':
						if (isset($custom_fields[$col['slug']][0]))
							echo $custom_fields[$col['slug']][0];
						break;
					case 'taxonomy':
						$terms=wp_get_post_terms($post_id,$col['slug']);
						if ($terms)
							echo $terms[0]->name;
						break;
					default: // meta //
						if (isset($custom_fields[$col['slug']][0]))
							echo $custom_fields[$col['slug']][0];
						break;
				endswitch;
				else :
					// assume meta for legacy (1.0.1) purposes //
					if (isset($custom_fields[$col['slug']][0]))
						echo $custom_fields[$col['slug']][0];
				endif;
			endif;
		endforeach;
	}		

}

function ajax_pickle_cms_admin_col_change_post_type() {
	echo pickle_cms_metabox_taxonomy_dropdown($_POST['post_type']);
		
	wp_die();
}
add_action('wp_ajax_pickle_cms_admin_col_change_post_type', 'ajax_pickle_cms_admin_col_change_post_type');

function pickle_cms_metabox_taxonomy_dropdown($post_type='', $selected='') {
	$html='';
	$metabox_fields=array();
	$taxonomies=pickle_cms_get_taxonomies($post_type);
	$metaboxes=pickle_cms_get_metaboxes($post_type);
	
	foreach ($metaboxes as $metabox) :
		$metabox_fields=array_merge($metabox_fields, pickle_cms_get_metabox_fields($metabox['mb_id']));
	endforeach;	
	
	$html.='<select name="metabox_taxonomy">';
		$html.='<option value="0">Select One</option>';
		
		if (!empty($taxonomies)) :
			$html.='<option value="0">Taxonomy:</option>';
			
			foreach ($taxonomies as $taxonomy) :
				$html.='<option value="'.$taxonomy['name'].'" '.selected($selected, $taxonomy['name'], false).'>&nbsp;&nbsp;'.$taxonomy['args']['label'].'</option>';
			endforeach;
		endif;

		if (!empty($metabox_fields)) :
			$html.='<option value="0">Metabox Fields:</option>';
			
			foreach ($metabox_fields as $metabox_field) :
				$html.='<option value="'.$metabox_field['id'].'" '.selected($selected, $metabox_field['id'], false).'>&nbsp;&nbsp;'.$metabox_field['title'].'</option>';
			endforeach;
		endif;
	
	$html.='</select>';	

	return $html;
}

// taxonomies //
function pickle_cms_get_taxonomies($object_type='') {
	$taxonomies=array();
	$all_taxonomies=picklecms()->admin->components['taxonomies']->items;

	if (empty($object_type)) :
		$taxonomies=$all_taxonomies;
	else :	
		foreach ($all_taxonomies as $taxonomy) :	
			if (in_array($object_type, $taxonomy['object_type'])) :
				$taxonomies[]=$taxonomy;
			endif;
		endforeach;
	endif;
	
	return $taxonomies;
}

// metaboxes //
function pickle_cms_get_metaboxes($object_type='', $metabox_id='') {
	$fields=array();
	$metaboxes=array();
	$all_metaboxes=picklecms()->admin->components['metaboxes']->items;
	
	// get metaboxes //
	if (empty($metabox_id)) :
		$metaboxes=$all_metaboxes;
	else :
		foreach ($all_metaboxes as $metabox) :
			if ($metabox_id==$metabox['mb_id']) :
				$metaboxes[]=$metabox;
			endif;
		endforeach;	
	endif;
	
	// check object type and remove if not in //
	if (!empty($object_type)) :
		foreach ($metaboxes as $key => $metabox) :
			if (!in_array($object_type, $metabox['post_types'])) :
				unset($metaboxes[$key]);
			endif;
		endforeach;
	endif;
	
	return $metaboxes;
}

function pickle_cms_get_metabox_fields($metabox_id='') {
	$fields=array();
	$all_metaboxes=picklecms()->admin->components['metaboxes']->items;
	
	foreach ($all_metaboxes as $metabox) :
		if ($metabox_id==$metabox['mb_id']) :
			return $metabox['fields'];
		endif;
	endforeach;
	
	return $fields;
}
?>