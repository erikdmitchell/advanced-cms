<?php

/**
 * mdw_cms_get_template_part function.
 *
 * @access public
 * @param string $template_name (default: '')
 * @param string $atts (default: '')
 * @return void
 */
function mdw_cms_get_template_part($template_name='', $atts='') {
	if (empty($template_name))
		return false;

	ob_start();

	do_action('mdw_cms_get_template_part_'.$template_name);

	if (file_exists(get_stylesheet_directory().'/mdw-cms/'.$template_name.'.php')) :
		include(get_stylesheet_directory().'/mdw-cms/'.$template_name.'.php');
	elseif (file_exists(get_template_directory().'/mdw-cms/'.$template_name.'.php')) :
		include(get_template_directory().'/mdw-cms/'.$template_name.'.php');
	elseif (file_exists(MDW_CMS_PATH.'templates/'.$template_name.'.php')) :
		include(MDW_CMS_PATH.'templates/'.$template_name.'.php');
	endif;

	$html=ob_get_contents();

	ob_end_clean();

	return $html;
}

/**
 * mdw_cms_dashicon_grid function.
 *
 * @access public
 * @return void
 */
function mdw_cms_dashicon_grid() {
	$dashicons=mdw_cms_get_dashicons();
	$html=null;

	$html.='<div class="mdw-cms-dashicon-grid">';
		foreach ($dashicons as $dashicon) :
			$html.='<a href="#" class="dashicons '.$dashicon.'" data-icon="'.$dashicon.'"></a>';
		endforeach;
	$html.='</div>';

	echo $html;
}

/**
 * mdw_cms_get_dashicons function.
 *
 * @access public
 * @return void
 */
