<?php

/**
 * MDWSocialMedia class.
 */
class MDWSocialMedia {

	public $version='0.1.1';
	public $option_name='mdw_social_media_options';

	private $options;
	private $default_options;

	protected $fontawesome_file_url=false;
	protected $fontawesome_file_path=false;

	function __construct() {
		add_action('admin_init',array($this,'register_settings'));
		add_action('admin_menu',array($this,'add_plugin_page'));
		add_action('wp_enqueue_scripts',array($this,'scripts_styles'));
		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));

		$this->default_options=$this->setup_default_options();

		$this->fontawesome_file_url=plugin_dir_url(dirname(__FILE__)).'css/font-awesome-4.2.0.min.css';
		$this->fontawesome_file_path=plugin_dir_path(dirname(__FILE__)).'css/font-awesome-4.2.0.min.css';
	}

	function scripts_styles() {
		wp_enqueue_style('font-awesome-css','//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css',array(),'4.2.0');
	}

	function admin_scripts_styles() {
		wp_enqueue_style('font-awesome-css','//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css',array(),'4.2.0');
		wp_enqueue_style('social-media-admin-style', plugin_dir_url(dirname(__FILE__)).'/admin/css/social-media.css');

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-modal-script',plugin_dir_url(dirname(__FILE__)).'js/jquery.modal.min.js',array('jquery'),'0.5.5',true);
		wp_enqueue_script('social-media-script',plugin_dir_url(dirname(__FILE__)).'js/social-media.js',array('jquery'),'1.0.0',true);
	}

	function add_plugin_page() {
    add_theme_page('Social Media','Social Media','manage_options','social-media-settings',array($this,'social_media_display_options'));
	}

	function setup_default_options() {
		$options=array(
			'facebook' => array(
				'name' => 'Facebook',
				'url' => 'https://www.facebook.com/WordPress',
				'icon' => 'fa-facebook-square'
			),
			'twitter' => array(
				'name' => 'Twitter',
				'url' => 'https://twitter.com/wordpress',
				'icon' =>	'fa-twitter-square'
			)
		);

		return $options;
	}

	function social_media_display_options() {
		if (isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated']==true) :
			echo '<div class="updated social-media-settings">Theme Settings have been updated.</div>	';
		endif;

		echo '<h2>Social Media Settings</h2>';

		echo '<form method="post" action="options.php">';
    	settings_fields('social_media_options_group');
      do_settings_sections('social-media-settings');
      submit_button();
    echo '</form>';

    echo $this->get_fa_overlay();
	}

	/**
	 * our social media aka theme settings
	**/
	function register_settings() {
		if (false == get_option($this->option_name)) :
    	add_option($this->option_name);
    	$this->options=$this->default_options;
    else :
   		$this->options=get_option($this->option_name);
		endif;
/*
echo '<pre>';
print_r($this->options);
echo '</pre>';
*/
		$this->add_default_field();

		// Add the section so we can add our fields to it
		add_settings_section('social_media_section','',array($this,'social_media_section_cb'),'social-media-settings');

		// Add the field with the names and function to use for our new settings, put it in our new section
		foreach ($this->options as $id => $values) :
			$args=array(
				'id' => $id,
				'values' => $values
			);
			add_settings_field($id,$values['name'],array($this,'social_media_field_cb'),'social-media-settings','social_media_section',$args);
		endforeach;

		add_settings_field('buttons','New Field Name',array($this,'buttons_cb'),'social-media-settings','social_media_section');
		add_settings_field('display','Display Social Media',array($this,'sample_cb'),'social-media-settings','social_media_section');

		// Register our setting so that $_POST handling is done for us and
		// our callback function just has to echo the <input>
		register_setting('social_media_options_group','social_media_options',array($this,'validate_settings')); // sanitize
	}

	/**
	 * add_default_field function.
	 *
	 * @access public
	 * @return void
	 */
	function add_default_field() {
		$this->options['default_field']=array(
				'name' => '',
				'url' => '',
				'icon' =>	''
		);
	}

	/**
	 * This function is needed if we added a new section. This function will be run at the start of our section
	**/
	function social_media_section_cb() {
		//echo '<p>We can place our social media URLs here.</p>';
	}


	/**
	 * social_media_field_cb function.
	 *
	 * @access public
	 * @param mixed $arr
	 * @return void
	 */
	function social_media_field_cb($arr) {
		$html=null;
		$id=$arr['id'];
		$values=$arr['values'];

		$html.='<input name="social_media_options['.$id.'][url]" id="'.$id.'" class="regular-text" type="text" value="'.$values['url'].'" />';
		$html.='<span class="'.$id.'-icon-icon icon-img"><span class="icon-txt">Icon: </span><span class="icon-img-fa"><i class="fa '.$values['icon'].'"></i></span></span>';
		$html.='<a class="icon-modal-link" data-input-id="'.$id.'-icon" rel="modal:open" name="fa-icons-overlay" href="#fa-icons-overlay">Select Icon</a>';

		$html.='<input type="hidden" name="social_media_options['.$id.'][icon]" id="'.$id.'-icon" value="'.$values['icon'].'" />';
		$html.='<input type="hidden" name="social_media_options['.$id.'][name]" id="'.$id.'-name" value="'.$values['name'].'" />';

		echo $html;
	}


	/**
	 * buttons_cb function.
	 *
	 * @access public
	 * @return void
	 */
	function buttons_cb() {
		$html=null;

		$html.='<input name="add-field-name" id="add-field-name" class="regular-text" type="text" value="" />';
		$html.='<input type="button" name="add-field" id="add-field" class="button button-primary add-field" value="Add Field">';

		echo $html;
	}

	/**
	 * a sample function for displaying the social media
	 */
	function sample_cb() {

		echo '<code>';
			echo 'get_option("social_media_options");<br />';
		echo '</code>';
		echo '<p>There is also the Social Media widget (Appearance -> Widgets) that can be used anywhere on the site.</p>';
		echo '<p>Everything is stored in that option as an array, simply run through the array and display the urls.</p>';
		echo '<p>This class includes the Font Awesome css for easy usage in a theme.</p>';

	}

	/**
	 * valudate settings
	**/
	function validate_settings($input) {
		$new_input=array();

		foreach ($input as $id => $values) :
			if ($id!='default_field')
				$new_input[$id]=$values;
		endforeach;

		update_option($this->option_name,$new_input); // apparently this isn't "magically done"

		return $new_input;
	}

	/**
	 * generates a dropdown for font awesome
	 */
	function get_fa_overlay() {
		$html=null;
		$fa_icons=$this->get_fa_arr();

		$html.='<div id="fa-icons-overlay">';
			$html.='<a class="close-modal" rel="modal:close"></a>';
			$html.='<ul class="fa-icons-list">';
				foreach ($fa_icons as $icon) :
					$html.='<li id="'.$icon['class'].'"><a href="#" data-icon="'.$icon['class'].'"><i class="fa '.$icon['class'].'"></i>'.$icon['name'].'</a></li>';
				endforeach;
			$html.='</ul>';
		$html.='</div>';

		return $html;
	}

	/**
	 * uses a regext to filter through our fa ccs file and get the classes
	 * @return $icons array with icon name and class
	 */
	function get_fa_arr() {
		$fa_css_prefix='fa';
		$css = file_get_contents($this->fontawesome_file_path);
		$pattern='/\.('.$fa_css_prefix.'-(?:\w+(?:-)?)+):/';
		$icons=array();

		preg_match_all($pattern, $css, $matches, PREG_SET_ORDER);

		foreach($matches as $match) :
			$clean_match=preg_replace('/(fa-)/','',$match[1]);
			$clean_match=preg_replace('/(-)/',' ',$clean_match);

			$icons[]=array(
				'name' => ucwords($clean_match),
				'class' => $match[1]
			);
		endforeach;

		usort($icons, function ($a, $b) {
    	return strcmp($a['class'], $b['class']);
		});

		return $icons;
	}

}

new MDWSocialMedia();
?>