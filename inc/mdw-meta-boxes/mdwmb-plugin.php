<?php
class MDWMetaboxes {

	private $nonce = 'wp_upm_media_nonce'; // Represents the nonce value used to save the post media //
	private $version='1.2.5';
	private $option_name='mdw_meta_box_duped_boxes';

	protected $options=array();
	protected $post_types=array();

	public $fields=array();
	public $countries=array(
    "GB" => "United Kingdom",
    "US" => "United States",
    "AF" => "Afghanistan",
    "AL" => "Albania",
    "DZ" => "Algeria",
    "AS" => "American Samoa",
    "AD" => "Andorra",
    "AO" => "Angola",
    "AI" => "Anguilla",
    "AQ" => "Antarctica",
    "AG" => "Antigua And Barbuda",
    "AR" => "Argentina",
    "AM" => "Armenia",
    "AW" => "Aruba",
    "AU" => "Australia",
    "AT" => "Austria",
    "AZ" => "Azerbaijan",
    "BS" => "Bahamas",
    "BH" => "Bahrain",
    "BD" => "Bangladesh",
    "BB" => "Barbados",
    "BY" => "Belarus",
    "BE" => "Belgium",
    "BZ" => "Belize",
    "BJ" => "Benin",
    "BM" => "Bermuda",
    "BT" => "Bhutan",
    "BO" => "Bolivia",
    "BA" => "Bosnia And Herzegowina",
    "BW" => "Botswana",
    "BV" => "Bouvet Island",
    "BR" => "Brazil",
    "IO" => "British Indian Ocean Territory",
    "BN" => "Brunei Darussalam",
    "BG" => "Bulgaria",
    "BF" => "Burkina Faso",
    "BI" => "Burundi",
    "KH" => "Cambodia",
    "CM" => "Cameroon",
    "CA" => "Canada",
    "CV" => "Cape Verde",
    "KY" => "Cayman Islands",
    "CF" => "Central African Republic",
    "TD" => "Chad",
    "CL" => "Chile",
    "CN" => "China",
    "CX" => "Christmas Island",
    "CC" => "Cocos (Keeling) Islands",
    "CO" => "Colombia",
    "KM" => "Comoros",
    "CG" => "Congo",
    "CD" => "Congo, The Democratic Republic Of The",
    "CK" => "Cook Islands",
    "CR" => "Costa Rica",
    "CI" => "Cote D'Ivoire",
    "HR" => "Croatia (Local Name: Hrvatska)",
    "CU" => "Cuba",
    "CY" => "Cyprus",
    "CZ" => "Czech Republic",
    "DK" => "Denmark",
    "DJ" => "Djibouti",
    "DM" => "Dominica",
    "DO" => "Dominican Republic",
    "TP" => "East Timor",
    "EC" => "Ecuador",
    "EG" => "Egypt",
    "SV" => "El Salvador",
    "GQ" => "Equatorial Guinea",
    "ER" => "Eritrea",
    "EE" => "Estonia",
    "ET" => "Ethiopia",
    "FK" => "Falkland Islands (Malvinas)",
    "FO" => "Faroe Islands",
    "FJ" => "Fiji",
    "FI" => "Finland",
    "FR" => "France",
    "FX" => "France, Metropolitan",
    "GF" => "French Guiana",
    "PF" => "French Polynesia",
    "TF" => "French Southern Territories",
    "GA" => "Gabon",
    "GM" => "Gambia",
    "GE" => "Georgia",
    "DE" => "Germany",
    "GH" => "Ghana",
    "GI" => "Gibraltar",
    "GR" => "Greece",
    "GL" => "Greenland",
    "GD" => "Grenada",
    "GP" => "Guadeloupe",
    "GU" => "Guam",
    "GT" => "Guatemala",
    "GN" => "Guinea",
    "GW" => "Guinea-Bissau",
    "GY" => "Guyana",
    "HT" => "Haiti",
    "HM" => "Heard And Mc Donald Islands",
    "VA" => "Holy See (Vatican City State)",
    "HN" => "Honduras",
    "HK" => "Hong Kong",
    "HU" => "Hungary",
    "IS" => "Iceland",
    "IN" => "India",
    "ID" => "Indonesia",
    "IR" => "Iran (Islamic Republic Of)",
    "IQ" => "Iraq",
    "IE" => "Ireland",
    "IL" => "Israel",
    "IT" => "Italy",
    "JM" => "Jamaica",
    "JP" => "Japan",
    "JO" => "Jordan",
    "KZ" => "Kazakhstan",
    "KE" => "Kenya",
    "KI" => "Kiribati",
    "KP" => "Korea, Democratic People's Republic Of",
    "KR" => "Korea, Republic Of",
    "KW" => "Kuwait",
    "KG" => "Kyrgyzstan",
    "LA" => "Lao People's Democratic Republic",
    "LV" => "Latvia",
    "LB" => "Lebanon",
    "LS" => "Lesotho",
    "LR" => "Liberia",
    "LY" => "Libyan Arab Jamahiriya",
    "LI" => "Liechtenstein",
    "LT" => "Lithuania",
    "LU" => "Luxembourg",
    "MO" => "Macau",
    "MK" => "Macedonia, Former Yugoslav Republic Of",
    "MG" => "Madagascar",
    "MW" => "Malawi",
    "MY" => "Malaysia",
    "MV" => "Maldives",
    "ML" => "Mali",
    "MT" => "Malta",
    "MH" => "Marshall Islands",
    "MQ" => "Martinique",
    "MR" => "Mauritania",
    "MU" => "Mauritius",
    "YT" => "Mayotte",
    "MX" => "Mexico",
    "FM" => "Micronesia, Federated States Of",
    "MD" => "Moldova, Republic Of",
    "MC" => "Monaco",
    "MN" => "Mongolia",
    "MS" => "Montserrat",
    "MA" => "Morocco",
    "MZ" => "Mozambique",
    "MM" => "Myanmar",
    "NA" => "Namibia",
    "NR" => "Nauru",
    "NP" => "Nepal",
    "NL" => "Netherlands",
    "AN" => "Netherlands Antilles",
    "NC" => "New Caledonia",
    "NZ" => "New Zealand",
    "NI" => "Nicaragua",
    "NE" => "Niger",
    "NG" => "Nigeria",
    "NU" => "Niue",
    "NF" => "Norfolk Island",
    "MP" => "Northern Mariana Islands",
    "NO" => "Norway",
    "OM" => "Oman",
    "PK" => "Pakistan",
    "PW" => "Palau",
    "PA" => "Panama",
    "PG" => "Papua New Guinea",
    "PY" => "Paraguay",
    "PE" => "Peru",
    "PH" => "Philippines",
    "PN" => "Pitcairn",
    "PL" => "Poland",
    "PT" => "Portugal",
    "PR" => "Puerto Rico",
    "QA" => "Qatar",
    "RE" => "Reunion",
    "RO" => "Romania",
    "RU" => "Russian Federation",
    "RW" => "Rwanda",
    "KN" => "Saint Kitts And Nevis",
    "LC" => "Saint Lucia",
    "VC" => "Saint Vincent And The Grenadines",
    "WS" => "Samoa",
    "SM" => "San Marino",
    "ST" => "Sao Tome And Principe",
    "SA" => "Saudi Arabia",
    "SN" => "Senegal",
    "SC" => "Seychelles",
    "SL" => "Sierra Leone",
    "SG" => "Singapore",
    "SK" => "Slovakia (Slovak Republic)",
    "SI" => "Slovenia",
    "SB" => "Solomon Islands",
    "SO" => "Somalia",
    "ZA" => "South Africa",
    "GS" => "South Georgia, South Sandwich Islands",
    "ES" => "Spain",
    "LK" => "Sri Lanka",
    "SH" => "St. Helena",
    "PM" => "St. Pierre And Miquelon",
    "SD" => "Sudan",
    "SR" => "Suriname",
    "SJ" => "Svalbard And Jan Mayen Islands",
    "SZ" => "Swaziland",
    "SE" => "Sweden",
    "CH" => "Switzerland",
    "SY" => "Syrian Arab Republic",
    "TW" => "Taiwan",
    "TJ" => "Tajikistan",
    "TZ" => "Tanzania, United Republic Of",
    "TH" => "Thailand",
    "TG" => "Togo",
    "TK" => "Tokelau",
    "TO" => "Tonga",
    "TT" => "Trinidad And Tobago",
    "TN" => "Tunisia",
    "TR" => "Turkey",
    "TM" => "Turkmenistan",
    "TC" => "Turks And Caicos Islands",
    "TV" => "Tuvalu",
    "UG" => "Uganda",
    "UA" => "Ukraine",
    "AE" => "United Arab Emirates",
    "UM" => "United States Minor Outlying Islands",
    "UY" => "Uruguay",
    "UZ" => "Uzbekistan",
    "VU" => "Vanuatu",
    "VE" => "Venezuela",
    "VN" => "Viet Nam",
    "VG" => "Virgin Islands (British)",
    "VI" => "Virgin Islands (U.S.)",
    "WF" => "Wallis And Futuna Islands",
    "EH" => "Western Sahara",
    "YE" => "Yemen",
    "YU" => "Yugoslavia",
    "ZM" => "Zambia",
    "ZW" => "Zimbabwe"
  );

