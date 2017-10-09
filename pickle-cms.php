<?php
/*
Plugin Name: Pickle CMS
Plugin URI:
Description: Adds customized functionality to the site to make WordPress super awesome.
Version: 0.1.0
Author: Erik Mitchell
Author URI: http://erikmitchell.net
Text Domain: picklecms
Domain PAth: /languages
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

final class PickleCMS {

	public $version='0.1.0';

	protected static $_instance=null;
	
	public $metaboxes=null;
	
	public $post_types=null;
	
	public $taxonomies=null;
	
	public $admin_columns=null;

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
		$this->define('PICKLE_CMS_PATH', plugin_dir_path(__FILE__));
		$this->define('PICKLE_CMS_URL', plugin_dir_url(__FILE__));
		$this->define('PICKLE_CMS_ADMIN_PATH', plugin_dir_path(__FILE__).'admin/');
		$this->define('PICKLE_CMS_ADMIN_URL', plugin_dir_url(__FILE__).'admin/');
		$this->define('PICKLE_CMS_VERSION', $this->version);
	}

	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	public function includes() {
		/**
		 * admin
		 */
		include_once(PICKLE_CMS_PATH.'admin/functions.php'); // admin functions
		include_once(PICKLE_CMS_PATH.'admin/admin.php'); // admin class
		 
		/**
		 * general
		 */
		include_once(PICKLE_CMS_PATH.'functions.php'); // contains misc functions
		
		/**
		 * classes
		 */
		include_once(PICKLE_CMS_PATH.'classes/metaboxes.php');
		include_once(PICKLE_CMS_PATH.'classes/post-types.php');
		include_once(PICKLE_CMS_PATH.'fields/pickle-cms-fields.php'); // metabox fields
		 
		/**
		 * libraries
		 */
		include_once(PICKLE_CMS_PATH.'lib/countries-states.php'); // contains global vars/arrays for states and countries
		
		// setup metaboxes
		// setup post types
		$this->post_types=new PickleCMS_Post_Types();
		// setup taxonomies
		// setup admin columns?
	}

	private function init_hooks() {

	}

}

function picklecms() {
	return PickleCMS::instance();
}

// Global for backwards compatibility.
$GLOBALS['picklecms'] = picklecms();
?>