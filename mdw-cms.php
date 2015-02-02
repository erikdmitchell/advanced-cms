<?php
/*
Plugin Name: MDW CMS
Description: Adds cusomtized functionality to the site to make WordPress super awesome.
Version: 1.1.2
Author: MillerDesignworks
Author URI: http://www.millerdesignworks.com
License: GPL2
@erikdmitchell
*/

require_once(plugin_dir_path(__FILE__).'inc/mdw-custom-tax.php');
require_once(plugin_dir_path(__FILE__).'inc/admin-columns.php');
require_once(plugin_dir_path(__FILE__).'inc/custom-widgets.php');
require_once(plugin_dir_path(__FILE__).'admin-page.php');

require_once(plugin_dir_path(__FILE__).'gui/admin-pages.php'); // our new visual gui
require_once(plugin_dir_path(__FILE__).'gui/custom-post-types.php');
require_once(plugin_dir_path(__FILE__).'gui/mdw-meta-boxes/mdwmb-plugin.php');
require_once(plugin_dir_path(__FILE__).'gui/legacy.php');
require_once(plugin_dir_path(__FILE__).'gui/upgrade.php');


require_once(plugin_dir_path(__FILE__).'/classes/slider.php'); // our bootstrap slider
require_once(plugin_dir_path(__FILE__).'/classes/social-media.php'); // our social media page
require_once(plugin_dir_path(__FILE__).'/classes/inflector.php'); // our pluralizing/singular functions


$admin_notices=array();

/**
 * look for a config file in the plugins dir, this is utalized if it exists and is not over written by the plugin
 */
/**
 * to prevent over writes, we use a wp option now
 * version 1.1.0 -- Not used due to previous setup - will be integrated later
 */
//add_action('wp_head','mdwcms_loaded');
//function mdwcms_loaded() {
	//global $admin_notices;

	if (get_option('mdw_cms_version')) :
		$admin_notices[]=array(
			'class' => 'updated',
			'message' => 'MDW CMS Version '.get_option('mdw_cms_version').' is in use.'
		);
	else :
		// we need to do a legacy update //
		if (file_exists(plugin_dir_path(dirname(__FILE__)).'mdw-cms-config.php')) :
			$admin_notices[]=array(
				'class' => 'error',
				'message' => 'MDW CMS is currently using a custom config file. Please update to the latest version of the plugin.'
			);
			require_once(plugin_dir_path(dirname(__FILE__)).'mdw-cms-config.php');
			MDWCMSlegacy::legacy_remove_old_config_file(plugin_dir_path(dirname(__FILE__)).'mdw-cms-config.php');
		else :
			// 	we dont need to do anything //
			$admin_notices[]=array(
				'class' => 'error',
				'message' => 'MDW CMS is currently using the default config file. Please update to the latest version of the plugin.'
			);
			require_once(plugin_dir_path(__FILE__).'mdw-cms-config-sample.php');
			MDWCMSlegacy::legacy_remove_old_config_file(plugin_dir_path(__FILE__).'mdw-cms-config-sample.php');
			// update_option('mdw_cms_version',$this->version);
		endif;
	endif;
//}

/**
 * displays our admin notices for the legacy support/migration of older content
*/
add_action('admin_notices','mdwcms_admin_notices');
function mdwcms_admin_notices() {
	global $admin_notices;

	$html=null;

	foreach ($admin_notices as $notice) :
		$html.='<div class="'.$notice['class'].'"><p>'.$notice['message'].'</p></div>';
	endforeach;

	echo $html;
}





/**
 * runs our update functions
 * updater json: http://www.millerdesignworks.com/mdw-wp-plugins/mdw-cms-metadata.json
 * udater zip url: http://www.millerdesignworks.com/mdw-wp-plugins/mdw-cms.zip
 */
require_once(plugin_dir_path(__FILE__).'/updater/plugin-update-checker.php');
if (class_exists('PucFactory')) :
	$MyUpdateChecker = PucFactory::buildUpdateChecker (
	    'http://www.millerdesignworks.com/mdw-wp-plugins/mdw-cms-metadata.json',
	    __FILE__,
	    'mdw-cms'
	);
endif;
?>
