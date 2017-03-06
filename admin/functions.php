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

/**
 * advanced_cms_admin_metabox_fields function.
 *
 * @access public
 * @param string $fields (default: '')
 * @return void
 */
function advanced_cms_admin_metabox_fields($fields='') {
	global $advanced_cms_admin;

	$field_counter=0;
	$field_id=0;
	$field='';
echo 'advanced_cms_admin_metabox_fields<br>';
print_r($fields);
	if (empty($fields)) :
		$advanced_cms_admin->build_field_rows($field_id, $field, $field_counter);
	else :
		foreach ($fields as $field_id => $field) :
			if (isset($field['field_id'])) :
				$field_id=$field['field_id'];
			else :
				$field_id=0;
			endif;

			$advanced_cms_admin->build_field_rows($field_id, $field, $field_counter);

			$field_counter++;
		endforeach;
	endif;
}

/**
 * advanced_cms_setup_metabox_row function.
 *
 * @access public
 * @param string $args (default: '')
 * @return void
 */
function advanced_cms_setup_metabox_row($args='') {
	$default_args=array(
		'field_id' => 0,
		'order' => 0,
		'classes' => '',
		'repeatable' => false,
		'options' => '',
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

/**
 * advanced_cms_options_rows function.
 *
 * @access public
 * @param string $options (default: '')
 * @param int $field_key (default: 0)
 * @return void
 */
function advanced_cms_options_rows($options='', $field_key=0) {
echo "advanced_cms_options_rows<br>";
	echo advanced_cms_get_options_rows($options, $field_key);
}

/**
 * advanced_cms_get_options_rows function.
 *
 * @access public
 * @param string $options (default: '')
 * @param int $field_key (default: 0)
 * @return void
 */
function advanced_cms_get_options_rows($options='', $field_key=0) {
	$output=null;
echo 'advanced_cms_get_options_rows<br>';
print_r($options);
	if (!empty($options)) :
		foreach ($options as $key => $option) :
			$output.=advanced_cms_generate_option_row(array(
				'row_id' => $key,
				'field_key' => $field_key,
				'name' => $option['name'],
				'value' => $option['value'],
			));
		endforeach;
	else :
		$output.=advanced_cms_generate_option_row(array(
			'field_key' => $field_key,
		));
	endif;

	return $output;
}

/**
 * advanced_cms_generate_option_row function.
 *
 * @access public
 * @param string $args (default: '')
 * @return void
 */
function advanced_cms_generate_option_row($args='') {
	$html=null;
	$default_args=array(
		'row_id' => 0,
		'field_key' => 0,
		'name' => '',
		'value' => '',
	);
	$args=wp_parse_args($args, $default_args);

	extract($args);

	$html.='<div class="option-row" id="option-row-'.$row_id.'">';
		$html.='<div class="name-field">';
			$html.='<label for="options-name">Name</label>';
			$html.='<input type="text" name="fields['.$field_key.'][options]['.$row_id.'][name]" class="options-item name" value="'.$name.'" />';
		$html.='</div>';

		$html.='<div class="value-field">';
			$html.='<label for="options-value">Value</label>';
			$html.='<input type="text" name="fields['.$field_key.'][options]['.$row_id.'][value]" class="options-item value" value="'.$value.'" />';
		$html.='</div>';
	$html.='</div>';

	return $html;
}
?>