<?php
class acmsField_Datepicker extends acmsField {

	function __construct() {	
		// vars
		$this->name = 'datepicker';
		$this->label = __('Datepicker', '');
		$this->defaults = array(
			'default_value'	=>	'',
			'formatting' 	=>	'html',
			'maxlength'		=>	'',
			'placeholder'	=>	'',
			'description' => '',
			'class' => '',
		);
		$this->options=0;
		
		// do not delete!
    	parent::__construct();
	}
	
	function create_field($field) {
		// vars
		$o=array( 'class', 'name', 'id', 'value', 'placeholder', 'maxlength');
		$e = '';
		
		// maxlength
		if ($field['maxlength'] !== "" )
			$o[] = 'maxlength';
		
		$e .= '<div class="input-wrap">';
		$e .= '<input type="text"';
		
		foreach( $o as $k ) {
			$e .= ' ' . $k . '="' . esc_attr( $field[ $k ] ) . '"';	
		}
		
		$e .= ' />';
		$e .= '</div>';
		
		/*<input type="text" class="advanced-cms-datepicker" name="<?php echo $atts['id']; ?>" id="<?php echo $atts['id']; ?>" value="<?php echo $value; ?>" />*/
		// return
		return $e;
	}
			
	function create_options( $field ) {
		// vars
		$key = $field['order'];	
		?>
		<div class="field-row field_option_<?php echo $this->name; ?>">
			<label for="">Default Value</label>
			<?php 
			do_action('create_options_field_text', array(
				'type' => 'text',
				'name'	=>	'fields[' .$key.'][default_value]',
				'value'	=>	$field['default_value'],
			));
			?>
		</div>

		<div class="field-row field_option_<?php echo $this->name; ?>">
			<label>Placeholder Text</label>
		
			<?php 
			do_action('create_options_field_text', array(
				'type'	=>	'text',
				'name'	=>	'fields[' .$key.'][placeholder]',
				'value'	=>	$field['placeholder'],
			));
			?>
		</div>

		<div class="field-row field_option_<?php echo $this->name; ?>">
			<label>Formatting</label>
		
			<?php 
			do_action('create_options_field_text', array(
				'type' => 'select',
				'name' => 'fields[' .$key.'][formatting]',
				'value'	=> $field['formatting'],
				'choices' => array(
					'none'	=>	__("No formatting",'acf'),
					'html'	=>	__("Convert HTML into tags",'acf')
				)
			));
			?>
		</div>

		<div class="field-row field_option_<?php echo $this->name; ?>">
			<label>Description</label>
		
			<?php 
			do_action('create_options_field_text', array(
				'type'	=>	'textarea',
				'name'	=>	'fields[' .$key.'][description]',
				'value'	=>	$field['description'],
			));
			?>
		</div>

		<div class="field-row field_option_<?php echo $this->name; ?>">
			<label for="">Classes</label>
			<?php 
			do_action('create_options_field_text', array(
				'type' => 'text',
				'name'	=>	'fields[' .$key.'][class]',
				'value'	=>	$field['class'],
			));
			?>
		</div>
		<?php
		
	}
	
}

function register_amcs_datepicker_field() {
	acms_register_field(new acmsField_Datepicker());	
}
add_action('acms_register_field', 'register_amcs_datepicker_field');
?>