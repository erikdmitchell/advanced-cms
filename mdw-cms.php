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

//require_once(plugin_dir_path(__FILE__).'inc/mdw-custom-post-types.php');
require_once(plugin_dir_path(__FILE__).'inc/mdw-custom-tax.php');
require_once(plugin_dir_path(__FILE__).'inc/admin-columns.php');
//require_once(plugin_dir_path(__FILE__).'inc/mdw-meta-boxes/mdwmb-plugin.php');
//require_once(plugin_dir_path(__FILE__).'inc/mdw-meta-boxes/ajax-meta-boxes.php'); // may roll into mdwmd-plugin
require_once(plugin_dir_path(__FILE__).'inc/custom-widgets.php');
require_once(plugin_dir_path(__FILE__).'admin-page.php');

require_once(plugin_dir_path(__FILE__).'gui/admin-pages.php'); // our new visual gui
require_once(plugin_dir_path(__FILE__).'gui/custom-post-types.php');
require_once(plugin_dir_path(__FILE__).'gui/mdw-meta-boxes/mdwmb-plugin.php');

require_once(plugin_dir_path(__FILE__).'/classes/slider.php'); // our bootstrap slider
require_once(plugin_dir_path(__FILE__).'/classes/social-media.php'); // our social media page
require_once(plugin_dir_path(__FILE__).'/classes/inflector.php'); // our pluralizing/singular functions


class mdwcms {
	
	public $version='1.1.1';
	
	protected $admin_notices=array();
	
	function __construct() {
		add_action('plugins_loaded',array($this,'mdwcms_loaded'));
		add_action('admin_notices',array($this,'mdwcms_admin_notices'));
		
		update_option('mdw_cms_version',$this->version);
	}

	/**
	 * look for a config file in the plugins dir, this is utalized if it exists and is not over written by the plugin
	 */
	/**
	 * to prevent over writes, we use a wp option now
	 * version 1.1.0 -- Not used due to previous setup - will be integrated later
	 */
	function mdwcms_loaded() {
		if (get_option('mdw_cms_version')) :
			/*
			$this->admin_notices[]=array(
				'class' => 'updated',
				'message' => 'MDW CMS Version '.get_option('mdw_cms_version').' is in use.'
			);
			*/
		else :
			// we need to do a legacy update //
			if (file_exists(plugin_dir_path(dirname(__FILE__)).'mdw-cms-config.php')) :
				$this->admin_notices[]=array(
					'class' => 'error',
					'message' => 'MDW CMS is currently using a custom config file. Please update to the latest version of the plugin.'
				);
				require_once(plugin_dir_path(dirname(__FILE__)).'mdw-cms-config.php');
			else :
				// 	we dont need to do anything //
				$this->admin_notices[]=array(
					'class' => 'error',
					'message' => 'MDW CMS is currently using the default config file. Please update to the latest version of the plugin.'
				);			
				require_once(plugin_dir_path(__FILE__).'mdw-cms-config-sample.php');
			endif;
		endif;
	}

	/**
	 * displays our admin notices for the legacy support/migration of older content
	 */
	function mdwcms_admin_notices() {
		$html=null;
		
		foreach ($this->admin_notices as $notice) :
			$html.='<div class="'.$notice['class'].'"><p>'.$notice['message'].'</p></div>';
		endforeach;
		
		echo $html;
	}
	
}
$mdwcms=new mdwcms();






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