<?php
	global $mdw_cms_admin;

	$base_url=admin_url('tools.php?page=mdw-cms&tab=mdw-cms-tax');
	$btn_text='Create';
	$name=null;
	$label=null;
	$object_type=null;
	$hierarchical=1;
	$show_ui=1;
	$show_admin_col=1;
	$id=-1;

	// edit custom taxonomy //
	if (isset($_GET['edit']) && $_GET['edit']=='tax') :
		foreach ($mdw_cms_admin->options['taxonomies'] as $key => $tax) :
			if ($tax['name']==$_GET['slug']) :
				extract($mdw_cms_admin->options['taxonomies'][$key]);
				$label=$args['label'];
				$id=$key;
			endif;
		endforeach;
	endif;

	if ($id!=-1)
		$btn_text='Update';
?>

<h3>Add New Custom Taxonomy</h3>

<div class="left-col">
	<form class="custom-taxonomies" method="post">
		<input type="hidden" name="tax-id" id="tax-id" value=<?php echo $id; ?> />
		<?php wp_nonce_field('update_taxonomies', 'mdw_cms_admin'); ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">
						<label for="name" class="required">Name</label>
					</th>
					<td>
						<input type="text" name="name" id="name" value="<?php echo $name; ?>" /><span class="example">(e.g. brands)</span>
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
						<input type="text" name="label" id="label" value="<?php echo $label; ?>" /><span class="example">(e.g. Brands)</span>
					</td>
				</tr>

				<?php echo $mdw_cms_admin->get_post_types_list($object_type); ?>

			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="add-tax" id="submit" class="button button-primary" value="<?php echo $btn_text; ?>">
		</p>

	</form>
</div>

<div class="custom-taxonomies-list mdw-cms-edit-list right-col">
	<h3>Custom Taxonomies</h3>

	<?php if ($mdw_cms_admin->options['taxonomies']) : ?>
		<?php foreach ($mdw_cms_admin->options['taxonomies'] as $tax) : ?>
			<div class="tax-row mdw-cms-edit-list-row" data-name="<?php echo $tax['name']; ?>">
				<div class="label"><?php echo $tax['args']['label']; ?></div>
				<div class="edit">[<a href="<?php echo $base_url; ?>&edit=tax&slug=<?php echo $tax['name']; ?>">Edit</a>]</div>
				<div class="delete">[<a href="#">Delete</a>]</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

</div><!-- .custom-taxonomies-list -->

