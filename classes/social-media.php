<?php
class MDWSocialMedia {

	public $version='0.1.0';
	
	private $options;

	function __construct() {
		add_action('admin_init',array($this,'register_settings'));
		add_action('admin_menu',array($this,'add_plugin_page'));
		add_action('wp_enqueue_scripts',array($this,'scripts_styles'));
	}
	
	function scripts_styles() {
		wp_enqueue_style('font-awesome-css','//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css',array(),'4.0.3','all');
	}
	
	function add_plugin_page() {
    add_theme_page('Social Media','Social Media','manage_options','social-media-settings',array($this,'social_media_display_options'));
	}
	
	function social_media_display_options() {
		$this->options=get_option('social_media_options');

		if (isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated']==true) :
			echo '<div class="updated social-media-settings">Theme Settings have been updated.</div>	';
		endif;
		
		echo '<h2>Social Media Settings</h2>';
	
		echo '<form method="post" action="options.php">';
    	settings_fields('social_media_options_group');
      do_settings_sections('social-media-settings');
      submit_button();
    echo '</form>';
	}

	/**
	 * our social media aka theme settings
	**/	
	function register_settings() {
		if (false == get_option('social_media_options')){
    	add_option('social_media_options');
    }  
        
		// Add the section so we can add our fields to it
		add_settings_section('social_media_section','',array($this,'social_media_section_cb'),'social-media-settings');
		
		// Add the field with the names and function to use for our new settings, put it in our new section
		add_settings_field('facebook','Facebook',array($this,'facebook_callback_function'),'social-media-settings','social_media_section');		
		add_settings_field('pinterest','Pinterest',array($this,'pinterest_cb'),'social-media-settings','social_media_section');	
		add_settings_field('twitter','Twitter',array($this,'twitter_cb'),'social-media-settings','social_media_section');	
		add_settings_field('houzz','Houzz',array($this,'houzz_cb'),'social-media-settings','social_media_section');
		add_settings_field('sample','Display Social Media',array($this,'sample_cb'),'social-media-settings','social_media_section');		

		// Register our setting so that $_POST handling is done for us and
		// our callback function just has to echo the <input>
		register_setting('social_media_options_group','social_media_options',array($this,'validate_settings')); // sanitize
	}
	
	/**
	 * This function is needed if we added a new section. This function will be run at the start of our section
	**/	
	function social_media_section_cb() {
		echo '<p>We can place our social media URLs here.</p>';
	}
	
	function facebook_callback_function() {
		if (isset( $this->options['facebook'] )) :
			$value=$this->options['facebook'];
		else :
			$value=null;
		endif;

		echo '<input name="social_media_options[facebook]" id="facebook" class="regular-text" type="text" value="'.$value.'" /> Facebook URL';
	}

	function pinterest_cb() {
		if (isset( $this->options['pinterest'] )) :
			$value=$this->options['pinterest'];
		else :
			$value=null;
		endif;

		echo '<input name="social_media_options[pinterest]" id="pinterest" class="regular-text" type="text" value="'.$value.'" /> Pinterest URL';
	}

	function twitter_cb() {
		if (isset( $this->options['twitter'] )) :
			$value=$this->options['twitter'];
		else :
			$value=null;
		endif;

		echo '<input name="social_media_options[twitter]" id="twitter" class="regular-text" type="text" value="'.$value.'" /> Twitter URL';
	}
	
	function houzz_cb() {
		if (isset( $this->options['houzz'] )) :
			$value=$this->options['houzz'];
		else :
			$value=null;
		endif;

		echo '<input name="social_media_options[houzz]" id="houzz" class="regular-text" type="text" value="'.$value.'" /> Houzz URL';
	}

	/**
	 * a sample function for displaying the social media
	 */
	function sample_cb() {
	
		echo '<code>';
			echo '$sm_options=get_option(\'social_media_options\');<br />';
		echo '</code>';
		echo '<p>Everything is stored in that option as an array, simply run through the array and display the urls.</p>';
		echo '<p>This class includes the Font Awesome css for easy usage in a theme.</p>';
		
	}	

	/**
	 * valudate settings
	**/
	function validate_settings($input) {
		$new_input=array();
		
		if (isset($input['facebook']))
			$new_input['facebook']=sanitize_text_field($input['facebook']);
		if (isset($input['pinterest']))
			$new_input['pinterest']=sanitize_text_field($input['pinterest']);
		if (isset($input['twitter']))
			$new_input['twitter']=sanitize_text_field($input['twitter']);
		if (isset($input['houzz']))
			$new_input['houzz']=sanitize_text_field($input['houzz']);					
			
		return $new_input;
	}

}

new MDWSocialMedia();
?>