	/**
	 * constructs our function, setups our scripts and styles, attaches meta box to wp actions
	 */
	function __construct() {
		$config=get_option('mdw_cms_metaboxes');

		$this->fields=array(
			'address' => array(
				'repeatable' => 1,
				'options' => 0,
				'format' => 0,
			),
			'checkbox' => array(
				'repeatable' => 1,
				'options' => 0,
				'format' => 0,
			),
			'colorpicker' => array(
				'repeatable' => 0,
				'options' => 0,
				'format' => 0,
			),
			'date' => array(
				'repeatable' => 0,
				'options' => 0,
				'format' => 1,
			),
			'email' => array(
				'repeatable' => 1,
				'options' => 0,
				'format' => 0,
			),
			'media' => array(
				'repeatable' => 0,
				'options' => 0,
				'format' => 0,
			),
			'media_images' => array(
				'repeatable' => 0,
				'options' => 0,
				'format' => 0,
			),
			'phone' => array(
				'repeatable' => 1,
				'options' => 0,
				'format' => 0,
			),
			'radio' => array(
				'repeatable' => 1,
				'options' => 0,
				'format' => 0,
			),
			'select' => array(
				'repeatable' => 0,
				'options' => 1,
				'format' => 0,
			),
			'text' => array(
				'repeatable' => 1,
				'options' => 0,
				'format' => 0,
			),
			'textarea' => array(
				'repeatable' => 1,
				'options' => 0,
				'format' => 0,
			),
			'timepicker' => array(
				'repeatable' => 0,
				'options' => 0,
				'format' => 0,
			),
			'url'	 => array(
				'repeatable' => 1,
				'options' => 0,
				'format' => 0,
			),
			'wysiwyg' => array(
				'repeatable' => 0,
				'options' => 0,
				'format' => 0,
			)
		);
		$this->config=$this->setup_config($config); // set our config

		add_action('admin_enqueue_scripts',array($this,'register_admin_scripts_styles'));
		add_action('wp_enqueue_scripts',array($this,'register_scripts_styles'));
		add_action('save_post',array($this,'save_custom_meta_data'));
		add_action('add_meta_boxes',array($this,'mdwmb_add_meta_box'));
		//add_action('wp_ajax_dup-box',array($this,'duplicate_meta_box'));
		//add_action('wp_ajax_remove-box',array($this,'remove_duplicate_meta_box'));

		add_action('wp_ajax_duplicate_metabox_field',array($this,'ajax_duplicate_metabox_field'));
		add_action('wp_ajax_remove_duplicate_metabox_field',array($this,'ajax_remove_duplicate_metabox_field'));
	}

