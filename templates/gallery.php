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

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>