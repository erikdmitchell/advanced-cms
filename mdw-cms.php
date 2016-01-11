<?php
/*
Plugin Name: MDW CMS
Description: Adds cusomtized functionality to the site to make WordPress super awesome.
Version: 2.2.0
Author: MillerDesignworks
Author URI: http://www.millerdesignworks.com
License: GPL2
@erikdmitchell
*/

require_once(plugin_dir_path(__FILE__).'inc/legacy.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-meta-boxes/mdwmb-plugin.php');

require_once(plugin_dir_path(__FILE__).'admin-pages.php'); // our new visual gui
require_once(plugin_dir_path(__FILE__).'functions.php'); // general stand alone functions

require_once(plugin_dir_path(__FILE__).'classes/admin-columns.php'); // custom admin columns class
require_once(plugin_dir_path(__FILE__).'classes/custom-taxonomy.php'); // calls custom taxonomies
require_once(plugin_dir_path(__FILE__).'classes/custom-post-types.php'); // calls custom post types
require_once(plugin_dir_path(__FILE__).'classes/inflector.php'); // our pluralizing/singular functions
require_once(plugin_dir_path(__FILE__).'classes/social-media.php'); // our social media page


require_once(plugin_dir_path(__FILE__).'widgets/init.php'); // our widgets

/**
 * runs our update functions
 * updater json: http://www.millerdesignworks.com/mdw-wp-plugins/mdw-cms-metadata.json
 * udater zip url: http://www.millerdesignworks.com/mdw-wp-plugins/mdw-cms.zip
 */
require_once(plugin_dir_path(__FILE__).'inc/updater/plugin-update-checker.php');
if (class_exists('PucFactory')) :
	$MyUpdateChecker = PucFactory::buildUpdateChecker (
	    'http://www.millerdesignworks.com/mdw-wp-plugins/mdw-cms-metadata.json',
	    __FILE__,
	    'mdw-cms'
	);
endif;
?>
