<?php

final class Pickle_CMS_Fields {

	public $version = '0.1.0';
	
	protected static $_instance=null;
	
	public $fields=array();
	
	public $field=array();
	
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}	

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	private function define_constants() {
		$this->define('PCKLE_CMS_FIELDS_PATH', PICKLE_CMS_PATH.'fields/');
	}

	public function includes() {
		include_once(PCKLE_CMS_FIELDS_PATH.'field.php');
		include_once(PCKLE_CMS_FIELDS_PATH.'text.php');
		//include_once(PICKLE_CMS_PATH.'fields/datepicker/datepicker.php');
		
		$this->field=new Pickle_CMS_Field();	
	}

	private function init_hooks() {
		add_action('init', array($this, 'register_fields'));
		
		add_action('wp_ajax_metabox_change_field_type', array($this, 'change_field_type'));
	}

	private function define($name, $value) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	public function register_fields() {
		$field_classes=array();
		
		foreach (get_declared_classes() as $class) :
			if (is_subclass_of($class, 'Pickle_CMS_Field'))
				$field_classes[]=$class;
		endforeach;
		
		foreach ($field_classes as $field_class) :
			$fc=new $field_class();
			
			$this->fields[$fc->name]=$fc;
		endforeach;
	}
	
	public function change_field_type() {		
		$field=$this->fields[$_POST['field']];

		echo $field->create_options(array(
			'key' => $_POST['key']
		));

		wp_die();
	}
	
	public function is_field_type($field_type='') {
		if (array_key_exists($field_type, $this->fields))
			return true;
			
		return false;
	}

}

function pickle_cms_fields() {
	return Pickle_CMS_Fields::instance();
}

// Global for backwards compatibility.
$GLOBALS['pickle_cms_fields'] = pickle_cms_fields();
?>