<?php
// Prevent loading this file directly and/or if the class is already defined
if ( ! defined( 'ABSPATH' ) || class_exists( 'WP_BitBucket_Updater' ) || class_exists( 'WP_BitBucket_Updater' ) )
	return;

class WPBitBucketUpdater {

	public $version='0.1.3';

	var $config;
	var $bb;
	var $missing_config;

	private $bitbucket_data;

	/**
	 * Class Constructor
	 *
	 * @since 1.0
	 * @param array $config the configuration required for the updater to work
	 * @see has_minimum_config()
	 * @return void
	 */
	public function __construct( $config = array() ) {
		include_once(dirname(__FILE__).'/class.BitBucket.php');

		$defaults = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => dirname( plugin_basename( __FILE__ ) ),
			'bb_passwrod' => '', // not used
			'sslverify' => true, // not used
			'access_token' => '', // not used
		);

		$this->config = wp_parse_args( $config, $defaults );
		
		$this->bb = new BitBucket($this->config['bb_username'],$this->config['bb_password'],$this->config['bb_oauth_key'],$this->config['bb_oauth_secret']);
		
		// if the minimum config isn't set, issue a warning and bail
		if ( ! $this->has_minimum_config() ) {
			$message = 'The GitHub Updater was initialized without the minimum required configuration, please check the config in your plugin. The following params are missing: ';
			$message .= implode( ',', $this->missing_config );
			_doing_it_wrong( __CLASS__, $message , $this->version );
			return;
		}
		
