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