	/**
	 * register_admin_scripts_styles function.
	 *
	 * @access public
	 * @param mixed $hook
	 * @return void
	 */
	function register_admin_scripts_styles($hook) {
		global $post;

		wp_enqueue_style('mdwmb-admin-css',plugins_url('/css/admin.css',__FILE__));
		wp_enqueue_style('jquery-ui-style','//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css',array(),'1.10.4');
		wp_enqueue_style('colpick-css',plugins_url('/css/colpick.css',__FILE__));
		wp_enqueue_style('jq-timepicker-style',plugins_url('/css/jquery.ui.timepicker.css',__FILE__));
		//wp_enqueue_style('aja-meta-boxes-css',plugins_url('css/ajax-meta-boxes.css',__FILE__),array(),'1.0.0','all');

		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('colpick-js',plugins_url('/js/colpick.js',__FILE__));
		wp_enqueue_script('jq-timepicker',plugins_url('/js/jquery.ui.timepicker.js',__FILE__));
		wp_enqueue_script('jquery-maskedinput-script',plugins_url('/js/jquery.maskedinput.min.js',__FILE__),array('jquery'),'1.3.1',true);
		wp_enqueue_script('jq-validator-script',plugins_url('/js/jquery.validator.js',__FILE__),array('jquery'),'1.0.0',true);
		wp_enqueue_script('mdw-cms-js',plugins_url('/js/functions.js',__FILE__),array('jquery'),'1.0.0',true);
		wp_enqueue_script('duplicate-metabox-fields',plugins_url('js/duplicate-metabox-fields.js',__FILE__),array('jquery'),'1.0.2');

		//wp_enqueue_script('metabox-duplicator',plugins_url('/js/metabox-duplicator.js',__FILE__),array('jquery'),'0.1.0',true);
		//wp_enqueue_script('metabox-remover',plugins_url('/js/metabox-remover.js',__FILE__),array('jquery'),'0.1.0',true);

		if (isset($post->ID)) :
			$post_id=$post->ID;
		else :
			$post_id=false;
		endif;

/*
		$options=array();

		$options['postID']=$post_id;

		if (!empty($this->config)) :
			foreach ($this->config as $config) :
				//if ($config['duplicate']) :
					$options[]=array(
						'metaboxID' => $config['mb_id'],
						'metaboxClass' => $config['mb_id'].'-meta-box',
						'metaboxTitle' => $config['title'],
						'metaboxPrefix' => $config['prefix'],
						'metaboxPostTypes' => $config['post_types'],
					);
				//endif;
			endforeach;
		endif;
*/

		//wp_localize_script('metabox-duplicator','options',$options);
		//wp_localize_script('metabox-remover','options',get_option($this->option_name));

		$mdwcmsjs=array(
			'dateFormat' => 'mm/dd/yy'
		);

		if (!empty($this->config) && $post_id) :
			foreach ($this->config as $config) :
				if (!in_array(get_post_type($post->ID),$config['post_types']))
					continue;

				foreach ($config['fields'] as $field) :
					if (isset($field['format']['value']) && !empty($field['format']['value'])) :
						if ($field['field_type']=='date') :
							$mdwcmsjs['dateFormat']=$field['format']['value'];
						endif;
					endif;
				endforeach;

			endforeach;
		endif;

		wp_localize_script('mdw-cms-js','wp_options',$mdwcmsjs);
	}

	/**
	 * register_scripts_styles function.
	 *
	 * @access public
	 * @return void
	 */
	function register_scripts_styles() {
		wp_enqueue_style('custom-video-js_css',plugins_url('/css/custom-video-js.css',__FILE__));

		wp_enqueue_script('video-js_js','//vjs.zencdn.net/4.2/video.js',array(),'4.2', true);
		wp_enqueue_style('video-js_css','//vjs.zencdn.net/4.2/video-js.css',array(),'4.2');
	}

	/**
	 * check_config_prefix function.
	 *
	 * makes sure our prefix starts with '_'
	 *
	 * @access public
	 * @param bool $config (default: false)
	 * @param bool $prefix (default: false)
	 * @return $prefix
	 */
	function check_config_prefix($prefix=false) {
		if (!$prefix)
			return false;

		if (substr($prefix,0,1)!='_')
			$prefix='_'.$prefix;

		return $prefix;
	}

	/**
	 * autoloads our helper classes and functions
	 * @param string $class_name - the name of the field/class to include
	**/
	private function autoload_class($filename) {
		require_once(plugin_dir_path(__FILE__).$filename.'.php');

		return new $filename;
	}

