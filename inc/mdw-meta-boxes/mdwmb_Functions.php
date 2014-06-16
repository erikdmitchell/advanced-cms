<?php
class mdwmb_Functions {
	
	function __construct() {
		// nothing as of yet //	
	}
	
	public static function mdwm_wp_editor($content,$editor_id,$settings) {
		ob_start(); // Turn on the output buffer
		wp_editor($content,$editor_id,$settings); // Echo the editor to the buffer
		$editor_contents = ob_get_clean(); // Store the contents of the buffer in a variable

		return $editor_contents;
	}
	
	/**
	 * generates a preformatted get post meta field
	 * @param int $post_id Post ID.
	 * @param string $key Optional. The meta key to retrieve. By default, returns data for all keys.
	 * @param bool $single Whether to return a single value.
	 * @param string The type (format) to display
	 * @return mixed Will be an array if $single is false. Will be value of meta data field if $single is true.
	 */
	public static function get_post_meta_advanced($post_id,$key='',$single=false,$type='') {
		$metadata=get_metadata('post', $post_id, $key, $single);
		
		switch($type) :
			case 'phone' :
				$raw_phone=str_replace(' ','',$metadata);
				$raw_phone=str_replace('-','',$raw_phone);
				$raw_phone=str_replace('(','',$raw_phone);
				$raw_phone=str_replace(')','',$raw_phone);
				return '<a href="tel:'.$raw_phone.'">'.$metadata.'</a>';
				break;
			case 'fax' :
				$raw_fax=str_replace(' ','',$metadata);
				$raw_fax=str_replace('-','',$raw_fax);
				$raw_fax=str_replace('(','',$raw_fax);
				$raw_fax=str_replace(')','',$raw_fax);
				return '<a href="fax:'.$raw_fax.'">'.$metadata.'</a>';
				break;
			case 'url' :
				return '<a href="'.$metadata.'" target="_blank">'.$metadata.'</a>';
				break;
			default:
				return $metadata; 
				break;
		endswitch;
		
		return $metadata;
	}
}
?>
