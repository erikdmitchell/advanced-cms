<?php
class MDW_CPT {

	protected $post_types=array();

	function __construct() {
		add_action('init',array($this,'create_post_types'));		
	}
	
	function create_post_types() {
		foreach ($this->post_types as $post_type => $args) :
			// format our post type by forcing it to lowercase and replacing spaces with hyphens //
			$post_type=strtolower($post_type);
			$post_type=str_replace(' ','-',$post_type);

			$post_type_mod=str_replace('-',' ',$post_type);

			// WILL NEED TO REDO SPACES FOR FORMAL
			if (substr($post_type,-1)=='s') :
				$post_type_plural=$post_type_mod;
			else :
				$post_type_plural=$post_type_mod.'s';
			endif;
			
			$post_type_formal=ucwords($post_type_mod);
			$post_type_formal_plural=ucwords($post_type_plural);
	
			// setup our default 'args' //
			$taxonomies='post_tag';
			$supports=array('title','thumbnail','editor','revisions');
			$hierarchical=false;
			
			// check for custom 'args' //
			if (isset($args['taxonomies']))
				$taxonomies=$args['taxonomies'];

			if (isset($args['supports']))
				$supports=$args['supports'];

			if (isset($args['hierarchical']))
				$hierarchical=$args['hierarchical'];

			register_post_type($post_type,
				array(
					'labels' => array(
						'name' => _x($post_type_formal_plural,$post_type_formal_plural,$post_type),
						'singular_name' => _x($post_type_formal,$post_type),
						'add_new' => _x('Add New',$post_type),
						'add_new_item' => __('Add New '.$post_type_formal),
						'edit_item' => __('Edit '.$post_type_formal),
						'new_item' => __('New '.$post_type_formal),
						'all_items' => __('All '.$post_type_formal_plural),
						'view_item' => __('View '.$post_type_formal),
						'search_items' => __('Search '.$post_type_formal_plural),
						'not_found' =>  __('No '.$post_type_plural.' found'),
						'not_found_in_trash' => __('No '.$post_type_plural.' found in Trash'), 
						'parent_item_colon' => '',
						'menu_name' => $post_type_formal_plural
					),
					'public' => true,
					'has_archive' => false,
					'show_in_menu' => true,
					'menu_position'=> 5,
					'supports' => $supports,
					'taxonomies' => array($taxonomies),
					'hierarchical' => $hierarchical
				)
			);
		
		endforeach;
	}

	/**
	 * adds post types (slug) to our post_types array
	 * @param string/array $args - the slug name of the post type(s)
	**/
	public function add_post_types($args) {
		// determine if we have a simple or complex post type via $args - get first key and determine if number //
		if (is_numeric(key($args))) :
			foreach ($args as $type) :
				$this->post_types[$type]=array();
			endforeach;			
		else :
			$this->post_types=$args;
		endif;	
	}

}

$mdw_custom_post_types=new MDW_CPT();
?>
