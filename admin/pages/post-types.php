<?php global $mdw_cms_admin; ?>

<div class="mdw-cms-admin-page post-types-page">

	<h2>Post Types <a href="<?php mdw_cms_admin_link(array('tab' => 'post-types', 'action' => 'update')); ?>" class="page-title-action">Add New</a></h2>

	<table class="wp-list-table widefat fixed striped mdw-cms-post-types">
		<thead>
		<tr>
			<th scope="col" id="label" class="post-type">
				Post Type
			</th>
			<th scope="col" id="singular-label" class="singular-label">Singular Label</th>
			<th scope="col" id="description" class="description">Description</th>
			<th scope="col" id="actions" class="actions">&nbsp;</th>
		</thead>

		<tbody class="post-type-list">
			<?php if (count($mdw_cms_admin->options['post_types'])) : ?>
				<?php foreach($mdw_cms_admin->options['post_types'] as $id => $post_type) : ?>
					<tr id="item-<?php echo $id; ?>" class="item">
						<td class="post-type" data-colname="Post Type">
							<strong><a class="row-title" href="<?php mdw_cms_admin_link(array('tab' => 'post-types', 'action' => 'update', 'slug' => $post_type['name'])); ?>"><?php echo $post_type['label']; ?></a></strong>
						</td>
						<td class="singular-label" data-colname="Singular Label"><?php echo $post_type['singular_label']; ?></td>
						<td class="description" data-colname="Description"><?php echo $post_type['description']; ?></td>
						<td class="actions" data-colname="Actions"><a href="<?php mdw_cms_admin_link(); ?>"><span class="dashicons dashicons-trash"></span></a></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>

			<?php endif; ?>
			</tbody>

		<tfoot>
			<tr>
			</tr>
		</tfoot>

	</table>

</div>