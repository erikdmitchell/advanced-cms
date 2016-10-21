<?php

/**
 * mdw_cms_gallery_shortcode function.
 *
 * @access public
 * @param mixed $atts
 * @return void
 */
function mdw_cms_gallery_shortcode($atts) {
	global $post;

  $atts=shortcode_atts(array(
    'id' => '',
    'size' => 'full',
		'show_indicators' => true,
		'show_controls' => true,
  ), $atts);

  if (empty($atts['id']))
  	return false;

  $atts['image_ids']=get_post_meta($post->ID, $atts['id'], true);

	$gallery=new MDWCMSGallery($atts);

  return mdw_cms_get_template_part('gallery');
}
add_shortcode('mdw-cms-gallery', 'mdw_cms_gallery_shortcode');

/**
 * mdw_cms_gallery_output function.
 *
 * @access public
 * @param string $args (default: '')
 * @return void
 */
function mdw_cms_gallery_output($args='') {
	echo mdw_cms_get_gallery_output($args);
}

/**
 * mdw_cms_get_gallery_output function.
 *
 * @access public
 * @param string $args (default: '')
 * @return void
 */
function mdw_cms_get_gallery_output($args='') {
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

	$gallery=new MDWCMSGallery($args);

  return mdw_cms_get_template_part('gallery');
}
?>
