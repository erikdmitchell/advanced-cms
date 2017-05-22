<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

global $pickle_cms_fields;

$pickle_cms_fields=array();

class PickleCMSField {
	public $id;
	
	public $title;

	public $category;

	//public $defaults;

	public $options;

	function __construct() {
		$default_args=array(
			'id' => '',
			'title' => '',
			'category' => 'basic',
			'options' => array(),
		);
		$args=pickle_cms_parse_args($args, $default_args);

		$this->id=$args['id'];
		$this->title=$args['title'];
		$this->category=$args['category'];
		$this->options=$args['options'];	
				
		$this->add_filter('create_field_'.$this->id, array($this, 'create_field'), 10, 1);
		$this->add_filter('load_field_options_'.$this->id, array($this, 'create_options'), 10, 1);
		$this->add_action('create_options_field_'.$this->id, array($this, 'create_options_field'), 10, 1);		
		$this->add_action('create_field_options_'.$this->id, array($this, 'create_options'), 10, 1);	
	}
	
	function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
		if (is_callable($function_to_add)) {
			add_filter($tag, $function_to_add, $priority, $accepted_args);
		}
	}
	
	function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
		if (is_callable($function_to_add)) {
			add_action($tag, $function_to_add, $priority, $accepted_args);
		}
	}
	


/*
	function registered_fields($fields) {
		// defaults
		if (!$this->category)
			$this->category = __('Basic', '');

		// add to array
		$fields[$this->category][$this->name]=$this->label;
		
		// return array
		return $fields;
	}
*/
	
	function create_options_field($field) {		
		// vars
		$html='';
		
		$html.='<div class="input-wrap">';
		
			switch ($field['type']) :
				case 'select' :
					$html.='<select name="'.$field['name'].'">';
						foreach ($field['choices'] as $value => $display) :
							$html.='<option value="'.$value.'" '.selected($field['value'], $value, false).'>'.$display.'</option>';
						endforeach;
					$html.='</select>';
					break;
				case 'textarea' :
					$html.='<textarea name="'.$field['name'].'">'.$field['value'].'</textarea>';
					break;
				default:
					$html.='<input type="'.$field['type'].'" name="'.$field['name'].'" value="'.$field['value'].'" />';
			endswitch;
		
		$html.='</div>';		
		
		echo $html;
	}
	
	protected function parse_defaults($field) {	
		$field=wp_parse_args($field, (array) $this->defaults);
		
		return $field;
	}
		
	/*
	*  load_field_defaults
	*
	*  action called when rendering the head of an admin screen. Used primarily for passing PHP to JS
	*
	*  @type	filer
	*  @date	1/06/13
	*
	*  @param	$field	{array}
	*  @return	$field	{array}
	*/
	
/*
	function load_field_defaults( $field )
	{
		if( !empty($this->defaults) )
		{
			foreach( $this->defaults as $k => $v )
			{
				if( !isset($field[ $k ]) )
				{
					$field[ $k ] = $v;
				}
			}
		}
		
		return $field;
	}
*/
    public function _register() {
	    global $pickle_cms_fields;
	    
	    $pickle_cms_fields[$this->id]=$this;
    }	
}

/**
 * pickle_cms_fields_init function.
 * 
 * @access public
 * @return void
 */
function pickle_cms_fields_init() {
    pickle_cms_register_fields('PickleCMSField_Text');
 
    do_action('pickle_cms_fields_init');
}
add_action('init', 'pickle_cms_fields_init', 1);
?>