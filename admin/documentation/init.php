<?php
global $advanced_cms_doc_version;

$advanced_cms_doc_version='0.1.1';

/**
 * advanced_cms_load_documentation function.
 *
 * @access public
 * @return void
 */
function advanced_cms_load_documentation() {
	echo '<h2>Documentation</h2>';
	
	advanced_cms_admin_docs_pages_list();
}

/**
 * advanced_cms_admin_docs_pages_list function.
 *
 * @access public
 * @return void
 */
function advanced_cms_admin_docs_pages_list() {
	$html=null;
	$pages=advanced_cms_admin_docs_pages();

	if (empty($pages))
		return false;

	$html.='<ul class="advanced-cms-doc-pages">';

		foreach ($pages as $page) :
			$html.='<li><a href="'.advanced_cms_docs_page_link($page).'">'.advanced_cms_docs_page_name($page).'</a></li>';
		endforeach;

	$html.='</ul>';

	echo $html;
}

/**
 * advanced_cms_docs_page_name function.
 *
 * @access public
 * @param string $page (default: '')
 * @return void
 */
function advanced_cms_docs_page_name($page='') {
	$page=str_replace('-', ' ', $page); // remove -
	$page=ucwords($page); // Upper Case Words

	return $page;
}

/**
 * advanced_cms_docs_page_link function.
 *
 * @access public
 * @param string $page (default: '')
 * @return void
 */
function advanced_cms_docs_page_link($page='') {
	$url=add_query_arg(
		array(
			'page' => 'advanced-cms',
			'documentation' => $page,
		),
		admin_url('tools.php')
	);

	return $url;
}

/**
 * advanced_cms_admin_docs_pages function.
 *
 * @access public
 * @return void
 */
function advanced_cms_admin_docs_pages() {
	$filenames=array();

	if ($handle = opendir(ADVANCED_CMS_PATH.'admin/documentation/')) :

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
 * advanced_cms_get_doc_template function.
 *
 * @access public
 * @param string $template_name (default: '')
 * @param string $atts (default: '')
 * @return void
 */
function advanced_cms_get_doc_template($template_name='', $atts='') {
	if (empty($template_name))
		return false;

	ob_start();

	if (file_exists(ADVANCED_CMS_PATH.'admin/documentation/'.$template_name.'.php')) :
		include(ADVANCED_CMS_PATH.'admin/documentation/'.$template_name.'.php');
	endif;

	$html=ob_get_contents();

	ob_end_clean();

	return $html;
}

/**
 * advanced_cms_docs_breadcrumb function.
 *
 * @access public
 * @return void
 */
function advanced_cms_docs_breadcrumb() {
	$html=null;
	$current_page=isset($_GET['documentation']) ? $_GET['documentation'] : '';
	$doc_url=add_query_arg(array('page' => 'advanced-cms'), admin_url('tools.php'));

	$html.='<div class="advanced-cms-doc-breadcrumb">';
		$html.='<span class="first"><a href="'.$doc_url.'">Documentation</a></span>';
		$html.='<span class="sep">&rsaquo;</span>';
		$html.='<span class="last">'.advanced_cms_docs_page_name($current_page).'</span>';
	$html.='</div>';

	return $html;
}

function advanced_cms_doc_header($classes='') {
	$html=null;

	$html.='<div class="advanced-cms-documentation '.$classes.'">';
		$html.='<h2>Documentation</h2>';

		$html.=advanced_cms_docs_breadcrumb();

	echo $html;
}

/**
 * advanced_cms_doc_footer function.
 *
 * @access public
 * @return void
 */
function advanced_cms_doc_footer() {
	global $advanced_cms_doc_version;

	$html=null;

	$html.='</div>';

	$html.='<div class="advanced-cms-doc-version">Doc Version '.$advanced_cms_doc_version.'</div>';

	echo $html;
}
?>