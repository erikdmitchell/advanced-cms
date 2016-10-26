<?php mdw_cms_doc_header(); ?>

	<section>
		<h3>add_mdw_cms_metabox_custom_fields-XXX</h3>

		<p>
			Use this filter to add custom fields to a metabox. This is usually done in functions.php, but can be expanded and used elsewhere.<br />
			The XXX refers to the specific metabox id you wish to attach the field to.
		</p>

		<p>
			Parameters: $extra_fields,$prefix.
		</p>

		<p>
			Sample using the metabox Trail Head Details (trail_head_details)
			<pre><code>
				function custom_meta_fields($extra_fields,$prefix) {
				    global $MDWMetaboxes;

				    $args=array(
				        'field_label' => 'Trails',
				        'field_id' => $MDWMetaboxes->generate_field_id($prefix,'Trails')
				    );

				    $extra_fields[]=$args;

				    return $extra_fields;
				}
				add_filter('add_mdw_cms_metabox_custom_fields-trail_head_details','custom_meta_fields',10,2);
			</code></pre>
		</p>

		<p>
			Sample with multiple fields (amenity_details)
			<pre><code>
				function custom_meta_fields_amenity_details($extra_fields,$prefix) {
				    global $MDWMetaboxes;

				    $trails=array(
				        'field_label' => 'Trails',
				        'field_id' => $MDWMetaboxes->generate_field_id($prefix,'Trails')
				    );

				    $trail_heads=array(
				        'field_label' => 'Trail Heads',
				        'field_id' => $MDWMetaboxes->generate_field_id($prefix,'Trail Heads')
				    );

				    $extra_fields[]=$trails;
				    $extra_fields[]=$trail_heads;

				    return $extra_fields;
				}
				add_filter('add_mdw_cms_metabox_custom_fields-amenity_details','custom_meta_fields_amenity_details',10,2);
			</code></pre>
		</p>
	</section>

	<section>
		<h3>add_mdw_cms_metabox_custom_fields-XXX</h3>

		<p>
			Use this filter to add the actual metabox input (input, textarea, dropdown, etc) to the custom field. Must be used with add_mdw_cms_metabox_custom_fields-XXX.<br />
			The XXX refers to the specific field id you wish to attach the field to.
		</p>

		<p>
			Parameters: $id,$values
		</p>

		<p>
			Sample using our field created above for the Trails field in the Trail Head Details metabox
			<pre><code>
				function trails_select_box($id,$values) {
				    $html=null;
				    $trails=get_all_trails();

				    if (!count($trails))
				        return false;

				    $html.='&lt;select multiple size="6" name="'.$id.'[]" id="'.$id.'"&gt;';

				            foreach ($trails as $trail) :
				                $selected=null;

				                if (is_array($values) && !empty($values) && in_array($trail->ID,$values))
				                    $selected='selected="selected"';

				                $html.='&lt;option value="'.$trail->ID.'" '.$selected.'&gt;'.$trail->post_title.'&lt;/option&gt;';
				            endforeach;

				    $html.='&lt;/select&gt;';

				    return $html;
				}
				add_filter('add_mdw_cms_metabox_custom_input-_trail_head_details_trails','trails_select_box',10,2);
			</code></pre>
		</p>
	</section>

<?php mdw_cms_doc_footer(); ?>