<?php
/*
Plugin Name: MDW CMS
Description: Adds cusomtized functionality to the site to make WordPress super awesome.
Version: 1.0.9
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
 * to prevent over writes, we use a wp option now
 * this allows us to generate said option from config files from previous versions (leagcy support)
 * version 1.1.0 -- Not used due to previous setup - will be integrated later
 */
/*
$config_file=plugin_dir_path(__FILE__).'mdw-cms-config-sample.php';

if (file_exists(plugin_dir_path(__FILE__).'mdw-cms-config.php'))
	$config_file=plugin_dir_path(__FILE__).'mdw-cms-config.php';

if (get_option('mdw_cms_config_TEST')) :
	// option exists, do nothing ?
else :
	// no option, load default, or (for legacy), load $config_file
	$config_file_raw=htmlspecialchars(file_get_contents($config_file)); // get file as raw text
//echo $config_file.'<br>';	

echo '<pre>';
print_r($config_file_raw);
print_r(explode(';',$config_file_raw));
echo '</pre>';	
endif;
*/

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
