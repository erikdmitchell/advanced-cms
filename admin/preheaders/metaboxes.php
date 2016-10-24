<?php
global $mdw_cms_admin, $mdw_cms_metabox_args;

$args='';
$default_args=array(
	'base_url' => admin_url('tools.php?page=mdw-cms&tab=metaboxes'),
	'btn_text' => 'Create',
	'mb_id' => '',
	'title' => '',
	'prefix' => '',
	'post_types' => '',
	'edit_class_v' => '',
	'fields' => '',
	'title' => '',
);

// edit //
if (isset($_GET['edit']) && $_GET['edit']=='mb') :
	foreach ($mdw_cms_admin->options['metaboxes'] as $key => $mb) :
		if ($mb['mb_id']==$_GET['mb_id']) :
			$args=$mdw_cms_admin->options['metaboxes'][$key];
			$args['btn_text']='Update';
		endif;
	endforeach;
endif;

$mdw_cms_metabox_args=mdw_cms_parse_args($args, $default_args);
?>