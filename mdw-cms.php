<?php
/*
Plugin Name: MDW CMS
Description: Adds cusomtized functionality to the site to make WordPress super awesome.
Version: 2.1.9.1
Author: MillerDesignworks
Author URI: http://www.millerdesignworks.com
License: GPL2
@erikdmitchell
*/

define('MDW_CMS_PATH', plugin_dir_path(__FILE__));
define('MDW_CMS_URL', plugin_dir_url(__FILE__));

require_once(plugin_dir_path(__FILE__).'inc/admin-columns.php');
require_once(plugin_dir_path(__FILE__).'inc/custom-widgets.php');
require_once(plugin_dir_path(__FILE__).'inc/legacy.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-meta-boxes/countries-states.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-meta-boxes/mdwmb-plugin.php');

require_once(MDW_CMS_PATH.'admin/functions.php'); // admin functions
require_once(MDW_CMS_PATH.'admin.php'); // admin class
require_once(MDW_CMS_PATH.'admin/documentation/init.php'); // handles our documentation

require_once(plugin_dir_path(__FILE__).'classes/custom-taxonomy.php'); // calls custom taxonomies
require_once(plugin_dir_path(__FILE__).'classes/custom-post-types.php'); // calls custom post types
require_once(plugin_dir_path(__FILE__).'classes/social-media.php'); // our social media page
require_once(plugin_dir_path(__FILE__).'classes/inflector.php'); // our pluralizing/singular functions

require_once(plugin_dir_path(__FILE__).'widgets/init.php'); // our widgets

require_once(MDW_CMS_PATH.'shortcodes/init.php'); // our shortcodes
require_once(MDW_CMS_PATH.'functions.php'); // contains misc functions

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
