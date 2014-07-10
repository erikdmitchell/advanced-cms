<?php
include_once(plugin_dir_path(__FILE__).'lib/bitbucket-updater.php');

// begin auto updater stuff //
if (is_admin()) :
	$config=array(
		'slug' => plugin_basename(__FILE__),
		'admin_username' => 'millerdesign',
		'repository_slug' => 'mdw-cms',
		'repository_username' => 'millerdesign',
		'plugin_file' => 'mdw-cms.php',
		'proper_folder_name' => 'mdw-cms',
		'bb_username' => 'MDWCMSOAuth',
		'bb_password' => '', // not used
		'bb_oauth_key' => 'FfwUWHVGyYM6kW6dmq',
		'bb_oauth_secret' => 'tFhcqc9MBPmPghmqPdg6Fb8Xff8vj5kh',
		'sslverify' => true, // not used
		'requires' => '3.0',
		'tested' => '3.9',
		'readme' => 'README.md',
		'access_token' => '',	// not used
	);
	new WPBitBucketUpdater($config);	
endif;
?>