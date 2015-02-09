<?php
class MDWCustomTaxonomies {

	protected $taxonomies=array();

	function __construct() {
		$this->taxonomies=get_option('mdw_cms_taxonomies');

		add_action('init',array($this,'create_taxonomies'));
	}

	function create_taxonomies() {
		foreach ($this->taxonomies as $taxonomy) :
			register_taxonomy(
				$taxonomy['name'],
				$taxonomy['object_type'],
				$taxonomy['args']
			);
		endforeach;
	}

}

new MDWCustomTaxonomies();
?>