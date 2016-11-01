<?php if (mdw_cms_gallery_have_images()) : ?>

	<div id="mdw-cms-carousel-<?php mdw_cms_gallery_id(); ?>" class="mdw-cms carousel slide" data-ride="carousel">

	  <?php mdw_cms_gallery_indicators(); ?>

		<div class="carousel-inner" role="listbox">
			<?php while ( mdw_cms_gallery_have_images() ) : mdw_cms_gallery_the_image(); ?>

		    <div class="<?php mdw_cms_image_class(); ?>">
		      <?php mdw_cms_gallery_image(); ?>

		      <?php if (mdw_cms_gallery_has_caption()) : ?>
			      <div class="carousel-caption">
			        <?php mdw_cms_gallery_image_caption(); ?>
						</div>
					<?php endif; ?>
		    </div>

		  <?php endwhile; ?>
		</div>

		<?php mdw_cms_gallery_controls(); ?>

	</div>

<?php endif; ?>