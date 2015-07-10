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
?>