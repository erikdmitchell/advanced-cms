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
}
?>