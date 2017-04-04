<?php
 
class acmsField {
	var $name,
		$title,
		$category,
		$defaults,
		$options;

	function __construct() {
		add_filter('acms_registered_fields', array($this, 'registered_fields'), 10, 1);
		
		$this->add_filter('create_field_'.$this->name, array($this, 'create_field'), 10, 1);
		$this->add_filter('load_field_options_'.$this->name, array($this, 'create_options'), 10, 1);
		$this->add_action('create_options_field_'.$this->name, array($this, 'create_options_field'), 10, 1);		
		$this->add_action('create_field_options_'.$this->name, array($this, 'create_options'), 10, 1);	
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
	
}

?>