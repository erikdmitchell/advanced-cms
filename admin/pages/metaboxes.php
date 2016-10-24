<?php global $mdw_cms_admin, $mdw_cms_metabox_args; ?>

<h3><?php echo $mdw_cms_metabox_args['title']; ?></h3>

<div class="left-col">
	<form class="custom-metabox" action="" method="post">
		<?php wp_nonce_field('update_metaboxes', 'mdw_cms_admin'); ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">
						<label for="mb_id" class="required ">Metabox ID</label>
					</th>
					<td>
						<input type="text" name="mb_id" id="mb_id" class="required" value="<?php echo $mdw_cms_metabox_args['mb_id']; ?>" /><span class="example">(e.g. movie_details)</span>
						<div id="mdw-cms-name-error" class=""></div>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="title" class="required">Title</label>
					</th>
					<td>
						<input type="text" name="title" id="title" class="required" value="<?php echo $mdw_cms_metabox_args['title']; ?>" /><span class="example">(e.g. Movie Details)</span>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="prefix" class="">Prefix</label>
					</th>
					<td>
						<input type="text" name="prefix" id="prefix" class="" value="<?php echo $mdw_cms_metabox_args['prefix']; ?>" /><span class="example">(e.g. movies)</span>
					</td>
				</tr>

				<?php echo $mdw_cms_admin->get_post_types_list($mdw_cms_metabox_args['post_types']); ?>

				<tr>
					<td colspan="2" class="add-fields sortable-div <?php echo $mdw_cms_metabox_args['edit_class_v']; ?>">
						<h3>Metabox Fields</h3>

						<?php mdw_cms_admin_metabox_fields($mdw_cms_metabox_args['fields']); ?>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="update-metabox" id="submit" class="button button-primary" value="<?php echo $mdw_cms_metabox_args['btn_text']; ?>">
			<input type="button" name="add-field" id="add-field-btn" class="button button-primary add-field" value="Add Field">
		</p>
	</form>
</div>

<div class="custom-metabox-list mdw-cms-edit-list right-col">
	<h3>Custom Metaboxes</h3>

	<?php if ($mdw_cms_admin->options['metaboxes']) : ?>
		<?php foreach ($mdw_cms_admin->options['metaboxes'] as $mb) : ?>
			<div class="metabox-row mdw-cms-edit-list-row" data-id="<?php echo $mb['mb_id']; ?>">
				<div class="mb label"><?php echo $mb['title']; ?></div>
				<div class="edit">[<a href="<?php echo $mdw_cms_metabox_args['base_url'];?>&edit=mb&mb_id=<?php echo $mb['mb_id']; ?>">Edit</a>]</div>
				<div class="delete">[<a href="#">Delete</a>]</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

</div>