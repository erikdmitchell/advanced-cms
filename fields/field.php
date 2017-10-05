<?php
class Pickle_CMS_Field {
	
	public $id;
	
	public $name;

	public $label;

	public $defaults=array();

	public $options=array();

	public function __construct() {	
		add_action('wp_ajax_pickle_cms_add_meta_box_field', array($this, 'ajax_add_field'));
	}

	protected function create_options_field($args) {
		$html='';

		$html.='<div class="field-row field_option_'.$this->name.'">';
			$html.='<label>'.$args['label'].'</label>';

			$html.='<div class="input-wrap">';
			
				switch ($args['type']) :
					case 'select' :
						$html.='<select name="'.$args['name'].'">';
							foreach ($args['choices'] as $value => $display) :
								$html.='<option value="'.$value.'" '.selected($args['value'], $value, false).'>'.$display.'</option>';
							endforeach;
						$html.='</select>';
						break;
					case 'textarea' :
						$html.='<textarea name="'.$args['name'].'">'.$args['value'].'</textarea>';
						break;
					default:
						$html.='<input type="'.$args['type'].'" name="'.$args['name'].'" value="'.$args['value'].'" />';
				endswitch;
			
			$html.='</div>';
		
		$html.='</div>';		
		
		echo $html;
	}
	
	public function add_field($key=0, $field='') {
		$default='text';
		$html='';
		$field=pickle_cms_parse_args($field, pickle_cms_fields()->fields[$default]);

		$html.='<div class="sortable pickle-cms-fields-wrapper" id="fields-wrapper-'.$key.'" data-key="'.$key.'">';
		
			$html.='<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
		
			$html.='<div class="field-row">';
				$html.='<label for="title">Title</label>';
		
				$html.='<input type="text" name="fields['.$key.'][title]" class="field-type field-title" value="'.$field['title'].'" />';
			$html.='</div>';
		
			$html.='<div class="field-row">';
				$html.='<label for="field-type">Field Type</label>';
		
				$html.='<select class="field-type type" name="fields['.$key.'][field_type]">';
					$html.='<option value=0>Select One</option>';
					foreach (pickle_cms_fields()->fields as $id => $pickle_cms_field) :
						$html.='<option value="'.$pickle_cms_field->name.'" '.selected($field['field-type'], $pickle_cms_field->name).'>'.$pickle_cms_field->label.'</option>';
					endforeach;
				$html.='</select>';
			$html.='</div>';
		
			$html.='<div class="field-options"></div>'; // populated via ajax
		
			$html.='<div class="field-row">';
				$html.='<label for="id">Field ID</label>';
		
				$html.='<div class="gen-field-id">';
					$html.='<input type="text" readonly="readonly" class="field-type field-id" value="" /> <span class="description">(use as meta key)</span>';
				$html.='</div>';
			$html.='</div>';
		
			$html.='<div class="remove">';
				$html.='<input type="button" name="remove-field" id="remove-field-btn" class="button button-primary remove-field" data-id="fields-wrapper-'.$key.'" value="Remove">';
			$html.='</div>';
		
			$html.='<input type="hidden" name="fields['.$field['order'].'][order]" class="order name-item" value="'.$field['order'].'" />';	
		
		$html.='</div>';

		return $html;		
	}	

	protected function parse_defaults($field) {	
		$field=pickle_cms_parse_args($field, (array) $this->defaults);
		
		return $field;
	}
	
	public function create_options($field) {}
	
	public function ajax_add_field() {	
		echo $this->add_field();
		
		wp_die();
	}	
			
}

new Pickle_CMS_Field();
?>