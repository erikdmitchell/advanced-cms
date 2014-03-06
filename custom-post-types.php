<?php

$mdw_custom_post_types->add_post_types('suppliers');
//$custom_post_types->add_post_types(array('shoes','cars'));

$mdw_custom_taxonomies->add_taxonomy('supplier-type','suppliers','Supplier Type');
// custom taxonomy code //
/*
add_action('init','my_taxonomies',0); 
function my_taxonomies() {
	register_taxonomy( 
		'supplier-type', 
		'suppliers', 
		array( 
			'hierarchical' => true, 
			'label' => 'Supplier Type', 
			'query_var' => true, 
			'rewrite' => true 
		)
	);	
}
*/

?>