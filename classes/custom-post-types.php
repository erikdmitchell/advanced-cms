<?php
class MDWCustomPostTypes {

	protected $post_types=array();

	function __construct() {
		$this->post_types=get_option('mdw_cms_post_types');

		add_action('init',array($this,'create_post_types'));
	}

	function create_post_types() {
		$Words=new Inflector();

		if (empty($this->post_types))
			return false;

		foreach ($this->post_types as $post_type) :
			// setup our default 'args' //
			$supports=array();

			$taxonomies='post_tag';

			// semi legacy support //
			if (!isset($post_type['hierarchical']))
				$post_type['hierarchical']=false;

			// check for custom 'args' //
			if (isset($post_type['title'])) :
				if ($post_type['title']) :
					$supports[]='title';
				endif;
			else :
				$supports[]='title';
			endif;

			if (isset($post_type['thumbnail'])) :
				if ($post_type['thumbnail']) :
					$supports[]='thumbnail';
				endif;
			else :
				$supports[]='thumbnail';
			endif;

			if (isset($post_type['editor'])) :
				if ($post_type['editor']) :
					$supports[]='editor';
				endif;
			else :
				$supports[]='editor';
			endif;

			if (isset($post_type['revisions'])) :
				if ($post_type['revisions']) :
					$supports[]='revisions';
				endif;
			else :
				$supports[]='revisions';
			endif;

			if (isset($post_type['page_attributes']))
				$supports[]='page-attributes';

			register_post_type($post_type['name'],
				array(
					'labels' => array(
						'name' => _x($post_type['label'],$post_type['label'],$post_type['name']),
						'singular_name' => _x($post_type['singular_label'],$post_type['name']),
						'add_new' => _x('Add New',$post_type['name']),
						'add_new_item' => __('Add New '.$post_type['singular_label']),
						'edit_item' => __('Edit '.$post_type['singular_label']),
						'new_item' => __('New '.$post_type['singular_label']),
						'all_items' => __('All '.$post_type['label']),
						'view_item' => __('View '.$post_type['singular_label']),
						'search_items' => __('Search '.$post_type['label']),
						'not_found' =>  __('No '.$post_type['label'].' found'),
						'not_found_in_trash' => __('No '.$post_type['label'].' found in Trash'),
						'parent_item_colon' => '',
						'menu_name' => $post_type['label']
					),
					'public' => true,
					'has_archive' => false,
					'show_in_menu' => true,
					'menu_position'=> 5,
					'supports' => $supports,
					'taxonomies' => array($taxonomies),
					'hierarchical' => $post_type['hierarchical']
				)
			);

		endforeach;
	}

}

new MDWCustomPostTypes();
?>
