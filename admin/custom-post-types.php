<?php
function ajax_update_cpt($args=array()) {
	if (empty($args))
		return false;

	$response=array();

	extract($args);

	if ($page_action=='edit') :
		$response['content']=$this->cpt_admin_page($id);
		$response['notice']=null;
	elseif ($page_action=='delete') : // remove post type and update option //
		unset($post_types[$id]);
		$post_types=array_values($post_types);
		update_option('mdw_cms_post_types',$post_types);

		$response=true; // reload page //
	elseif ($page_action=='add') :
		$form_data_final=array();

		foreach ($form_data as $input) :
			$form_data_final[$input['name']]=$input['value'];
		endforeach;

		if ($this->update_custom_post_types($form_data_final)) :
			$post_types=get_option('mdw_cms_post_types');
			foreach ($post_types as $key => $post_type) :
				if ($post_type['name']==$form_data_final['name']) :
					$id=$key;
					$slug=$post_type['name'];
				endif;
			endforeach;

			// b/c we do a page reload, this hides some vars so that we can load with the edit screen up //
			$response['id']=$id;
			$response['notice']=urlencode('<div class="updated">Post type "'.$slug.'" has been created.</div>');
		else :
			$response['content']=$this->cpt_admin_page();
			$response['notice']='<div class="error">There was an issue creating the post type "'.$slug.'</div>';
		endif;
	elseif ($page_action=='update') :
		$form_data_final=array();

		foreach ($form_data as $input) :
			$form_data_final[$input['name']]=$input['value'];
		endforeach;

		$id=$form_data_final['cpt-id'];
		$slug=$form_data_final['name'];

		if ($this->update_custom_post_types($form_data_final)) :
			$response['content']=$this->cpt_admin_page($id);
			$response['notice']='<div class="updated">Post type "'.$slug.'" has been updated.</div>';
		else :
			$response['content']=$this->cpt_admin_page($id);
			$response['notice']='<div class="error">There was an issue updating the post type "'.$slug.'</div>';
		endif;
	endif;

	return $response;
}
?>