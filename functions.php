<?php
function mdwcms_admin_page($slug=false) {
	if (!$slug)
		return false;

	include(plugin_dir_path(__FILE__).'adminpages/'.$slug.'.php');
}

function mdwcms_get_post_types_list($selected_pt=false,$output='checkbox') {
	$html=null;
	$args=array(
		'public' => true
	);
	$post_types_arr=get_post_types($args);

	$html.='<div class="form-row row post-type-list-admin">';
		$html.='<label for="post_type" class="col-md-3">Post Type</label>';
		$html.='<div class="post-types-cbs col-md-3">';
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

	echo $html;
}

function mdwcms_display_existing_taxonomies($args=array()) {
	$html=null;
	$mdwcms_options=mdwcms_get_options();
	$default_args=array(
		'base_url' => admin_url()
	);
	$args=array_merge($default_args,$args);

	extract($args);

	if ($mdwcms_options['taxonomies']) :
		foreach ($mdwcms_options['taxonomies'] as $tax) :
			$html.='<div class="tax-row row">';
				$html.='<span class="tax col-md-5">'.$tax['args']['label'].'</span><span class="edit col-md-2">[<a href="'.$base_url.'&edit=tax&slug='.$tax['name'].'">Edit</a>]</span><span class="delete col-md-2">[<a href="'.$base_url.'&delete=tax&slug='.$tax['name'].'">Delete</a>]</span>';
			$html.='</div>';
		endforeach;
	endif;

	echo $html;
}

function mdwcms_get_taxonomy_details($tax_slug=false) {
	if (!$tax_slug)
		return false;

	$mdwcms_options=mdwcms_get_options();
	$data=array();

	foreach ($mdwcms_options['taxonomies'] as $key => $tax) :
		if ($tax['name']==$tax_slug) :
			$data=array(
				'id' => $key,
				'name' => $tax['name'],
				'label' => $tax['args']['label'],
				'object_type' => $tax['object_type']
			);

			return $data;
		endif;
	endforeach;

	return false;
}
?>