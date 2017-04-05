<?php
class picklecmsField_Text extends picklecmsField {

	function __construct() {	
		// vars
		$this->name = 'text';
		$this->label = __('Text', '');
		$this->defaults = array(
			'default_value'	=>	'',
			'formatting' 	=>	'html',
			'maxlength'		=>	'',
			'placeholder'	=>	'',
			'description' => '',
			'class' => 'regular-text',
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
		
		
		// return
		return $e;
	}
	
	function create_options($field) {
		// vars
		$key = $field['order'];
		$field=$this->parse_defaults($field);			
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
			<label>Character Limit</label>
		
			<?php 
			do_action('create_options_field_text', array(
				'type'	=>	'number',
				'name'	=>	'fields[' .$key.'][maxlength]',
				'value'	=>	$field['maxlength'],
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
	
	//
	function format_value($value, $post_id, $field) {
		$value = htmlspecialchars($value, ENT_QUOTES);
		
		return $value;
	}
	
}

function register_picklecms_text_field() {
	picklecms_register_field(new picklecmsField_Text());	
}
add_action('picklecms_register_field', 'register_picklecms_text_field');
?>