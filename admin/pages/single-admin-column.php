<?php 
global $pickle_cms_admin;	

$args=pickle_cms_setup_admin_columns_args(); 
?>

<h3><?php echo $args['header']; ?> <a href="<?php pickle_cms_admin_link(array('tab' => 'columns', 'action' => 'update')); ?>" class="page-title-action">Add New</a></h3>

<div class="pickle-cms-admin-page single-admin-column">

	<form class="pickle-cms-admin-column" method="post">
		<?php wp_nonce_field('update_columns', 'pickle_cms_admin'); ?>

		<table class="form-table">
			<tbody>

				<?php echo $pickle_cms_admin->get_post_types_list('', 'dropdown'); ?>

				<tr>
					<th scope="row">
						<label for="label" class="required">Taxonomy/Meta</label>
					</th>
					<td>
						Choose from taxonomy and meta to display
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="label" class="required">Label</label>
					</th>
					<td>
						<input type="text" name="label" id="label" class="required" value="<?php echo $args['label']; ?>" />
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