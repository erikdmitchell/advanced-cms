<?php
function mdw_cms_get_template_part($template_name='') {
	if (empty($template_name))
		return false;

	ob_start();

	do_action('mdw_cms_get_template_part'.$template_name);

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
?>