	/**
	 * creates the actual metabox itself using the id and title from the config file and attaches it to the post type
	 * callback: generate_meta_box_fields
	**/
	function mdwmb_add_meta_box() {
		global $config_id,$post;

		//$this->build_duplicated_boxes($post->ID); // must do here b/c we need the post id
		if (empty($this->config))
			return false;

		$this->add_custom_fields(); // method for adding custom metabox fields outside the cms //

		foreach ($this->config as $key => $config) :
			$config_id=$config['mb_id']; // for use in our classes function

/*
			if (isset($config['removable'])) :
				$removable=$config['removable'];
			else :
				$removable=false;
			endif;
*/

			foreach ($config['post_types'] as $post_type) :
		    add_meta_box(
		    	$config['mb_id'],
		      __($config['title'],'Upload_Meta_Box'),
		      array($this,'generate_meta_box_fields'),
		      $post_type,
		      'normal',
		      'high',
		      array(
		      	'config_key' => $key,
						//'duplicate' => $config['duplicate'],
						'meta_box_id' => $config['mb_id'],
						//'removable' => $removable,
						'post_id' => $post->ID
		      )
		    );

		    //if ($config['duplicate'])
			    //add_filter('postbox_classes_'.$post_type.'_'.$config['id'],array($this,'add_meta_box_classes'));

	    endforeach;
    endforeach;
	}

	/**
	 * adds classes to our meta box
	**/
/*
	function add_meta_box_classes($classes=array()) {
		global $config_id;

		$classes[]='dupable';
		$classes[]=$config_id.'-meta-box';

		return $classes;
	}
*/

	/**
	 * cycles through the fields (set in add_field)
	 * calls the generate_field() function
	**/
	function generate_meta_box_fields($post,$metabox) {
		$html=null;
		$this->fields=null; // this needs to be adjusted for legacy v 1.1.8
		$row_counter=1;

		wp_enqueue_script('umb-admin',plugins_url('/js/metabox-media-uploader.js',__FILE__),array('jquery'));

		wp_nonce_field(plugin_basename( __FILE__ ),$this->nonce);

		// because our legacy and current setups can be different, we need this function to do our post fields //
		$this->add_post_fields($this,$metabox,$post->ID);

		$html.='<div class="mdw-cms-meta-box umb-meta-box">';

			foreach ($this->config as $config) :

				if ($metabox['args']['meta_box_id']==$config['mb_id']) :

					if (!empty($config['fields'])) :
						$this->add_fields_array($config['fields'],$config['mb_id']);
					endif;

				endif;

			endforeach;

			// output all of our fields //
			if (isset($this->fields)) :

				// sort fields by order //
				usort($this->fields, function ($a, $b) {
					if (function_exists('bccomp')) :
						return bccomp($a['order'], $b['order']);
					else :
						return strcmp($a['order'], $b['order']);
					endif;
				});

				foreach ($this->fields as $field) :
					$classes=$field['id'].' type-'.$field['type'];

					if ($field['duplicate'])
						$classes.=' clone';

					$html.='<div id="meta-row-'.$row_counter.'" class="meta-row '.$classes.'" data-input-id="'.$field['id'].'" data-field-type="'.$field['type'].'" data-field-order="'.$field['order'].'">';
						$html.='<label for="'.$field['id'].'">'.$field['label'].'</label>';
						$html.=$this->generate_field($field);

						if ($field['duplicate'])
							$html.='<button type="button" class="ajaxmb-field-btn delete">Delete Field</button>'; // add delete btn

					$html.='</div>';
					$row_counter++;
				endforeach;
			endif;

/*
			if ($metabox['args']['duplicate'])
				$html.='<div class="dup-meta-box"><a href="#" data-meta-id="'.$metabox['args']['meta_box_id'].'">Duplicate Box</a></div>';

			if ($metabox['args']['removable'])
				$html.='<div class="remove-meta-box"><a href="#" data-meta-id="'.$metabox['args']['meta_box_id'].'" data-post-id="'.$post->ID.'">Remove Box</a></div>';
*/
			$html.='<input type="hidden" id="mdw-cms-metabox-id" name="mdw-cms-metabox-id" value="'.$metabox['args']['meta_box_id'].'" />';
			$html.='<input type="hidden" id="mdw-cms-config-key" name="mdw-cms-config-key" value="'.$metabox['args']['config_key'].'" />';
			$html.='<input type="hidden" id="mdw-cms-post-id" name="mdw-cms-post-id" value="'.$metabox['args']['post_id'].'" />';
		$html.='</div>';

		echo $html;
	}

