<?php
/**
 * adminDefault class.
 */
class adminDefault {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action('admin_enqueue_scripts',array($this,'admin_scripts_styles'));
		add_action('init',array($this,'add_page'));
	}

	/**
	 * admin_scripts_styles function.
	 *
	 * @access public
	 * @param mixed $hook
	 * @return void
	 */
	public function admin_scripts_styles($hook) {
		$disable_bootstrap=false;

		if (isset($this->options['options']) && is_array($this->options['options']))
			extract($this->options['options']);

		if (!$disable_bootstrap) :
			wp_enqueue_style('mdw-cms-bootstrap-custom-style',plugins_url('/css/bootstrap.css',__FILE__));
		endif;
	}

	/**
	 * add_page function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_page() {
		mdw_cms_add_admin_page(array(
			'id' => 'main',
			'name' => 'Main',
			'function' => array($this,'admin_page'),
			'order' => 0,
			'options' => array(
				'disable_bootstrap' => 0
			)
		));
	}

	/**
	 * admin_page function.
	 *
	 * the main (default) admin page. acts as a landing page.
	 *
	 * @access public
	 * @return void
	 */
	function admin_page() {
		$html=null;
		$disable_bootstrap=false;
		$options=array();
		$label_class='col-md-3';
		$input_class='col-md-3';
		$description_class='col-md-6';
		$description_ext_class='col-md-9 col-md-offset-3';

		//if (isset($this->options['options']))
			//$options=$this->options['options'];

		$html.='<h3>Options</h3>';

/*
		if (isset($_POST['update-options']) && isset($_POST['options'])) :
			$options=$this->update_options($_POST['options']);
		endif;
*/

		if (is_array($options))
			extract($options);

		$html.='<div class="mdw-cms-default">';

			$html.='<form class="mdw-cms-options" method="post">';

				$html.='<div class="mdw-cms-options-row row">';
					$html.='<label for="options[disable_bootstrap]" class="'.$label_class.'">Disable Bootstrap</label>';
					$html.='<input type="checkbox" name="options[disable_bootstrap]" class="'.$input_class.'" value="1" '.checked('1',$disable_bootstrap, false).' />';
					$html.='<span class="description '.$description_class.'">If this box is checked, the MDW CMS bootstrap stylesheet will be disabled.</span>';
					$html.='<div class="description-ext '.$description_ext_class.'">Our admin pages utilize some bootstrap styles for responsiveness. In some cases, this can cause conflicts with other themes and/or plugins that also use bootstrap.</div>';
				$html.='</div>';

				$html.='<p class="submit"><input type="submit" name="update-options" id="update-options" class="button button-primary" value="Update Options"></p>';
				$html.='<input type="hidden" name="options[update]" value="1" />';

			$html.='</form>';

			$html.='<p>';
				$html.='For more information, please <a href="https://bitbucket.org/millerdesign/mdw-cms/wiki/">visit our WIKI</a>. At this time, only admins can access the wiki. If you need access please contact us.';
			$html.='</p>';

			$html.=MDWCMSlegacy::get_legacy_page();
		$html.='</div><!-- .mdw-cms-default -->';

		echo $html;
	}

}

new adminDefault();
?>