<?php
	global $mdw_cms_admin;

	$html=null;
	$disable_bootstrap=false;

	print_r($mdw_cms_admin);
?>

<h3>Options</h3>

<div class="mdw-cms-default container">

	<form class="mdw-cms-options" method="post">
		<?php wp_nonce_field('update_main', 'mdw_cms_admin'); ?>

		<div class="mdw-cms-options-row">
			<label for="options[disable_bootstrap]" class="">Disable Bootstrap</label>
			<input type="checkbox" name="options[disable_bootstrap]" class="" value="1" <?php checked('1', $disable_bootstrap, false); ?> />
			<span class="description">If this box is checked, the MDW CMS bootstrap stylesheet will be disabled.</span>
			<div class="description-ext">
				Our admin pages utilize some bootstrap styles for responsiveness. In some cases, this can cause conflicts with
				other themes and/or plugins that also use bootstrap.
			</div>
		</div>

		<p class="submit"><input type="submit" name="update-options" id="update-options" class="button button-primary" value="Update Options"></p>
	</form>

	<p>
		For more information, please <a href="https://bitbucket.org/millerdesign/mdw-cms/wiki/">visit our WIKI</a>. At this time, only admins can
		access the wiki. If you need access please contact us.
	</p>

	<?php echo MDWCMSlegacy::get_legacy_page(); ?>
</div><!-- .mdw-cms-default -->