MDW CMS
===========

Adds customized functionality to the site to make WordPress super awesome.  

Usage Instructions
===========

See [Wiki](https://bitbucket.org/millerdesign/mdw-cms/wiki) for details.

Changelog
===========

### 2.1.6

	Added option for excerpt in Custom Post Types.
	
	Reworked Custom Post Type functionality. Edit and Delete are all done via ajax. (more to come in future releases)
	
	When a Custom Post Type name is changed, the db post_type also gets changed to maintain compatibility.
	
	Extended the metabox ids that are reserved to prevent odd conflicts.

### 2.1.5.4

	Added admin-metaboxes.js and jquery.metabox-id-check.js to help with our metaboxes in the admin panel.
	Added check for post type when saving metabox.
	Added check for duplicate metabox ids.
	
	Fixed glitch with post_fields on initial metabox creation.
	Fixed glitch where data was not being reset properly for each field on a metabox save.

### 2.1.5.3

	Added get_gallery_image_ids() to help with our load attached images function.
	Added mdw_cms_get_gallery_image_ids filter to allow more control over get_gallery_image_ids().
	Added mdw_cms_get_gallery_images filter for getting the gallery images to display in the gallery meta field.
	Added mdw_cms_media_settings_gallery_shortcode filter to further customize our shortcode used in our WP Gallery.
	
	Removed the gallery init override for getting gallery images and ids. The filters can be utilized to do that.

### 2.1.5.2

	Add two filters to position the meta boxes: 		      
		mdw_cms_add_metabox_context_{$config['mb_id']}
		mdw_cms_add_metabox_priority_{$config['mb_id']}
		
	Tweaked styling for custom metabox admin panel.

### 2.1.5.1

	Added Remove Gallery button.

	Fixed major glitch in gallery functionality. It is now more dynamic and works smoother.

	Tweaked styling for custom metabox fields.
	
	Note: multiple galleries on the same page (ie 2+ gallery fields) are not tested.

### 2.1.5

	Added Gallery field to custom metabox
	Added jquery.mediauploader.js
	Added state array to metabox class 

### 2.1.4.1

	Fixed metabox glitch causing update posts failure.

### 2.1.4

	Added address field option to metabox fields.

	Fixed glitch when updating metaboxes and there are no field values.
	Fixed issue where changing the Field Type did not change the field options in metabox admin area.
	Fixed attribute box showing up on all custom post types. The default is not to show and now the code reflects that.

	Reworked some issues with input box styling on the CMS admin page.
	
	Updated heading level for widget titles in Content Widget.

### 2.1.3

	Added $prefix=null to build_field_rows() to fix glitch when mb isn’t created yet.
	Added a minor redirect when someone creates a new metabox. The redirect keeps them on that page.
	Added a fallback so that the default Post Type for metaboxes is post.
	Added ability to customize the date field in metaboxes.

	Widgets:
		Added Content Widget
		Added Social Media Widget
	
	Fixed $field_id error in metaboxes_admin_page().
	Fixed admin error positioning so that it is no longer off screen.
	Fixed duplicate metabox fields so that the new fields dropdown is cleared. 
	Fixed js glitch when calculating the new ids and fields for cloning. This allows multiple fields to be added at once and on the initial create screen.
	

### 2.1.2

	Fixed error where options was not an array. Basically an init error that we just check for.
	Fixed css error that was overriding WP default styles (admin.css and bootstrap.css).

### 2.1.1

	Fixed duplicate filed (meta box field) glitch.	

### 2.1.0

	Added bootstrap grid to the plugin. For admin layout only at this point.
	Added 'page attributes' as an option for custom post types.
	Added 'options' to the main page in the cms plugin. Plan is for further development of this.
	Added more detailed descriptions to the options in our post types, metaboxes, etc.
	Added Field ID to the metabox admin page. Allows for easier implementation with get_post_meta().
	
	Reworked mb.js so that when we add a new field, it clears the inherited values.	
	Reworked add new field (mb.js) and our mb admin page so that we now utilize the last existing field instead of a default one.
	Reworked some admin styling beyond bootstrap.
	
### 2.0.9

	Added a name check function (js based) to custom post types and taxonomies.
	Added ob_start() to our admin notices to prevent a wp error.
	
	Fixed glitch when editing a custom post type, the disabled button is turned off.
	Fixed order when adding a new field to a metabox in the admin area. The order was being set as 'default' not a number.
	
	Migrated functions from mdwmb_Functions to our mdwmb plugin file.

### 2.0.8

	Minor js loading tweaks.
	
	Fixed major bccmp() issue where the plugin would fail if not found.

### 2.0.7

	Minor tweaks and adjustments. More of a super stable release point.

### 2.0.6

	Added jQuery custom post name checker.
	Added prefix check to our metabox updater admin function.
	Added some filters for custom metabox fields outside cms.

	Fixed error in duplicate meta box fields where js was looking at input, not actual field (ie textarea).
	Fixed issue with generating the field id with invalid input.

	Tweaked custom fields select box glitch and add fields glitch.
	
	Prev Ver: 1.1.6

### 2.0.5
	
	Added mdw-cms-meta-box class to the admin metaboxes
	Added various hidden fields to the metaboxes for field duplication.
	Added post_fields to our metabox config array to hold specific post fields
	Added ajax_duplicate_metabox_field() and ajax_remove_duplicate_metabox_field() to our MDWMetaboxes class.
	Added add_post_fields() to MDWMetaboxes class, called via generate_meta_box_fields().
	Added numeric check to MDWMetaboxes->add_field() for our post fields.
	
	Fixed glitch in MDWMetaboxes->update_metaboxes() to account for out post fields
	
	Prev Ver: 1.1.5

### 2.0.4

	Added generate_field_id() to MDWMetaboxes class and implemented it in	add_field() and MDWCMSgui::update_metaboxes.
	Added secondary check to MDWMetaboxes save_custom_meta_data for field id.
	Added delete custom taxonomy.
	
	Fixed glitch on custom taxonomy with and empty array.
	Fixed issues where a new, non-legacy plugin returned an options error on activation.	
	Fixed glitch where metaboxes were not updating.
	Fixed MDWMetaboxes->check_config_prefix to just accept prefix.
	Fixed glitch where our js was setting up our datepicker in european (-) but in american style layout.
	Fixed errant error message on taxonomy update.
		
	Renamed datebox (metaboxes) class to mdw-cms-datepicker to prevent potential conflicts.
	
	Prev Ver: 1.1.4

### 2.0.3

	Added custom taxonomies to custom post types.
	Added class MDWCustomTaxonomies to handle taxonomies.
	
	Prev Ver: 1.1.3

### 2.0.2

	Added legacy functions and classes for older versions of the plugin.
	Reworked Social Media add on to be more flexible.
	Tweaked GUI and updated it to be the standard for this plugin.
	
	Prev Ver: 1.1.2

### 2.0.1
	
	Finalized updater and tested it. This is the min version we need for auto updates.
	Reworked metabox field ids to utilize the label and not some generic number.
	
	Prev Ver: 1.1.1

### 2.0.0

	We have split this plugin into two versions after version 1.1.1. Version 2.0.0 is the start of our gui interface.
	While 2.0.0 can be utilized to upgrade v1.x.x to 2.0.0+, we have found a need to keep v1.
	There will be some version changes in the history and some other little tweaks to accommodate this split.

### 1.1.0

	Pulled out custom config file to prevent it from being overwritten. This will be stored as a variable at some point.

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

	Added an admin options page for our custom code. Prevents overwriting of code on updates. -- This has been postponed do to more detailed setup requirements then I thought.
	Added admin.css for use on our options page and plugin.
	Added a new class: AJAXMetaBoxes, that allows dynamic creation of meta fields similar to the WP Custom Fields functions. -- not fully integrated yet
  
### 1.0.2

	Added ability to create very basic, custom widgets.
	Removed default data from plugin. Will incoroprate into seperate file.
	Fixed $nonce issue in MDW Meta Boxes.
	Added $post_id to (if (!current_user_can('edit_post',$post_id)) return;) in our save meta. The lack of id threw an error.
	Added check_config_prefix($config) to meta boxes to ensure our prefix starts with an '_'.
	Our admin columns now use a 'type' parameter for meta and taxonomy fields.
	Added post_tag taxonomy to our custom post type.
	Expanded our custom post type to include taxonomies and supports in our array.

### 1.0.1

	Added to Git
	Added mdw-meta-boxes to the inc folder. Allows us to use a simple custom meta box generator.
 
	Fixed some glitches in custom admin columns when there is some data missing.

Credits
===========

This plugin is built and maintained by [Miller Designworks](http://millerdesignworks.com "Miller Designworks")

License
===========

GPL 2 I think
