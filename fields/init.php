<?php
define('PCKLE_CMS_FIELDS_PATH', PICKLE_CMS_PATH.'fields/');
	
require_once(PCKLE_CMS_FIELDS_PATH.'field.php');
//require_once(PCKLE_CMS_FIELDS_PATH.'text.php');
//require_once(PICKLE_CMS_PATH.'fields/datepicker/datepicker.php');	

function pickle_cms_setup_fields() {
	print_r($allClasses = get_declared_classes());
}
add_action('init', 'pickle_cms_setup_fields', 0);
?>