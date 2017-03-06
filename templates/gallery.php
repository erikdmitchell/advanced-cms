<?php if (advanced_cms_gallery_have_images()) : ?>

	<div id="advanced-cms-carousel-<?php advanced_cms_gallery_id(); ?>" class="advanced-cms carousel slide" data-ride="carousel">

	  <?php advanced_cms_gallery_indicators(); ?>

		<div class="carousel-inner" role="listbox">
			<?php while ( advanced_cms_gallery_have_images() ) : advanced_cms_gallery_the_image(); ?>

		    <div class="<?php advanced_cms_image_class(); ?>">
		      <?php advanced_cms_gallery_image(); ?>

		      <?php if (advanced_cms_gallery_has_caption()) : ?>
			      <div class="carousel-caption">
			        <?php advanced_cms_gallery_image_caption(); ?>
						</div>
					<?php endif; ?>
		    </div>

		  <?php endwhile; ?>
		</div>

		<?php advanced_cms_gallery_controls(); ?>

	</div>

<?php endif; ?>