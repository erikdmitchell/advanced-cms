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
	
	public function create_field($field) {
		$field['name']=$field['id'];
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
		$output='';				
		$key=$field['key'];
		$field=$this->parse_defaults($field);

		$output.=$this->create_options_field(array(
			'label' => 'Default Value',
			'type' => 'text',
			'name'	=> 'fields[' .$key.'][default_value]',
			'value'	=> $field['default_value'],
		)); 

		$output.=$this->create_options_field(array(
			'label' => 'Placeholder Text',
			'type' => 'text',
			'name'	=> 'fields[' .$key.'][placeholder]',
			'value'	=> $field['placeholder'],
		));
		
		$output.=$this->create_options_field(array(
			'label' => 'Formatting',
			'type' => 'select',
			'name' => 'fields[' .$key.'][formatting]',
			'value'	=> $field['formatting'],
			'choices' => array(
				'none'	=>	__("No formatting", 'pickle-cms'),
				'html'	=>	__("Convert HTML into tags", 'pickle-cms')
			)
		)); 				
		
		$output.=$this->create_options_field(array(
			'label' => 'Character Limit',
			'type'	=>	'number',
			'name'	=>	'fields[' .$key.'][maxlength]',
			'value'	=>	$field['maxlength'],
		));
		
		$output.=$this->create_options_field(array(
			'label' => 'Description',
			'type'	=>	'textarea',
			'name'	=>	'fields[' .$key.'][description]',
			'value'	=>	$field['description'],
		));

		$output.=$this->create_options_field(array(
			'label' => 'Classes',
			'type' => 'text',
			'name'	=>	'fields[' .$key.'][class]',
			'value'	=>	$field['class'],
		));
		
		return $output;
	}
	
	function format_value($value, $post_id, $field) {
		$value = htmlspecialchars($value, ENT_QUOTES);
		
		return $value;
	}
	
}
?>