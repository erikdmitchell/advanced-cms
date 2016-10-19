<?php
function mdw_cms_template_loader($template) {
	global $post;

	$located=false;
echo $template;
	// check theme(s), then plugin //
	if (file_exists(get_stylesheet_directory().'/fantasy-cycling/'.$template.'.php')) :
		$located=get_stylesheet_directory().'/fantasy-cycling/'.$template.'.php';
	elseif (file_exists(get_template_directory().'/fantasy-cycling/'.$template.'.php')) :
		$located=get_template_directory().'/fantasy-cycling/'.$template.'.php';
	elseif (file_exists(FANTASY_CYCLING_PATH.'templates/'.$template.'.php')) :
		$located=FANTASY_CYCLING_PATH.'templates/'.$template.'.php';
	endif;

	// we found a template //
	if ($located)
		$template=$located;

	return $template;
}
add_filter('template_include', 'mdw_cms_template_loader');
?>