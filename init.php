<?php
/**
 * load_pre_headers function.
 *
 * @access public
 * @return void
 */
function load_admin_page_pre_headers() {
	// if not admin, we out //
	if (!is_admin())
		return false;

	// check we are on an admin page //
	if (!is_mdw_cms_admin_page())
		return false;

	$tab=get_mdw_cms_admin_tab();
	$filename=MDW_CMS_PATH.'admin/preheaders/'.$tab.'.php';

	if (file_exists($filename))
		include_once($filename);
}
add_action('admin_head', 'load_admin_page_pre_headers');

/**
 * mdw_cms_admin_pages_list function.
 *
 * @access public
 * @return void
 */
function mdw_cms_admin_pages_list() {
	$filenames=array();

	if ($handle = opendir(MDW_CMS_PATH.'admin/pages/')) :

		while (false !== ($entry = readdir($handle))) :

			if ($entry != "." && $entry != "..") :
				$filename=$entry;
				$filenames[]=preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
			endif;
		endwhile;

		closedir($handle);
	endif;

	return $filenames;
}
?>