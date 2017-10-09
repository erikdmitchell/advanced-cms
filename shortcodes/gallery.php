<?php

/**
 * pickle_cms_gallery_shortcode function.
 *
 * @access public
 * @param mixed $atts
 * @return void
 */
function pickle_cms_gallery_shortcode($atts) {
	global $post;

  $atts=shortcode_atts(array(
    'id' => '',
    'size' => 'full',
		'show_indicators' => true,
		'show_controls' => true,
		'bootstrap' => true,
  ), $atts);

  if (empty($atts['id']))
  	return false;

  $atts['image_ids']=get_post_meta($post->ID, $atts['id'], true);

	$gallery=new pickleCMSGallery($atts);

  return pickle_cms_get_template_part('gallery');
}
add_shortcode('pickle-cms-gallery', 'pickle_cms_gallery_shortcode');

/**
 * pickle_cms_gallery_output function.
 *
 * @access public
 * @param string $args (default: '')
 * @return void
 */
function pickle_cms_gallery_output($args='') {
	echo pickle_cms_get_gallery_output($args);
}

/**
 * pickle_cms_get_gallery_output function.
 *
 * @access public
 * @param string $args (default: '')
 * @return void
 */
function pickle_cms_get_gallery_output($args='') {
	global $post;

  $default_args=array(
	  'post_id' => '',
    'id' => '',
    'size' => 'full',
		'show_indicators' => true,
		'show_controls' => true,
  );
  $args=wp_parse_args($args, $default_args);

	extract($args);

  if (empty($post_id))
  	$post_id=$post->ID;

  $args['image_ids']=get_post_meta($post_id, $id, true);

	$gallery=new pickleCMSGallery($args);

  return pickle_cms_get_template_part('gallery');
}
?>
