MDW CMS
===========

Adds cusomtized functionality to the site to make WordPress super awesome.  

Usage instructions
===========

### Custom Post Types
 * The custom post type class is already called and stored in $mdw_custom_post_types.
 * Use $mdw_custom_post_types->add_post_types('post_type'); to add post types.
 * Post types can be added individually  ('post_type') or in a group ('post_type_1,post_type_2')
 
### Custom Taxonomies
 * The custom taxonomy class is already called and stored in $mdw_custom_taxonomies.
 * Use $mdw_custom_taxonomies->add_taxonomy($taxonomy,$object_type,$label); to add taxonomies.
 * The parameters:
  * @param string $taxonomy - the taxonomy name (slug form)
  * @param string $object_type - name of the object type ie: post,page,custom_post_type
  * @param string $label - the taxonomy display name

### Custom Admin Columns
 * Initiate the class new MDW_Admin_Columns($config) and that will generate the columns.
 * @param array $config requires the post_type and one or more columns, which require a slug and label:
 
	$config=array(
	'post_type' => 'sample',
	'columns' => array(
	array (
	'slug' => '_url',
	'label' => 'URL'
	),
	array(
	'slug' => '_address',
	'label' => 'Address'
	)
	),
	);


Changelog
===========

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
