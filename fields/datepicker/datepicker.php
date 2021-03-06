<?php
class picklecmsField_Datepicker extends picklecmsField {

	function __construct() {	
		// vars
		$this->name = 'datepicker';
		$this->label = __('Datepicker', '');
		$this->defaults = array(
			'default_value'	=>	'',
			'formatting' 	=>	'yy-mm-dd',
			'placeholder'	=>	'',
			'description' => '',
		);
		$this->options=0;
		
		// do not delete!
    	parent::__construct();
    	
    	add_action('admin_enqueue_scripts', array($this, 'admin_scripts_styles'));
	}
	
	public function admin_scripts_styles() {
		wp_register_script('picklecms-datepicker-script', PICKLE_CMS_URL.'fields/datepicker/datepicker.js', array('jquery-ui-datepicker'), '0.1.0', true);
		
		wp_register_style('picklecms-datepicker-style', PICKLE_CMS_URL.'fields/datepicker/datepicker.css', '', '0.1.0');
	}
	
	function create_field($field) {
		// merge defaults here //
	
		if (!isset($field['formatting']))
			$field['formatting']=$this->defaults['formatting'];
		
		wp_localize_script('picklecms-datepicker-script', 'picklecmsDatepicker', array(
			'format' => $field['formatting']
		));
		
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('picklecms-datepicker-script');
		
		wp_enqueue_style('picklecms-datepicker-style');
		
		$opts=array('name', 'id', 'value', 'placeholder');
		$html='';
		
		$html.= '<div class="input-wrap">';
		$html.= '<input type="text" class="picklecms-datepicker"';
		
		foreach($opts as $key) :
			if (isset($field[$key])) :
				$value=esc_attr($field[$key]);
			else :
				$value=$this->defaults[$key];
			endif;
			
			$html.=' '.$key.'="'.$value.'"';	
		endforeach;
		
		$html.= ' />';
		$html.= '</div>';

		return $html;
	}
			
	function create_options( $field ) {
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
					'yy-mm-dd'	=>	__('yy-mm-dd')
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

function register_picklecms_datepicker_field() {
	picklecms_register_field(new picklecmsField_Datepicker());	
}
add_action('picklecms_register_field', 'register_picklecms_datepicker_field');
?>