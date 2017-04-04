<?php
/**
 * advanced_cms_get_admin_page function.
 * 
 * @access public
 * @param bool $template_name (default: false)
 * @param mixed $attributes (default: null)
 * @return void
 */
function advanced_cms_get_admin_page($template_name=false, $attributes=null) {
	if (!$attributes )
		$attributes = array();

	if (!$template_name)
		return false;

	include(ADVANCED_CMS_PATH.'admin/pages/'.$template_name.'.php');

	$html=ob_get_contents();

	if (ob_get_length()) ob_end_clean();

	return $html;
}

/**
 * advanced_cms_parse_args function.
 *
 * Similar to wp_parse_args() just a bit extended to work with multidimensional arrays :)
 * credit: http://mekshq.com/recursive-wp-parse-args-wordpress-function/
 *
 * @access public
 * @param mixed &$a
 * @param mixed $b
 * @return void
 */
function advanced_cms_parse_args( &$a, $b ) {
	$a = (array) $a;
	$b = (array) $b;
	$result = $b;
	foreach ( $a as $k => &$v ) {
		if ( is_array( $v ) && isset( $result[ $k ] ) ) {
			$result[ $k ] = advanced_cms_parse_args( $v, $result[ $k ] );
		} else {
			$result[ $k ] = $v;
		}
	}

	return $result;
}

/**
 * is_advanced_cms_admin_page function.
 *
 * @access public
 * @return void
 */
function is_advanced_cms_admin_page() {
	if (isset($_GET['page']) && $_GET['page']=='advanced-cms')
		return true;

	return false;
}

/**
 * get_advanced_cms_admin_tab function.
 *
 * @access public
 * @return void
 */
function get_advanced_cms_admin_tab() {
	if (isset($_GET['page']) && $_GET['page']=='advanced-cms' && isset($_GET['tab']))
		return $_GET['tab'];

	return false;
}
?>