<?php
class mdw_Custom_Tax {

	protected $taxonomies=array();

	function __construct() {
		add_action('init',array($this,'create_taxonomies'));
	}
	
	function create_taxonomies() {
		foreach ($this->taxonomies as $taxonomy) :
			register_taxonomy( 
				$taxonomy['taxonomy'], 
				$taxonomy['object_type'], 
				array( 
					'hierarchical' => true, 
					'label' => $taxonomy['label'], 
					'query_var' => true, 
					'rewrite' => true 
				)
			);		
		endforeach;
	}

	/**
	 * adds taxonomies (slug) to our taxonomies array
	 * @param string $taxonomy - the taxonomy name (slug form)
	 * @param string $object_type - name of the object type ie: post,page,custom_post_type
	 * @param string $label - the taxonomy display name
	**/
	public function add_taxonomy($taxonomy,$object_type,$label) {
		$arr=array(
			'taxonomy' => $taxonomy,
			'object_type' => $object_type,
			'label' => $label
		);
		array_push($this->taxonomies,$arr);
	}

}

$mdw_custom_taxonomies=new mdw_Custom_Tax();
?>