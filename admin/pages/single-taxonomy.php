<?php $taxonomy=picklecms()->admin->components['taxonomies']->setup(); ?>

<h3><?php echo $taxonomy['header']; ?> <a href="<?php pickle_cms_admin_link(array('tab' => 'taxonomies', 'action' => 'update')); ?>" class="page-title-action">Add New</a></h3>

<div class="pickle-cms-admin-page single-taxonomy-page">

	<form class="custom-taxonomies" method="post">
		<input type="hidden" name="tax-id" id="tax-id" value=<?php echo $taxonomy['id']; ?> />
		<?php wp_nonce_field('update_taxonomies', 'pickle_cms_admin'); ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">
						<label for="name" class="required">Name</label>
					</th>
					<td>
						<input type="text" name="name" id="name" class="required" value="<?php echo $taxonomy['name']; ?>" /><span class="example">(e.g. brands)</span>
						<div id="pickle-cms-name-error" class=""></div>
						<p class="description">
							Max 20 characters, can not contain capital letters or spaces. Cannot be the same name as a (custom) post type.
						</p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="label" class="required">Label</label>
					</th>
					<td>
						<input type="text" name="label" id="label" class="required" value="<?php echo $taxonomy['label']; ?>" /><span class="example">(e.g. Brands)</span>
					</td>
				</tr>

				<?php echo picklecms()->admin->get_post_types_list($taxonomy['object_type']); ?>

			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="add-tax" id="submit" class="button button-primary" value="<?php echo $taxonomy['btn_text']; ?>">
		</p>

	</form>

</div>