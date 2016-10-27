<?php
global $mdw_cms_admin;

$mdw_cms_taxonomy_args=mdw_cms_setup_taxonomy_args();
?>

<h3><?php echo $mdw_cms_taxonomy_args['header']; ?></h3>

<div class="mdw-cms-admin-page single-taxonomy-page">

	<form class="custom-taxonomies" method="post">
		<input type="hidden" name="tax-id" id="tax-id" value=<?php echo $mdw_cms_taxonomy_args['id']; ?> />
		<?php wp_nonce_field('update_taxonomies', 'mdw_cms_admin'); ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">
						<label for="name" class="required">Name</label>
					</th>
					<td>
						<input type="text" name="name" id="name" class="required" value="<?php echo $mdw_cms_taxonomy_args['name']; ?>" /><span class="example">(e.g. brands)</span>
						<div id="mdw-cms-name-error" class=""></div>
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
						<input type="text" name="label" id="label" class="required" value="<?php echo $mdw_cms_taxonomy_args['label']; ?>" /><span class="example">(e.g. Brands)</span>
					</td>
				</tr>

				<?php echo $mdw_cms_admin->get_post_types_list($mdw_cms_taxonomy_args['object_type']); ?>

			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="add-tax" id="submit" class="button button-primary" value="<?php echo $mdw_cms_taxonomy_args['btn_text']; ?>">
		</p>

	</form>

</div>