<?php
function mdw_cms_gallery_shortcode($atts) {
	global $post;

  extract(shortcode_atts(array(
    'id' => ''
  ), $atts));

  if (empty($id))
  	return false;

  $image_ids=get_post_meta($post->ID, $id, true);



  return "the ids: $image_ids";
}
add_shortcode('mdw-cms-gallery', 'mdw_cms_gallery_shortcode');
?>