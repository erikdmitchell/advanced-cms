<?php
global $pickle_cms_doc_version;

$pickle_cms_doc_version='0.1.0';

/**
 * pickle_cms_load_documentation function.
 *
 * @access public
 * @return void
 */
function pickle_cms_load_documentation() {
	echo '<h2>Documentation</h2>';
	
	pickle_cms_admin_docs_pages_list();
}

/**
 * pickle_cms_admin_docs_pages_list function.
 *
 * @access public
 * @return void
 */
function pickle_cms_admin_docs_pages_list() {
	$html=null;
	$pages=pickle_cms_admin_docs_pages();

	if (empty($pages))
		return false;

	$html.='<ul class="pickle-cms-doc-pages">';

		foreach ($pages as $page) :
			$html.='<li><a href="'.pickle_cms_docs_page_link($page).'">'.pickle_cms_docs_page_name($page).'</a></li>';
		endforeach;

	$html.='</ul>';

	echo $html;
}

/**
 * pickle_cms_docs_page_name function.
 *
 * @access public
 * @param string $page (default: '')
 * @return void
 */
function pickle_cms_docs_page_name($page='') {
	$page=str_replace('-', ' ', $page); // remove -
	$page=ucwords($page); // Upper Case Words

	return $page;
}

/**
 * pickle_cms_docs_page_link function.
 *
 * @access public
 * @param string $page (default: '')
 * @return void
 */
function pickle_cms_docs_page_link($page='') {
	$url=add_query_arg(
		array(
			'page' => 'pickle-cms',
			'documentation' => $page,
		),
		admin_url('tools.php')
	);

	return $url;
}

/**
 * pickle_cms_admin_docs_pages function.
 *
 * @access public
 * @return void
 */
function pickle_cms_admin_docs_pages() {
	$filenames=array();

	if ($handle = opendir(PICKLE_CMS_PATH.'admin/documentation/')) :

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
 * pickle_cms_get_doc_template function.
 *
 * @access public
 * @param string $template_name (default: '')
 * @param string $atts (default: '')
 * @return void
 */
function pickle_cms_get_doc_template($template_name='', $atts='') {
	if (empty($template_name))
		return false;

	ob_start();

	if (file_exists(PICKLE_CMS_PATH.'admin/documentation/'.$template_name.'.php')) :
		include(PICKLE_CMS_PATH.'admin/documentation/'.$template_name.'.php');
	endif;

	$html=ob_get_contents();

	ob_end_clean();

	return $html;
}

/**
 * pickle_cms_docs_breadcrumb function.
 *
 * @access public
 * @return void
 */
function pickle_cms_docs_breadcrumb() {
	$html=null;
	$current_page=isset($_GET['documentation']) ? $_GET['documentation'] : '';
	$doc_url=add_query_arg(array('page' => 'pickle-cms'), admin_url('tools.php'));

	$html.='<div class="pickle-cms-doc-breadcrumb">';
		$html.='<span class="first"><a href="'.$doc_url.'">Documentation</a></span>';
		$html.='<span class="sep">&rsaquo;</span>';
		$html.='<span class="last">'.pickle_cms_docs_page_name($current_page).'</span>';
	$html.='</div>';

	return $html;
}

/**
 * pickle_cms_doc_header function.
 * 
 * @access public
 * @param string $classes (default: '')
 * @return void
 */
function pickle_cms_doc_header($classes='') {
	$html=null;

	$html.='<div class="pickle-cms-documentation '.$classes.'">';
		$html.='<h2>Documentation</h2>';

		$html.=pickle_cms_docs_breadcrumb();

	echo $html;
}

/**
 * pickle_cms_doc_footer function.
 *
 * @access public
 * @return void
 */
function pickle_cms_doc_footer() {
	global $pickle_cms_doc_version;

	$html=null;

	$html.='</div>';

	$html.='<div class="pickle-cms-doc-version">Doc Version '.$pickle_cms_doc_version.'</div>';

	echo $html;
}
?>