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
?>