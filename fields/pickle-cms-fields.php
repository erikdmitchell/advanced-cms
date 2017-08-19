<?php

final class Pickle_CMS_Fields {

	public $version = '0.1.0';

	public $product_factory = null;

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
	}

	private function define_constants() {
		$this->define('PCKLE_CMS_FIELDS_PATH', PICKLE_CMS_PATH.'fields/');
	}

	public function includes() {
		//include_once(PCKLE_CMS_FIELDS_PATH.'field.php');
		//include_once(PCKLE_CMS_FIELDS_PATH.'text.php');
		//include_once(PICKLE_CMS_PATH.'fields/datepicker/datepicker.php');	

		//$this->query = new WC_Query();
	}

	private function init_hooks() {
		add_action('init', array($this, 'init'), 0);
	}

	private function define($name, $value) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	public function init() {
		//$this->product_factory=new WC_Product_Factory(); // Product Factory to create new product instances.
	}

}

function pickle_cms_fields() {
	return Pickle_CMS_Fields();
}
// Global for backwards compatibility.
$GLOBALS['pickle_cms_fields'] = pickle_cms_fields();
?>