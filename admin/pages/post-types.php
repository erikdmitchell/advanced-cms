<?php
global $mdw_cms_options;

$id=-1;
$name=null;
$label=null;
$singular_label=null;
$description=null;
$title=1;
$thumbnail=1;
$editor=1;
$revisions=1;
$excerpt=0;
$hierarchical=0;
$page_attributes=0;

// when a cpt is created is runs a fake form so the page refreshes properly //
if (isset($_POST['create-cpt']) && $_POST['create-cpt'])
	$id=$_POST['id'];

// load cpt if we have one //
if ($id!=-1) :
	extract($mdw_cms_options['post_types'][$id]);
endif;
?>

<div class="wrap">
	<h2>Add New Custom Post Type</h2>

	<form action="" method="post" class="custom-post-types">
		<input type="hidden" name="update_mdw_cms_post_type" value="1">
		<input type="hidden" name="cpt-id" id="cpt-id" value=<?php echo $id; ?> />
		<input type="hidden" name="cpt-prev-name" id="cpt-prev-name" value=<?php echo $name; ?> />
		<?php wp_nonce_field('update_cpt','mdw_cms_admin'); ?>

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="name" class="required">Post Type Name</label></th>
					<td>
						<input name="name" type="text" id="name" value="<?php echo $name; ?>" class="regular-text">
						<div id="mdw-cms-name-error" class="<?php echo $error_class; ?>"></div>
						<p class="description">Max 20 characters, can not contain capital letters or spaces. Reserved post types: post, page, attachment, revision, nav_menu_item.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="label" class="">Label</label></th>
					<td>
						<input name="label" type="text" id="label" value="<?php echo $label; ?>" class="regular-text">
						<span class="description right">(e.g. Movies)</span>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="singular_label" class="">Singular Label</label></th>
					<td>
						<input name="singular_label" type="text" id="singular_label" value="<?php echo $singular_label; ?>" class="regular-text">
						<span class="description right">(e.g. Movie)</span>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="description" class="">Description</label></th>
					<td>
						<textarea name="description" rows="5" cols="45" id="description" class=""><?php echo $description; ?></textarea>
					</td>
				</tr>
				<tr class="avanced-option">
					<th scope="row"><label for="title">Title</label></th>
					<td>
						<select name="title" id="title">
							<option value="1" <?php echo selected($title,1,false); ?>>True</option>
							<option value="0" <?php echo selected($title,0,false); ?>>False</option>
						</select>
						<span class="description right">(default True)</span>
					</td>
				</tr>
				<tr class="avanced-option">
					<th scope="row"><label for="thumbnail">Thumbnail</label></th>
					<td>
						<select name="thumbnail" id="thumbnail">
							<option value="1" <?php echo selected($thumbnail,1,false); ?>>True</option>
							<option value="0" <?php echo selected($thumbnail,0,false); ?>>False</option>
						</select>
						<span class="description right">(default True)</span>
					</td>
				</tr>
				<tr class="avanced-option">
					<th scope="row"><label for="editor">Editor</label></th>
					<td>
						<select name="editor" id="editor">
							<option value="1" <?php echo selected($editor,1,false); ?>>True</option>
							<option value="0" <?php echo selected($editor,0,false); ?>>False</option>
						</select>
						<span class="description right">(default True)</span>
					</td>
				</tr>
				<tr class="avanced-option">
					<th scope="row"><label for="revisions">Revisions</label></th>
					<td>
						<select name="revisions" id="revisions">
							<option value="1" <?php echo selected($revisions,1,false); ?>>True</option>
							<option value="0" <?php echo selected($revisions,0,false); ?>>False</option>
						</select>
						<span class="description right">(default True)</span>
					</td>
				</tr>
				<tr class="avanced-option">
					<th scope="row"><label for="excerpt">Excerpt</label></th>
					<td>
						<select name="excerpt" id="disp_excerpt">
							<option value="1" <?php echo selected($excerpt,1,false); ?>>True</option>
							<option value="0" <?php echo selected($excerpt,0,false); ?>>False</option>
						</select>
						<span class="description right">(default False)</span>
					</td>
				</tr>
				<tr class="avanced-option">
					<th scope="row"><label for="hierarchical">Hierarchical</label></th>
					<td>
						<select name="hierarchical" id="hierarchical">
							<option value="1" <?php echo selected($hierarchical,1,false); ?>>True</option>
							<option value="0" <?php echo selected($hierarchical,0,false); ?>>False</option>
						</select>
						<span class="description right">(default False)</span>
						<p class="description">Whether the post type is hierarchical (e.g. page). Allows Parent to be specified. Note: "page-attributes" must be set to true to show the parent select box.</p>
					</td>
				</tr>
				<tr class="avanced-option">
					<th scope="row"><label for="page_attributes">Page Attributes</label></th>
					<td>
						<select name="page_attributes" id="page_attributes">
							<option value="1" <?php echo selected($page_attributes,1,false); ?>>True</option>
							<option value="0" <?php echo selected($page_attributes,0,false); ?>>False</option>
						</select>
						<span class="description right">(default False)</span>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit"><?php mdw_cms_post_types_submit_button(); ?></p>

	</form>

	<div class="custom-post-types-list col-md-4">
		<h2>Custom Post Types</h2>

		<?php if (isset($mdw_cms_options['post_types'])) : ?>
			<?php foreach ($mdw_cms_options['post_types'] as $key => $cpt) : ?>
				<div id="cpt-list-<?php echo $key; ?>" class="cpt-row row mdw-cms-edit-delete-list">
					<span class="cpt "><?php echo $cpt['label']; ?></span>
					<span class="edit">[<a href="" data-tab-url="<?php echo $this->tab_url; ?>" data-item-type="cpt" data-slug="<?php echo $cpt['name']; ?>" data-page-action="edit" data-action="update_cpt" data-id="<?php echo $key; ?>" data-title="Custom Post Type">Edit</a>]</span>
					<span class="delete <?php echo $delete_class; ?>">[<a href="" data-tab-url="<?php echo $this->tab_url; ?>" data-item-type="cpt" data-slug="<?php echo $cpt['name']; ?>" data-page-action="delete" data-action="update_cpt" data-id="<?php echo $key; ?>" data-title="Custom Post Type">Delete</a>]</span>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

</div><!-- .wrap -->