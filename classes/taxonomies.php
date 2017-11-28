<?php
class PickleCMS_Taxonomies {

	public $taxonomies=array();

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	function __construct() {
		$this->taxonomies=get_option('pickle_cms_taxonomies');

		add_action('init', array($this, 'create_taxonomies'));
	}

	/**
	 * create_taxonomies function.
	 * 
	 * @access public
	 * @return void
	 */
	function create_taxonomies() {
		if (isset($this->taxonomies) && !empty($this->taxonomies)) :
			foreach ($this->taxonomies as $taxonomy) :
				register_taxonomy(
					$taxonomy['name'],
					$taxonomy['object_type'],
					$taxonomy['args']
				);
			endforeach;
		endif;
	}

}
?>