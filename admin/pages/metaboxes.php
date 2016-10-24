<?php
global $MDWMetaboxes, $mdw_cms_admin;

$base_url=admin_url('tools.php?page=mdw-cms&tab=metaboxes');
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
$title='';

// edit //
if (isset($_GET['edit']) && $_GET['edit']=='mb') :
	$default_args=array(
		'mb_id' => '',
		'title' => '',
		'prefix' => '',
		'post_types' => null,
		'fields' => false,
		'edit_class_v' => 'visible',
		'btn_text' => 'Update',
	);
	$args=array();

	foreach ($mdw_cms_admin->options['metaboxes'] as $key => $mb) :
		if ($mb['mb_id']==$_GET['mb_id']) :
			$args=$mdw_cms_admin->options['metaboxes'][$key];
		endif;
	endforeach;

	$args=wp_parse_args($args, $default_args);

	extract($args);
endif;
?>

<h3><?php echo $title; ?></h3>

<div class="left-col">
	<form class="custom-metabox" method="post">
		<?php wp_nonce_field('update_metaboxes', 'mdw_cms_admin'); ?>

		<table class="form-table">
			<tbody>

				<tr>
					<th scope="row">
						<label for="mb_id" class="required ">Metabox ID</label>
					</th>
					<td>
						<input type="text" name="mb_id" id="mb_id" class="required" value="<?php echo $mb_id; ?>" /><span class="example">(e.g. movie_details)</span>
						<div id="mdw-cms-name-error" class=""></div>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="title" class="required">Title</label>
					</th>
					<td>
						<input type="text" name="title" id="title" class="required" value="<?php echo $title; ?>" /><span class="example">(e.g. Movie Details)</span>
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
					<td colspan="2" class="add-fields sortable-div <?php echo $edit_class_v; ?>">
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
</div>

<div class="custom-metabox-list mdw-cms-edit-list right-col">
	<h3>Custom Metaboxes</h3>

	<?php if ($mdw_cms_admin->options['metaboxes']) : ?>
		<?php foreach ($mdw_cms_admin->options['metaboxes'] as $mb) : ?>
			<div class="metabox-row mdw-cms-edit-list-row" data-id="<?php echo $mb['mb_id']; ?>">
				<div class="mb label"><?php echo $mb['title']; ?></div>
				<div class="edit">[<a href="<?php echo $base_url;?>&edit=mb&mb_id=<?php echo $mb['mb_id']; ?>">Edit</a>]</div>
				<div class="delete">[<a href="#">Delete</a>]</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>

</div>