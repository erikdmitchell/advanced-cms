<?php

/**
 * mdw_cms_load_documentation function.
 *
 * @access public
 * @return void
 */
function mdw_cms_load_documentation() {
	echo '<h2>Documentation</h2>';
	mdw_cms_admin_docs_pages_list();
}
add_action('mdw_cms_main_age', 'mdw_cms_load_documentation');

/**
 * mdw_cms_admin_docs_pages_list function.
 *
 * @access public
 * @return void
 */
function mdw_cms_admin_docs_pages_list() {
	$html=null;
	$pages=mdw_cms_admin_docs_pages();

	if (empty($pages))
		return false;

	$html.='<ul class="mdw-cms-doc-pages">';

		foreach ($pages as $page) :
			$html.='<li><a href="'.mdw_cms_docs_page_link($page).'">'.mdw_cms_docs_page_name($page).'</a></li>';
		endforeach;

	$html.='</ul>';

	echo $html;
}

/**
 * mdw_cms_docs_page_name function.
 *
 * @access public
 * @param string $page (default: '')
 * @return void
 */
function mdw_cms_docs_page_name($page='') {
	$page=str_replace('-', ' ', $page); // remove -
	$page=ucwords($page); // Upper Case Words

	return $page;
}

/**
 * mdw_cms_docs_page_link function.
 *
 * @access public
 * @param string $page (default: '')
 * @return void
 */
function mdw_cms_docs_page_link($page='') {
	$url=add_query_arg(
		array(
			'page' => 'mdw-cms',
			'documentation' => $page,
		),
		admin_url('tools.php')
	);

	return $url;
}

/**
 * mdw_cms_admin_docs_pages function.
 *
 * @access public
 * @return void
 */
function mdw_cms_admin_docs_pages() {
	$filenames=array();

	if ($handle = opendir(MDW_CMS_PATH.'admin/documentation/')) :

		while (false !== ($entry = readdir($handle))) :

			if ($entry != "." && $entry != "..") :
				$filename=$entry;

				if ($filename=='init.php')
					continue;

				$filenames[]=preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
			endif;
		endwhile;

		closedir($handle);
	endif;

	return $filenames;
}

/**
 * mdw_cms_get_doc_template function.
 *
 * @access public
 * @param string $template_name (default: '')
 * @param string $atts (default: '')
 * @return void
 */
function mdw_cms_get_doc_template($template_name='', $atts='') {
	if (empty($template_name))
		return false;

	ob_start();

	if (file_exists(MDW_CMS_PATH.'admin/documentation/'.$template_name.'.php')) :
		include(MDW_CMS_PATH.'admin/documentation/'.$template_name.'.php');
	endif;

	$html=ob_get_contents();

	ob_end_clean();

	return $html;
}

/**
 * mdw_cms_docs_breadcrumb function.
 *
 * @access public
 * @return void
 */
function mdw_cms_docs_breadcrumb() {
	$html=null;
	$current_page=isset($_GET['documentation']) ? $_GET['documentation'] : '';
	$doc_url=add_query_arg(array('page' => 'mdw-cms'), admin_url('tools.php'));

	$html.='<div class="mdw-cms-doc-breadcrumb">';
		$html.='<span class="first"><a href="'.$doc_url.'">Documentation</a></span>';
		$html.='<span class="sep">&rsaquo;</span>';
		$html.='<span class="last">'.mdw_cms_docs_page_name($current_page).'</span>';
	$html.='</div>';

	return $html;
}

function mdw_cms_doc_header($classes='') {
	$html=null;

	$html.='<div class="mdw-cms-documentation '.$classes.'">';
		$html.='<h2>Documentation</h2>';

		$html.=mdw_cms_docs_breadcrumb();

	echo $html;
}

/**
 * mdw_cms_doc_footer function.
 *
 * @access public
 * @return void
 */
function mdw_cms_doc_footer() {
	$html=null;

	$html.='</div>';

	echo $html;
}
?>