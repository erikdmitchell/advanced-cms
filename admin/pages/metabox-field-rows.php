<?php
global $MDWMetaboxes, $mdw_cms_admin;

extract($attributes);

$field_description=null;
$prefix=null;

// if we are editing, get values //
if (isset($_GET['edit']) && $_GET['edit']=='mb') :
	foreach ($mdw_cms_admin->options['metaboxes'] as $key => $mb) :
		if ($mb['mb_id']==$_GET['mb_id']) :
			extract($mdw_cms_admin->options['metaboxes'][$key]);
		endif;
	endforeach;
endif;

// is field repeatable? //
if (isset($field['repeatable']) && $field['repeatable']) :
	$repeatable_checked='checked="checked"';
else :
	$repeatable_checked=null;
endif;

// setup field format if found //
if (isset($field['format']['value'])) :
	$format=$field['format']['value'];
else :
	$format=null;
endif;

// set field description //
if (isset($field['field_description']) && !empty($field['field_description']))
	$field_description=$field['field_description'];

// check field type //
if (!isset($field['field_type']))
	$field['field_type']='';

// check field label //
if (!isset($field['field_label']))
	$field['field_label']='';
?>

<div class="sortable mdw-cms-fields-wrapper <?php echo $classes; ?>" id="fields-wrapper-<?php echo $field_id; ?>">
	<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>

	<div class="field-row">
		<label for="field_type">Field Type</label>

		<select class="field_type name-item" name="fields[<?php echo $field_id; ?>][field_type]">
			<option value=0>Select One</option>
			<?php foreach ($MDWMetaboxes->fields as $field_type => $setup) : ?>
				<option value="<?php echo $field_type; ?>" <?php selected($field['field_type'], $field_type); ?>><?php echo $field_type; ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="field-row">
		<label for="field_label">Label</label>

		<input type="text" name="fields[<?php echo $field_id; ?>][field_label]" class="field_type name-item" value="<?php echo $field['field_label']; ?>" />
	</div>

		<div class="field-options" id="">
			<?php foreach ($MDWMetaboxes->fields as $field_type => $setup) : ?>
				<div class="type" data-field-type="<?php echo $field_type; ?>">
					<?php if ($setup['repeatable']) : ?>
						<div class="field repeatable row">
							<div class="field-repeatable-label">
								<label for="repeatable">Repeatable</label>
							</div>
							<div class="field-repeatable-check">
								<input type="checkbox" name="fields[<?php echo $field_id; ?>][repeatable]" value="1" class="repeatable-box name-item" <?php echo $repeatable_checked; ?> />
							</div>
						</div>
					<?php endif; ?>

					<?php if ($setup['options']) : ?>
						<div class="field options" id="field-options-<?php echo $field_id; ?>">
							<label for="options">Options</label>

							<?php if (isset($field['options']) && !empty($field['options'])) : ?>
								<?php foreach ($field['options'] as $key => $option) : ?>
									<div class="option-row" id="option-row-<?php echo $key; ?>">
										<label for="options-default-name">Name</label>
										<input type="text" name="fields[<?php echo $field_id; ?>][options][<?php echo $key; ?>][name]" class="options-item name" value="<?php echo $option['name']; ?>" />
										<label for="options-default-value">Value</label>
										<input type="text" name="fields[<?php echo $field_id; ?>][options][<?php echo $key; ?>][value]" class="options-item value" value="<?php echo $option['value']; ?>" />
									</div><!-- .option-row -->
								<?php endforeach; ?>
							<?php endif; ?>

							<div class="option-row default" id="option-row-default">
								<label for="options-default-name">Name</label>
								<input type="text" name="fields[<?php echo $field_id; ?>][options][default][name]" class="options-item name" value="" />
								<label for="options-default-value">Value</label>
								<input type="text" name="fields[<?php echo $field_id; ?>][options][default][value]" class="options-item value" value="" />
							</div><!-- .option-row -->

							<div class="add-option-field"><input type="button" name="add-option-field" class="add-option-field-btn button button-primary" value="Add Option"></div>
						</div>
					<?php endif; ?>

					<?php if ($setup['format']) : ?>
						<div class="field format">
							<div class="field-format-label">
								<label for="format">Format</label>
							</div>
							<div class="field-format-check">
								<input type="text" name="fields[<?php echo $field_id; ?>][format][value]" class="options-item value" value="<?php echo $format; ?>" />
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div><!-- .field-options -->

	<div class="field-row">
		<label for="field_description">Field Description</label>

		<input type="text" name="fields[<?php echo $field_id; ?>][field_description]" class="field_type long-text name-item" value="<?php echo $field_description; ?>" />
	</div>

	<div class="field-row">
		<label for="field_id">Field ID</label>

		<div class="gen-field-id">
			<input type="text" readonly="readonly" class="field_type long-text" value="<?php echo $MDWMetaboxes->generate_field_id($prefix, $field['field_label'], $field_id); ?>" /> <span class="description">(use as meta key)</span>
		</div>
	</div>

		<div class="remove">
			<input type="button" name="remove-field" id="remove-field-btn" class="button button-primary remove-field" data-id="fields-wrapper-<?php echo $field_id; ?>" value="Remove">
		</div>

		<input type="hidden" name="fields[<?php echo $field_id; ?>][order]" class="order name-item" value="<?php echo $order; ?>" />

</div>