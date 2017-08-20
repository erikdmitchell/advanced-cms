<?php	
class Pickle_CMS_Field_Text extends Pickle_CMS_Field {

	public function __construct() {			
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
		$this->options='';
		
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

		return $e;
	}
	
	public function create_options($field) {				
		$key = $field['order'];
		$field=$this->parse_defaults($field);
		?>
		
		<?php $this->create_options_field(array(
			'label' => 'Default Value',
			'type' => 'text',
			'name'	=>	'fields[' .$key.'][default_value]',
			'value'	=>	$field['default_value'],
		)); ?>

		<div class="field-row field_option_<?php echo $this->name; ?>">
			<label>Placeholder Text</label>
		
			<?php $this->create_options_field(array(
				'type'	=>	'text',
				'name'	=>	'fields[' .$key.'][placeholder]',
				'value'	=>	$field['placeholder'],
			)); ?>
		</div>

		<div class="field-row field_option_<?php echo $this->name; ?>">
			<label>Formatting</label>
		
			<?php $this->create_options_field(array(
				'type' => 'select',
				'name' => 'fields[' .$key.'][formatting]',
				'value'	=> $field['formatting'],
				'choices' => array(
					'none'	=>	__("No formatting", 'pickle-cms'),
					'html'	=>	__("Convert HTML into tags", 'pickle-cms')
				)
			)); ?>
		</div>
		
		<div class="field-row field_option_<?php echo $this->name; ?>">
			<label>Character Limit</label>
		
			<?php $this->create_options_field(array(
				'type'	=>	'number',
				'name'	=>	'fields[' .$key.'][maxlength]',
				'value'	=>	$field['maxlength'],
			)); ?>
		</div>

		<div class="field-row field_option_<?php echo $this->name; ?>">
			<label>Description</label>
		
			<?php $this->create_options_field(array(
				'type'	=>	'textarea',
				'name'	=>	'fields[' .$key.'][description]',
				'value'	=>	$field['description'],
			)); ?>
		</div>

		<div class="field-row field_option_<?php echo $this->name; ?>">
			<label for="">Classes</label>
			<?php $this->create_options_field(array(
				'type' => 'text',
				'name'	=>	'fields[' .$key.'][class]',
				'value'	=>	$field['class'],
			)); ?>
		</div>
		<?php
		
	}
	
	//
	function format_value($value, $post_id, $field) {
		$value = htmlspecialchars($value, ENT_QUOTES);
		
		return $value;
	}
	
}
?>