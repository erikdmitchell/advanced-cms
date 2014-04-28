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
        
		// Add the section so we can add our fields to it
		add_settings_section($this->settings_section,'',array($this,'section_cb'),$this->settings_page);
		
		// Add the field with the names and function to use for our new settings, put it in our new section
		add_settings_field('cms-code','Custom Code',array($this,'cms_code_cb'),$this->settings_page,$this->settings_section);			

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