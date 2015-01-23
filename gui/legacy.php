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
			$Words=new Inflector();
			$post_types=array();
			$clean_post_types=array();
			$mdw_cms_post_types=get_option('mdw_cms_post_types');

			if (is_numeric(key($args))) :
				foreach ($args as $type) :
					$post_types[$type]=array();
				endforeach;			
			else :
				$post_types=$args;
			endif;
			
			// clean and setup for migration //
			foreach ($post_types as $name => $post_type) :
				$title=0;
				$thumbail=0;
				$editor=0;
				$revisions=0;
				$hierarchical=0;
				$word_type='plural';
			
				if (isset($args['word_type']))
					$word_type=$args['word_type'];				
				
				// format our post type by forcing it to lowercase and replacing spaces with hyphens //	
				$post_type_name=strtolower($name);
				$post_type_name=str_replace(' ','-',$post_type_name);
	
				$post_type_name_mod=str_replace('-',' ',$post_type_name);
	
				// WILL NEED TO REDO SPACES FOR FORMAL
				if ($word_type=='plural') :
					$post_type_plural=$post_type_name;
					$post_type_formal=$Words->singularize(ucwords($post_type_name_mod));
					$post_type_formal_plural=ucwords($post_type_plural);
				else :
					$post_type_plural=$Words->pluralize($post_type_name);
					$post_type_formal=ucwords($post_type_name_mod);
					$post_type_formal_plural=ucwords($post_type_plural);
				endif;				
				
				if (in_array('title',$post_type['supports']))
					$title=1;
			
				if (in_array('thumbnail',$post_type['supports']))
					$thumbail=1;
					
				if (in_array('editor',$post_type['supports']))
					$editor=1;
					
				if (in_array('revisions',$post_type['supports']))
					$revisions=1;

				if (isset($post_type['hierarchical']) && $post_type['hierarchical'])
					$hierarchical=1;
				
				$clean_post_types[]=array(
			    'name' => $name,
			    'label' => $post_type_formal,
			    'singular_label' => $post_type_formal_plural,
			    'description' => '',
			    'title' => $title,
			    'thumbnail' => $thumbail,
			    'editor' => $editor,
			    'revisions' => $revisions,
			    'hierarchical' => $hierarchical,
			    'add-cpt' => 'Create',
			    'cpt-id' => -1,
				);
			endforeach;

			foreach ($clean_post_types as $pt) :
				$flag=false;

				if (!empty($mdw_cms_post_types)) :
					foreach ($mdw_cms_post_types as $mdw_pt) :
						if ($mdw_pt['name']==$pt['name'])
							$flag=true;
					endforeach;
				endif;
				
				if (!$flag) :
					MDWCMSgui::update_custom_post_types($pt);
echo 'Custom Post Types Migrated<br>';					
				endif;
			endforeach;
		}

	}

	$mdw_custom_post_types=new MDW_CPT();

endif;

