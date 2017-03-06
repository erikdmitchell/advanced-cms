<?php
global $advanced_cms_gallery;
global $advanced_cms_image;

class advancedCMSGallery {

	public $images;

	public $current_image=-1;

	public $image_count=0;

	public $image;

	public $id;

	public $show_indicators;

	public $show_controls;

	public $bootstrap=0;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param string $args (default: '')
	 * @return void
	 */
	public function __construct($args='') {
		global $advanced_cms_gallery;

		if (!empty($args))
			$advanced_cms_gallery=$this->query($args);
	}

	/**
	 * default_query_vars function.
	 *
	 * @access public
	 * @return void
	 */
	public function default_query_vars() {
		$array=array(
			'id' => 'gallery',
			'image_ids' => '',
			'size' => 'full',
			'show_indicators' => true,
			'show_controls' => true,
		);

		return $array;
	}

	/**
	 * set_query_vars function.
	 *
	 * @access public
	 * @param string $query (default: '')
	 * @return void
	 */
	public function set_query_vars($query='') {
		$args=wp_parse_args($query, $this->default_query_vars());

		if (!empty($args['image_ids']) && !is_array($args['image_ids']))
			$args['image_ids']=explode(',', $args['image_ids']);

		if ($args['show_indicators']) :
			$this->show_indicators=true;
		else :
			$this->show_indicators=false;
		endif;

		if ($args['show_controls']) :
			$this->show_controls=true;
		else :
			$this->show_controls=false;
		endif;

		if ($args['bootstrap']) :
			$this->bootstrap=true;
		else :
			$this->bootstrap=false;
		endif;

		return $args;
	}

	/**
	 * query function.
	 *
	 * @access public
	 * @param string $query (default: '')
	 * @return void
	 */
	public function query($query='') {
		$this->query_vars=$this->set_query_vars($query);

		$this->set_gallery_id($this->query_vars['id']);

		$this->get_images($this->query_vars);

		if ($this->bootstrap) :
			wp_enqueue_script('advanced-cms-gallery-bootstrap-script', advanced_CMS_URL.'/shortcodes/js/bootstrap-carousel.min.js', array('jquery'), '3.3.7', true);

			wp_enqueue_style('advanced-cms-gallery-bootstrap-style', advanced_CMS_URL.'/shortcodes/css/bootstrap-carousel.css', array(), '3.3.7');
		endif;

		return $this;
	}

	/**
	 * get_images function.
	 *
	 * @access public
	 * @param string $vars (default: '')
	 * @return void
	 */
	public function get_images($vars='') {
		$images=array();

		if (!isset($vars['image_ids']) || empty($vars['image_ids']))
			return false;

		foreach ($vars['image_ids'] as $key => $image_id) :

			$metadata=wp_get_attachment_metadata($image_id);
			$image_post=get_post($image_id);

			if (!$metadata || !$image_post)
				continue;

			$image=new stdClass();
			$image->id='image-'.$key;
			$image->image=wp_get_attachment_image($image_id, $vars['size']);
			$image->caption=$image_post->post_excerpt;
			$image->description=$image_post->post_content;

			$images[]=$image;
		endforeach;

		$this->images=$images;
		$this->image_count=count($images);

		return $this->images;
	}

	/**
	 * set_gallery_id function.
	 *
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function set_gallery_id($id) {
		$this->id=$id;
	}

	/**
	 * have_images function.
	 *
	 * @access public
	 * @return void
	 */
	public function have_images() {
		if ($this->current_image + 1 < $this->image_count) :
			return true;
		elseif ( $this->current_image + 1 == $this->image_count && $this->image_count > 0 ) :
			$this->rewind_images();
		endif;

		return false;
	}

	/**
	 * the_image function.
	 *
	 * @access public
	 * @return void
	 */
	public function the_image() {
		global $advanced_cms_image;

		$advanced_cms_image = $this->next_image();
	}

  /**
   * next_image function.
   *
   * @access public
   * @return void
   */
  public function next_image() {
		$this->current_image++;

		$this->image = $this->images[$this->current_image];

		return $this->image;
	}

	/**
	 * rewind_images function.
	 *
	 * @access public
	 * @return void
	 */
	public function rewind_images() {
		$this->current_image = -1;

		if ( $this->image_count > 0 )
			$this->image = $this->images[0];
	}

}

