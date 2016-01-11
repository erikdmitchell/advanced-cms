<?php
/*
A series of useful utility widgets to include content
from pages, posts, custom post types in widgetized
areas. flexible and useful.

All widgets included and called (initialized) here.

@marshalloram/@erikdmitchell
*/

/**
 * include widget files
 */
require_once(plugin_dir_path(__FILE__).'content-widget/content-widget.php'); // content widget
require_once(plugin_dir_path(__FILE__).'social-media/social-media.php'); // social media widget

// register our widgets //
function mdw_cms_widgets_init() {
	register_widget('MDW_Content_Widget');
	register_widget( 'SocialMedia' );
}
add_action('widgets_init','mdw_cms_widgets_init');
?>