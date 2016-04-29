<?php
/**
 * get_post_types_list function.
 *
 * @access public
 * @param bool $selected_pt (default: false)
 * @param string $output (default: 'checkbox')
 * @return void
 */
function get_post_types_list($selected_pt=false,$output='checkbox') {
	$html=null;
	$args=array(
		'public' => true
	);
	$post_types_arr=get_post_types($args);

	$label_class='col-md-3';
	$input_class='col-md-3';

	$html.='<div class="form-row row post-type-list-admin">';
		$html.='<label for="post_type" class="'.$label_class.'">Post Type</label>';
		$html.='<div class="post-types-cbs '.$input_class.'">';
			$counter=0;
			foreach ($post_types_arr as $type) :
				if ($counter==0) :
					$class='first';
				else :
					$class='';
				endif;

				if ($selected_pt && in_array($type,$selected_pt)) :
					$checked='checked=checked';
				else :
					$checked=null;
				endif;


				$html.='<div class="col-md-12">';
					$html.='<input type="checkbox" name="post_types[]" value="'.$type.'" '.$checked.'>'.$type.'<br />';
				$html.='</div>';

				$counter++;
			endforeach;
		$html.='</div>';
	$html.='</div>';

	return $html;
}

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

	ob_end_clean();

	return $html;
}
?>