/**
 * advanced_cms_gallery_have_images function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_have_images() {
	global $advanced_cms_gallery;

	return $advanced_cms_gallery->have_images();
}

/**
 * advanced_cms_gallery_the_image function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_the_image() {
	global $advanced_cms_gallery;

	$advanced_cms_gallery->the_image();
}

/**
 * advanced_cms_gallery_id function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_id() {
	echo 	advanced_cms_gallery_get_id();
}

/**
 * advanced_cms_gallery_get_id function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_get_id() {
	global $advanced_cms_gallery;

	return $advanced_cms_gallery->id;
}

/**
 * advanced_cms_image_class function.
 *
 * @access public
 * @param string $class (default: '')
 * @param mixed $image_id (default: null)
 * @return void
 */
function advanced_cms_image_class($class='', $image_id=null) {
	echo join(' ', advanced_cms_get_image_class($class, $image_id));
}

/**
 * advanced_cms_get_image_class function.
 *
 * @access public
 * @param string $class (default: '')
 * @param mixed $image_id (default: null)
 * @return void
 */
function advanced_cms_get_image_class($class='', $image_id=null) {
	global $advanced_cms_gallery, $advanced_cms_image;

	$classes=array();

	if ($class) :
		if (!is_array($class))
			$class=explode(',', $classe);

		$classes=array_map('esc_attr', $class);
	else :
		$class=array();
	endif;

	if (!$image_id)
		$image_id=$advanced_cms_image->id;

	$classes[]=$image_id;
	$classes[]='item';

	if ($advanced_cms_gallery->current_image==0)
		$classes[]='active';

	$classes=array_map('esc_attr', $classes);

	return array_unique($classes);
}

/**
 * advanced_cms_gallery_image function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_image() {
	echo advanced_cms_gallery_get_image();
}

/**
 * advanced_cms_gallery_get_image function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_get_image() {
	global $advanced_cms_image;

	return $advanced_cms_image->image;
}

/**
 * advanced_cms_gallery_image_caption function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_image_caption() {
	echo advanced_cms_gallery_get_image_caption();
}

/**
 * advanced_cms_gallery_get_image_caption function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_get_image_caption() {
	global $advanced_cms_image;

	return $advanced_cms_image->caption;
}

/**
 * advanced_cms_gallery_has_caption function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_has_caption() {
	global $advanced_cms_image;

	if ($advanced_cms_image->caption!='')
		return true;

	return false;
}

/**
 * advanced_cms_gallery_indicators function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_indicators() {
	echo advanced_cms_gallery_get_indicators();
}

/**
 * advanced_cms_gallery_get_indicators function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_get_indicators() {
	global $advanced_cms_gallery;

	$html=null;

	if (!$advanced_cms_gallery->image_count || !$advanced_cms_gallery->show_indicators)
		return false;

	$html.='<ol class="carousel-indicators">';
		foreach ($advanced_cms_gallery->images as $key => $image) :
			if ($key==0) :
				$class='active';
			else :
				$class='';
			endif;

	  	$html.='<li data-target="#advanced-cms-carousel-'.advanced_cms_gallery_get_id().'" data-slide-to="'.$key.'" class="'.$class.'"></li>';
		endforeach;
	$html.='</ol>';

	return $html;
}

/**
 * advanced_cms_gallery_controls function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_controls() {
	echo advanced_cms_gallery_get_controls();
}

/**
 * advanced_cms_gallery_get_controls function.
 *
 * @access public
 * @return void
 */
function advanced_cms_gallery_get_controls() {
	global $advanced_cms_gallery;

	$html=null;

	if (!$advanced_cms_gallery->image_count || !$advanced_cms_gallery->show_controls)
		return false;

  $html.='<a class="left carousel-control" href="#advanced-cms-carousel-'.advanced_cms_gallery_get_id().'" role="button" data-slide="prev">';
    $html.='<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>';
    $html.='<span class="sr-only">Previous</span>';
  $html.='</a>';
  $html.='<a class="right carousel-control" href="#advanced-cms-carousel-'.advanced_cms_gallery_get_id().'" role="button" data-slide="next">';
    $html.='<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>';
    $html.='<span class="sr-only">Next</span>';
  $html.='</a>';

	return $html;
}
?>