function mdw_cms_get_dashicons() {
	$dashicons=array(
		'dashicons-admin-site',
		'dashicons-dashboard',
		'dashicons-admin-media',
		'dashicons-admin-page',
		'dashicons-admin-comments',
		'dashicons-admin-appearance',
		'dashicons-admin-plugins',
		'dashicons-admin-users',
		'dashicons-admin-tools',
		'dashicons-admin-settings',
		'dashicons-admin-network',
		'dashicons-admin-generic',
		'dashicons-admin-home',
		'dashicons-admin-collapse',
		'dashicons-filter',
		'dashicons-admin-customizer',
		'dashicons-admin-multisite',
		'dashicons-admin-links',
		'dashicons-admin-post',
		'dashicons-format-image',
		'dashicons-format-gallery',
		'dashicons-format-audio',
		'dashicons-format-video',
		'dashicons-format-chat',
		'dashicons-format-status',
		'dashicons-format-aside',
		'dashicons-format-quote',
		'dashicons-welcome-edit-page',
		'dashicons-welcome-add-page',
		'dashicons-welcome-view-site',
		'dashicons-welcome-widgets-menus',
		'dashicons-welcome-comments',
		'dashicons-welcome-learn-more',
		'dashicons-image-crop',
		'dashicons-image-rotate',
		'dashicons-image-rotate-left',
		'dashicons-image-rotate-right',
		'dashicons-image-flip-vertical',
		'dashicons-image-flip-horizontal',
		'dashicons-image-filter',
		'dashicons-undo',
		'dashicons-redo',
		'dashicons-editor-bold',
		'dashicons-editor-italic',
		'dashicons-editor-ul',
		'dashicons-editor-ol',
		'dashicons-editor-quote',
		'dashicons-editor-alignleft',
		'dashicons-editor-aligncenter',
		'dashicons-editor-alignright',
		'dashicons-editor-insertmore',
		'dashicons-editor-spellcheck',
		'dashicons-editor-distractionfree:before,',
		'dashicons-editor-expand',
		'dashicons-editor-contract',
		'dashicons-editor-kitchensink',
		'dashicons-editor-underline',
		'dashicons-editor-justify',
		'dashicons-editor-textcolor',
		'dashicons-editor-paste-word',
		'dashicons-editor-paste-text',
		'dashicons-editor-removeformatting',
		'dashicons-editor-video',
		'dashicons-editor-customchar',
		'dashicons-editor-outdent',
		'dashicons-editor-indent',
		'dashicons-editor-help',
		'dashicons-editor-strikethrough',
		'dashicons-editor-unlink',
		'dashicons-editor-rtl',
		'dashicons-editor-break',
		'dashicons-editor-code',
		'dashicons-editor-paragraph',
		'dashicons-editor-table',
		'dashicons-align-left',
		'dashicons-align-right',
		'dashicons-align-center',
		'dashicons-align-none',
		'dashicons-lock',
		'dashicons-unlock',
		'dashicons-calendar',
		'dashicons-calendar-alt',
		'dashicons-visibility',
		'dashicons-hidden',
		'dashicons-post-status',
		'dashicons-edit',
		'dashicons-trash',
		'dashicons-sticky',
		'dashicons-external',
		'dashicons-arrow-up',
		'dashicons-arrow-down',
		'dashicons-arrow-left',
		'dashicons-arrow-right',
		'dashicons-arrow-up-alt',
		'dashicons-arrow-down-alt',
		'dashicons-arrow-left-alt',
		'dashicons-arrow-right-alt',
		'dashicons-arrow-up-alt2',
		'dashicons-arrow-down-alt2',
		'dashicons-arrow-left-alt2',
		'dashicons-arrow-right-alt2',
		'dashicons-leftright',
		'dashicons-sort',
		'dashicons-randomize',
		'dashicons-list-view',
		'dashicons-excerpt-view',
		'dashicons-grid-view',
		'dashicons-move',
		'dashicons-hammer',
		'dashicons-art',
		'dashicons-migrate',
		'dashicons-performance',
		'dashicons-universal-access',
		'dashicons-universal-access-alt',
		'dashicons-tickets',
		'dashicons-nametag',
		'dashicons-clipboard',
		'dashicons-heart',
		'dashicons-megaphone',
		'dashicons-schedule',
		'dashicons-wordpress',
		'dashicons-wordpress-alt',
		'dashicons-pressthis',
		'dashicons-update',
		'dashicons-screenoptions',
		'dashicons-cart',
		'dashicons-feedback',
		'dashicons-cloud',
		'dashicons-translation',
		'dashicons-tag',
		'dashicons-category',
		'dashicons-archive',
		'dashicons-tagcloud',
		'dashicons-text',
		'dashicons-media-archive',
		'dashicons-media-audio',
		'dashicons-media-code',
		'dashicons-media-default',
		'dashicons-media-document',
		'dashicons-media-interactive',
		'dashicons-media-spreadsheet',
		'dashicons-media-text',
		'dashicons-media-video',
		'dashicons-playlist-audio',
		'dashicons-playlist-video',
		'dashicons-controls-play',
		'dashicons-controls-pause',
		'dashicons-controls-forward',
		'dashicons-controls-skipforward',
		'dashicons-controls-back',
		'dashicons-controls-skipback',
		'dashicons-controls-repeat',
		'dashicons-controls-volumeon',
		'dashicons-controls-volumeoff',
		'dashicons-yes',
		'dashicons-no',
		'dashicons-no-alt',
		'dashicons-plus',
		'dashicons-plus-alt',
		'dashicons-plus-alt2',
		'dashicons-minus',
		'dashicons-dismiss',
		'dashicons-marker',
		'dashicons-star-filled',
		'dashicons-star-half',
		'dashicons-star-empty',
		'dashicons-flag',
		'dashicons-info',
		'dashicons-warning',
		'dashicons-share',
		'dashicons-share-alt',
		'dashicons-share-alt2',
		'dashicons-twitter',
		'dashicons-rss',
		'dashicons-email',
		'dashicons-email-alt',
		'dashicons-facebook',
		'dashicons-facebook-alt',
		'dashicons-networking',
		'dashicons-googleplus',
		'dashicons-location',
		'dashicons-location-alt',
		'dashicons-camera',
		'dashicons-images-alt',
		'dashicons-images-alt2',
		'dashicons-video-alt',
		'dashicons-video-alt2',
		'dashicons-video-alt3',
		'dashicons-vault',
		'dashicons-shield',
		'dashicons-shield-alt',
		'dashicons-sos',
		'dashicons-search',
		'dashicons-slides',
		'dashicons-analytics',
		'dashicons-chart-pie',
		'dashicons-chart-bar',
		'dashicons-chart-line',
		'dashicons-chart-area',
		'dashicons-groups',
		'dashicons-businessman',
		'dashicons-id',
		'dashicons-id-alt',
		'dashicons-products',
		'dashicons-awards',
		'dashicons-forms',
		'dashicons-testimonial',
		'dashicons-portfolio',
		'dashicons-book',
		'dashicons-book-alt',
		'dashicons-download',
		'dashicons-upload',
		'dashicons-backup',
		'dashicons-clock',
		'dashicons-lightbulb',
		'dashicons-microphone',
		'dashicons-desktop',
		'dashicons-laptop',
		'dashicons-tablet',
		'dashicons-smartphone',
		'dashicons-phone',
		'dashicons-smiley',
		'dashicons-index-card',
		'dashicons-carrot',
		'dashicons-building',
		'dashicons-store',
		'dashicons-album',
		'dashicons-palmtree',
		'dashicons-tickets-alt',
		'dashicons-money',
		'dashicons-thumbs-up',
		'dashicons-thumbs-down',
		'dashicons-layout',
		'dashicons-paperclip',
	);

	return $dashicons;
}

