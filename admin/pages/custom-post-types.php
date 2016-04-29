<?php
	global $mdw_cms_admin;

	$base_url=admin_url('tools.php?page=mdw-cms&tab=mdw-cms-cpt');
	$btn_text='Create';
	$name=null;
	$label=null;
	$singular_label=null;
	$description=null;
	$title=1;
	$thumbnail=1;
	$editor=1;
	$revisions=1;
	$hierarchical=0;
	$page_attributes=0;
	$id=-1;
	$btn_disabled='disabled';
	$comments=0;

	// edit custom post type //
	if (isset($_GET['edit']) && $_GET['edit']=='cpt') :
		foreach ($mdw_cms_admin->options['post_types'] as $key => $cpt) :
			if ($cpt['name']==$_GET['slug']) :
				extract($mdw_cms_admin->options['post_types'][$key]);
				$id=$key;
			endif;
		endforeach;
		$btn_disabled=null;
	endif;

	if ($id!=-1)
		$btn_text='Update';
?>


<div class="row">

	<form class="custom-post-types col-md-8" method="post">
		<h3>Add New Custom Post Type</h3>
		<div class="form-row row">
			<label for="name" class="required ">Post Type Name</label>
			<div class="input">
				<input type="text" name="name" id="name" value="<?php echo $name; ?>" />
			</div>
			<span class="description">(e.g. movie)</span>
			<div id="mdw-cms-name-error" class="<?php echo $error_class; ?>"></div>
			<div class="description-ext">Max 20 characters, can not contain capital letters or spaces. Reserved post types: post, page, attachment, revision, nav_menu_item.</div>
		</div>

		<div class="form-row row">
			<label for="label" class="">Label</label>
			<div class="input">
				<input type="text" name="label" id="label" value="<?php echo $label; ?>" />
			</div>
			<span class="description">(e.g. Movies)</span>
		</div>

		<div class="form-row row">
			<label for="singular_label" class="">Singular Label</label>
			<div class="input">
				<input type="text" name="singular_label" id="singular_label" value="<?php echo $singular_label; ?>" />
			</div>
			<span class="description">(e.g. Movie)</span>
		</div>

		<div class="form-row row">
			<label for="description" class="">Description</label>
			<textarea name="description" id="description" rows="4" cols="40"><?php echo $description; ?></textarea>
		</div>

		<div class="advanced-options">
			<div class="form-row row">
				<label for="title" class="">Title</label>
				<div class="">
					<select name="title" id="title">
						<option value="1" <?php selected ($title,1,false); ?>>True</option>
						<option value="0" <?php selected ($title,0,false); ?>>False</option>
					</select>
				</div>
				<span class="description">(default True)</span>
			</div>
			<div class="form-row row">
				<label for="thumbnail" class="">Thumbnail</label>
				<div class="">
					<select name="thumbnail" id="thumbnaill">
						<option value="1" <?php selected ($thumbnail,1,false); ?>>True</option>
						<option value="0" <?php selected ($thumbnail,0,false); ?>>False</option>
					</select>
				</div>
				<span class="description">(default True)</span>
			</div>
			<div class="form-row row">
				<label for="editor" class="">Editor</label>
				<div class="">
					<select name="editor" id="editor" >
						<option value="1" <?php selected ($editor,1,false); ?>>True</option>
						<option value="0" <?php selected ($editor,0,false); ?>>False</option>
					</select>
				</div>
				<span class="description">(default True)</span>
			</div>
			<div class="form-row row">
				<label for="revisions" class="">Revisions</label>
				<div class="">
					<select name="revisions" id="revisions">
						<option value="1" <?php selected ($revisions,1,false); ?>>True</option>
						<option value="0" <?php selected ($revisions,0,false); ?>>False</option>
					</select>
				</div>
				<span class="description">(default True)</span>
			</div>
			<div class="form-row row">
				<label for="hierarchical" class="">Hierarchical</label>
				<div class="">
					<select name="hierarchical" id="hierarchical">
						<option value="1" <?php selected ($hierarchical,1,false); ?>>True</option>
						<option value="0" <?php selected ($hierarchical,0,false); ?>>False</option>
					</select>
				</div>
				<span class="description">(default False)</span>
				<div class="description-ext">Whether the post type is hierarchical (e.g. page). Allows Parent to be specified. Note: "page-attributes" must be set to true to show the parent select box.</div>
			</div>

			<div class="form-row row">
				<label for="page_attributes" class="">Page Attributes</label>
				<div class="">
					<select name="page_attributes" id="page_attributes">
						<option value="1" <?php selected ($page_attributes,1,false); ?>>True</option>
						<option value="0" <?php selected ($page_attributes,0,false); ?>>False</option>
					</select>
				</div>
				<span class="description">(default False)</span>
			</div>

			<div class="form-row row">
				<label for="comments" class=""><?php echo __('Comments'); ?></label>
				<div class="">
					<select name="comments" id="comments">
						<option value="1" <?php selected ($comments,1,false); ?>>True</option>
						<option value="0" <?php selected ($comments,0,false); ?>>False</option>
					</select>
				</div>
				<span class="description">(default False)</span>
			</div>

		</div>
		<p class="submit"><input type="submit" name="add-cpt" id="submit" class="button button-primary" value="<?php echo $btn_text; ?>" <?php echo $btn_disabled; ?>></p>
		<input type="hidden" name="cpt-id" id="cpt-id" value=<?php echo $id; ?> />
	</form>

	<div class="custom-post-types-list col-md-4">
		<h3>Custom Post Types</h3>

		<?php if ($mdw_cms_admin->options['post_types']) : ?>
			<?php foreach ($mdw_cms_admin->options['post_types'] as $cpt) : ?>
				<div class="cpt-row row">
					<span class="cpt>"><?php echo $cpt['label']; ?></span><span class="edit">[<a href="<?php echo $base_url; ?>&edit=cpt&slug=<?php echo $cpt['name']; ?>">Edit</a>]</span><span class="delete">[<a href="<?php echo $base_url; ?>&delete=cpt&slug=<?php echo $cpt['name']; ?>">Delete</a>]</span>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>

	</div>

</div><!-- .row -->