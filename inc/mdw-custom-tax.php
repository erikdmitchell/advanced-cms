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
	 * added ability for taxonomy to be an array, kept other variables for legacy support
	 * @param string $taxonomy - the taxonomy name (slug form)
	 * @param string $object_type - name of the object type ie: post,page,custom_post_type
	 * @param string $label - the taxonomy display name
	**/
	public function add_taxonomy($taxonomy,$object_type=false,$label=false) {
		if (!$object_type && !$label) :
			foreach ($taxonomy as $tax => $values) :
				$arr=array(
					'taxonomy' => $tax,
					'object_type' => $values['object_type'],
					'label' => $values['label']
				);
				array_push($this->taxonomies,$arr);
			endforeach;
		else :
			$arr=array(
				'taxonomy' => $taxonomy,
				'object_type' => $object_type,
				'label' => $label
			);
			array_push($this->taxonomies,$arr);
		endif;		
	}

}

$mdw_custom_taxonomies=new mdw_Custom_Tax();
?>