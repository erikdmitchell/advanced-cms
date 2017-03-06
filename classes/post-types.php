<?php
class advancedCMSPostTypes {

	protected $post_types=array();

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	function __construct() {
		$this->post_types=get_option('advanced_cms_post_types');

		add_action('init', array($this, 'create_post_types'));
	}

	/**
	 * create_post_types function.
	 * 
	 * @access public
	 * @return void
	 */
	function create_post_types() {
		if (empty($this->post_types))
			return false;

		foreach ($this->post_types as $post_type) :
			$default_args=array(
				'name' => 'name',
				'label' => 'Label',
				'singular_label' => 'Single Label',
				'description' => '',
				'supports' => array(
					'title' => 0,
					'thumbnail' => 0,
					'editor' => 0,
					'revisions' => 0,
					'page_attributes' => 0,
					'excerpt' => 0,
					'comments' => 0,
				),
				'hierarchical' => 0, 
				'taxonomies' => 0, 
				'icon' => 'dashicons-admin-post'				
			);
			$args=advanced_cms_parse_args($post_type, $default_args);

			extract($args);

			register_post_type($post_type['name'],
				array(
					'labels' => array(
						'name' => _x($label, $label, $name),
						'singular_name' => _x($singular_label, $name),
						'add_new' => _x('Add New', $name),
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
					'menu_icon' => $icon,
					'show_in_menu' => true,
					'menu_position'=> 5,
					'supports' => $this->setup_supports($supports),
					'taxonomies' => array($taxonomies),
					'hierarchical' => $hierarchical
				)
			);

		endforeach;
	}

	/**
	 * setup_supports function.
	 * 
	 * @access protected
	 * @param array $arr (default: array())
	 * @return void
	 */
	protected function setup_supports($arr=array()) {
		$supports=array();
		
		if (empty($arr))
			return $supports;
			
		foreach ($arr as $key => $value) :
			if ($value)
				$supports[]=$key;
		endforeach;
		
		return $supports;
	}

}

new advancedCMSPostTypes();
?>
