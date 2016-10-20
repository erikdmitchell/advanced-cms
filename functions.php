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

function mdw_cms_dashicons_dropdown($name='', $selected='') {

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
		'dashicons-format-links',
		'dashicons-admin-post',
		'dashicons-format-standard',
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
		'dashicons-share1',
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
?>