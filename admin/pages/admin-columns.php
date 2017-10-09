<?php global $pickle_cms_admin; ?>

<div class="pickle-cms-admin-page admin-columns">

	<h2>Admin Columns <a href="<?php pickle_cms_admin_link(array('tab' => 'columns', 'action' => 'update')); ?>" class="page-title-action">Add New</a></h2>

	<table class="wp-list-table widefat fixed striped pickle-cms-admin-columns">
		<thead>
		<tr>
			<th scope="col" id="label" class="post-type">Post Type</th>
			<th scope="col" id="singular-label" class="singular-label">Admin Columns</th>
			<th scope="col" id="actions" class="actions">&nbsp;</th>
		</thead>

		<tbody class="post-type-list">
			<?php if (count($pickle_cms_admin->options['columns'])) : ?>
				<?php foreach($pickle_cms_admin->options['columns'] as $id => $post_type) : ?>
					<tr id="post-type-<?php echo $id; ?>" class="post-type">
						<td class="post-type" data-colname="Post Type">
							<strong><a class="row-title" href="<?php pickle_cms_admin_link(array('tab' => 'post-types', 'action' => 'update', 'slug' => $post_type['name'])); ?>"><?php echo $post_type['label']; ?></a></strong>
						</td>
						<td class="singular-label" data-colname="Singular Label"><?php echo $post_type['singular_label']; ?></td>
						<td class="description" data-colname="Description"><?php echo $post_type['description']; ?></td>
						<td class="actions" data-colname="Actions"><a href="<?php pickle_cms_admin_link(); ?>"><span class="dashicons dashicons-trash" data-slug="<?php echo $post_type['name']; ?>"></span></a></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>

</div>