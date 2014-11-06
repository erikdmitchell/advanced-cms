MDW CMS
===========

Adds cusomtized functionality to the site to make WordPress super awesome.  

Usage Instructions
===========

See BitBucket Wiki for details.
See the mdw-cms-demo.php for sample examples.

Changelog
===========

### 1.0.9
	Removed old BitBucket updater as it failed to work. Utilizing new, self hosted updater.
	
### 1.0.8
	Added Inflector class (Akelos PHP Application Framework) to handle pluralization of words.
	Added fields to metabox: colorpicker, time picker, select box.
	Added ablitty to duplicate meta box fields.
	
	Fixed issue with plural custom post types. Accounts for it and added ability to handle both via a word_type config option.
	
	Removed duplicate metabox. Functionality still exists, but we need to make this an option.
	Removed call for AJAX metaboxes. This class is great, but needs to be rolled in to primary class.

### 1.0.7
	Added legacy support for slider.
	Added git ignore file.
	Added more button and more button text to slider and config.

	Fixed meta box glitch, updated slider plugin. Now includes ability to limit slides and select caption type.

	We now have a sample config file that can be replaced by the user (similar to wp-config/wp-config-sample).
	Cleaned up sample file.

### 1.0.6
	Added our WP Bootstrap slider to the plugin. Can be called via shortcode. Sample shortcode is part of our admin page.
	Added the Social Media admin page ot the plugin. It's a seperate page from the CMS Settings page, but is still in Apperance.
	Added sample social media function to the Social Media class.
	Added upgrade functinoalty. Now, when updated, the plugin can be updated via the WP admin panel.

	Fixed a glitch where underscores (_) in post types were causing display issues.	
	
### 1.0.3
 * Added an admin options page for our custom code. Prevents overwriting of code on updates. -- This has been postponed do to more detailed setup requirements then I thought.
 * Added admin.css for use on our options page and plugin.
 * Added a new class: AJAXMetaBoxes, that allows dynamic creation of meta fields similar to the WP Custom Fields functions. -- not fully integrated yet
  
### 1.0.2
 * Added ability to create very basic, custom widgets.
 * Removed default data from plugin. Will incoroprate into seperate file.
 * Fixed $nonce issue in MDW Meta Boxes.
 * Added $post_id to (if (!current_user_can('edit_post',$post_id)) return;) in our save meta. The lack of id threw an error.
 * Added check_config_prefix($config) to meta boxes to ensure our prefix starts with an '_'.
 * Our admin columns now use a 'type' parameter for meta and taxonomy fields.
 * Added post_tag taxonomy to our custom post type.
 * Expanded our custom post type to include taxonomies and supports in our array.

### 1.0.1
 * Added to Git
 * Added mdw-meta-boxes to the inc folder. Allows us to use a simple custom meta box generator.
 
 * Fixed some glitches in custom admin columns when there is some data missing.

Credits
===========

This plugin is built and maintained by [Miller Designworks](http://millerdesignworks.com "Miller Designworks")

License
===========

GPL 2 I think