		$this->set_defaults();	

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'api_check' ) );

		// Hook into the plugin details screen
		add_filter( 'plugins_api', array( $this, 'get_plugin_info' ), 10, 3 );
		add_filter( 'upgrader_post_install', array( $this, 'upgrader_post_install' ), 10, 3 );

		// set timeout
		add_filter( 'http_request_timeout', array( $this, 'http_request_timeout' ) );
	}

	public function has_minimum_config() {

		$this->missing_config = array();

		$required_config_params = array(
			'admin_username',
			'repository_slug',
			'repository_username',
			'plugin_file',
			'bb_username',
			'bb_oauth_key',
			'bb_oauth_secret',
			'requires',
			'tested',
			'readme',
		);

		foreach ( $required_config_params as $required_param ) {
			if ( empty( $this->config[$required_param] ) )
				$this->missing_config[] = $required_param;
		}

		return ( empty( $this->missing_config ) );
	}

	/**
	 * Check wether or not the transients need to be overruled and API needs to be called for every single page load
	 *
	 * @return bool overrule or not
	 */
	public function overrule_transients() {
		return ( defined( 'WP_GITHUB_FORCE_UPDATE' ) && WP_GITHUB_FORCE_UPDATE );
	}

	/**
	 * Callback fn for the http_request_timeout filter
	 *
	 * @since 1.0
	 * @return int timeout value
	 */
	public function http_request_timeout() {
		return 2;
	}

	/**
	 * Set defaults
	 *
	 * @since 1.0
	 * @return void
	 */
	public function set_defaults() {
		//if ( ! isset( $this->config['repository_info'] ) )
			//$this->config['repository_info'] = $this->bb->get_repository_info($this->config['repository_slug'],$this->config['admin_username']);

		if ( ! isset( $this->config['zip_url'] ) ) // THIS NEEDS TO BE CHANGED
			$this->config['zip_url'] = $this->bb->get_zip_url($this->config['repository_slug'],$this->config['repository_username'],$this->config['proper_folder_name']);
			
		if ( ! isset( $this->config['new_version'] ) )
			$this->config['new_version'] = $this->get_new_version();

		if ( ! isset( $this->config['last_updated'] ) )
			$this->config['last_updated'] = $this->get_date();

		if ( ! isset( $this->config['description'] ) )
			$this->config['description'] = $this->get_description();

		$plugin_data = $this->get_plugin_data();
		if ( ! isset( $this->config['plugin_name'] ) )
			$this->config['plugin_name'] = $plugin_data['Name'];

		if ( ! isset( $this->config['version'] ) )
			$this->config['version'] = $plugin_data['Version'];

		if ( ! isset( $this->config['author'] ) )
			$this->config['author'] = $plugin_data['Author'];

		if ( ! isset( $this->config['homepage'] ) )
			$this->config['homepage'] = $plugin_data['PluginURI'];

		if ( ! isset( $this->config['readme'] ) ) // THIS MAY NO LONGER BE NEEDED SINCE WE READ FROM THE PLUGIN FILE
			$this->config['readme'] = 'README.md';		
		
		$this->config['proper_folder_name']=$this->check_folder_name($this->config['proper_folder_name']); // just double checking our folder name -- trying to account for user changes	
	}

	/**
	 * Get New Version from bitbucket
	 *
	 * @since 1.0
	 * @return int $version the version number
	 */
	public function get_new_version() {
		$version = get_site_transient( $this->config['slug'].'_new_version' );

		//if (  !isset( $version ) || !$version || '' == $version  ) {
			$raw_response=$this->bb->get_single_file($this->config['repository_slug'],$this->config['plugin_file'],$this->config['admin_username']);
			
			if (!$raw_response)
				$version=false;

			if (is_array($raw_response)) :
				if (!empty($raw_response['response'])) :
					$rr_arr=explode('*',$raw_response['response']);
					foreach ($rr_arr as $key => $value) :
						if (strpos($value,'Version')!==false) :		
							$version_arr=explode(':',$value);				
							$version=trim($version_arr[1]);
						endif;
					endforeach;
				endif;
			endif;
	
			/*
			if (is_array($raw_response)) :
				if (!empty($raw_response['response']))
					preg_match( '#^\s*Version\:\s*(.*)$#im', $raw_response['response'], $matches );
			endif;
			
			if ( empty( $matches[1] ) )
				$version = false;
			else
				$version = $matches[1];
			*/
			
			// refresh every 6 hours
			if ( false !== $version )
				set_site_transient( $this->config['slug'].'_new_version', $version, 60*60*6 );
		//}

		return $version;
	}

	/**
	 * Get BitBucket Data from the specified repository
	 *
	 * @since 1.0
	 * @return array $github_data the data
	 */
	public function get_bitbucket_data() {
		if ( isset( $this->bitbucket_data ) && ! empty( $this->bitbucket_data ) ) {
			$bitbucket_data = $this->bitbucket_data;
		} else {
			$bitbucket_data = get_site_transient( $this->config['slug'].'_bitbucket_data' );

			if ( $this->overrule_transients() || ( ! isset( $bitbucket_data ) || ! $bitbucket_data || '' == $bitbucket_data ) ) {
				$bitbucket_data = $this->bb->get_changesets_list($this->config['repository_slug'],$this->config['admin_username']);

				if (!$bitbucket_data)
					return false;

				$bitbucket_data = json_decode( $bitbucket_data['response'] );

				// refresh every 6 hours
				set_site_transient( $this->config['slug'].'_bitbucket_data', $bitbucket_data, 60*60*6 );
			}

			// Store the data in this class instance for future calls
			$this->bitbucket_data = $bitbucket_data;
		}

		return $bitbucket_data;
	}


	/**
	 * Get update date
	 *
	 * @since 1.0
	 * @return string $date the date
	 */
	public function get_date() {
		$_date = $this->get_bitbucket_data();
		return ( !empty( $_date->changesets[0]->timestamp ) ) ? date( 'Y-m-d', strtotime( $_date->changesets[0]->timestamp ) ) : false;
	}


	/**
	 * Get plugin description
	 *
	 * @since 1.0
	 * @return string $description the description
	 */
	public function get_description() {
		$_description = $this->get_bitbucket_data();
		return ( !empty( $_description->changesets[0]->message ) ) ? $_description->changesets[0]->message : false;
	}


	/**
	 * Get Plugin data
	 *
	 * @since 1.0
	 * @return object $data the data
	 */
	public function get_plugin_data() {
		include_once ABSPATH.'/wp-admin/includes/plugin.php';
		$data = get_plugin_data( WP_PLUGIN_DIR.'/'.$this->config['slug'] );
		return $data;
	}


	/**
	 * Hook into the plugin update check and connect to github
	 *
	 * @since 1.0
	 * @param object  $transient the plugin data transient
	 * @return object $transient updated plugin data transient
	 */
	public function api_check( $transient ) {
		// Check if the transient contains the 'checked' information
		// If not, just return its value without hacking it
		if ( empty( $transient->checked ) )
			return $transient;

		// check the version and decide if it's new
		$update = version_compare( $this->config['new_version'], $this->config['version'] );

		if ( 1 === $update ) {
			$response = new stdClass;
			$response->new_version = $this->config['new_version'];
			$response->slug = $this->config['proper_folder_name'];
			$response->url=$this->bb->get_zip_url($this->config['repository_slug'],$this->config['repository_username'],$this->config['proper_folder_name']);
			$response->package=$this->bb->get_zip_url($this->config['repository_slug'],$this->config['repository_username'],$this->config['proper_folder_name']);

			// If response is false, don't alter the transient
			if ( false !== $response )
				$transient->response[ $this->config['slug'] ] = $response;
		}

		return $transient;
	}


	/**
	 * Get Plugin info
	 *
	 * @since 1.0
	 * @param bool    $false  always false
	 * @param string  $action the API function being performed
	 * @param object  $args   plugin arguments
	 * @return object $response the plugin info
	 */
	public function get_plugin_info( $false, $action, $response ) {

		// Check if this call API is for the right plugin
		if ( !isset( $response->slug ) || $response->slug != $this->config['slug'] )
			return false;

		$response->slug = $this->config['slug'];
		$response->plugin_name  = $this->config['plugin_name'];
		$response->version = $this->config['new_version'];
		$response->author = $this->config['author'];
		$response->homepage = $this->config['homepage'];
		$response->requires = $this->config['requires'];
		$response->tested = $this->config['tested'];
		$response->downloaded   = 0;
		$response->last_updated = $this->config['last_updated'];
		$response->sections = array( 'description' => $this->config['description'] );
		$response->download_link = $this->config['zip_url'];

		return $response;
	}


	/**
	 * Upgrader/Updater
	 * Move & activate the plugin, echo the update message
	 *
	 * @since 1.0
	 * @param boolean $true       always true
	 * @param mixed   $hook_extra not used
	 * @param array   $result     the result of the move
	 * @return array $result the result of the move
	 */
	public function upgrader_post_install( $true, $hook_extra, $result ) {

		global $wp_filesystem;

		// Move & Activate
		$proper_destination = WP_PLUGIN_DIR.'/'.$this->config['proper_folder_name'];
		$wp_filesystem->move( $result['destination'], $proper_destination );
		$result['destination'] = $proper_destination;
		$activate = activate_plugin( WP_PLUGIN_DIR.'/'.$this->config['slug'] );

		// Output the update message
		$fail  = __( 'The plugin has been updated, but could not be reactivated. Please reactivate it manually.', 'github_plugin_updater' );
		$success = __( 'Plugin reactivated successfully.', 'github_plugin_updater' );
		echo is_wp_error( $activate ) ? $fail : $success;
		return $result;

	}
	
	function check_folder_name($proper_folder_name) {
		$current_folder_name=str_replace(plugins_url(), '', plugin_dir_url(dirname(__FILE__)));
		$current_folder_name=str_replace('/','',$current_folder_name);
		
		if ($current_folder_name!=$proper_folder_name)
			return $current_folder_name;
			
		return $proper_folder_name;
	}
}
?>
