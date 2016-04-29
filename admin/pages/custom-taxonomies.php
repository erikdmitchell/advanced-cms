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

<div class="row">

	<form class="custom-taxonomies col-md-8" method="post">
		<h3>Add New Custom Taxonomy</h3>
		<div class="form-row row">
			<label for="name" class="required">Name</label>
			<div class="input">
				<input type="text" name="name" id="name" value="<?php echo $name; ?>" />
			</div>
			<span class="description">(e.g. brands)</span>
			<div id="mdw-cms-name-error" class=""></div>
			<div class="description-ext">Max 20 characters, can not contain capital letters or spaces. Cannot be the same name as a (custom) post type.</div>
		</div>

		<div class="form-row row">
			<label for="label" class="<?php echo $label_class; ?>">Label</label>
			<div class="input">
				<input type="text" name="label" id="label" value="<?php echo $label; ?>" />
			</div>
			<span class="description">(e.g. Brands)</span>
		</div>

		<?php echo get_post_types_list($object_type); ?>

		<p class="submit"><input type="submit" name="add-tax" id="submit" class="button button-primary" value="<?php echo $btn_text; ?>" disabled></p>
		<input type="hidden" name="tax-id" id="tax-id" value=<?php echo $id; ?> />
	</form>

	<div class="custom-taxonomies-list col-md-4">
		<h3>Custom Taxonomies</h3>

		<?php if ($mdw_cms_admin->options['taxonomies']) : ?>
			<?php foreach ($mdw_cms_admin->options['taxonomies'] as $tax) : ?>
				<div class="tax-row row">
					<span class="tax"><?php echo $tax['args']['label']; ?></span><span class="edit">[<a href="<?php echo $base_url; ?>&edit=tax&slug=<?php echo $tax['name']; ?>">Edit</a>]</span><span class="delete">[<a href="<?php echo $base_url; ?>&delete=tax&slug=<?php echo $tax['name']; ?>">Delete</a>]</span>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>

	</div><!-- .custom-taxonomies-list -->

</div><!-- .row -->