/**
 * mdw_cms_get_field_template function.
 *
 * @access public
 * @param string $template_name (default: '')
 * @param string $atts (default: '')
 * @param string $value (default: '')
 * @return void
 */
function mdw_cms_get_field_template($template_name='', $atts='', $value='') {
	if (empty($template_name))
		return false;

	ob_start();

	if (file_exists(get_stylesheet_directory().'/mdw-cms/metabox-fields/'.$template_name.'.php')) :
		include(get_stylesheet_directory().'/mdw-cms/metabox-fields/'.$template_name.'.php');
	elseif (file_exists(get_template_directory().'/mdw-cms/metabox-fields/'.$template_name.'.php')) :
		include(get_template_directory().'/mdw-cms/metabox-fields/'.$template_name.'.php');
	elseif (file_exists(MDW_CMS_PATH.'templates/metabox-fields/'.$template_name.'.php')) :
		include(MDW_CMS_PATH.'templates/metabox-fields/'.$template_name.'.php');
	endif;

	$html=ob_get_contents();

	ob_end_clean();

	return $html;
}

/**
 * mdw_cms_checked_checkbox function.
 *
 * @access public
 * @param mixed $checked
 * @param bool $current (default: true)
 * @param bool $echo (default: true)
 * @return void
 */
function mdw_cms_checked_checkbox($checked, $current=true, $echo=true) {
	$type='checked';

	if (is_serialized($checked))
		$checked=unserialize($checked);

	if (!is_array($checked))
		$checked=explode(',', $checked);

	if (in_array($current, $checked)) :
		$result=" $type='$type'";
	else :
		$result='';
	endif;

	if ($echo)
		echo $result;

	return $result;
}

/**
 * mdw_cms_admin_link function.
 *
 * @access public
 * @param string $args (default: '')
 * @return void
 */
function mdw_cms_admin_link($args='') {
	echo mdw_cms_get_admin_link($args);
}

/**
 * mdw_cms_get_admin_link function.
 *
 * @access public
 * @param string $args (default: '')
 * @return void
 */
function mdw_cms_get_admin_link($args='') {
	$default_args=array(
		'page' => 'mdw-cms',
	);
	$args=wp_parse_args($args, $default_args);

	$url=add_query_arg($args, admin_url('tools.php'));

	return $url;
}

/**
 * mdw_cms_setup_post_type_args function.
 *
 * @access public
 * @return void
 */
function mdw_cms_setup_post_type_args() {
	global $mdw_cms_admin;

	$default_args=array(
		'base_url' => admin_url('tools.php?page=mdw-cms&tab=post-types'),
		'btn_text' => 'Create',
		'name' => '',
		'label' => '',
		'singular_label' => '',
		'description' => '',
		'title' => 1,
		'thumbnail' => 1,
		'editor' => 1,
		'revisions' => 1,
		'hierarchical' => 0,
		'page_attributes' => 0,
		'excerpt' => 0,
		'id' => -1,
		'comments' => 0,
		'header' => 'Add New Custom Post Type',
		'icon' => 'dashicons-admin-post',
		'error_class' => '',
	);

	// edit custom post type //
	if (isset($_GET['slug']) && $_GET['slug']) :
		foreach ($mdw_cms_admin->options['post_types'] as $key => $post_type) :
			if ($post_type['name']==$_GET['slug']) :
				$args=$post_type;
				$args['header']='Edit Post Type';
				$args['btn_text']='Update';
				$args['id']=$key;
			endif;
		endforeach;
	endif;

	$args=mdw_cms_parse_args($args, $default_args);

	return $args;
}

/**
 * mdw_cms_setup_metabox_args function.
 *
 * @access public
 * @return void
 */
function mdw_cms_setup_metabox_args() {
	global $mdw_cms_admin;

	$default_args=array(
		'base_url' => admin_url('tools.php?page=mdw-cms&tab=metaboxes'),
		'btn_text' => 'Create',
		'mb_id' => '',
		'title' => '',
		'prefix' => '',
		'post_types' => '',
		'edit_class_v' => '',
		'fields' => '',
		'title' => '',
		'header' => 'Add New Metabox',
	);

	// edit //
	if (isset($_GET['id']) && $_GET['id']) :
		foreach ($mdw_cms_admin->options['metaboxes'] as $metabox) :
			if ($metabox['mb_id']==$_GET['id']) :
				$args=$metabox;
				$args['header']='Edit Metabox';
				$args['btn_text']='Update';
			endif;
		endforeach;
	endif;

	$args=mdw_cms_parse_args($args, $default_args);

	return $args;
}

/**
 * mdw_cms_metabox_post_types_list function.
 *
 * @access public
 * @param string $post_types (default: '')
 * @return void
 */
function mdw_cms_metabox_post_types_list($post_types='') {
	if (empty($post_types))
		return false;

	$post_types_list=implode(', ', $post_types);

	echo $post_types_list;
}
?>