	/**
	 * generates the input box of each meta field
	 * uses a switch case to determine which field to output (default is text)
	 * @param array $args (set in the add_field() function via the add_field() function)
	**/
	function generate_field($args) {
		global $post;

		$html=null;
		$values=get_post_custom($post->ID);
		$classes='field-input';
		$description=null;
		$description_visible=false;
		$format=false;

		if (isset($values[$args['id']][0])) :
			$value=$values[$args['id']][0];
		else :
			$value=null;
		endif;

		if (!empty($args['field_description']))
			$description=$args['field_description'];

		if (!empty($args['format']))
			$format=$args['format'];

		switch ($args['type']) :
			case 'address':
				$line1=null;
				$line2=null;
				$city=null;
				$state=null;
				$zip=null;
				$country='US';

				if (isset($value))
					extract(unserialize($value));

				$html.='<div class="address-wrap">';
					$html.='<div class="line-1">';
						$html.='<input type="text" class="'.$classes.'" name="'.$args['id'].'[line1]" value="'.$line1.'" />';
					$html.='</div>';
					$html.='<div class="line-2">';
						$html.='<input type="text" class="'.$classes.'" name="'.$args['id'].'[line2]" value="'.$line2.'" />';
					$html.='</div>';
					$html.='<div class="city">';
						$html.='<span>City</span><input type="text" class="'.$classes.'" name="'.$args['id'].'[city]" value="'.$city.'" />';
					$html.='</div>';
					$html.='<div class="state">';
						$html.='<span>State/Province</span><input type="text" class="'.$classes.'" name="'.$args['id'].'[state]" value="'.$state.'" />';
					$html.='</div>';
					$html.='<div class="zip">';
						$html.='<span>Postal Code</span><input type="text" class="'.$classes.'" name="'.$args['id'].'[zip]" value="'.$zip.'" />';
					$html.='</div>';
					$html.='<div class="country">';
						$html.='<span>Country</span>'.$this->get_countries_dropdown($args['id'].'[country]',$country);
					$html.='</div>';
				$html.='</div>';
				break;
			case 'checkbox':
				$html.='<input type="checkbox" class="'.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" '.checked($value,'on',false).' />';
				break;
			case 'colorpicker' :
				$html.='<input type="text" class="colorPicker" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'date':
				$html.='<input type="text" class="mdw-cms-datepicker" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'date-time':
				//$html.='<input type="text" class="datepicker" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				//$html.='<input type="text" class="timepicker" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'email' :
				$html.='<input type="text" class="email validator '.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'media':
				$html.='<input id="'.$args['id'].'" class="uploader-input regular-text" type="text" name="'.$args['id'].'" value="'.$value.'" />';
				$html.='<input class="uploader button" name="'.$args['id'].'_button" id="'.$args['id'].'_button" value="Upload" />';
				$html.='<input type="hidden" name="_name" value="'.$args['id'].'" />';

				if (!$description_visible) :
					$html.='<div class="description field_description">'.$description.'</div>';
					$description_visible=true;
				endif;

				if ($value) :
					$html.='<div class="mdw-cms-meta-box-thumb umb-media-thumb">';
						$attr=array('src' => $value);
						$thumbnail=get_the_post_thumbnail($post->ID,'thumbnail',$attr);
						$attachment_id=$this->get_attachment_id_from_url($value);

						if (get_post_mime_type($attachment_id)!='image') :
							$html.=wp_get_attachment_image($attachment_id,'thumbnail',true);
						else :
							$html.=$thumbnail;
						endif;
						$html.='<p><a class="remove" data-type-img-id="'.$args['id'].'" href="#">Remove</a></p>';
					$html.='</div>';
				endif;

				break;
			case 'phone':
				$html.='<input type="text" class="phone '.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'radio':
				//$html.='<input type="radio" class="'.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" '.checked($value,'on',false).' /> '.$value;
				break;
			case 'select' :
				$html.='<select name="'.$args['id'].'" id="'.$args['id'].'">';
					$html.='<option>Select One</option>';
					if (isset($args['options']) && is_array($args['options'])) :
						foreach ($args['options'] as $option) :
							$html.='<option value="'.$option['value'].'" '.selected($value,$option['value'],false).'>'.$option['name'].'</option>';
						endforeach;
					endif;
				$html.='</select>';
				break;
			case 'text' :
				$html.='<input type="text" class="'.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'textarea':
				$html.='<textarea class="textarea '.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'">'.$value.'</textarea>';
				break;
			case 'timepicker' :
				$html.='<input type="text" class="timepicker" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'url':
				$html.='<input type="text" class="url validator '.$classes.'" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
				break;
			case 'wysiwyg':
				$settings=array(
					//'media_buttons' => false,
					//'textarea_rows' => 10,
					//'quicktags' => false
				);

				$html.=$this->mdwm_wp_editor($value,$args['id'],$settings);
				break;
			case 'media_images' :
				$images=$this->get_all_media_images();
				$value_arr=unserialize($value);

				$html.='<select multiple size="10" name="'.$args['id'].'[]" id="'.$args['id'].'">';

						foreach ($images as $image) :
							$selected=null;

							if (is_array($value_arr) && !empty($value_arr) && in_array($image->ID,$value_arr))
								$selected='selected="selected"';

							$html.='<option value="'.$image->ID.'" '.$selected.'>'.$image->post_title.'</option>';
						endforeach;

				$html.='</select>';

				break;
			case 'custom' :
				if (is_serialized($value)) :
					$values=unserialize($value);
				else :
					$values=$value;
				endif;

				if (!is_array($values))
					$values=array($values);

				$html.=apply_filters('add_mdw_cms_metabox_custom_input-'.$args['id'],$args['id'],$values);
				break;
			default:
				$html.='<input type="text" name="'.$args['id'].'" id="'.$args['id'].'" value="'.$value.'" />';
		endswitch;

		if ($format)
			$html.='<span class="format">('.$format.')</span>';

		if ($args['repeatable'])
			$html.='<button type="button" class="ajaxmb-field-btn duplicate">Duplicate Field</button>';

		if (!$description_visible)
			$html.='<div class="description field_description">'.$description.'</div>';

		return $html;
	}

