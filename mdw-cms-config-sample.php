<?php
// add post types //
//$mdw_custom_post_types->add_post_types(array('meats','suppliers'));
$args=array(
	'meats' => array(
		'supports' => array('title','thumbnail','revisions'),
		'taxonomies' => false
	),
	'suppliers' => array(),
	'slides' => array(
		'supports' => array('title','thumbnail','revisions'),
		'taxonomies' => false
	),
);
$mdw_custom_post_types->add_post_types($args);

// add custom taxonomy //
$mdw_custom_taxonomies->add_taxonomy('supplier-type','suppliers','Supplier Type');

// custom meta boxes for suppliers //
/*
$config=array(
	'id' => 'supplier_meta',
	'title' => 'Supplier Details',
	'prefix' => 'supplier',
	'post_types' => 'suppliers',
	'duplicate' => 1,
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
*/

// custom meta boxes for meats //
$config=array(
	array(
		'id' => 'meats_details',
		'title' => 'Meats Details',
		'prefix' => 'meats',
		'post_types' => 'meats',
		'duplicate' => 0,
		'fields' => array(
			'brand' => array(
				'type' => 'text',
				'label' => 'Brand'		
			)
		)
	),
	array(
		'id' => 'supplier_meta',
		'title' => 'Supplier Details',
		'prefix' => 'supplier',
		'post_types' => 'suppliers',
		'duplicate' => 1,
		'fields' => array(
			'address' => array(
				'type' => 'textarea',
				'label' => 'Address'		
			),
			'url' => array(
				'type' => 'text',
				'label' => 'URL'		
			)			
		)		
	)
);
$meta=new mdw_Meta_Box($config);

// admin columns for suppliers //
$arr=array(
	'post_type' => 'suppliers',
	'columns' => array(
		array (
			'slug' => '_supplier_url',
			'label' => 'URL',
			'type' => 'meta'
		),
		array(
			'slug' => '_supplier_address',
			'label' => 'Address',
		),
		array(
			'slug' => 'supplier-type',
			'label' => 'Type',
			'type' => 'taxonomy'
		)		
	),
);

$mdw_admin_columns=new MDW_Admin_Columns($arr);

// custom widgets //
$params=array(
	array(
		'id' => 'mdw-cms-widget-id',
		'title' => __('MDW CMS Custom Widget'),
		'description' => __('A custom widget created via the MDW CMS plugin.'),
 		'fields' => array(
 			array(
 				'id' => 'title',
 				'type' => 'text',
 				'label' => 'Title:',
 				'description' => 'The title of our widget.',
 			),
 			array(
 				'id' => 'slogan',
 				'type' => 'text',
 				'label' => 'Slogan:',
 			),
 			array(
 				'id' => 'details',
 				'type' => 'textarea',
 				'label' => 'Details:',
 			), 			
 		),
	),
);

new MDW_Widget_Creator($params);
/*
// meta boxes //
$config=array(
	'id' => 'sample_mb_id',
	'title' => 'Sample Meta Box',
	// 'prefix' => '', //
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
*/