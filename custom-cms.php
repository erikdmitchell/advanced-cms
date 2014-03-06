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

require_once(plugin_dir_path(__FILE__).'inc/MDW_CPT.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-custom-tax.php');
require_once(plugin_dir_path(__FILE__).'inc/admin-columns.php');
require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/custom-post-types.php");
require_once(WP_PLUGIN_DIR . "/" . basename(dirname(__FILE__)) . "/meta-boxes.php");