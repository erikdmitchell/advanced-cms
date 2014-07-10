<?php
/*
 Builds and admin options type page where we will store our cms functionality, it can also be stored in the themes function.php file.
 Since: 1.0.3
 This will prevent the overriding of custom code un plugin updates.
*/

class MDWCMS_Options {

	private $options;
	
	public $settings_page='mdw-cms-options';
	public $settings_section='mdw-cms-section';
	public $setting_group='mdw-cms-options-group';
	public $settings_options='mdw_cms_options';

	function __construct() {
		add_action('admin_init',array($this,'register_settings'));
		add_action('admin_menu',array($this,'add_plugin_page'));
		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));
	}
	
	function admin_scripts_styles() {
		wp_enqueue_style('custom-cms-css',plugins_url('css/admin.css',__FILE__),array(),'0.1.0','all');
	}
	
	function add_plugin_page() {
    add_theme_page('CMS Settings','CMS Settings','manage_options',$this->settings_page,array($this,'social_media_display_options'));
	}
	
	function social_media_display_options() {
		$this->options=get_option($this->settings_options);

		echo '<h2>Custom CMS Settings</h2>';

		if (isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated']==true) :
			echo '<div class="updated '.$this->settings_page.'">The settings have been updated.</div>	';
		endif;
	
		echo '<form method="post" action="options.php">';
    	settings_fields($this->setting_group);
      do_settings_sections($this->settings_page);
      submit_button();
    echo '</form>';
	}

	/**
	 * our social media aka theme settings
	**/	
	function register_settings() {
		if (false == get_option($this->settings_options))
    	add_option($this->settings_options);
        
		// Setup our section and fields for our custom php code //
		//add_settings_section($this->settings_section,'',array($this,'section_cb'),$this->settings_page);
		//add_settings_field('cms-code','Custom Code',array($this,'cms_code_cb'),$this->settings_page,$this->settings_section);			

		// Setup our section and fields for our bootstrap slider //
		add_settings_section('mdw-cms-slider','Bootstrap Slider',array($this,'mdw_cms_slider_cb'),$this->settings_page);
		add_settings_field('slider-code','Slider Code',array($this,'slider_code_cb'),$this->settings_page,'mdw-cms-slider');
		add_settings_field('slider-code-atts','Slider Shortcode Attributes',array($this,'slider_code_atts_cb'),$this->settings_page,'mdw-cms-slider');	

		// Register our setting so that $_POST handling is done for us and
		// our callback function just has to echo the <input>
		register_setting($this->setting_group,$this->settings_options,array($this,'validate_settings')); // sanitize
	}
	
	/**
	 * This function is needed if we added a new section. This function will be run at the start of our section
	**/	
	function section_cb() {
		//echo '<p>Intro text for our settings section</p>';
	}

	function cms_code_cb() {
		if (isset( $this->options['custom_code'] )) :
			$value=$this->options['custom_code'];
		else :
			$value=null;
		endif;

		echo '<textarea name="'.$this->settings_options.'[custom_code]" id="custom_code" class="">'.$value.'</textarea>';
		echo '<span class="description">Add your customized php code utilizing the CMS Framework.</span>';
	}

	// BEGIN BOOTSTRAP SLIDER SETUP //
	/**
	 * Ouputs our notes after the section title
	**/	
	function mdw_cms_slider_cb() {
		echo '<p>Note: only works if Bootstrap is part of the active theme.</p>';
	}

	/**
	 * generates the sample shortcode for slider
	 */
	function slider_code_cb() {
		echo '<code>[bootstrap-slider slider_id="slider" post_type="slides"]</code>';
	}

	/**
	 * generates the sample shortcode attributes for slider
	 */
	function slider_code_atts_cb() {
		echo '<ul class="atts">';
			echo '<li>';
				echo '<strong>slider_id</strong><br />';
				echo '(string) The id for the slider.';
			echo '</li>';
			echo '<li>';
				echo '<strong>post_type</strong><br />';
				echo '(string) The post type of the posts for the slider.';
			echo '</li>';
			echo '<li>';
				echo '<strong>indicators</strong><br />';
				echo '(true/false) Display the slide indicators.';
			echo '</li>';
			echo '<li>';
				echo '<strong>slides</strong><br />';
				echo '(true/false) Display the slides.';
			echo '</li>';
			echo '<li>';
				echo '<strong>captions</strong><br />';
				echo '(true/false) Display slide captions.';
			echo '</li>';
			echo '<li>';
				echo '<strong>controls</strong><br />';
				echo '(true/false) Display the slide left/right controls.';
			echo '</li>';													
		echo '</ul>';
	}
			
	/**
	 * valudate settings
	**/
	function validate_settings($input) {
		$new_input=array();
		
		if (isset($input['custom_code']))
			$new_input['custom_code']=sanitize_text_field($input['custom_code']);				
			
		return $new_input;
	}

}

new MDWCMS_Options();
?>