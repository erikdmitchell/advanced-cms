<?php
if (class_exists('mdw_Meta_Box')) :
	$config=array(
		'id' => 'sample_mb_id',
		'title' => 'Sample Meta Box',
		/* 'prefix' => '', */
		'post_types' => 'page'
	);
	$sample_meta_box = new mdw_Meta_Box($config);
	
	$sample_meta_box->add_field(array(
		'id' => 'sample'
	));
	$sample_meta_box->add_field(array(
		'id' => 'sample-text',
		'type' => 'text',
	));
	$sample_meta_box->add_field(array(
		'id' => 'sample-checkbox',
		'type' => 'checkbox',
		'label' => 'Checkbox',
	));		
	$sample_meta_box->add_field(array(
		'id' => 'sample-textarea',
		'type' => 'textarea',
		'label' => 'Textarea',
	));	
	$sample_meta_box->add_field(array(
		'id' => 'sample-wysiwyg',
		'type' => 'wysiwyg',
		'label' => 'WYSIWYG',
	));	
	$sample_meta_box->add_field(array(
		'id' => 'sample-media',
		'type' => 'media',
		'label' => 'Media',
	));	
	$sample_meta_box->add_field(array(
		'id' => 'sample-media-video',
		'type' => 'media',
		'label' => 'Media - Video',
	));	
	$sample_meta_box->add_field(array(
		'id' => 'sample-media-file',
		'type' => 'media',
		'label' => 'Media - File',
	));
	
	$config=array(
		'id' => 'supplier_meta',
		'title' => 'Supplier Details',
		/* 'prefix' => '', */
		'post_types' => 'suppliers'
	);
	$suppliers_meta=new mdw_Meta_Box($config);
	
	$suppliers_meta->add_field(array(
		'id' => 'address',
		'type' => 'textarea',
		'label' => 'Address'
	));
	$suppliers_meta->add_field(array(
		'id' => 'url',
		'type' => 'text',
		'label' => 'URL'
	));
endif;
?>