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

	if (file_exists(get_stylesheet_directory().'/mdw-cms/admin-pages/'.$template_name.'.php')) :
		include(get_stylesheet_directory().'/mdw-cms/admin-pages/'.$template_name.'.php');
	elseif (file_exists(get_template_directory().'/mdw-cms/admin-pages/'.$template_name.'.php')) :
		include(get_template_directory().'/mdw-cms/admin-pages/'.$template_name.'.php');
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
			$result[ $k ] = mdw_cms_parse_args( $v, $result[ $k ] );
		} else {
			$result[ $k ] = $v;
		}
	}

	return $result;
}

/**
 * is_mdw_cms_admin_page function.
 *
 * @access public
 * @return void
 */
function is_mdw_cms_admin_page() {
	if (isset($_GET['page']) && $_GET['page']=='mdw-cms')
		return true;

	return false;
}

/**
 * get_mdw_cms_admin_tab function.
 *
 * @access public
 * @return void
 */
function get_mdw_cms_admin_tab() {
	if (isset($_GET['page']) && $_GET['page']=='mdw-cms' && isset($_GET['tab']))
		return $_GET['tab'];

	return false;
}

/**
 * mdw_cms_admin_metabox_fields function.
 *
 * @access public
 * @param string $fields (default: '')
 * @return void
 */
function mdw_cms_admin_metabox_fields($fields='') {
	global $mdw_cms_admin;

	$field_counter=0;
	$field_id=0;
	$field='';

	if (empty($fields)) :
		$mdw_cms_admin->build_field_rows($field_id, $field, $field_counter);
	else :
		foreach ($fields as $field_id => $field) :
			if (isset($field['field_id'])) :
				$field_id=$field['field_id'];
			else :
				$field_id=0;
			endif;

			$mdw_cms_admin->build_field_rows($field_id, $field, $field_counter);

			$field_counter++;
		endforeach;
	endif;
}

/**
 * mdw_cms_setup_metabox_row function.
 *
 * @access public
 * @param string $args (default: '')
 * @return void
 */
function mdw_cms_setup_metabox_row($args='') {
	$default_args=array(
		'field_id' => 0,
		'order' => 0,
		'classes' => '',
		'repeatable' => '',
		'field_description' => '',
		'field_type' => '',
		'field_label' => '',
		'repeatable_checked' => '',
		'clean_format' => '',
	);
	$args=wp_parse_args($args, $default_args);

	// is field repeatable? //
	if (isset($args['repeatable']) && $args['repeatable'])
		$args['repeatable_checked']='checked="checked"';

	// setup field format if found //
	if (isset($args['format']['value']))
		$args['clean_format']=$args['format']['value'];

	return $args;
}

function mdw_cms_options_rows($options='', $field_key=0) {
	echo 	mdw_cms_get_options_rows($options, $field_key);
}

function mdw_cms_get_options_rows($options='', $field_key=0) {
	$output=null;
//print_r($options);
	if (!empty($options)) :
		foreach ($options as $key => $option) :
			$output.=mdw_cms_generate_option_row(array(
				'row_id' => $key,
				'field_key' => $field_key,
				'name' => $option['name'],
				'value' => $option['value'],
			));
		endforeach;
	else :
		$output.=mdw_cms_generate_option_row(array(
			'field_key' => $field_key,
		));
	endif;

	return $output;
}

function mdw_cms_generate_option_row($args='') {
	$html=null;
	$default_args=array(
		'row_id' => 0,
		'field_key' => 0,
		'name' => '',
		'value' => '',
	);
	$args=wp_parse_args($args, $default_args);
//print_r($args);
	extract($args);

	$html.='<div class="option-row" id="option-row-'.$row_id.'">';
		$html.='<label for="options-default-name">Name</label>';
		$html.='<input type="text" name="fields['.$field_key.'][options]['.$row_id.'][name]" class="options-item name" value="'.$name.'" />';
		$html.='<label for="options-default-value">Value</label>';
		$html.='<input type="text" name="fields['.$field_key.'][options]['.$row_id.'][value]" class="options-item value" value="'.$value.'" />';
	$html.='</div>';

	return $html;
}
?>