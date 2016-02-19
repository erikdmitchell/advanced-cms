<?php
/**
 * adminDefault class.
 */
class adminDefault {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action('init',array($this,'add_page'));
	}

	/**
	 * add_page function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_page() {
		mdw_cms_add_admin_page(array(
			'id' => 'main',
			'name' => 'Main',
			'function' => array($this,'admin_page'),
			'order' => 0,
			'options' => array(
				'disable_bootstrap' => 0
			)
		));
	}

	/**
	 * admin_page function.
	 *
	 * @access public
	 * @return void
	 */
	public function admin_page() {
		mdw_cms_load_admin_page('main');
	}

}

new adminDefault();
?>