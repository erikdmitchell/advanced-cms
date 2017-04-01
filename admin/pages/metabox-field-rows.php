<?php
global $advancedMetaboxes, $advanced_cms_admin, $advanced_cms_metabox_args;

$field=advanced_cms_setup_metabox_row($attributes);
?>

<pre>
	<?php print_r($field); ?>
</pre>

<div class="sortable advanced-cms-fields-wrapper <?php echo $field['classes']; ?>" id="fields-wrapper-<?php echo $field['order']; ?>">
	<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>

	<div class="field-row">
		<label for="title">Title</label>

		<input type="text" name="fields[<?php echo $field['order']; ?>][title]" class="field_type name-item field-title" value="<?php echo $field['title']; ?>" />
	</div>

	<div class="field-row">
		<label for="field_type">Field Type</label>

		<select class="field_type name-item field-type" name="fields[<?php echo $field['order']; ?>][field_type]">
			<option value=0>Select One</option>
			<?php foreach ($advancedMetaboxes->fields as $_field) : ?>
				<option value="<?php echo $_field->name; ?>" <?php selected($field['field_type'], $_field->name); ?>><?php echo $_field->label; ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="field-options">

		<div class="options field-row options-row" id="field-options-<?php echo $field['order']; ?>" data-option-type="options">
			<label for="options">Options</label><br />

			<?php advanced_cms_options_rows($field['options'], $field['order']); ?>

			<div class="add-option-field"><input type="button" name="add-option-field" class="add-option-field-btn button button-primary" value="Add Option"></div>
		</div>

		<div class="format field-row options-row" data-option-type="format">
			<div class="field-format-label">
				<label for="format">Format</label>
			</div>
			<div class="field-format-check">
				<input type="text" name="fields[<?php echo $field['order']; ?>][format][value]" class="options-item value" value="<?php echo $field['clean_format']; ?>" />
			</div>
		</div>

	</div><!-- .field-options -->

	<?php do_action('create_field_options_'.$field['field_type'], $field); ?>


	<div class="field-row">
		<label for="id">Field ID</label>

		<div class="gen-field-id">
			<input type="text" readonly="readonly" class="field_type field-id" value="<?php echo $advancedMetaboxes->generate_field_id($advanced_cms_metabox_args['prefix'], $field['field_title'], $field['id']); ?>" /> <span class="description">(use as meta key)</span>
		</div>
	</div>

		<div class="remove">
			<input type="button" name="remove-field" id="remove-field-btn" class="button button-primary remove-field" data-id="fields-wrapper-<?php echo $field['field_id']; ?>" value="Remove">
		</div>

		<input type="hidden" name="fields[<?php echo $field['order']; ?>][order]" class="order name-item" value="<?php echo $field['order']; ?>" />

</div>