	/**
	 * a public function that allows the user to add a field to the meta box
	 * @param array $args
	 							id (field id)
	 							type (type of input field)
	 							label (for field)
	 							value (of field)
	 * because we allow multiple configs now, we must use legacy support (1 config) and expand to allow for multi configs (pre 1.1.8)
	**/
	public function add_field($args,$meta_id=false) {
		if (count($this->config)==1) :
			$prefix=$this->config[0]['prefix'];
		else :
			if ($meta_id) :
				foreach ($this->config as $config) :
					if (isset($config['id']) && $config['id']==$meta_id) :
						$prefix=$config['prefix'];
					elseif (isset($config['mb_id']) && $config['mb_id']==$meta_id) :
						$prefix=$config['prefix'];
					endif;
				endforeach;
			endif;
		endif;

		$new_field=array('id' => '', 'type' => 'text', 'label' => 'Text Box', 'value' => '', 'order' => 0);
		$new_field=array_merge($new_field,$args);

		if (is_numeric($new_field['id']))
			$new_field['id']=$this->generate_field_id($prefix,$new_field['label'],$new_field['id']);

		$this->fields[$new_field['id']]=$new_field;
	}

	/**
	 * generate_field_id function.
	 *
	 * @access public
	 * @param bool $prefix (default: false)
	 * @param bool $label (default: false)
	 * @param bool $field_id (default: false)
	 * @return $id
	 */
	function generate_field_id($prefix=false,$label=false,$field_id=false) {
		$id=null;

		if (!$prefix || !$label)
			return false;

		$prefix=$this->check_config_prefix($prefix);

		if (empty($label)) :
			$id=$prefix.'_'.$field_id;
		else :
			$id=$prefix.'_'.strtolower($this->clean_special_chars($label));
		endif;

		return $id;
	}

	/**
	 * a variation of the add_fields function
	 * this allows us to generate our fields with a passed array
	 * added 1.1.8
	**/
	function add_fields_array($arr,$meta_id) {
		$fields_counter=0;
		foreach ($arr as $id => $values) :
			$options=false;
			$repeatable=0;
			$order=0;
			$description=null;
			$format=null;

			if (isset($values['options']))
				$options=$values['options'];

			if (isset($values['repeatable']))
				$repeatable=1;

			if (isset($values['order']))
				$order=$values['order'];

			if (isset($values['field_description']))
				$description=$values['field_description'];

			if (isset($values['format']['value']))
				$format=$values['format']['value'];

			$args=array(
				'id' => $id,
				'type' => $values['field_type'],
				'label' => $values['field_label'],
				'order' => $order,
				'options' => $options,
				'repeatable' => $repeatable,
				'duplicate' => 0,
				'format' => $format,
				'field_description' => $description
			);

			$this->add_field($args,$meta_id);
			$fields_counter++;
		endforeach;
	}

	/**
	 * add_post_fields function.
	 *
	 * @access public
	 * @param bool $arr (default: false)
	 * @param bool $metabox (default: false)
	 * @param bool $post_id (default: false)
	 * @return void
	 */
	function add_post_fields($arr=false,$metabox=false,$post_id=false) {
		if (!$arr || empty($arr) || !$metabox || !$post_id)
			return false;

		foreach ($this->config as $config) :
			if ($metabox['args']['meta_box_id']==$config['mb_id']) :
				if (isset($config['post_fields']) && !empty($config['post_fields'])) :
					foreach ($config['post_fields'] as $post_field) :
						if ($post_field['post_id']==$post_id) :
							$args=array(
								'id' => $post_field['field_id'],
								'type' => $post_field['field_type'],
								'label' => $post_field['field_label'],
								'order' => $post_field['order'],
								'options' => 0,
								'repeatable' => 0,
								'duplicate' => 1
							);
							$this->add_field($args,$post_field['metabox_id']);
						endif;
					endforeach;
				endif;
			endif;
		endforeach;
	}

	/**
	 * save_custom_meta_data function.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @return void
	 */
	public function save_custom_meta_data($post_id) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our nonce isn't there, or we can't verify it, bail
		if (!isset($_POST[$this->nonce]) || !wp_verify_nonce($_POST[$this->nonce],plugin_basename(__FILE__))) return;

		// if our current user can't edit this post, bail
		if (!current_user_can('edit_post',$post_id)) return;

		//$this->build_duplicated_boxes($post_id); // must do here again b/c this action is added before we have all the info
		$this->add_custom_fields(); // method for adding custom metabox fields outside the cms // -- this is added here as well for proper saving

		// cycle through config fields and find matches //
		foreach ($this->config as $config) :
			$data=null;
			$prefix=$config['prefix'];

			foreach ($config['fields'] as $id => $field) :
				$field_id=$prefix.'_'.$id;

				if (isset($field['field_id']))
					$field_id=$field['field_id'];

				if (isset($_POST[$field_id])):
					$data=$_POST[$field_id]; // submitted value //
				endif;

				// fix notices on unchecked check boxes //
				//if (get_post_meta($post_id, $field['id']) == "") :
				//	add_post_meta($post_id, $field['id'], $data, true);
				//elseif ($data != get_post_meta($post_id, $field['id'], true)) :

				if ($data=='') :
					delete_post_meta($post_id, $field_id, get_post_meta($post_id, $field_id, true));
				else :
					update_post_meta($post_id, $field_id, $data);
				endif;

			endforeach;

			// if there's specific post fields, look for those //
			if (isset($config['post_fields']) && !empty($config['post_fields'])) :
				foreach ($config['post_fields'] as $post_field) :
					if ($post_field['post_id']==$post_id) :
						$field_id=$post_field['field_id'];

