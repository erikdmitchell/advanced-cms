<?php
/*
Plugin Name: Advanced CMS
Description: Adds customized functionality to the site to make WordPress super awesome.
Version: 0.1.0
Author: Erik Mitchell
Author URI: http://erikmitchell.net
License: GPL2
*/

define('ADVANCED_CMS_PATH', plugin_dir_path(__FILE__));
define('ADVANCED_CMS_URL', plugin_dir_url(__FILE__));
define('ADVANCED_CMS_ADMIN_PATH', plugin_dir_path(__FILE__).'admin/');
define('ADVANCED_CMS_ADMIN_URL', plugin_dir_url(__FILE__).'admin/');
define('ADVANCED_CMS_VERSION', '0.1.0');

require_once(ADVANCED_CMS_PATH.'init.php'); // basic init
require_once(ADVANCED_CMS_PATH.'admin/functions.php'); // admin functions
require_once(ADVANCED_CMS_PATH.'admin.php'); // admin class
require_once(ADVANCED_CMS_PATH.'admin/documentation/init.php'); // handles our documentation
require_once(ADVANCED_CMS_PATH.'classes/admin-columns.php'); // allows for custom admin columns
require_once(ADVANCED_CMS_PATH.'classes/taxonomies.php'); // calls custom taxonomies
require_once(ADVANCED_CMS_PATH.'classes/post-types.php'); // calls custom post types
require_once(ADVANCED_CMS_PATH.'classes/metaboxes.php'); // our custom metabox class
require_once(ADVANCED_CMS_PATH.'functions.php'); // contains misc functions
require_once(ADVANCED_CMS_PATH.'lib/countries-states.php'); // contains global vars/arrays for states and countries
require_once(ADVANCED_CMS_PATH.'shortcodes/init.php'); // our shortcodes
require_once(ADVANCED_CMS_PATH.'widgets/init.php'); // our widgets

// metabox fields //
require_once(ADVANCED_CMS_PATH.'fields/_field.php');
require_once(ADVANCED_CMS_PATH.'fields/text.php');
?>
