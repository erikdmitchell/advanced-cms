<?php
$base_url=admin_url('tools.php?page=mdw-cms&tab=mdw-cms-tax');
$btn_text='Create';
$name=null;
$label=null;
$object_type=null;
$disabled='disabled';
$id=-1;

if (isset($_GET['edit']) && $_GET['edit']=='tax') :
	$tax=mdwcms_get_taxonomy_details($_GET['slug']);

	if ($tax)
		extract($tax);
endif;

// we hav an id, so we can edit //
if ($id!=-1) :
	$btn_text='Update';
	$disabled=null;
endif;
?>
<h2>Custom Taxonomies</h2>

<div class="row">

	<form class="custom-taxonomies col-md-8" method="post">
		<h3>Add New Custom Taxonomy</h3>
		<div class="form-row row">
			<label for="name" class="required col-md-3">Name</label>
			<div class="input col-md-3">
				<input type="text" name="name" id="name" value="<?php echo $name; ?>" />
			</div>
			<span class="description col-md-6">(e.g. brands)</span>
			<div id="mdw-cms-name-error" class="col-md-12"></div>
			<div class="description-ext col-md-9 col-md-offset-3">Max 20 characters, can not contain capital letters or spaces. Cannot be the same name as a (custom) post type.</div>
		</div>

		<div class="form-row row">
			<label for="label" class="col-md-3">Label</label>
			<div class="input col-md-3">
				<input type="text" name="label" id="label" value="<?php echo $label; ?>" />
			</div>
			<span class="description col-md-6">(e.g. Brands)</span>
		</div>

		<?php mdwcms_get_post_types_list($object_type); ?>

		<p class="submit"><input type="submit" name="add-tax" id="submit" class="button button-primary" value="<?php echo $btn_text; ?>" <?php echo $disabled; ?>></p>
		<input type="hidden" name="tax-id" id="tax-id" value=<?php echo $id; ?> />
	</form>

	<div class="custom-taxonomies-list col-md-4">
		<h3>Custom Taxonomies</h3>
		<?php
		mdwcms_display_existing_taxonomies(array(
			'base_url' => $base_url
		));
		?>
	</div><!-- .custom-taxonomies-list -->

</div><!-- .row -->