						if (isset($_POST[$field_id])):
							$data=$_POST[$field_id]; // submitted value //
						endif;

						if ($data=='') :
							delete_post_meta($post_id, $field_id, get_post_meta($post_id, $field_id, true));
						else :
							update_post_meta($post_id, $field_id, $data);
						endif;

					endif;
				endforeach;
			endif;

		endforeach;
exit;
	}

	function duplicate_meta_box() {
		$this->save_duplicate_meta_box($_POST['postID']);

		exit;
	}

	function remove_duplicate_meta_box() {
		$option=get_option($this->option_name);

		// check for option key //
		if (!isset($_POST['optionKey']))
			return false;

		$option_to_remove=$option[$_POST['optionKey']];

		// do some quick checks to make sure all is ok //
		if ($option_to_remove['post_id']!=$_POST['postID'])
			return false;

		if ($option_to_remove['id']!=$_POST['metaID'])
			return false;

		// remove post meta //
		foreach ($option_to_remove['fields'] as $id => $field) :
			$meta_key=$option_to_remove['prefix'].'_'.$id;
			if ($meta_key)
				delete_post_meta($_POST['postID'],$meta_key);
		endforeach;

		unset($option[$_POST['optionKey']]); // remove from option
		$option=array_values($option); // reset keys

		update_option($this->option_name,$option); // update our option

		return true;

		exit;
	}

	/**
	 * saves our meta field data when we are duplicating a field
	 * the field needs to be saved so that our class can be updated with the apropriate fields
	 * it's done via ajax, so the users should not see anything
	**/
	public function save_duplicate_meta_box($post_id) {
		// Bail if we're doing an auto save
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		// if our current user can't edit this post, bail
		if (!current_user_can('edit_post',$post_id)) return;

		$data=null;
		$option=false;
		$option_arr=array();
		$current_option_arr=array();
		$new_option_arr=array();
		$prefix=$_POST['prefix'];

		foreach ($_POST['fields'] as $id => $field) :
			$field_id=$prefix.'_'.$id;
			$data='';
			//update_post_meta($post_id,$field_id,$data);
		endforeach;

		// build our option so that we know we have duped boxes //
		$option=$this->option_name;
		$option_arr=array(
			'post_id' => $_POST['postID'],
			'id' => $_POST['id'],
			'title' => $_POST['title'],
			'prefix' => $_POST['prefix'],
			'post_types' => $_POST['post_types'],
			'duplicate' => 0,
			'fields' => $_POST['fields'],
		);

/*
print_r($option_arr);

		if ($option)
			$current_option_arr=get_option($option);

		if ($current_option_arr) :
			array_push($option_arr,$current_option_arr);
		else :
			$new_option_arr[0]=$option_arr;
			$option_arr=$new_option_arr;
		endif;

print_r($option_arr);

		if ($option)
			update_option($option,$option_arr);
*/
	}

	/**
	 * setup our config with defaults and adjusments
	**/
	function setup_config($configs=array()) {
		$ran_string=substr(substr("abcdefghijklmnopqrstuvwxyz",mt_rand(0,25),1).substr(md5(time()),1),0,5);
		$default_config=array(
			'mb_id' => 'mdwmb_'.$ran_string,
			'title' => 'Default Meta Box',
			'prefix' => '_mdwmb',
			'post_types' => 'post,page',
			//'duplicate' => 0,
			//'fields' => array(), // for legacy support (pre 1.1.8)
			'post_fields' => array()
		);

		if (empty($configs))
			return false;

		foreach ($configs as $key => $config) :
			$config=array_merge($default_config,$config);

			if (!is_array($config['post_types'])) :
				$config['post_types']=explode(",",$config['post_types']);
			endif;

			$config['prefix']=$this->check_config_prefix($config['prefix']); // makes sure our prefix starts with '_'
			$configs[$key]=$config;
		endforeach;

		return $configs;
	}

