<?php
global $advancedMetaboxes, $advanced_cms_admin, $advanced_cms_metabox_args;

$args=advanced_cms_setup_metabox_row($attributes);
?>

<div class="sortable advanced-cms-fields-wrapper <?php echo $args['classes']; ?>" id="fields-wrapper-<?php echo $args['order']; ?>">
	<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>

	<div class="field-row">
		<label for="field_label">Field Label</label>

		<input type="text" name="fields[<?php echo $args['order']; ?>][field_label]" class="field_type name-item field-label" value="<?php echo $args['field_label']; ?>" />
	</div>

	<div class="field-row">
		<label for="field_type">Field Type</label>

		<select class="field_type name-item field-type" name="fields[<?php echo $args['order']; ?>][field_type]">
			<option value=0>Select One</option>
			<?php foreach ($advancedMetaboxes->fields as $field_type => $setup) : ?>
				<option value="<?php echo $field_type; ?>" <?php selected($args['field_type'], $field_type); ?>><?php echo $field_type; ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="field-options">

		<div class="repeatable field-row option-row" data-option-type="repeatable">
			<div class="field-repeatable-label">
				<label for="repeatable">Repeatable</label>
			</div>
			<div class="field-repeatable-check">
				<input type="checkbox" name="fields[<?php echo $args['order']; ?>][repeatable]" value="1" class="repeatable-box name-item" <?php echo $args['repeatable_checked']; ?> />
			</div>
		</div>

		<div class="options field-row option-row" id="field-options-<?php echo $args['order']; ?>" data-option-type="options">
			<label for="options">Options</label>

			<?php advanced_cms_options_rows($args['options'], $args['order']); ?>

			<div class="add-option-field"><input type="button" name="add-option-field" class="add-option-field-btn button button-primary" value="Add Option"></div>
		</div>

		<div class="format field-row option-row" data-option-type="format">
			<div class="field-format-label">
				<label for="format">Format</label>
			</div>
			<div class="field-format-check">
				<input type="text" name="fields[<?php echo $args['order']; ?>][format][value]" class="options-item value" value="<?php echo $args['clean_format']; ?>" />
			</div>
		</div>

	</div><!-- .field-options -->

	<div class="field-row">
		<label for="field_description">Field Description</label>

		<input type="text" name="fields[<?php echo $args['order']; ?>][field_description]" class="field_type name-item field-description" value="<?php echo $args['field_description']; ?>" />
	</div>

	<div class="field-row">
		<label for="field_id">Field ID</label>

		<div class="gen-field-id">
			<input type="text" readonly="readonly" class="field_type field-id" value="<?php echo $advancedMetaboxes->generate_field_id($advanced_cms_metabox_args['prefix'], $args['field_label'], $args['field_id']); ?>" /> <span class="description">(use as meta key)</span>
		</div>
	</div>

		<div class="remove">
			<input type="button" name="remove-field" id="remove-field-btn" class="button button-primary remove-field" data-id="fields-wrapper-<?php echo $args['field_id']; ?>" value="Remove">
		</div>

		<input type="hidden" name="fields[<?php echo $args['order']; ?>][order]" class="order name-item" value="<?php echo $args['order']; ?>" />

</div>