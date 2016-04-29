<?php
/**
 * mdw_cms_get_template function.
 *
 * @access public
 * @param bool $template_name (default: false)
 * @param mixed $attributes (default: null)
 * @return void
 */
function mdw_cms_get_template($template_name=false, $attributes=null) {
	if (!$attributes )
		$attributes = array();

	if (!$template_name)
		return false;

	do_action('mdw_cms_before_'.$template_name);

	if (file_exists(get_stylesheet_directory().'/ultimate-league-management/pages/'.$template_name.'.php')) :
		include(get_stylesheet_directory().'/ultimate-league-management/pages/'.$template_name.'.php');
	elseif (file_exists(get_template_directory().'/ultimate-league-management/pages/'.$template_name.'.php')) :
		include(get_template_directory().'/ultimate-league-management/pages/'.$template_name.'.php');
	else :
		include(MDW_CMS_PATH.'admin/pages/'.$template_name.'.php');
	endif;

	do_action('mdw_cms_after_'.$template_name);

	$html=ob_get_contents();

	if (ob_get_length()) ob_end_clean();

	return $html;
}

/**
 * mdw_cms_parse_args function.
 *
 * Similar to wp_parse_args() just a bit extended to work with multidimensional arrays :)
 * credit: http://mekshq.com/recursive-wp-parse-args-wordpress-function/
 *
 * @access public
 * @param mixed &$a
 * @param mixed $b
 * @return void
 */
function mdw_cms_parse_args( &$a, $b ) {
	$a = (array) $a;
	$b = (array) $b;
	$result = $b;
	foreach ( $a as $k => &$v ) {
		if ( is_array( $v ) && isset( $result[ $k ] ) ) {
			$result[ $k ] = meks_wp_parse_args( $v, $result[ $k ] );
		} else {
			$result[ $k ] = $v;
		}
	}

	return $result;
}
?>