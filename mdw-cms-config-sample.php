<?php
// add post types //
//$mdw_custom_post_types->add_post_types(array('meats','suppliers'));
$args=array(
	'meat' => array(
		'supports' => array('title','thumbnail','revisions'),
		'taxonomies' => false,
		'word_type' => 'singular',
	),
	'suppliers' => array(),
	'slides' => array(
		'supports' => array('title','thumbnail','revisions'),
		'taxonomies' => false
	)
);
$mdw_custom_post_types->add_post_types($args);

// add custom taxonomy //
$mdw_custom_taxonomies->add_taxonomy('supplier-type','suppliers','Supplier Type');

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
				'type' => 'url',
				'label' => 'URL'		
			),
			'phone' => array(
				'type' => 'phone',
				'label' => 'Phone',
				'repeatable' => true
			),			
			'email' => array(
				'type' => 'email',
				'label' => 'email'
			),	
			'logo' => array(
				'type' => 'media',
				'label' => 'Logo'
			),
			'color' => array(
				'type' => 'colorpicker',
				'label' => 'Color'
			),
			'time' => array(
				'type' => 'timepicker',
				'label' => 'Time'
			),
			'date' => array(
				'type' => 'date',
				'label' => 'Date'
			),
			'category' => array(
				'type' => 'select',
				'options' => array('one','two','three'),
				'label' => 'Category'
			),
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