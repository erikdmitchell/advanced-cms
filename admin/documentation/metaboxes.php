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

			<p><strong>Field Types:</strong></p>

			<ul class="doc-sub-list metabox-fields-list">
				<li>
					<span class="field-type">address</span>:
					<span class="field-description">creates an address field</span>
					<span class="field-options">(repeatable)</span>
				</li>
				<li>
					<span class="field-type">button</span>:
					<span class="field-description">Creates a button in the metabox. This would be useful to link an external script/action.</span>
				</li>
				<li>
					<span class="field-type">checkbox</span>:
					<span class="field-description">creates a check box list</span>
					<span class="field-options">(repeatable|options)</span>
				</li>
				<li>
					<span class="field-type">colorpicker</span>:
					<span class="field-description">Generates a field with a jQuery colorpicker. This field contains the hex code for the color.</span>
				</li>
				<li>
					<span class="field-type">date</span>:
					<span class="field-description">displays a jQuery date box (<a href="http://api.jqueryui.com/datepicker/" target="_blank">date formatting</a>)</span>
					<span class="field-options">(format)</span>
				</li>
				<li>
					<span class="field-type">gallery</span>:
					<span class="field-description">allows you to create a gallery using the media uploader</span>
				</li>
				<li>
					<span class="field-type">email</span>:
					<span class="field-description">a field for entering an email address</span>
					<span class="field-options">(repeatable)</span>
				</li>
				<li>
					<span class="field-type">media</span>:
					<span class="field-description">uses the media uploader to input the url of a media item (also shows a thumbnail)</span>
				</li>
				<li>
					<span class="field-type">media_images</span>:
					<span class="field-description">creates a multi select box of all media library images - it returns an array of image ids</span>
				</li>
				<li>
					<span class="field-type">phone</span>:
					<span class="field-description">creates a pre-formatted box for a phone number</span>
					<span class="field-options">(repeatable)</span>
				</li>
				<li>
					<span class="field-type">radio</span>:
					<span class="field-description">creates a radio button list</span>
					<span class="field-options">(repeatable|options)</span>
				</li>
				<li>
					<span class="field-type">select</span>:
					<span class="field-description">creates a select (dropdown) menu</span>
					<span class="field-options">(options)</span>
				</li>
				<li>
					<span class="field-type">text</span>:
					<span class="field-description">displays a text input box</span>
					<span class="field-options">(repeatable)</span>
				</li>
				<li>
					<span class="field-type">textarea</span>:
					<span class="field-description">displays a textarea input box</span>
					<span class="field-options">(repeatable)</span>
				</li>
				<li>
					<span class="field-type">timepicker</span>:
					<span class="field-description">displays a jQuery time picker</span>
				</li>
				<li>
					<span class="field-type">url</span>:
					<span class="field-description">a field for entering a url</span>
					<span class="field-options">(repeatable)</span>
				</li>
				<li>
					<span class="field-type">wysiwyg</span>:
					<span class="field-description">displays a wordpress content editor</span>
				</li>
			</ul>

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

<?php mdw_cms_doc_footer(); ?>