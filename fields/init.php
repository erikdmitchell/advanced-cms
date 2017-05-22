<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

global $pickle_cms_fields_init;

class PickleCMSFieldsInit {

	public $fields=array();

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param string $args (default: '')
	 * @return void
	 */
	public function __construct($args='') {
		add_action('pickle_cms_fields_init', array($this, '_register_fields'), 100);
	}

    /**
     * register function.
     * 
     * @access public
     * @param mixed $field
     * @return void
     */
    public function register($field) {
		$this->fields[$field]=new $field();
	}
	
	/**
	 * unregister function.
	 * 
	 * @access public
	 * @param mixed $field
	 * @return void
	 */
	public function unregister($field) {
		unset($this->fields[$field]);
	}
	
	/**
	 * _register_fields function.
	 * 
	 * @access public
	 * @return void
	 */
	public function _register_fields() {
		global $pickle_cms_fields;
		
		$keys=array_keys($this->fields);
		$registered=array_keys($pickle_cms_fields);

		foreach ($keys as $key) :
			if (in_array($this->fields[$key]->id, $registered, true)) :
				unset($this->fields[$key]);
				continue;
			endif;

			$this->fields[$key]->_register();
		endforeach;		
	}
	  
}

$pickle_cms_fields_init=new PickleCMSFieldsInit();

/**
 * pickle_cms_register_fields function.
 * 
 * @access public
 * @param mixed $field
 * @return void
 */
function pickle_cms_register_fields($field) {
    global $pickle_cms_fields_init;

    $pickle_cms_fields_init->register($field);
}
?>