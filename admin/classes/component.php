<?php
class PickleCMS_Admin_Component {
	
	public $slug='';
	
	public $name='';
	
	public $items='';
	
	public function __construct() {
		add_filter('pickle_cms_admin_tabs', array($this, 'add_admin_tab'));
	}

	protected function get_option($name='', $default='') {
		$option=get_option($name, $default);
		
		if ($option=='')
			$option=$default;

		return $option;
	}
	
	public function add_admin_tab($tabs) {
		$tabs[$this->slug]=$this->name;
		
		return $tabs;
	}

}	
?>