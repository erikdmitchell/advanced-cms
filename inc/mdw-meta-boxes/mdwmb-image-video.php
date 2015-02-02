<?php
/****************************************************************************************************
 * this file includes all of our audio/video functions to be used with the "media" metabox
****************************************************************************************************/

/**
 * displays the post meta for our media metabox
 * @param integer $post_id - the post id for the meta
 * @param string $meta_name - the name of the meta
 * @param string $size - the image size for meta, default=thimbnail
 * @param array $args - TBD ~ will use for extra things like video args and what not
 * returns the image/video/file icon
**/
function mdwm_display_post_media_meta($post_id,$meta_name,$size='thumbnail',$args=array()) {
	global $wpdb;
	
	$mime_type=get_meta_mime_type($post_id,$meta_name);
	
	if (strpos($mime_type,'image')!==false) :
		$attachment=$wpdb->get_col($wpdb->prepare("SELECT ID FROM ".$wpdb->prefix."posts"." WHERE guid='%s';",get_post_meta($post_id,$meta_name,true))); 
		return wp_get_attachment_image($attachment[0],$size);
	elseif (strpos($mime_type,'video')!==false) :
		$url=get_post_meta($post_id,$meta_name,true);
		$attr=array(
			'src' => get_post_meta($post_id,$meta_name,true),
			//'poster' => '', // image to show //
			//'loop' => 'off', // off/on
			//'autoplay' => 'off', // off/on
			//'preload' => 'metadata', // metadata/none/auto
			/* 'height' -- predefined */
			/* 'width' -- predefined */
		);
		return wp_video_shortcode($attr);
	else :
		$icon=wp_mime_type_icon($mime_type);
		return '<img src="'.$icon.'" class="icon" />'; // should be document.png //
	endif;
}

/**
 * determines if the meta is a video
 * @param integer $post_id - the post id for the meta
 * @pram string $meta_name - the name of the meta
 * returns true/false
**/
/*
function is_media_video($post_id,$meta_name) {
	$mime_type=get_meta_mime_type($post_id,$meta_name);
	
	if (strpos($mime_type,'video')!==false) :
		return true;
	endif;
	
	return false;
}
*/

/**
 * gets the meta data for the meta if it is a video
 * @param integer $post_id - the post id for the meta
 * @pram string $meta_name - the name of the meta
 * returns array of video data or false
**/
function get_media_video_data($post_id,$meta_name) {
	$attachment_id=pippin_get_image_id(get_post_meta($post_id,$meta_name,true));
	$mime_type=get_meta_mime_type($post_id,$meta_name);
	
	if (strpos($mime_type,'video')!==false) :
		$video_arr=array(
			'attachment_id' => $attachment_id,
			'url' => get_post_meta($post_id,$meta_name,true),
			'mime_type' => $mime_type,
			'image' => wp_get_attachment_image($attachment_id,'full',true),		
		);
		
		return $video_arr;
	endif;
	
	return false;
}

/**
 * gets the meta data for the meta if it is an image
 * @param integer $post_id - the post id for the meta
 * @pram string $meta_name - the name of the meta
 * @param string $size - the size of the thumbnail (default: thumbnail)
 * returns array of image data or false
**/
function get_media_image_data($post_id,$meta_name,$size='thumbnail') {

	if (get_post_meta($post_id,$meta_name,true)) :
		$pid=pippin_get_image_id(get_post_meta($post_id,$meta_name,true));
		
		$image_arr=array(
			'attachment_id' => $pid,
			'image_data' => wp_get_attachment_image_src($pid,$size)
		);

		return $image_arr;
	endif;
	
	return false;	
}

/**
 * returns the actual image, not the url
 * @param integer $post_id - the post id for the meta
 * @pram string $meta_name - the name of the meta
 * @param string $size - the size of the thumbnail (default: thumbnail)
 * returns the image or flase
**/
/*
function umb_get_post_media_image($post_id,$meta_name,$size='thumbnail') {
	global $wpdb;
	
	$prefix = $wpdb->prefix;
	
	if (get_post_meta($post_id,$meta_name,true)) :
		$attachment=$wpdb->get_col($wpdb->prepare("SELECT ID FROM ".$prefix."posts"." WHERE guid='%s';",get_post_meta($post_id,$meta_name,true))); 
		return wp_get_attachment_image($attachment[0],$size);
	endif;
	
	return false;
}
*/

/*******************
 * Helper Functions
*******************/

function get_meta_mime_type($post_id,$meta_name) {
	$attachment_id=pippin_get_image_id(get_post_meta($post_id,$meta_name,true));
	if (get_post_mime_type($attachment_id)) :
		return get_post_mime_type($attachment_id);
	endif;
	
	return false;
}

function pippin_get_image_id($image_url) {
	global $wpdb;
	
	$prefix = $wpdb->prefix;
	
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $image_url )); 
  
  return $attachment[0]; 
}

?>