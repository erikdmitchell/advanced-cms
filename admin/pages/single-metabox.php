<?php
global $advanced_cms_admin;

$advanced_cms_metabox_args=advanced_cms_setup_metabox_args();
?>

<h3><?php echo $advanced_cms_metabox_args['header']; ?> <a href="<?php advanced_cms_admin_link(array('tab' => 'metaboxes', 'action' => 'update')); ?>" class="page-title-action">Add New</a></h3>

<div class="advanced-cms-admin-page single-metabox-page">
	<form class="custom-metabox" action="" method="post">
		<?php wp_nonce_field('update_metaboxes', 'advanced_cms_admin'); ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">
						<label for="mb_id" class="required ">Metabox ID</label>
					</th>
					<td>
						<input type="text" name="mb_id" id="mb_id" class="required" value="<?php echo $advanced_cms_metabox_args['mb_id']; ?>" /><span class="example">(e.g. movie_details)</span>
						<div id="advanced-cms-name-error" class=""></div>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="title" class="required">Title</label>
					</th>
					<td>
						<input type="text" name="title" id="title" class="required" value="<?php echo $advanced_cms_metabox_args['title']; ?>" /><span class="example">(e.g. Movie Details)</span>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="prefix" class="">Prefix</label>
					</th>
					<td>
						<input type="text" name="prefix" id="prefix" class="" value="<?php echo $advanced_cms_metabox_args['prefix']; ?>" /><span class="example">(e.g. movies)</span>
					</td>
				</tr>

				<?php echo $advanced_cms_admin->get_post_types_list($advanced_cms_metabox_args['post_types']); ?>

				<tr>
					<td colspan="2" class="add-fields sortable-div <?php echo $advanced_cms_metabox_args['edit_class_v']; ?>">
						<h3>Metabox Fields</h3>

						<?php advanced_cms_admin_metabox_fields($advanced_cms_metabox_args['fields']); ?>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="update-metabox" id="submit" class="button button-primary" value="<?php echo $advanced_cms_metabox_args['btn_text']; ?>">
			<input type="button" name="add-field" id="add-field-btn" class="button button-primary add-field" value="Add Field">
		</p>
	</form>
</div>