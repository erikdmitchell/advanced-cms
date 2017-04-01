<?php
 
class acmsField {
	var $name,
		$title,
		$category,
		$defaults,
		$repeatable,
		$options,
		$format;

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

	function load_field() {
		//load_field( $field, $field_key, $post_id = false )
/*
// load cache
		if( !$field )
		{
			$field = wp_cache_get( 'load_field/key=' . $field_key, 'acf' );
		}
		
		
		// load from DB
		if( !$field )
		{
			// vars
			global $wpdb;
			
			
			// get field from postmeta
			$sql = $wpdb->prepare("SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = %s", $field_key);
			
			if( $post_id )
			{
				$sql .= $wpdb->prepare("AND post_id = %d", $post_id);
			}
	
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			
			
			
			// nothing found?
			if( !empty($rows) )
			{
				$row = $rows[0];
				
				
				// return field if it is not in a trashed field group
				if( get_post_status( $row['post_id'] ) != "trash" )
				{
					$field = $row['meta_value'];
					$field = maybe_unserialize( $field );
					$field = maybe_unserialize( $field ); // run again for WPML
					
					
					// add field_group ID
					$field['field_group'] = $row['post_id'];
				}
				
			}
		}
		
		
		// apply filters
		$field = apply_filters('acf/load_field_defaults', $field);
		
		
		// apply filters
		foreach( array('type', 'name', 'key') as $key )
		{
			// run filters
			$field = apply_filters('acf/load_field/' . $key . '=' . $field[ $key ], $field); // new filter
		}
		
	
		// set cache
		wp_cache_set( 'load_field/key=' . $field_key, $field, 'acf' );
		
		return $field;
	
*/		
	}
	
	function update_value() {
	//update_value( $value, $post_id, $field )	
	/*
				// strip slashes
		// - not needed? http://support.advancedcustomfields.com/discussion/3168/backslashes-stripped-in-wysiwyg-filed
		//if( get_magic_quotes_gpc() )
		//{
			$value = stripslashes_deep($value);
		//}
		
		
		// apply filters		
		foreach( array('key', 'name', 'type') as $key )
		{
			// run filters
			$value = apply_filters('acf/update_value/' . $key . '=' . $field[ $key ], $value, $post_id, $field); // new filter
		}
		
		
		// if $post_id is a string, then it is used in the everything fields and can be found in the options table
		if( is_numeric($post_id) )
		{
			// allow ACF to save to revision!
			update_metadata('post', $post_id, $field['name'], $value );
			update_metadata('post', $post_id, '_' . $field['name'], $field['key']);
		}
		elseif( strpos($post_id, 'user_') !== false )
		{
			$user_id = str_replace('user_', '', $post_id);
			update_metadata('user', $user_id, $field['name'], $value);
			update_metadata('user', $user_id, '_' . $field['name'], $field['key']);
		}
		else
		{
			// for some reason, update_option does not use stripslashes_deep.
			// update_metadata -> http://core.trac.wordpress.org/browser/tags/3.4.2/wp-includes/meta.php#L82: line 101 (does use stripslashes_deep)
			// update_option -> http://core.trac.wordpress.org/browser/tags/3.5.1/wp-includes/option.php#L0: line 215 (does not use stripslashes_deep)
			$value = stripslashes_deep($value);
			
			$this->update_option( $post_id . '_' . $field['name'], $value );
			$this->update_option( '_' . $post_id . '_' . $field['name'], $field['key'] );
		}
		
		
		// update the cache
		wp_cache_set( 'load_value/post_id=' . $post_id . '/name=' . $field['name'], $value, 'acf' );
		*/
	
	}
	/*
			function load_field_defaults( $field )
	{
		// validate $field
		if( !is_array($field) )
		{
			$field = array();
		}
		
		
		// defaults
		$defaults = array(
			'key' => '',
			'label' => '',
			'name' => '',
			'_name' => '',
			'type' => 'text',
			'order_no' => 1,
			'instructions' => '',
			'required' => 0,
			'id' => '',
			'class' => '',
			'conditional_logic' => array(
				'status' => 0,
				'allorany' => 'all',
				'rules' => 0
			),
		);
		$field = array_merge($defaults, $field);
		
		
		// Parse Values
		$field = apply_filters( 'acf/parse_types', $field );
		
		
		// field specific defaults
		$field = apply_filters('acf/load_field_defaults/type=' . $field['type'] , $field);
				
		
		// class
		if( !$field['class'] )
		{
			$field['class'] = $field['type'];
		}
		
		
		// id
		if( !$field['id'] )
		{
			$id = $field['name'];
			$id = str_replace('][', '_', $id);
			$id = str_replace('fields[', '', $id);
			$id = str_replace('[', '-', $id); // location rules (select) does'nt have "fields[" in it
			$id = str_replace(']', '', $id);
			
			$field['id'] = 'acf-field-' . $id;
		}
		
		
		// _name
		if( !$field['_name'] )
		{
			$field['_name'] = $field['name'];
		}
		
		
		// clean up conditional logic keys
		if( !empty($field['conditional_logic']['rules']) )
		{
			$field['conditional_logic']['rules'] = array_values($field['conditional_logic']['rules']);
		}
		
		
		// return
		return $field;
	}
	*/
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