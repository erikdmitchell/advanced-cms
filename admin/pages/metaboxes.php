<?php
	global $MDWMetaboxes, $mdw_cms_admin;
//print_r($mdw_cms_admin);
	$base_url=admin_url('tools.php?page=mdw-cms&tab=mdw-cms-metaboxes');
	$btn_text='Create';
	$html=null;
	$mb_id=null;
	$title=null;
	$prefix=null;
	$post_types=null;
	$edit_class_v='';
	$fields=false;
	$field_counter=0;
	$field_id=0;

	// edit //
	if (isset($_GET['edit']) && $_GET['edit']=='mb') :
		foreach ($this->options['metaboxes'] as $key => $mb) :
			if ($mb['mb_id']==$_GET['mb_id']) :
				extract($this->options['metaboxes'][$key]);
				$edit_class_v='visible';
				$btn_text='Update';
			endif;
		endforeach;
	endif;
?>

<h3>Add Metabox</h3>

<form class="custom-metabox" method="post">
	<?php wp_nonce_field('upadte_taxonomies', 'mdw_cms_admin'); ?>

	<table class="form-table">
		<tbody>

			<tr>
				<th scope="row">
					<label for="mb_id" class="required ">Metabox ID</label>
				</th>
				<td>
					<input type="text" name="mb_id" id="mb_id" class="" value="<?php echo $mb_id; ?>" /><span class="example">(e.g. movie_details)</span>
					<div class="mdw-cms-name-error" class=""></div>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="title" class="">Title</label>
				</th>
				<td>
					<input type="text" name="title" id="title" class="" value="<?php echo $title; ?>" /><span class="example">(e.g. Movie Details)</span>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="prefix" class="">Prefix</label>
				</th>
				<td>
					<input type="text" name="prefix" id="prefix" class="" value="<?php echo $prefix; ?>" /><span class="example">(e.g. movies)</span>
				</td>
			</tr>

			<?php echo $mdw_cms_admin->get_post_types_list($post_types); ?>

			<tr>
				<td class="add-fields sortable-div <?php echo $edit_class_v; ?>">
					<h3>Metabox Fields</h3>

					<?php if ($fields) : ?>
						<?php foreach ($fields as $field_id => $field) : ?>
							<?php $mdw_cms_admin->build_field_rows($field_id,$field,$field_counter); ?>
							<?php $field_counter++; ?>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if ($field_counter==0) : // 0 is default ie no fields exist ?>
						<?php $mdw_cms_admin->build_field_rows($field_id, '', $field_counter); // add 'default' field // ?>
					<?php endif; ?>
				</td>
			</tr>
		</tbody>
	</table>

	<p class="submit">
		<input type="submit" name="update-metabox" id="submit" class="button button-primary" value="<?php echo $btn_text; ?>">
		<input type="button" name="add-field" id="add-field-btn" class="button button-primary add-field" value="Add Field">
	</p>
</form>

<div class="custom-metabox-list">
	<h3>Custom Metaboxes</h3>

	<?php if ($mdw_cms_admin->options['metaboxes']) : ?>
		<?php foreach ($mdw_cms_admin->options['metaboxes'] as $mb) : ?>
			<div class="metabox-row">
				<span class="mb"><?php echo $mb['title']; ?></span><span class="edit">[<a href="<?php echo $base_url;?>&edit=mb&mb_id=<?php echo $mb['mb_id']; ?>">Edit</a>]</span><span class="delete">[<a href="<?php echo $base_url; ?>&delete=mb&mb_id=<?php echo $mb['mb_id']; ?>">Delete</a>]</span>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

</div>