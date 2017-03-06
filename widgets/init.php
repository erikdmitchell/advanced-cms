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
require_once(ADVANCED_CMS_PATH.'advanced-content.php'); // content widget

// register our widgets //
function advanced_cms_widgets_init() {
	register_widget('advanced_Content_Widget');
}
add_action('widgets_init','advanced_cms_widgets_init');
?>