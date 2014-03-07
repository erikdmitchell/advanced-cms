<?php
/*
Plugin Name: MDW CMS
Description: Adds cusomtized functionality to the site to make WordPress super awesome.
Version: 1.0.1
Author: MillerDesignworks
Author URI: http://www.millerdesignworks.com
License: GPL2
@erikdmitchell
*/

require_once(plugin_dir_path(__FILE__).'inc/mdw-custom-post-types.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-custom-tax.php');
require_once(plugin_dir_path(__FILE__).'inc/admin-columns.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-meta-boxes/mdwmb-plugin.php');
require_once(plugin_dir_path(__FILE__).'inc/custom-widgets.php');
require_once(plugin_dir_path(__FILE__).'meta-boxes.php');

/**
	Setup our custom CMS here
**/
// add post types //
$mdw_custom_post_types->add_post_types('suppliers');

// add custom taxonomy //
$mdw_custom_taxonomies->add_taxonomy('supplier-type','suppliers','Supplier Type');

// admin columns //
$arr=array(
	'post_type' => 'suppliers',
	'columns' => array(
		array (
			'slug' => '_mdwmb_url',
			'label' => 'URL'
		),
		array(
			'slug' => '_mdwmb_address',
			'label' => 'Address'
		)
	),
);

$mdw_admin_columns=new MDW_Admin_Columns($arr);

// custom widgets //
$params=array(
	array(
		'id' => 'type',
		'title' => __('My Title'),
		'description' => __('This is a description of the widget.')
	),
	array(
		'id' => 'type2',
		'title' => __('My Title2'),
		'description' => __('This is a description of the widget 2.')
	)		
);

new MDW_Widget_Creator($params);

?>