if (!class_exists('mdw_Meta_Box')) :
	
	class mdw_Meta_Box {
		
		function __construct($config=array()) {
			if (!$this->is_multi($config)) :
				$config=$this->convert_to_multi($config);
			endif;

			$this->config=$this->setup_config($config); // set our config
			
			$this->convert_metaboxes();		
		}
		
		/**
		 * is array multidimensional (check only first level)
		 * takes in array
		 * returns true/false
		 */
		function is_multi($a) {
			if (isset($a[0]))
				return true;
	
			return false;
		}

		/**
		 * setup our config with defaults and adjusments
		**/
		function setup_config($configs) {
			$ran_string=substr(substr("abcdefghijklmnopqrstuvwxyz",mt_rand(0,25),1).substr(md5(time()),1),0,5);
			$default_config=array(
				'id' => 'mdwmb_'.$ran_string,
				'title' => 'Default Meta Box',
				'prefix' => '_mdwmb',
				'post_types' => 'post,page',
				'duplicate' => 0,
				'fields' => array(), // for legacy support (pre 1.1.8)
			);
	
			foreach ($configs as $key => $config) :
				$config=array_merge($default_config,$config);
	
				if (!is_array($config['post_types'])) :
					$config['post_types']=explode(",",$config['post_types']);
				endif;			
				
				$config=$this->check_config_prefix($config); // makes sure our prefix starts with '_'
				
				$configs[$key]=$config;
			endforeach;
	
			return $configs;
		}

		/**
		 * makes sure our prefix starts with '_'
		 * @param array $config
		 * returns array $config
		**/
		function check_config_prefix($config) {
			if (substr($config['prefix'],0,1)!='_')
				$config['prefix']='_'.$config['prefix'];
		
			return $config;
		}
	
		/**
		 *
		 */
		function convert_metaboxes() {
/*
Array
(
    [0] => Array
        (
            [id] => solution_home_box
            [title] => Home Box Content
            [prefix] => _solution
            [post_types] => Array
                (
                    [0] => solutions
                )

            [duplicate] => 1
            [fields] => Array
                (
                    [home_icon] => Array
                        (
                            [type] => media
                            [label] => Icon
                        )

                    [home_text] => Array
                        (
                            [type] => textarea
                            [label] => Text
                        )

                    [circle_steps] => Array
                        (
                            [type] => checkbox
                            [label] => Steps Layout Circle
                        )

                    [break_circle] => Array
                        (
                            [type] => checkbox
                            [label] => Break Circle
                        )

                )

        )

    [1] => Array
        (
            [id] => add_news_link
            [title] => Add News Link
            [prefix] => _post
            [post_types] => Array
                (
                    [0] => post
                )

            [duplicate] => 1
            [fields] => Array
                (
                    [news_link] => Array
                        (
                            [type] => url
                            [label] => Link
                        )

                )

        )

*/
			foreach ($this->config as $metabox) :
				$fields_arr=array();
				
				foreach ($metabox['fields'] as $field_slug => $field) :
					$fields_arr[]=array(
						'field_type' => $field['type'],
						'field_label' => $field['label'],
						'options' => array(
							'default' => array(
								'name' => '',
								'value' => ''	
							)
						),
					);
				endforeach;
				
				$new_arr[]=array(
					'mb_id' => $metabox['id'],
					'title' => $metabox['title'],
					'prefix' => $metabox['prefix'],
					'post_types' => $metabox['post_types'],
					'fileds' => $fields_arr,
					'update-metabox' => 'Create'
				);
			endforeach;		
echo '<pre>';
print_r($new_arr);
echo '</pre>';
		}
/*
Array
(
    [mb_id] => supplier_details
    [title] => Supplier Details
    [prefix] => supplier
    [post_types] => Array
        (
            [0] => suppliers
        )

    [fields] => Array
        (
            [0] => Array
                (
                    [field_type] => url
                    [field_label] => URL
                    [options] => Array
                        (
                            [default] => Array
                                (
                                    [name] => 
                                    [value] => 
                                )

                        )

                )

            [1] => Array
                (
                    [field_type] => email
                    [field_label] => Email
                    [options] => Array
                        (
                            [default] => Array
                                (
                                    [name] => 
                                    [value] => 
                                )

                        )

                )

            [2] => Array
                (
                    [field_type] => textarea
                    [field_label] => Address
                    [options] => Array
                        (
                            [0] => Array
                                (
                                    [name] => Search Engine
                                    [value] => search-engine
                                )

                            [default] => Array
                                (
                                    [name] => 
                                    [value] => 
                                )

                        )

                )

            [3] => Array
                (
                    [field_type] => phone
                    [field_label] => Phone
                    [options] => Array
                        (
                            [default] => Array
                                (
                                    [name] => 
                                    [value] => 
                                )

                        )

                )

            [4] => Array
                (
                    [field_type] => media
                    [field_label] => Logo
                    [options] => Array
                        (
                            [default] => Array
                                (
                                    [name] => 
                                    [value] => 
                                )

                        )

                )

            [5] => Array
                (
                    [field_type] => colorpicker
                    [field_label] => Color
                    [options] => Array
                        (
                            [default] => Array
                                (
                                    [name] => 
                                    [value] => 
                                )

                        )

                )

            [6] => Array
                (
                    [field_type] => timepicker
                    [field_label] => Time
                    [options] => Array
                        (
                            [default] => Array
                                (
                                    [name] => 
                                    [value] => 
                                )

                        )

                )

            [7] => Array
                (
                    [field_type] => date
                    [field_label] => Date
                    [options] => Array
                        (
                            [default] => Array
                                (
                                    [name] => 
                                    [value] => 
                                )

                        )

                )

            [default] => Array
                (
                    [field_type] => 0
                    [field_label] => 
                    [options] => Array
                        (
                            [default] => Array
                                (
                                    [name] => 
                                    [value] => 
                                )

                        )

                )

        )

    [update-metabox] => Update
)
*/




/*
Array
(
    [0] => Array
        (
            [mb_id] => supplier_details
            [title] => Supplier Details
            [prefix] => supplier
            [post_types] => Array
                (
                    [0] => suppliers
                )

            [fields] => Array
                (
                    [0] => Array
                        (
                            [field_type] => url
                            [field_label] => URL
                            [options] => Array
                                (
                                )

                        )

                    [1] => Array
                        (
                            [field_type] => email
                            [field_label] => Email
                            [options] => Array
                                (
                                )

                        )

                    [2] => Array
                        (
                            [field_type] => textarea
                            [field_label] => Address
                            [options] => Array
                                (
                                    [0] => Array
                                        (
                                            [name] => Search Engine
                                            [value] => search-engine
                                        )

                                )

                        )

                    [3] => Array
                        (
                            [field_type] => phone
                            [field_label] => Phone
                            [options] => Array
                                (
                                )

                        )

                    [4] => Array
                        (
                            [field_type] => media
                            [field_label] => Logo
                            [options] => Array
                                (
                                )

                        )

                    [5] => Array
                        (
                            [field_type] => colorpicker
                            [field_label] => Color
                            [options] => Array
                                (
                                )

                        )

                    [6] => Array
                        (
                            [field_type] => timepicker
                            [field_label] => Time
                            [options] => Array
                                (
                                )

                        )

                    [7] => Array
                        (
                            [field_type] => date
                            [field_label] => Date
                            [options] => Array
                                (
                                )

                        )

                )

        )

)
*/			
		
	}
	
endif;
?>