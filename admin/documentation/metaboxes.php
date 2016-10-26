<?php mdw_cms_doc_header(); ?>

	<p>
		Below is a detailed description of each field (aka settings) for each custom taxonomy:
	</p>

	<h3>Metabox</h3>

	<ul class="doc-list metabox-list">
		<li>
			<div class="field">Metabox ID</div>
			<div class="description"><span class="type">(string)</span> the id (slug) for the metabox</div>
		</li>
		<li>
			<div class="field">Title</div>
			<div class="description"><span class="type">(string)</span> the label shown in the admin area of the metabox</div>
		</li>
		<li>
			<div class="field">Prefix</div>
			<div class="description"><span class="type">(string)</span> the prefix used when calling the meta fields</div>
		</li>
		<li>
			<div class="field">Post Type</div>
			<div class="description"><span class="type">(checkbox)</span> select which post type(s) to attach the taxonomy to. In the case of attachment, this may not work with the new (4.0) media section view. You may need to utilize the older, list type view.</div>
		</li>
	</ul>

	<h3>Metabox Fields</h3>

	<ul class="doc-list metabox-field-list">
		<li>
			<div class="field">Field Label</div>
			<div class="description"><span class="type">(string)</span> the label shown in the admin area of the field</div>
		</li>
		<li>
			<div class="field">Field Type</div>
			<div class="description"><span class="type">(dropdown)</span> the type of field</div>
		</li>
		<li>
			<div class="field">Field Description</div>
			<div class="description"><span class="type">(string)</span> the description of what the field is for</div>
		</li>
		<li>
			<div class="field">Field ID</div>
			<div class="description"><span class="type">(auto generated)</span> the "meta key" used to retrieve the field data</div>
		</li>
	</ul>

	<a href="http://api.jqueryui.com/datepicker/" target="_blank">jQuery Datepicker (date formatting)</a>

<?php mdw_cms_doc_footer(); ?>



			'address' => array(
				'repeatable' => 1,
				'options' => 0,
				'format' => 0,
			),
			'button' => array(
				'repeatable' => 0,
				'options' => 0,
				'format' => 0,
			),
			'checkbox' => array(
				'repeatable' => 1,
				'options' => 1,
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
			'gallery' => array(
				'repeatable' => 0,
				'options' => 0,
				'format' => 0,
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
				'options' => 1,
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