WordPress BitBucket Updater
===========

This plugin is our automatic updater for (Private) BitBucket hosted repositories. It is designed to be implemented within itself.  

Usage Instructions
===========

This plugin is designed to be integrated into other plugins. Once it's included and called within a plugin, it will provide the ability to host a WordPress plugin in a private BitBucket repository and have that plugin automatically update.

In order for this plugin to work, there's two components: the integration with a plugin and the updating of the BitBucket repository.

### Plugin Integration

The bb-wp-plugin.php contains all you need to integrate the updater into your plugin.

	* Copy the lib folder over.
	* Place the bb-wp-plugin.php contents in your plugin, or a file within the plugin.
	* Edit the $config array.
	* If for some reason the lib folder is moved, the includ_once path must be adjusted.
	
### Config Array Components
	
	Note: anything marked as not used will either be integrated or reomved by version 0.1.6.
	
	Here's the BitBucket url: https://bitbucket.org/erikdmitchell/bitbucket-wp-plugin-tester.
	
	Anything marked url is takend from this.
	
	* 'slug' => plugin_basename(__FILE__),
	*	'admin_username' => 'erikdmitchell', - url
	*	'repository_slug' => 'bitbucket-wp-plugin-tester', - url
	*	'repository_username' => 'erikdmitchell', - url
	*	'plugin_file' => 'bb-wp-plugin.php', - this is the actual plugin file where the plugin information is, we need this to trigger the updater
	*	'proper_folder_name' => 'bb-wp-plugin',
	*	'bb_username' => 'MDWMobileOAuth', - see OAuth notes below
	*	'bb_password' => '', - not used
	*	'bb_oauth_key' => 'xTqNxD7HjWsvbJW934', - see OAuth notes below
	*	'bb_oauth_secret' => 'kg6FweUhG9Fy59y2jkqcgS8CEdxBwAva', - see OAuth notes below
	*	'sslverify' => true, - not used
	*	'requires' => '3.0', - the minimum require WordPress version for the plugin.
	*	'tested' => '3.3', - the WordPress version the plugin has been tested too.
	*	'readme' => 'README.md', - the plugins readme file name
	*	'access_token' => '',	- not used
	
### OAuth on BitBucket

	Click here and follow step one to setup the neccessary components. (https://confluence.atlassian.com/display/BITBUCKET/OAuth+on+Bitbucket#OAuthonBitbucket-Step1.CreateanOAuthkeyandsecret "OAuth on BitBucket")
	Once you set it up, you'll have OAuth information the config array needs.

### Updating BitBucket Repository

When the master branch (currently it musted be named master) is updated on BitBucket and the plugin Version parameter (set in the config: 'plugin_file' => 'FILE.php') is changed, it will trigger the automatic update.

That's all it takes.

Changelog
===========

### 1.0.4
	* Fixed issues #1 and #2.
	
	* Added request_api_zip() to the BBA class. 
	
	* Modified get_zip_url() in BB class. We now use OAuth to get the zip file.
	
	* Removed get_repo() function from BB class.
	
	* Updated readme file for usage information.
	
### 1.0.3
	* Added a folder checker for updates.
	* Added some credits and minor details.

	* Removed junk code for/from testing/debugging.

	* Updated version and fixed VERSION constant in updater file.
 
Credits
===========

This plugin is built and maintained by [Miller Designworks](http://millerdesignworks.com "Miller Designworks")

License
===========

GPL 2 I think
