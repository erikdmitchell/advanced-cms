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
			$title=false;
			$thumbnail=false;
			$editor=false;
			$revisions=false;
			$page_attributes=false;
			$hierarchical=false;
			$comments=false;

			extract($post_type);

			// check for custom 'args' //
			if ($title)
				$supports[]='title';

			if ($thumbnail)
				$supports[]='thumbnail';

			if ($editor)
				$supports[]='editor';

			if ($revisions)
				$supports[]='revisions';

			if ($page_attributes)
				$supports[]='page-attributes';

			if ($comments)
				$supports[]='comments';

			register_post_type($post_type['name'],
				array(
					'labels' => array(
						'name' => _x($label,$label,$name),
						'singular_name' => _x($singular_label,$name),
						'add_new' => _x('Add New',$name),
						'add_new_item' => __('Add New '.$singular_label),
						'edit_item' => __('Edit '.$singular_label),
						'new_item' => __('New '.$singular_label),
						'all_items' => __('All '.$label),
						'view_item' => __('View '.$singular_label),
						'search_items' => __('Search '.$label),
						'not_found' =>  __('No '.$label.' found'),
						'not_found_in_trash' => __('No '.$label.' found in Trash'),
						'parent_item_colon' => '',
						'menu_name' => $label
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

}

new MDWCustomPostTypes();
?>
