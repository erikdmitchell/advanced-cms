<?php
global $pickle_cms_admin, $pickle_cms_metabox_args, $pickle_metaboxes;

$pickle_cms_metabox_args=pickle_cms_setup_metabox_args();
?>
<pre>
	<?php print_r($pickle_cms_metabox_args); ?>
</pre>

<h3><?php echo $pickle_cms_metabox_args['header']; ?> <a href="<?php pickle_cms_admin_link(array('tab' => 'metaboxes', 'action' => 'update')); ?>" class="page-title-action">Add New</a></h3>

<div class="pickle-cms-admin-page single-metabox-page">
	<form class="custom-metabox" action="" method="post">
		<?php wp_nonce_field('update_metaboxes', 'pickle_cms_admin'); ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">
						<label for="mb_id" class="required ">Metabox ID</label>
					</th>
					<td>
						<input type="text" name="mb_id" id="mb_id" class="required" value="<?php echo $pickle_cms_metabox_args['mb_id']; ?>" /><span class="example">(e.g. movie_details)</span>
						<div id="pickle-cms-name-error" class=""></div>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="title" class="required">Title</label>
					</th>
					<td>
						<input type="text" name="title" id="title" class="required" value="<?php echo $pickle_cms_metabox_args['title']; ?>" /><span class="example">(e.g. Movie Details)</span>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="prefix" class="">Prefix</label>
					</th>
					<td>
						<input type="text" name="prefix" id="prefix" class="" value="<?php echo $pickle_cms_metabox_args['prefix']; ?>" /><span class="example">(e.g. movies)</span>
					</td>
				</tr>

				<?php echo $pickle_cms_admin->get_post_types_list($pickle_cms_metabox_args['post_types']); ?>

				<tr>
					<td colspan="2" class="add-fields sortable-div <?php echo $pickle_cms_metabox_args['edit_class_v']; ?>">
						<h3>Metabox Fields</h3>
						
						<?php foreach ($pickle_cms_metabox_args['fields'] as $field): ?>
							<?php $key=$field['order']; ?>

<pre>
	<?php print_r($field); ?>
</pre>
							
							<div class="sortable pickle-cms-fields-wrapper" id="fields-wrapper-<?php echo $key; ?>">
								<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
							
								<div class="field-row">
									<label for="title">Title</label>
							
									<input type="text" name="fields[<?php echo $key; ?>][title]" class="field_type name-item field-title" value="<?php echo $field['title']; ?>" />
								</div>
							
								<div class="field-row">
									<label for="field_type">Field Type</label>
							
									<select class="field_type name-item field-type" name="fields[<?php echo $key; ?>][field_type]">
										<option value=0>Select One</option>
										<?php foreach ($pickle_metaboxes->fields as $_field) : ?>
											<option value="<?php echo $_field->name; ?>" <?php selected($field['field_type'], $_field->name); ?>><?php echo $_field->label; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							
								<div class="field-options">
									<?php do_action('create_field_options_'.$field['field_type'], $field); ?>
								</div>
							
								<div class="field-row">
									<label for="id">Field ID</label>
							
									<div class="gen-field-id">
										I would like this to be stored
										<input type="text" readonly="readonly" class="field_type field-id" value="<?php echo $pickle_metaboxes->generate_field_id($pickle_cms_metabox_args['prefix'], $field['title'], $field['id']); ?>" /> <span class="description">(use as meta key)</span>
									</div>
								</div>
							
								<div class="remove">
									<input type="button" name="remove-field" id="remove-field-btn" class="button button-primary remove-field" data-id="fields-wrapper-<?php echo $key; ?>" value="Remove">
								</div>
							
								<input type="hidden" name="fields[<?php echo $field['order']; ?>][order]" class="order name-item" value="<?php echo $field['order']; ?>" />
							</div>
							
						<?php endforeach; ?>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="update-metabox" id="submit" class="button button-primary" value="<?php echo $pickle_cms_metabox_args['btn_text']; ?>">
			<input type="button" name="add-field" id="add-field-btn" class="button button-primary add-field" value="Add Field">
		</p>
	</form>
</div>