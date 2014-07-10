MDW CMS
===========

Adds cusomtized functionality to the site to make WordPress super awesome.  

Usage Instructions
===========

### Custom Post Types
 * The custom post type class is already called and stored in $mdw_custom_post_types.
 * Use $mdw_custom_post_types->add_post_types(); to add post types.
 * Post types can be added individually or in a group.
 	* The simple syntax is: $mdw_custom_post_types->add_post_types(array('meats','suppliers'));
 	* More advanced options can be added using a more advanced syntax:
 		$args=array(
 			'meats' => array(
 				'supports' => array('title','thumbnail','revisions'),
 				'taxonomies' => false
 			),
 			'suppliers' => array()
 		);
 		$mdw_custom_post_types->add_post_types($args);
 
### Custom Taxonomies
 * The custom taxonomy class is already called and stored in $mdw_custom_taxonomies.
 * Use $mdw_custom_taxonomies->add_taxonomy($taxonomy,$object_type,$label); to add taxonomies.
 * The parameters:
  * @param string $taxonomy - the taxonomy name (slug form)
  * @param string $object_type - name of the object type ie: post,page,custom_post_type
  * @param string $label - the taxonomy display name

### Custom Admin Columns
 * Initiate the class new MDW_Admin_Columns($config) and that will generate the columns.
 * @param array $config requires the post_type and one or more columns, which require a slug and label. An optional type paramater has also been added.

See the mdw-cms-demo.php for detailed examples.

Changelog
===========

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
