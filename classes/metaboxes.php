<?php
	
class PickleCMS_Metaboxes {

	public $config;

	function __construct() {

	}

	public function register_admin_scripts_styles($hook) {
		
	}

	protected function check_config_prefix($prefix=false) {
		if (!$prefix)
			return false;

		if (substr($prefix, 0, 1)!='_')
			$prefix='_'.$prefix;

		return $prefix;
	}





	public function generate_field_id($prefix=false, $label=false, $field_id=false) {
		$id=null;

		if (!$prefix || !$label)
			return false;

		$prefix=$this->check_config_prefix($prefix);

		if (empty($label)) :
			$id=$prefix.'_'.$field_id;
		else :
			$id=$prefix.'_'.strtolower($this->clean_special_chars($label));
		endif;

		return $id;
	}





/*
	protected function registered_fields() {
		$registered_fields=array();
		
		foreach ($this->config as $mb) :
			foreach ($mb['fields'] as $field) :
				$registered_fields[]=$field['id'];
			endforeach;
		endforeach;
		
		return $registered_fields;
	}
*/

	public function clean_special_chars($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

		return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}


	
}
?>