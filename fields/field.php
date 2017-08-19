<?php
class Pickle_CMS_Field {
	
	public $id;
	
	public $title;

	public $category;

	public $defaults;

	public $options;

	function __construct($args='') {
		$default_args=array(
			'id' => '',
			'title' => '',
			'category' => 'basic',
			'defaults' => array(),
			'options' => array(),
		);
		$args=pickle_cms_parse_args($args, $default_args);

		$this->id=$args['id'];
		$this->title=$args['title'];
		$this->category=$args['category'];
		$this->options=$args['options'];	
print_r($this);		
		add_action('wp_ajax_pickle_cms_add_meta_box_field', array($this, 'ajax_add_field'));
	}
	
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

/*
	protected function parse_defaults($field) {	
		$field=wp_parse_args($field, (array) $this->defaults);
		
		return $field;
	}
*/
	
	public function add_field($key=0, $field='') {
		global $pickle_cms_fields;	
		
		$default='text';
		$html='';
		$field=pickle_cms_parse_args($field, $pickle_cms_fields[$default]);

		$html.='<div class="sortable pickle-cms-fields-wrapper" id="fields-wrapper-'.$key.'">';
		
			$html.='<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
		
			$html.='<div class="field-row">';
				$html.='<label for="title">Title</label>';
		
				$html.='<input type="text" name="fields['.$key.'][title]" class="field_type name-item field-title" value="'.$field['title'].'" />';
			$html.='</div>';
		
			$html.='<div class="field-row">';
				$html.='<label for="field_type">Field Type</label>';
		
				$html.='<select class="field_type name-item field-type" name="fields['.$key.'][field_type]">';
					$html.='<option value=0>Select One</option>';
					foreach ($pickle_cms_fields as $id => $_field) :
						$html.='<option value="'.$_field->name.'" '.selected($field['field_type'], $_field->name).'>'.$_field->label.'</option>';
					endforeach;
				$html.='</select>';
			$html.='</div>';
		
			$html.='<div class="field-options">';
				$html.='this cannot be an action (field options)';
				//do_action('create_field_options_'.$field['field_type'], $field);
			$html.='</div>';
		
			$html.='<div class="field-row">';
				$html.='<label for="id">Field ID</label>';
		
				$html.='<div class="gen-field-id">';
					$html.='I would like this to be stored - Field ID';
					//$html.='<input type="text" readonly="readonly" class="field_type field-id" value="'.$pickle_metaboxes->generate_field_id($pickle_cms_metabox_args['prefix'], $field['title'], $field['id']).'" /> <span class="description">(use as meta key)</span>';
				$html.='</div>';
			$html.='</div>';
		
			$html.='<div class="remove">';
				$html.='<input type="button" name="remove-field" id="remove-field-btn" class="button button-primary remove-field" data-id="fields-wrapper-'.$key.'" value="Remove">';
			$html.='</div>';
		
			$html.='<input type="hidden" name="fields['.$field['order'].'][order]" class="order name-item" value="'.$field['order'].'" />';	
		
		$html.='</div>';

		return $html;		
	}	
	
	public function ajax_add_field() {
		echo $this->add_field();
		
		wp_die();
	}	
			
}

new Pickle_CMS_Field();
?>