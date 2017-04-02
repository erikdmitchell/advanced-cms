<?php
 
class acmsField {
	var $name,
		$title,
		$category,
		$defaults,
		$options;

// admin options
// metabox setup field
// metabox save field
// potential front end output

	function __construct() {
		$this->add_filter('create_field_'.$this->name, array($this, 'create_field'), 10, 1);
		$this->add_action('create_options_field_'.$this->name, array($this, 'create_options_field'), 10, 1);		
		$this->add_action('create_field_options_'.$this->name, array($this, 'create_options'), 10, 1);
/*
		// register field
		add_filter('acf/registered_fields', array($this, 'registered_fields'), 10, 1);
		add_filter('acf/load_field_defaults/type=' . $this->name, array($this, 'load_field_defaults'), 10, 1);
		
		// value
		$this->add_filter('acf/load_value/type=' . $this->name, array($this, 'load_value'), 10, 3);
		$this->add_filter('acf/update_value/type=' . $this->name, array($this, 'update_value'), 10, 3);
		$this->add_filter('acf/format_value/type=' . $this->name, array($this, 'format_value'), 10, 3);
		$this->add_filter('acf/format_value_for_api/type=' . $this->name, array($this, 'format_value_for_api'), 10, 3);
		
		
		// field
		$this->add_filter('acf/load_field/type=' . $this->name, array($this, 'load_field'), 10, 3);
		$this->add_filter('acf/update_field/type=' . $this->name, array($this, 'update_field'), 10, 2);
		$this->add_action('acf/create_field/type=' . $this->name, array($this, 'create_field'), 10, 1);
		$this->add_action('acf/create_field_options/type=' . $this->name, array($this, 'create_options'), 10, 1);
		
		
		// input actions
		$this->add_action('acf/input/admin_enqueue_scripts', array($this, 'input_admin_enqueue_scripts'), 10, 0);
		$this->add_action('acf/input/admin_head', array($this, 'input_admin_head'), 10, 0);
		$this->add_filter('acf/input/admin_l10n', array($this, 'input_admin_l10n'), 10, 1);
		
		
		// field group actions
		$this->add_action('acf/field_group/admin_enqueue_scripts', array($this, 'field_group_admin_enqueue_scripts'), 10, 0);
		$this->add_action('acf/field_group/admin_head', array($this, 'field_group_admin_head'), 10, 0);
*/
		
	}
	
	function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
		if( is_callable($function_to_add) )
		{
			add_filter($tag, $function_to_add, $priority, $accepted_args);
		}
	}
	
	function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
		if( is_callable($function_to_add) )
		{
			add_action($tag, $function_to_add, $priority, $accepted_args);
		}
	}

	
/*
	function registered_fields( $fields ) {
		// defaults
		if( !$this->category )
		{
			$this->category = __('Basic', 'acf');
		}
		
		
		// add to array
		$fields[ $this->category ][ $this->name ] = $this->label;
		
		
		// return array
		return $fields;
	}
*/
	
	
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