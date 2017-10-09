<?php
/*
A series of useful utility widgets to include content
from pages, posts, custom post types in widgetized
areas. flexible and useful.

All widgets included and called (initialized) here.
*/

/**
 * include widget files
 */
require_once(PICKLE_CMS_PATH.'widgets/advanced-content/advanced-content.php'); // content widget

// register our widgets //
function pickle_cms_widgets_init() {
	register_widget('advancedContentWidget');
}
add_action('widgets_init','pickle_cms_widgets_init');

/**
 * PICKLE_CMS_widgets_sripts_styles function.
 * 
 * @access public
 * @return void
 */
function pickle_cms_widgets_sripts_styles() {
	wp_enqueue_script('advanced-widgets-js', PICKLE_CMS_URL.'widgets/advanced-content/advanced-content.js');
	wp_enqueue_style('advanced-content-widget-css', PICKLE_CMS_URL.'widgets/advanced-content/advanced-content.css');
}
add_action('admin_enqueue_scripts', 'pickle_cms_widgets_sripts_styles');
?>