/*
	function build_duplicated_boxes($post_id=false) {
		if (!$post_id)
			return false;

		$option_arr=get_option($this->option_name);

		if (!count($option_arr) || !$option_arr)
			return false;

		foreach ($option_arr as $option) :
			if ($option['post_id']==$post_id) :
				$option['removable']=true; // allows us to have a remove button
				array_push($this->config,$option);
			endif;
		endforeach;

		return;
	}
*/

	/**
	 * clean_special_chars function.
	 *
	 * removes all special chars from a string
	 *
	 * @access public
	 * @param mixed $string
	 * @return $string
	 */
	function clean_special_chars($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

		return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	}

	/**
	 * ajax_duplicate_metabox_field function.
	 *
	 * adds specific fields to indvidual posts/pages via our duplicate field button
	 * because we use a config array, when the field is duplicated via js, it needs to be added
	 * however, the dup fields apply to individual posts only, so we have an array for them
	 *
	 * @access public
	 * @return void
	 */
	function ajax_duplicate_metabox_field() {
// todo: check empty
		$arr=array(
			'post_id' => $_POST['post_id'],
			'config_key' => $_POST['config_key'], // may not be needed
			'metabox_id' => $_POST['metabox_id'],
			'field_type' => $_POST['field_type'],
			'field_label' => $_POST['field_label'],
			'options' => array(),
			'field_id' => $_POST['field_id'],
			'order' => $_POST['order']
		);

		// check for dups and replace if found //
		$dup_flag=false;
		if (isset($this->config[$_POST['config_key']]['post_fields']) && !empty($this->config[$_POST['config_key']]['post_fields'])) :
			foreach ($this->config[$_POST['config_key']]['post_fields'] as $key => $post_field) :
				if ($post_field['field_id']==$arr['field_id']) :
					$this->config[$_POST['config_key']]['post_fields'][$key]=$arr;
					$dup_flag=true;
				endif;
			endforeach;
		endif;

		// insert if no dup found //
		if (!$dup_flag)
			$this->config[$_POST['config_key']]['post_fields'][]=$arr;

		if (update_option('mdw_cms_metaboxes',$this->config)) :
			echo true;
		else :
			echo false;
		endif;

		wp_die();
	}

	function ajax_remove_duplicate_metabox_field() {
// todo: check empty
		foreach ($this->config[$_POST['config_key']]['post_fields'] as $key => $post_field) :
			if ($post_field['field_id']==$_POST['field_id'])
				unset($this->config[$_POST['config_key']]['post_fields'][$key]);
		endforeach;

		$this->config[$_POST['config_key']]['post_fields']=array_values($this->config[$_POST['config_key']]['post_fields']);

		if (update_option('mdw_cms_metaboxes',$this->config)) :
			echo 'option updated';
		else :
			echo 'option failed to update (may be due to no fields being reomved)';
		endif;

		wp_die();
	}

	/**
	 * get_all_media_images function.
	 *
	 * @access public
	 * @return void
	 */
	function get_all_media_images() {
		$query_images_args = array(
			'posts_per_page' => -1,
			'post_type' => 'attachment',
			'post_mime_type' =>'image',
			'post_status' => 'inherit',
		);
		$query_images = new WP_Query( $query_images_args );

		$images = array();
		foreach ( $query_images->posts as $image) {
			$images[]=$image;
		}

		return $images;
	}

	/**
	 * get_attachment_id_from_url function.
	 *
	 * @access public
	 * @param string $attachment_url (default: '')
	 * @return void
	 */
	function get_attachment_id_from_url( $attachment_url = '' ) {

		global $wpdb;
		$attachment_id = false;

		// If there is no url, return.
		if ( '' == $attachment_url )
			return;

		// Get the upload directory paths
		$upload_dir_paths = wp_upload_dir();

		// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
		if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {

			// If this is the URL of an auto-generated thumbnail, get the URL of the original image
			$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );

			// Remove the upload path base directory from the attachment URL
			$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );

			// Finally, run a custom database query to get the attachment ID from the modified attachment URL
			$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );

		}

		return $attachment_id;
	}

	/**
	 * add_custom_fields function.
	 *
	 * method for adding custom metabox fields outside the cms
	 *
	 * @access public
	 * @return void
	 */
	function add_custom_fields() {
		foreach ($this->config as $key => $config) :

			$extra_fields=array();
			$extra_fields=apply_filters('add_mdw_cms_metabox_custom_fields-'.$config['mb_id'],$extra_fields,$config['prefix']);
			$extra_field_defaults=array(
				'field_type' => 'custom',
				'field_label' => 'Extra Field',
				'options' => array(),
				'field_description' => null,
				'order' => 99999,
				'field_id' => $this->generate_field_id($config['prefix'],'Extra Field')
			);

			if (empty($extra_fields))
				continue;

			foreach ($extra_fields as $extra_field) :
				$args=array_replace_recursive($extra_field_defaults,$extra_field);

				$this->config[$key]['fields'][]=$args;
			endforeach;

		endforeach;
	}

	/**
	 * mdwm_wp_editor function.
	 *
	 * @access protected
	 * @param mixed $content
	 * @param mixed $editor_id
	 * @param mixed $settings
	 * @return void
	 */
	protected function mdwm_wp_editor($content,$editor_id,$settings) {
		ob_start(); // Turn on the output buffer
		wp_editor($content,$editor_id,$settings); // Echo the editor to the buffer
		$editor_contents = ob_get_clean(); // Store the contents of the buffer in a variable

		return $editor_contents;
	}

	/**
	 * get_post_meta_advanced function.
	 *
	 * generates a preformatted get post meta field
	 *
	 * @access public
	 * @param mixed $post_id
	 * @param string $key (default: '')
	 * @param bool $single (default: false)
	 * @param string $type (default: '')
	 * @return void
	 *
	 * Not Used v2.0.9
	 *
	 */
	public function get_post_meta_advanced($post_id,$key='',$single=false,$type='') {
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

	/**
	 * get_countries_dropdown function.
	 *
	 * @access public
	 * @param string $name (default: 'country')
	 * @param string $selected (default: 'US')
	 * @return void
	 */
	public function get_countries_dropdown($name='country',$selected='US') {
		$html=null;

		$html.='<select name="'.$name.'">';
			$html.='<option value="0">Select Country</option>';
			foreach ($this->countries as $id => $country) :
				$html.='<option value="'.$id.'" '.selected($selected,$id,false).'>'.$country.'</option>';
			endforeach;
		$html.='</select>';

		return $html;
	}

} // end class

/**
 * this loads our load plugin first so that the meta boxes can be used throughout the site
**/
/*
add_action('plugins_loaded','load_plugin_first');
function load_plugin_first() {
	$path = str_replace( WP_PLUGIN_DIR . '/', '', __FILE__ );
	if ( $plugins = get_option( 'active_plugins' ) ) {
		if ( $key = array_search( $path, $plugins ) ) {
			array_splice( $plugins, $key, 1 );
			array_unshift( $plugins, $path );
			update_option( 'active_plugins', $plugins );
		}
	}
}
*/
$MDWMetaboxes = new MDWMetaboxes();
?>
