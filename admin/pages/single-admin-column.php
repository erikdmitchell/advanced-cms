<?php 
global $pickle_cms_admin;	

$args=pickle_cms_setup_admin_columns_args(); 
?>

<h3><?php echo $args['header']; ?> <a href="<?php pickle_cms_admin_link(array('tab' => 'columns', 'action' => 'update')); ?>" class="page-title-action">Add New</a></h3>

<div class="pickle-cms-admin-page single-admin-column">

	<form class="custom-post-types" method="post">
		<?php wp_nonce_field('update_columns', 'pickle_cms_admin'); ?>

		<table class="form-table">
			<tbody>

				<?php echo $pickle_cms_admin->get_post_types_list('', 'dropdown'); ?>

				<tr>
					<th scope="row">
						<label for="label" class="required">Label</label>
					</th>
					<td>
						Choose from taxonomy and meta to display
						<input type="text" name="label" id="label" class="required" value="<?php echo $args['label']; ?>" /><span class="example">(e.g. Movies)</span>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="singular_label" class="">Singular Label</label>
					</th>
					<td>
						<input type="text" name="singular_label" id="singular_label" value="<?php echo $args['singular_label']; ?>" /><span class="example">(e.g. Movie)</span>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="description" class="">Description</label>
					</th>
					<td>
						<textarea name="description" id="description" rows="4" cols="40"><?php echo $args['description']; ?></textarea>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="title" class="">Title</label>
					</th>
					<td>
						<select name="supports[title]" id="title">
							<option value="1" <?php selected($args['supports']['title'], 1); ?>>True</option>
							<option value="0" <?php selected($args['supports']['title'], 0); ?>>False</option>
						</select>
						<span class="example">(default True)</span>
					</td>
				</tr>

			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="add-cpt" id="submit" class="button button-primary" value="<?php echo $args['btn_text']; ?>">
		</p>

		<input type="hidden" name="cpt-id" id="cpt-id" value=<?php echo $args['id']; ?> />
	</form>

</div>