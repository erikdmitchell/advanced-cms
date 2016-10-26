<?php mdw_cms_doc_header(); ?>

<p>
	Below is a detailed description of each field (aka settings) for each custom post type:
</p>

<ul class="doc-list custom-post-type-list">
	<li>
		<div class="field">Post Type Name</div>
		<div class="description"><span class="type">(string)</span> The post type name. It will be used in the admin menus as well.</div>
	</li>
	<li>
		<div class="field">Label</div>
		<div class="description"><span class="type">(string)</span> A plural descriptive name for the post type marked for translation.</div>
	</li>
	<li>
		<div class="field">Singular Label</div>
		<div class="description"><span class="type">(string)</span> A singular name for the post type.</div>
	</li>
	<li>
		<div class="field">Description</div>
		<div class="description"><span class="type">(string)</span> A short descriptive summary of what the post type is.</div>
	</li>
	<li>
		<div class="field">Title</div>
		<div class="description"><span class="type">(true|false)</span> Show the title field.</div>
		<div class="default">Default: true</div>
	</li>
	<li>
		<div class="field">Thumbnail</div>
		<div class="description"><span class="type">(true|false)</span> Allow a featured image.</div>
		<div class="default">Default: true</div>
	</li>
	<li>
		<div class="field">Editor</div>
		<div class="description"><span class="type">(true|false)</span> Show the content editor field.</div>
		<div class="default">Default: true</div>
	</li>
	<li>
		<div class="field">Revisions</div>
		<div class="description"><span class="type">(true|false)</span> Allow revisions.</div>
		<div class="default">Default: true</div>
	</li>
	<li>
		<div class="field">Hierarchical</div>
		<div class="description"><span class="type">(true|false)</span> Whether the post type is hierarchical (e.g. page). Allows Parent to be specified.</div>
		<div class="default">Default: false</div>
	</li>
	<li>
		<div class="field">Page Attributes</div>
		<div class="description"><span class="type">(true|false)</span> menu order (hierarchical must be true)</div>
		<div class="default">Default: false</div>
	</li>
	<li>
		<div class="field">Excerpt</div>
		<div class="description"><span class="type">(true|false)</span> Allow the excerpt field.</div>
		<div class="default">Default: false</div>
	</li>
	<li>
		<div class="field">Comments</div>
		<div class="description"><span class="type">(true|false)</span> Supports comments (also displays comment count balloon on edit screen)</div>
		<div class="default">Default: false</div>
	</li>
	<li>
		<div class="field">Icon</div>
		<div class="description"><span class="type">(icon)</span> The icon displayed next to the post type in the menu.</div>
	</li>

</ul>

<?php mdw_cms_doc_footer(); ?>