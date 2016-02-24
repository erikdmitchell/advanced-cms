<?php extract(mdw_cms_setup_taxonomy_page_values()); ?>

<div class="wrap">
	<h2>Add New Custom Taxonomy</h2>

	<div id="taxonomies-admin-notices"></div>

	<form action="" method="post" class="custom-taxonomies">
		<input type="hidden" name="update_mdw_cms_taxonomy" value="1">
		<input type="hidden" name="tax_id" id="tax_id" value=<?php echo $id; ?> />
		<?php wp_nonce_field('update_tax','mdw_cms_admin'); ?>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="name" class="required">Name</label></th>
					<td>
						<input name="name" type="text" id="name" value="<?php echo $name; ?>" class="regular-text">
						<div id="mdw-cms-name-error" class="<?php echo $error_class; ?>"></div>
						<span class="description right">(e.g. brands)</span>
						<div class="description-ext">Max 20 characters, can not contain capital letters or spaces. Cannot be the same name as a (custom) post type.</div>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="label" class="">Label</label></th>
					<td>
						<input name="label" type="text" id="label" value="<?php echo $args['label']; ?>" class="regular-text">
						<span class="description right">(e.g. Brands)</span>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="post_type" class="">Post Type</label></th>
					<td><?php echo get_post_types_list($object_type); ?></td>
				</tr>
			</tbody>
		</table>

		<?php mdw_cms_taxonomies_submit_button($id); ?>
	</form>

	<div class="custom-taxonomies-list">
		<h2>Custom Taxonomies</h2>
		<?php mdw_cms_get_existing_taxonomies(); ?>
	</div><!-- .custom-taxonomies-list -->

</div><!-- .wrap -->