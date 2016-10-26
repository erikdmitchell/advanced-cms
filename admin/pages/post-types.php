<?php global $mdw_cms_admin; print_r($mdw_cms_admin->options['post_types']); ?>
<div class="">
	<table class="wp-list-table widefat fixed striped mdw-cms-post-types">
		<thead>
		<tr>
			<th scope="col" id="label" class="manage-column column-label column-primary">
				Post Type
			</th>
			<th scope="col" id="singular-label" class="manage-column column-singular-label">Singular Label</th>
			<th scope="col" id="description" class="manage-column column-description">Description</th>
		</thead>

		<tbody class="post-type-list">
			<?php if (count($mdw_cms_admin->options['post_types'])) : ?>
				<?php foreach($mdw_cms_admin->options['post_types'] as $id => $post_type) : ?>
					<tr id="item-<?php echo $id; ?>" class="item">
						<td class="column-label column-primary" data-colname="Post Type">
							<strong><a class="row-title" href=""><?php echo $post_type['label']; ?></a></strong>
						</td>
						<td class="column-singular-label" data-colname="Singular Label"><?php echo $post_type['singular_label']; ?></td>
						<td class="column-description" data-colname="Description"><?php echo $post_type['description']; ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>

			<?php endif; ?>
			</tbody>

		<tfoot>
		<tr>
			<td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Select All</label><input id="cb-select-all-2" type="checkbox"></td><th scope="col" class="manage-column column-title column-primary sortable desc"><a href="http://plugins.dev/wp-admin/edit.php?orderby=title&amp;order=asc"><span>Title</span><span class="sorting-indicator"></span></a></th><th scope="col" class="manage-column column-author">Author</th><th scope="col" class="manage-column column-categories">Categories</th><th scope="col" class="manage-column column-tags">Tags</th><th scope="col" class="manage-column column-comments num sortable desc"><a href="http://plugins.dev/wp-admin/edit.php?orderby=comment_count&amp;order=asc"><span><span class="vers comment-grey-bubble" title="Comments"><span class="screen-reader-text">Comments</span></span></span><span class="sorting-indicator"></span></a></th><th scope="col" class="manage-column column-date sortable asc"><a href="http://plugins.dev/wp-admin/edit.php?orderby=date&amp;order=desc"><span>Date</span><span class="sorting-indicator"></span></a></th>	</tr>
		</tfoot>

	</table>
</div>