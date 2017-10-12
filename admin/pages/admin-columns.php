<?php global $pickle_cms_admin; ?>

<div class="pickle-cms-admin-page admin-columns">

	<h2>Admin Columns <a href="<?php pickle_cms_admin_link(array('tab' => 'columns', 'action' => 'update')); ?>" class="page-title-action">Add New</a></h2>

	<table class="wp-list-table widefat fixed striped pickle-cms-admin-columns">
		<thead>
		<tr>
			<th scope="col" id="label" class="post-type">Post Type</th>
			<th scope="col" id="taxonomy-meta" class="taxonomy-meta">Taxonomy/Meta</th>
			<th scope="col" id="actions" class="actions">&nbsp;</th>
		</thead>

		<tbody class="admin-column-list">
			<?php if (count($pickle_cms_admin->options['admin_columns'])) : ?>
				<?php foreach($pickle_cms_admin->options['admin_columns'] as $id => $column) : ?>
					<tr id="admin-column-<?php echo $id; ?>" class="admin-column">
						<td class="post-type" data-colname="Post Type">
							<strong><a class="row-title" href="<?php pickle_cms_admin_link(array('tab' => 'columns', 'action' => 'update', 'post_type' => $column['post_type'], 'metabox_taxonomy' => $column['metabox_taxonomy'])); ?>"><?php echo $column['post_type']; ?></a></strong>
						</td>
						<td class="taxonomy-meta" data-colname="Taxonomy/Meta"><?php echo $column['metabox_taxonomy']; ?></td>
						<td class="actions" data-colname="Actions"><a href="<?php pickle_cms_admin_link(); ?>"><span class="dashicons dashicons-trash" data-slug=""></span></a></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>

</div>