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

require_once(plugin_dir_path(__FILE__).'inc/mdw-custom-post-types.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-custom-tax.php');
require_once(plugin_dir_path(__FILE__).'inc/admin-columns.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-meta-boxes/mdwmb-plugin.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-meta-boxes/ajax-meta-boxes.php'); // may roll into mdwmd-plugin
require_once(plugin_dir_path(__FILE__).'inc/custom-widgets.php');
require_once(plugin_dir_path(__FILE__).'admin-page.php');
require_once(plugin_dir_path(__FILE__).'/classes/slider.php'); // our bootstrap slider
require_once(plugin_dir_path(__FILE__).'/classes/social-media.php'); // our social media page
require_once(plugin_dir_path(__FILE__).'/classes/inflector.php'); // our pluralizing/singular functions

/**
 * look for a config file in the plugins dir, this is utalized if it exists and is not over written by the plugin
 */
if (file_exists(plugin_dir_path(dirname(__FILE__)).'mdw-cms-config.php')) :
	require_once(plugin_dir_path(dirname(__FILE__)).'mdw-cms-config.php');
else :
	require_once(plugin_dir_path(__FILE__).'mdw-cms-config-sample.php');
endif;

/**
 * runs our update functions
 * updater json: http://www.millerdesignworks.com/mdw-wp-plugins/mdw-cms-one-metadata.json
 * udater zip url: http://www.millerdesignworks.com/mdw-wp-plugins/mdw-cms-one.zip
 */
require_once(plugin_dir_path(__FILE__).'/updater/plugin-update-checker.php');
if (class_exists('PucFactory')) :
	$MyUpdateChecker = PucFactory::buildUpdateChecker (
	    'http://www.millerdesignworks.com/mdw-wp-plugins/mdw-cms-one-metadata.json',
	    __FILE__,
	    'mdw-cms-one'
	);
endif;
?>
