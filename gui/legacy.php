<?php
if (!class_exists('MDW_CPT')) :

	class MDW_CPT {
		
		function __contstruct() {
			
		}

		/**
		 * adds post types (slug) to our post_types array
		 * @param string/array $args - the slug name of the post type(s)
		 */
		public function add_post_types($args) {
			$post_types=array();

			if (is_numeric(key($args))) :
				foreach ($args as $type) :
					$post_types[$type]=array();
				endforeach;			
			else :
				$post_types=$args;
			endif;
			
echo '<pre>';
print_r($post_types);
echo '</pre>';				
		}

	}

	$mdw_custom_post_types=new MDW_CPT();

endif;

if (!class_exists('mdw_Meta_Box')) :
	
	class mdw_Meta_Box {
		
	}
	
endif;
?>