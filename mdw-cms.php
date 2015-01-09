<?php
/*
Plugin Name: MDW CMS
Description: Adds cusomtized functionality to the site to make WordPress super awesome.
Version: 1.1.1
Author: MillerDesignworks
Author URI: http://www.millerdesignworks.com
License: GPL2
@erikdmitchell
*/

require_once(plugin_dir_path(__FILE__).'inc/mdw-custom-post-types.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-custom-tax.php');
require_once(plugin_dir_path(__FILE__).'inc/admin-columns.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-meta-boxes/mdwmb-plugin.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-meta-boxes/ajax-meta-boxes.php'); // may roll into mdwmd-plugin
require_once(plugin_dir_path(__FILE__).'inc/custom-widgets.php');
require_once(plugin_dir_path(__FILE__).'admin-page.php');

require_once(plugin_dir_path(__FILE__).'gui/admin-pages.php'); // our new visual gui
require_once(plugin_dir_path(__FILE__).'gui/custom-post-types.php');
require_once(plugin_dir_path(__FILE__).'gui/mdw-meta-boxes/mdwmb-plugin.php');

require_once(plugin_dir_path(__FILE__).'/classes/slider.php'); // our bootstrap slider
require_once(plugin_dir_path(__FILE__).'/classes/social-media.php'); // our social media page
require_once(plugin_dir_path(__FILE__).'/classes/inflector.php'); // our pluralizing/singular functions

/**
 * look for a config file in the plugins dir, this is utalized if it exists and is not over written by the plugin
 */
/**
 * to prevent over writes, we use a wp option now
 * version 1.1.0 -- Not used due to previous setup - will be integrated later
 */
if (get_option('mdw_cms_version')) :
	// do nothing //
else :
	if (file_exists(plugin_dir_path(dirname(__FILE__)).'mdw-cms-config.php')) :
		require_once(plugin_dir_path(dirname(__FILE__)).'mdw-cms-config.php');
	else :
		require_once(plugin_dir_path(__FILE__).'mdw-cms-config-sample.php');
	endif;
endif;



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