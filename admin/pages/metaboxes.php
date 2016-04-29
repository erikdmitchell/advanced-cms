<?php
	global $MDWMetaboxes;

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
				$edit_class_v='visible
				$btn_text='Update
			endif;
		endforeach;
	endif;
?>

<div class="row">

	<form class="custom-metabox col-md-8" method="post">
		<h3>Add Metabox</h3>
		<div class="form-row row">
			<label for="mb_id" class="required ">Metabox ID</label>
			<div class="input">
				<input type="text" name="mb_id" id="mb_id" class="" value="'.$mb_id.'" />
			</div>
			<span class="description">(e.g. movie_details)</span>
			<div class="mdw-cms-name-error col-md-6 col-md-offset-3"></div>
		</div>

		<div class="form-row row">
			<label for="title" class="">Title</label>
			<div class="input">
				<input type="text" name="title" id="title" class="" value="'.$title.'" />
			</div>
			<span class="description">(e.g. Movie Details)</span>
		</div>

		<div class="form-row row">
			<label for="prefix" class="">Prefix</label>
			<div class="input">
				<input type="text" name="prefix" id="prefix" class="" value="'.$prefix.'" />
			</div>
			<span class="description">(e.g. movies)</span>
		</div>

		<?php echo get_post_types_list($post_types); ?>

		<div class="add-fields sortable-div '.$edit_class_v.'">

			<h3>Metabox Fields</h3>

			<?php if ($fields) : ?>
				<?php foreach ($fields as $field_id => $field) : ?>
					<?php echo $this->build_field_rows($field_id,$field,$field_counter); ?>
					<?php $field_counter++; ?>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ($field_counter==0) : // 0 is default ie no fields exist ?>
				<?php echo $this->build_field_rows($field_id,null,$field_counter); // add 'default' field // ?>
			<?php endif; ?>

		</div><!-- .add-fields -->
		<p class="submit">
			<input type="submit" name="update-metabox" id="submit" class="button button-primary" value="'.$btn_text.'">
			<input type="button" name="add-field" id="add-field-btn" class="button button-primary add-field" value="Add Field">
		</p>
	</form>

	<div class="custom-metabox-list col-md-4">
		<h3>Custom Metaboxes</h3>

		<?php if ($this->options['metaboxes']) : ?>
			<?php foreach ($this->options['metaboxes'] as $mb) : ?>
				<div class="metabox-row row">
					<span class="mb '.$existing_label_class.'">'.$mb['title'].'</span><span class="edit '.$edit_class.'">[<a href="'.$base_url.'&edit=mb&mb_id='.$mb['mb_id'].'">Edit</a>]</span><span class="delete '.$delete_class.'">[<a href="'.$base_url.'&delete=mb&mb_id='.$mb['mb_id'].'">Delete</a>]</span>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>

	</div>

</div>