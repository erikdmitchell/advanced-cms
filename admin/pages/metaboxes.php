<div class="pickle-cms-admin-page metaboxes-page">

	<h2>Metaboxes <a href="<?php pickle_cms_admin_link(array('tab' => 'metaboxes', 'action' => 'update')); ?>" class="page-title-action">Add New</a></h2>

	<table class="wp-list-table widefat fixed striped pickle-cms-metaboxes">
		<thead>
		<tr>
			<th scope="col" id="id" class="id">ID</th>
			<th scope="col" id="metabox" class="metabox">Metabox</th>
			<th scope="col" id="prefix" class="prefix">Prefix</th>
			<th scope="col" id="post-types" class="post-types">Post Types</th>
			<th scope="col" id="actions" class="actions">&nbsp;</th>
		</thead>

		<tbody class="metaboxes-list">
			<?php if (count(picklecms()->admin->components['metaboxes']->items)) : ?>
				<?php foreach(picklecms()->admin->components['metaboxes']->items as $metabox) : ?>
					<tr id="metabox-<?php echo $metabox['mb_id']; ?>">
						<td class="id" data-colname="ID">
							<?php echo $metabox['mb_id']; ?>
						</td>
						<td class="metabox" data-colname="Metabox">
							<strong><a class="row-title" href="<?php pickle_cms_admin_link(array('tab' => 'metaboxes', 'action' => 'update', 'id' => $metabox['mb_id'])); ?>"><?php echo $metabox['title']; ?></a></strong>
						</td>
						<td class="prefix" data-colname="Prefix"><?php echo $metabox['prefix']; ?></td>
						<td class="post-types" data-colname="Post Types"><?php pickle_cms_metabox_post_types_list($metabox['post_types']); ?></td>
						<td class="actions" data-colname="Actions"><a href="<?php pickle_cms_admin_link(); ?>"><span class="dashicons dashicons-trash" data-id="<?php echo $metabox['mb_id']; ?>"></span></a></td>
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